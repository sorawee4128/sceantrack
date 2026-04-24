<?php

namespace App\Http\Controllers;

use App\Enums\SceneCaseStatus;
use App\Http\Requests\Reports\ReportFilterRequest;
use App\Models\SceneCase;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(ReportFilterRequest $request)
    {
        $query = SceneCase::query()
            ->with(['doctor', 'assistant', 'policeStation', 'notificationType']);
        $query->whereYear('case_date', now()->year);
        if ($request->filled('month')) {
            $query->whereMonth('case_date', $request->month);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('case_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('case_date', '<=', $request->to_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if (! $request->user()->can('view all reports')) {
            $query->where(function ($q) use ($request) {
                $q->where('doctor_user_id', $request->user()->id)
                    ->orWhere('assistant_user_id', $request->user()->id);
            });
        }

        $chartType = $request->get('chart_type', 'management');

        $cases = (clone $query)
            ->latest('case_date')
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total' => (clone $query)->count(),
            'draft' => (clone $query)->where('status', SceneCaseStatus::DRAFT->value)->count(),
            'submitted' => (clone $query)->where('status', SceneCaseStatus::SUBMITTED->value)->count(),
        ];

        $byNotificationType = (clone $query)
            ->select('notification_type_id', DB::raw('COUNT(*) as total'))
            ->groupBy('notification_type_id')
            ->with('notificationType')
            ->get();

        $chartTitle = match ($chartType) {
            'police_station' => 'สถานีตำรวจ',
            'notification_type' => 'ประเภทคดี',
            'doctor' => 'แพทย์ผู้ตรวจ',
            'assistant' => 'ผู้ช่วยแพทย์',
            default => 'การจัดการ',
        };

        $chartByNotificationType = match ($chartType) {
            'police_station' => (clone $query)
                ->select('police_station_id', DB::raw('COUNT(*) as total'))
                ->groupBy('police_station_id')
                ->with('policeStation')
                ->get()
                ->map(fn ($row) => [
                    'label' => $row->policeStation?->name ?? 'ไม่ระบุ',
                    'total' => $row->total,
                ])
                ->values(),

            'notification_type' => (clone $query)
                ->select('notification_type_id', DB::raw('COUNT(*) as total'))
                ->groupBy('notification_type_id')
                ->with('notificationType')
                ->get()
                ->map(fn ($row) => [
                    'label' => $row->notificationType?->name ?? 'ไม่ระบุ',
                    'total' => $row->total,
                ])
                ->values(),

            'doctor' => (clone $query)
                ->select('doctor_user_id', DB::raw('COUNT(*) as total'))
                ->groupBy('doctor_user_id')
                ->with('doctor')
                ->get()
                ->map(fn ($row) => [
                    'label' => $row->doctor?->displayName() ?? 'ไม่ระบุ',
                    'total' => $row->total,
                ])
                ->values(),

            'assistant' => (clone $query)
                ->select('assistant_user_id', DB::raw('COUNT(*) as total'))
                ->groupBy('assistant_user_id')
                ->with('assistant')
                ->get()
                ->map(fn ($row) => [
                    'label' => $row->assistant?->displayName() ?? 'ไม่ระบุ',
                    'total' => $row->total,
                ])
                ->values(),

            default => collect([
                [
                    'label' => 'ทั้งหมด',
                    'total' => $summary['total'],
                ],
                [
                    'label' => 'แบบร่าง',
                    'total' => $summary['draft'],
                ],
                [
                    'label' => 'ส่งแล้ว',
                    'total' => $summary['submitted'],
                ],
            ]),
        };

        return view('reports.index', compact(
            'cases',
            'summary',
            'byNotificationType',
            'chartByNotificationType',
            'chartType',
            'chartTitle'
        ));
    }
}