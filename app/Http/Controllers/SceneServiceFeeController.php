<?php

namespace App\Http\Controllers;

use App\Models\SceneCase;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SceneServiceFeeController extends Controller
{
    public function index(Request $request)
    {
        $targets = User::role(['doctor','assistant'])->orderBy('name');
        $user = auth()->user();
        if ($request->boolean('my_only')) {
            $targets->where('id', $user->id);
        }
        $targets = $targets->get();
        $query = SceneCase::query()->where('status','submitted');
        if ($request->filled('month')) {
            $month = Carbon::createFromFormat('Y-m', $request->month);
            $query->whereBetween('case_date', [
                $month->copy()->startOfMonth()->toDateString(),
                $month->copy()->endOfMonth()->toDateString(),
            ]);
        }

        $cases = $query->get();
        $targets->transform(function ($target) use ($cases, $request) {
            $target->n = $cases->filter(function ($case) use ($target) {
                return ($case->doctor_user_id == $target->id || $case->assistant_user_id == $target->id) && $case->body_handling_id == 1;
            })->count();
            $target->i = $cases->filter(function ($case) use ($target) {
                return ($case->doctor_user_id == $target->id || $case->assistant_user_id == $target->id) && $case->body_handling_id == 4;
            })->count();
            $target->o = $cases->filter(function ($case) use ($target) {
                return ($case->doctor_user_id == $target->id || $case->assistant_user_id == $target->id) && $case->body_handling_id == 3;
            })->count();
            $target->f = $cases->filter(function ($case) use ($target) {
                return ($case->doctor_user_id == $target->id || $case->assistant_user_id == $target->id) && $case->body_handling_id == 2;
            })->count();
            $target->sum = $cases->filter(function ($case) use ($target) {
                return ($case->doctor_user_id == $target->id || $case->assistant_user_id == $target->id);
            })->count();

            $rate = 100;
            if ($target->hasRole('doctor')) {
              $rate = 1000;
            }

            $target->fee = '-'; 
            if (auth()->user()->id == $target->id || auth()->user()->hasRole('system')) {
                $target->fee = number_format($rate * $target->sum);
            }

            return $target;
        });

        $totals = [
            'n' => $targets->sum('n'),
            'f' => $targets->sum('f'),
            'o' => $targets->sum('o'),
            'i' => $targets->sum('i'),
            'sum' => $targets->sum('sum'),
            'fee' => $targets->sum(function ($user) {
                return is_numeric(str_replace(',', '', $user->fee))
                    ? (int) str_replace(',', '', $user->fee)
                    : 0;
            }),
        ];

        return view('scene-service-fee.index', compact('targets', 'totals'));
    }

    public function pdf(Request $request, string $role)
    {
        abort_unless(in_array($role, ['doctor', 'assistant']), 404);
        $rate = $role === 'doctor' ? 1000 : 100;
        $roleLabel = $role === 'doctor' ? 'แพทย์' : 'ผู้ช่วยแพทย์';
        $targets = User::role($role)->orderBy('name')->get();
        $query = SceneCase::query()
            ->with(['doctor', 'assistant'])
            ->where('status', 'submitted');
        
        if ($request->filled('month')) {
            $month = Carbon::createFromFormat('Y-m', $request->month);
            $query->whereBetween('case_date', [
                $month->copy()->startOfMonth()->toDateString(),
                $month->copy()->endOfMonth()->toDateString(),
            ]);
            $monthText = $month->locale('th')->translatedFormat('F ') . ($month->year + 543);
        } else {
            $monthText = 'ทั้งหมด';
        }

        $cases = $query->orderBy('case_date')->get();
        $rows = $targets->map(function ($user) use ($cases, $role, $rate) {
            $userCases = $cases->filter(function ($case) use ($user, $role) {
                return $role === 'doctor'
                    ? $case->doctor_user_id == $user->id
                    : $case->assistant_user_id == $user->id;
            })->values();

            return [
                'user' => $user,
                'cases' => $userCases,
                'rate' => $rate,
                'total' => $userCases->count() * $rate,
            ];
        })->filter(fn ($row) => $row['cases']->count() > 0)->values();
        $pdf = Pdf::loadView('scene-service-fee.pdf', [
            'rows' => $rows,
            'role' => $role,
            'roleLabel' => $roleLabel,
            'rate' => $rate,
            'monthText' => $monthText,
        ])->setPaper('a4', 'portrait');
        return $pdf->stream("ค่าตอบแทน({$roleLabel}).pdf");
    }
}
