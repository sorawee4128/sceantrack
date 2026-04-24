<?php

namespace App\Http\Controllers;

use App\Enums\SceneCaseStatus;
use App\Models\SceneCase;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $sceneCases = SceneCase::query();
        if ($request->boolean('my_only')) {
            $sceneCases->where(function ($query) use ($user) {
                $query->where('doctor_user_id', $user->id)
                      ->orWhere('assistant_user_id', $user->id);
            });
        }

        if ($request->filled('from_date')) {
            $sceneCases->whereDate('case_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $sceneCases->whereDate('case_date', '<=', $request->to_date);
        }

      
        $stats = [
            'submitted_cases' => SceneCase::query()
                ->where(function ($query) use ($user) {
                    $query->where('doctor_user_id', $user->id)
                          ->orWhere('assistant_user_id', $user->id);
                })
                ->where('status', SceneCaseStatus::SUBMITTED->value)
                ->count(),

            'month_cases' => SceneCase::query()
                ->whereBetween('case_date', [
                    now()->startOfMonth()->toDateString(),
                    now()->endOfMonth()->toDateString()
                ])
                ->count(),
        ];

        $recentCases = (clone $sceneCases)
            ->with(['shift', 'doctor', 'assistant', 'policeStation'])
            ->latest('case_date')
            ->limit(8)
            ->get();

        return view('dashboard.index', compact('stats', 'recentCases'));
    }
}