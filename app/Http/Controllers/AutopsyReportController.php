<?php

namespace App\Http\Controllers;

use App\Models\AutopsyCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutopsyReportController extends Controller
{
    public function index(Request $request)
    {
        $chartType = $request->input('chart_type', 'management');

        $query = AutopsyCase::query()->with([
            'doctor',
            'policeStation',
            'assistant',
            'photo',
            'lab',
            'scene',
        ]);

        if ($request->filled('month')) {
            $query->whereMonth('autopsy_date', $request->month);
        }

        $baseQuery = clone $query;

        $summary = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'submitted' => (clone $baseQuery)->where('status', 'submitted')->count(),
            'approve' => (clone $baseQuery)->where('status', 'approve')->count(),
        ];

        $chartTitle = match ($chartType) {
            'doctor' => 'แพทย์ผู้ผ่าพิสูจน์',
            'assistant' => 'ผู้ช่วยผ่า',
            'photo' => 'ผู้ช่วยถ่ายภาพ',
            'lab' => 'ส่งตรวจทางห้องปฏิบัติการ',
            'method' => 'รูปแบบการผ่า',
            'police_station' => 'สถานีตำรวจ',
            default => 'สถานะการจัดการ',
        };

        $chartData = $this->chartData($baseQuery, $chartType);

        $cases = $query
            ->latest('autopsy_date')
            ->paginate(15)
            ->withQueryString();

        return view('autopsy-reports.index', compact(
            'cases',
            'summary',
            'chartType',
            'chartTitle',
            'chartData'
        ));
    }

  private function chartData($query, string $chartType)
{
    return match ($chartType) {
        'doctor' => (clone $query)
            ->leftJoin('users', 'autopsy_cases.doctor_user_id', '=', 'users.id')
            ->selectRaw("COALESCE(users.full_name, users.name, 'ไม่ระบุ') as label, COUNT(*) as total")
            ->groupBy('users.full_name', 'users.name')
            ->orderByDesc('total')
            ->get(),

        'assistant' => (clone $query)
            ->leftJoin('autopsy_assistants', 'autopsy_cases.autopsy_assistant_id', '=', 'autopsy_assistants.id')
            ->selectRaw("COALESCE(autopsy_assistants.name, 'ไม่ระบุ') as label, COUNT(*) as total")
            ->groupBy('autopsy_assistants.name')
            ->orderByDesc('total')
            ->get(),

        'photo' => (clone $query)
            ->leftJoin('photo_assistants', 'autopsy_cases.photo_assistant_id', '=', 'photo_assistants.id')
            ->selectRaw("COALESCE(photo_assistants.name, 'ไม่ระบุ') as label, COUNT(*) as total")
            ->groupBy('photo_assistants.name')
            ->orderByDesc('total')
            ->get(),

        'lab' => (clone $query)
            ->leftJoin('labs', 'autopsy_cases.lab_id', '=', 'labs.id')
            ->selectRaw("COALESCE(labs.name, 'ไม่ระบุ') as label, COUNT(*) as total")
            ->groupBy('labs.name')
            ->orderByDesc('total')
            ->get(),

        'method' => (clone $query)
            ->selectRaw("autopsy_method as raw_label, COUNT(*) as total")
            ->groupBy('autopsy_method')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($item) => [
                'label' => $item->raw_label === 'autopsy' ? 'ผ่า' : 'ไม่ผ่า',
                'total' => $item->total,
            ]),

        'police_station' => (clone $query)
            ->leftJoin('police_stations', 'autopsy_cases.police_station_id', '=', 'police_stations.id')
            ->selectRaw("COALESCE(police_stations.name, 'ไม่ระบุ') as label, COUNT(*) as total")
            ->groupBy('police_stations.name')
            ->orderByDesc('total')
            ->get(),

        default => (clone $query)
            ->selectRaw("status as raw_label, COUNT(*) as total")
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($item) => [
                'label' => match ($item->raw_label) {
                    'pending' => 'รอดำเนินการ',
                    'submitted' => 'ส่งแล้ว',
                    'approve' => 'อนุมัติแล้ว',
                    default => $item->raw_label ?: 'ไม่ระบุ',
                },
                'total' => $item->total,
            ]),
    };
}
}