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
        $query = SceneCase::query()->with(['doctor', 'assistant', 'policeStation', 'notificationType']);

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

        $cases = (clone $query)->latest('case_date')->paginate(20)->withQueryString();

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

        return view('reports.index', compact('cases', 'summary', 'byNotificationType'));
    }
}
