<?php

namespace App\Http\Controllers;

use App\Enums\SceneCaseStatus;
use App\Http\Requests\SceneCases\StoreSceneCaseRequest;
use App\Http\Requests\SceneCases\UpdateSceneCaseRequest;
use App\Models\BodyHandling;
use App\Models\Gender;
use App\Models\NotificationType;
use App\Models\PoliceStation;
use App\Models\SceneCase;
use App\Models\SceneCasePhoto;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SceneCaseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', SceneCase::class);

        $query = SceneCase::query()->with(['shift', 'doctor', 'assistant', 'policeStation']);

        if (! $request->user()->can('manage scene cases')) {
            $query->where(function ($q) use ($request) {
                $q->where('doctor_user_id', $request->user()->id)
                    ->orWhere('assistant_user_id', $request->user()->id);
            });
        }

        $cases = $query
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = $request->q;
                $q->where(function ($qq) use ($search) {
                    $qq->where('scene_no', 'like', "%{$search}%")
                        ->orWhere('deceased_name', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest('case_date')
            ->paginate(15)
            ->withQueryString();
        return view('scene-cases.index', compact('cases'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', SceneCase::class);

        return view('scene-cases.create', $this->formData($request));
    }

    public function store(StoreSceneCaseRequest $request)
    {
        $shift = Shift::with(['doctor', 'assistant'])->findOrFail($request->shift_id);
        $this->ensureShiftBelongsToUserIfNeeded($request, $shift);

        $status = $request->action === 'submit' ? SceneCaseStatus::SUBMITTED : SceneCaseStatus::DRAFT;

        $sceneCase = SceneCase::create($request->safe()->except(['photos', 'action']) + [
            'doctor_user_id' => $shift->doctor_user_id,
            'assistant_user_id' => $shift->assistant_user_id,
            'status' => $status->value,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        $this->storePhotos($sceneCase, $request);

        return redirect()->route('scene-cases.show', $sceneCase)->with(
            'success',
            $status === SceneCaseStatus::SUBMITTED ? 'ส่ง Scene Case เรียบร้อยแล้ว' : 'บันทึกฉบับร่างเรียบร้อยแล้ว'
        );
    }

    public function show(SceneCase $sceneCase)
    {
        $this->authorize('view', $sceneCase);

        $sceneCase->load(['shift', 'doctor', 'assistant', 'policeStation', 'bodyHandling', 'notificationType', 'gender', 'photos']);

        return view('scene-cases.show', compact('sceneCase'));
    }

    public function edit(Request $request, SceneCase $sceneCase)
    {
        $this->authorize('update', $sceneCase);

        return view('scene-cases.edit', $this->formData($request, $sceneCase));
    }

    public function update(UpdateSceneCaseRequest $request, SceneCase $sceneCase)
    {
        if ($request->action === 'submit') {
            $this->authorize('submit', $sceneCase);
        } else {
            $this->authorize('update', $sceneCase);
        }

        $shift = Shift::with(['doctor', 'assistant'])->findOrFail($request->shift_id);
        $this->ensureShiftBelongsToUserIfNeeded($request, $shift);

        $status = $request->action === 'submit' ? SceneCaseStatus::SUBMITTED : SceneCaseStatus::DRAFT;

        $sceneCase->update($request->safe()->except(['photos', 'action']) + [
            'doctor_user_id' => $shift->doctor_user_id,
            'assistant_user_id' => $shift->assistant_user_id,
            'status' => $status->value,
            'updated_by' => $request->user()->id,
        ]);

        $this->storePhotos($sceneCase, $request);

        return redirect()->route('scene-cases.show', $sceneCase)->with(
            'success',
            $status === SceneCaseStatus::SUBMITTED ? 'อัปเดตและส่ง Scene Case เรียบร้อยแล้ว' : 'อัปเดตฉบับร่างเรียบร้อยแล้ว'
        );
    }

    public function destroy(SceneCase $sceneCase)
    {
        $this->authorize('delete', $sceneCase);

        foreach ($sceneCase->photos as $photo) {
            Storage::disk('public')->delete($photo->file_path);
        }

        $sceneCase->delete();

        return redirect()->route('scene-cases.index')->with('success', 'ลบ Scene Case เรียบร้อยแล้ว');
    }

    public function shiftInfo(Request $request, Shift $shift)
    {
        abort_unless(
            $request->user()->can('manage scene cases')
            || $request->user()->can('view own scene cases')
            || $request->user()->can('submit scene cases'),
            403
        );

        $this->ensureShiftBelongsToUserIfNeeded($request, $shift);

        return response()->json([
            'id' => $shift->id,
            'date' => $shift->shift_date->format('Y-m-d'),
            'type' => $shift->shift_type->value,
            'type_label' => $shift->shift_type->label(),
            'doctor_user_id' => $shift->doctor_user_id,
            'doctor_name' => $shift->doctor?->displayName(),
            'assistant_user_id' => $shift->assistant_user_id,
            'assistant_name' => $shift->assistant?->displayName(),
        ]);
    }

    protected function formData(Request $request, ?SceneCase $sceneCase = null): array
    {
        $shiftQuery = Shift::query()->with(['doctor', 'assistant'])->orderByDesc('shift_date');

        if (! $request->user()->can('manage scene cases')) {
            $shiftQuery->where(function ($q) use ($request) {
                $q->where('doctor_user_id', $request->user()->id)
                    ->orWhere('assistant_user_id', $request->user()->id);
            });
        }

        return [
            'sceneCase' => $sceneCase,
            'shifts' => $shiftQuery->get(),
            'policeStations' => PoliceStation::active()->orderBy('name')->get(),
            'bodyHandlings' => BodyHandling::active()->orderBy('name')->get(),
            'notificationTypes' => NotificationType::active()->orderBy('name')->get(),
            'genders' => Gender::active()->orderBy('name')->get(),
        ];
    }

    protected function ensureShiftBelongsToUserIfNeeded(Request $request, Shift $shift): void
    {
        if ($request->user()->can('manage scene cases')) {
            return;
        }

        abort_unless(
            $shift->doctor_user_id === $request->user()->id || $shift->assistant_user_id === $request->user()->id,
            403,
            'คุณไม่มีสิทธิ์เข้าถึงเมนูนี้'
        );
    }

    protected function storePhotos(SceneCase $sceneCase, Request $request): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        $currentSort = (int) $sceneCase->photos()->max('sort_order');

        foreach ($request->file('photos', []) as $file) {
            $path = $file->store('scene-cases/'.$sceneCase->id, 'public');

            SceneCasePhoto::create([
                'scene_case_id' => $sceneCase->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'sort_order' => ++$currentSort,
            ]);
        }
    }
}
