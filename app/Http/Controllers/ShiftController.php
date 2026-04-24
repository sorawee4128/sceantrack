<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shifts\StoreShiftRequest;
use App\Http\Requests\Shifts\UpdateShiftRequest;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;

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
                        'short_type' => $shift->shift_type->value == 'day' ? 'กลางวัน' : 'กลางคืน',
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
}
