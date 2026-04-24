<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shifts\StoreShiftRequest;
use App\Http\Requests\Shifts\UpdateShiftRequest;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;
use App\Enums\ShiftType;
class ShiftController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Shift::class);

        $shifts = Shift::query()
            ->with(['doctor', 'assistant'])
            ->orderByDesc('shift_date')
            ->orderBy('shift_type')
            ->paginate(15);

        return view('shifts.index', compact('shifts'));
    }

    public function events(Request $request)
    {
        $this->authorize('viewAny', Shift::class);

        $events = Shift::query()
            ->with(['doctor', 'assistant'])
            ->when($request->filled('start'), fn ($query) => $query->whereDate('shift_date', '>=', $request->start))
            ->when($request->filled('end'), fn ($query) => $query->whereDate('shift_date', '<=', $request->end))
            ->get()
            ->map(function (Shift $shift) {
                return [
                    'id' => $shift->id,
                    'title' => $shift->title(),
                    'start' => $shift->shift_date->format('Y-m-d'),
                    'allDay' => true,
                    'color' => $shift->shift_type->value == 'day' ? '#2563eb' : '#111827',
                     'extendedProps' => [
                        'shift_type' => $shift->shift_type,
                        'short_type' => $shift->shift_type->value == 'day' ? 'กะกลางวัน (08:00 - 16:00)' : 'กะกลางคืน (16:00 - 08:00)',
                        'doctor_short' => $shift->doctor?->full_name ?? '',
                        'assistant_short' => $shift->assistant?->full_name ?? '',
                        'edit_url' => route('shifts.edit', $shift),
                    ],
                ];
            });

        return response()->json($events);
    }

    public function create()
    {
        $this->authorize('create', Shift::class);

        return view('shifts.create', [
            'doctors' => User::role('doctor')->active()->orderBy('full_name')->get(),
            'assistants' => User::role('assistant')->active()->orderBy('full_name')->get(),
        ]);
    }

    public function store(StoreShiftRequest $request)
    {
        Shift::create($request->validated() + [
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        return redirect()->route('shifts.index')->with('success', 'บันทึกกะเวรเรียบร้อยแล้ว');
    }

    public function edit(Shift $shift)
    {
        $this->authorize('update', $shift);

        return view('shifts.edit', [
            'shift' => $shift,
            'doctors' => User::role('doctor')->active()->orderBy('full_name')->get(),
            'assistants' => User::role('assistant')->active()->orderBy('full_name')->get(),
        ]);
    }

    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        $this->authorize('update', $shift);

        $shift->update($request->validated() + ['updated_by' => $request->user()->id]);

        return redirect()->route('shifts.edit', $shift)->with('success', 'อัปเดตกะเวรเรียบร้อยแล้ว');
    }

    public function destroy(Shift $shift)
    {
        $this->authorize('delete', $shift);

        $shift->delete();

        return redirect()->route('shifts.index')->with('success', 'ลบกะเวรเรียบร้อยแล้ว');
    }

    public function autoGenerateYear(Request $request)
    {
        $this->authorize('manage shifts');
        $year = $request->input('year', now()->year);
        $doctors = User::role('doctor')->get();
        $assistants = User::role('assistant')->get();
        if ($doctors->isEmpty() || $assistants->isEmpty()) {
            return back()->with('error', 'ไม่พบข้อมูลแพทย์หรือผู้ช่วยแพทย์');
        }

        $created = 0;
        $skipped = 0;
        DB::transaction(function () use ($year, $doctors, $assistants, &$created, &$skipped) {
            $period = CarbonPeriod::create(
                "{$year}-01-01",
                "{$year}-12-31"
            );
            foreach ($period as $date) {
                foreach ([ShiftType::DAY, ShiftType::NIGHT] as $shiftType) {
                    $exists = Shift::whereDate('shift_date', $date)
                        ->where('shift_type', $shiftType->value)
                        ->exists();
                    if ($exists) {
                        $skipped++;
                        continue;
                    }
                    $doctor = $doctors->random();
                    $assistant = $assistants->random();
                    Shift::create([
                        'shift_date' => $date->format('Y-m-d'),
                        'shift_type' => $shiftType->value,
                        'doctor_user_id' => $doctor->id,
                        'assistant_user_id' => $assistant->id,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                    $created++;
                }
            }
        });

        return back()->with(
            'success',
            "สร้างเวรปี {$year} เรียบร้อยแล้ว: เพิ่ม {$created} รายการ, ข้าม {$skipped} รายการ"
        );
    }
}
