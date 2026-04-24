<?php

namespace App\Http\Controllers;

use App\Models\AutopsyCase;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
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
            $canAction = false;

            if ($user->hasRole('doctor') && $case->status === 'submitted' && $user->id == $case->doctor_user_id) {
                $canAction = true;
            }

            if ($user->hasRole('staff') && $case->status === 'pending') {
                $canAction = true;
            }

            $case->canAction = $canAction;
            return $case;
        });

        $doctors = User::role('doctor')->orderBy('name')->get();
        return view('approve-autopsy-cases.index', compact('cases', 'doctors'));
    }

    public function submitted(Request $request)
    {
        $cases = AutopsyCase::where('id',$request->autopsy_id)->first();
        $status = 'submitted';
        if ($cases->status == 'submitted') {
           $status = 'approve';
        } 
        $cases->status = $status;
        $cases->update();

        if ($cases->status == 'approve') {
            Mail::raw('Test Email จาก Forensics', function ($message) use ($cases){
                $message->to($cases->policeStation->email)
                    ->subject('ทดสอบ');
            });
        }

        return redirect()->route('approve-autopsy-cases.index');
    }
}
