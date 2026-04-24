<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutopsyCases\StoreAutopsyCaseRequest;
use App\Http\Requests\AutopsyCases\UpdateAutopsyCaseRequest;
use App\Models\PoliceStation;
use App\Models\SceneCase;
use App\Models\AutopsyCase;
use App\Models\PhotoAssistant;
use App\Models\Lab;
use App\Models\User;
use App\Models\AutopsyAssistant;
use Illuminate\Http\Request;

class AutopsyCaseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', AutopsyCase::class);
        $query = SceneCase::query()->with(['shift', 'doctor', 'assistant', 'policeStation'])->whereNull('autopsy_case_id');
        $query->whereIn('body_handling_id',[2,3,4]);
        if (! $request->user()->can('manage autopsy cases')) {
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
        return view('autopsy-cases.index', compact('cases'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', AutopsyCase::class);

        $scene = SceneCase::query()->with(['shift', 'doctor', 'assistant', 'policeStation'])
        ->whereNull('autopsy_case_id')
        ->where('id', $request->scene)->first();


        return view('autopsy-cases.create', $this->formData($request, $scene));
    }

    public function store(StoreAutopsyCaseRequest $request)
    {
        $autopsyCase = AutopsyCase::create([
            'autopsy_no' => $request->body_handling_code ."-". $request->autopsy_no,
            'scene_case_id' => $request->scene_case_id,
            'police_station_id' => $request->police_station_id,
            'doctor_user_id' => $request->doctor_user_id,
            'autopsy_date' => $request->autopsy_date,
            'autopsy_method' => $request->autopsy_method,
            'autopsy_assistant_id' => $request->autopsy_assistant_id,
            'photo_assistant_id' => $request->photo_assistant_id,
            'lab_id' => $request->lab_id,
            'remarks' => $request->remarks,
            'status' => 'pending',
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        $scene = SceneCase::where('id', $request->scene_case_id)->first();
        $scene->autopsy_case_id = $autopsyCase->id;
        $scene->update();

        return redirect()->route('autopsy-cases.index', $autopsyCase)->with(
            'success',
            'ส่ง Autopsy Case เรียบร้อยแล้ว'
        );
    }

    protected function formData(Request $request, ?SceneCase $sceneCase = null, ?AutopsyCase $autopsyCase = null): array
    {
        return [
            'autopsyCase' => $autopsyCase,
            'sceneCase' => $sceneCase,
            'doctors' => User::role('doctor')->active()->orderBy('full_name')->get(),
            'policeStations' => PoliceStation::active()->orderBy('name')->get(),
            'photoAssistants' => PhotoAssistant::active()->orderBy('name')->get(),
            'labs' => Lab::active()->orderBy('name')->get(),
            'autopsyAssistants' => AutopsyAssistant::active()->orderBy('name')->get(),
        ];
    }

     public function edit(Request $request, AutopsyCase $autopsyCase)
    {
        $this->authorize('update', $autopsyCase);

        $scene = SceneCase::query()->with(['shift', 'doctor', 'assistant', 'policeStation'])
        ->where('id', $autopsyCase->scene_case_id)->first();
        return view('autopsy-cases.edit', $this->formData($request, $scene, $autopsyCase));
    }

    public function update(UpdateAutopsyCaseRequest $request, AutopsyCase $autopsyCase)
    {
        if ($request->action === 'submit') {
            $this->authorize('submit', $autopsyCase);
        } else {
            $this->authorize('update', $autopsyCase);
        }
      
        $autopsyCase->update([
            'autopsy_no' => $request->body_handling_code ."-". $request->autopsy_no,
            'scene_case_id' => $request->scene_case_id,
            'police_station_id' => $request->police_station_id,
            'doctor_user_id' => $request->doctor_user_id,
            'autopsy_date' => $request->autopsy_date,
            'autopsy_method' => $request->autopsy_method,
            'autopsy_assistant_id' => $request->autopsy_assistant_id,
            'photo_assistant_id' => $request->photo_assistant_id,
            'lab_id' => $request->lab_id,
            'remarks' => $request->remarks,
            'status' => 'pending',
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return redirect()->route('approve-autopsy-cases.index', $autopsyCase)->with(
            'success',
            'อัปเดตและส่ง Autopsy Case เรียบร้อยแล้ว'
        );
    }

    public function show(AutopsyCase $autopsyCase)
    {

        $autopsyCase->load(['doctor', 'policeStation', 'lab', 'assistant', 'photo', 'scene']);

        return view('autopsy-cases.show', compact('autopsyCase'));
    }
}
