<?php

namespace App\Http\Controllers;

use App\Models\AutopsyCase;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class ApproveAutopsyCaseController extends Controller
{
    public function index(Request $request)
    {
        $query = AutopsyCase::query()->with(['doctor', 'scene', 'lab']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_user_id', $request->doctor_id);
        }

        if ($request->filled('month')) {
            $month = Carbon::createFromFormat('Y-m', $request->month);
            $query->whereBetween('autopsy_date', [
                $month->copy()->startOfMonth()->toDateString(),
                $month->copy()->endOfMonth()->toDateString(),
            ]);
        }

        $cases = $query
            ->latest('autopsy_date')
            ->paginate(15)
            ->withQueryString();

        $user = auth()->user();

        $cases->getCollection()->transform(function ($case) use ($user) {
            $isExpired = $case->created_at
                ? $case->created_at->lt(now()->subHours(24))
                : true;
            $canAction = false;
            // system / admin ทำได้เสมอ ไม่ติด 24 ชม.
            if ($user->hasRole(['system', 'admin'])) {
                $canAction = true;
            }

            // staff ทำได้เฉพาะ pending และยังไม่เกิน 24 ชม.
            elseif (
                $user->hasRole('staff') &&
                $case->status === 'pending'
            ) {
                $canAction = true;
            }

            // doctor ทำได้เฉพาะ submitted, เป็นหมอเจ้าของเคส และยังไม่เกิน 24 ชม.
            elseif (
                $user->hasRole('doctor') &&
                $case->status === 'submitted' &&
                $user->id == $case->doctor_user_id
            ) {
                $canAction = true;
            }

            $case->canAction = $canAction;
            $case->isExpired = $isExpired;

            return $case;
        });

        $doctors = User::role('doctor')->orderBy('name')->get();

        return view('approve-autopsy-cases.index', compact('cases', 'doctors'));
    }

    public function submitted(Request $request)
    {
        $case = AutopsyCase::with([
            'policeStation',
            'scene',
            'doctor',
            'lab',
        ])->findOrFail($request->autopsy_id);
        $status = 'submitted';
        if ($case->status === 'submitted') {
            $status = 'approve';
        }

        $case->status = $status;
        $case->save();
        if ($case->status === 'approve') {
            if (! $case->policeStation?->email) {
                return redirect()
                    ->route('approve-autopsy-cases.index')
                    ->with('error', 'อนุมัติแล้ว แต่ไม่พบอีเมลสถานีตำรวจ');
            }

            $pdf = Pdf::loadView('approve-autopsy-cases.notice-pdf', [
                'case' => $case,
            ])->setPaper('a4', 'portrait');
            Mail::send('emails.autopsy-approved', [
                'case' => $case,
            ], function ($message) use ($case, $pdf) {
                $message->to($case->policeStation->email)
                    ->subject('แจ้งความคืบหน้ารายงานตรวจศพทางนิติเวชศาสตร์ เลขที่ ' . $case->autopsy_no)
                    ->attachData(
                        $pdf->output(),
                        'แจ้งเตือนสถานะรายงาน-' . $case->autopsy_no . '.pdf',
                        [
                            'mime' => 'application/pdf',
                        ]
                    );
            });
        }

        return redirect()
            ->route('approve-autopsy-cases.index')
            ->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
    }
}
