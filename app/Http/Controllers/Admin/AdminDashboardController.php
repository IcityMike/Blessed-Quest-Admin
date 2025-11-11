<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\ClientBeneficiaries;
use App\Models\Events;
use App\Models\Transaction;
use App\Helpers\Common;
use App\Models\Admin;
use App\Models\City;
use App\Models\User;
use App\Models\Subscription_user;


use DB;

class AdminDashboardController extends Controller
{
    /***
    *   Developed by: Radhika Savaliya
    *   Description: Display dashboard page
    ***/
    public function dashboard()
    {
        $startDate = date('Y-m-01'); // hard-coded '01' for first day
        $endDate  = date('Y-m-t');
       
        $data['activeuserCount'] = User::where('status','active')->count();
        // dd($data);
        $data['inactiveuserCount'] = User::where('status','inactive')->orWhere('status','block')->count();

        $data['users_current_month'] = User::whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();

        //dd($data['users_current_month']);

        $data['latest_events'] = Events::count();

        $data['events_current_month'] = Events::whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();

        $data['totalTransactionCount'] = Transaction::count();
        $data['inprocessTransactionCount'] = Transaction::where('transaction_status','IN_PROCESS')->count();
        $data['initiateTransactionCount'] = Transaction::where('transaction_status','INITIATE')->count();
        $data['paidTransactionCount'] = Transaction::where('transaction_status','PAID')->count();

        $data['paidTransactionCounttot'] = Transaction::where('transaction_status','PAID')->sum('transaction_amount');

        $data['cancelledTransactionCount'] = Transaction::where('transaction_status','CANCELLED')->count();
        $data['failedTransactionCount'] = Transaction::where('transaction_status','FAILED')->count();
        
        return view('admin.dashboard.admin')
               ->with($data);
    }


    public function upcoming_subscription_list(Request $request){

        $transactions = '';
        $transactions = Subscription_user::orderByDesc('id');

        if(@$request->filled('from_date') && @$request->filled('to_date')) {

            $st_date = date('Y-m-d',strtotime($request->from_date));
            $en_date = date('Y-m-d',strtotime($request->to_date));

            //dd($st_date,$en_date,$request->from_date);
            $transactions = $transactions->whereBetween('end_date', [$st_date
                , $en_date]);
        }
        
        return datatables()->of($transactions)
            ->editColumn('user_id', function ($transactions) 
            {
                $userName = User::select('id','name')->where('id',$transactions->user_id)->first();

                return @$userName->name;
            })
            ->editColumn('subscription_name', function ($transactions) 
            {
                $subscription = Subscription_user::select('id','title','sub_title','user_id')->where('id',$transactions->subscription_id)->first();

                return @$subscription->title;
            })
            ->editColumn('amount', function ($transactions) 
            {
               
                return number_format($transactions->amount,2);
            })
            ->editColumn('end_date', function ($transactions) 
            {
                return date('d/m/Y',strtotime($transactions->end_date));
            })
            ->escapeColumns([])
            
          ->make(true);

    }


    public function getReports(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        // dd($startDate,$endDate);
        $data['activeuserCount'] = 0;
        $data['inactiveuserCount'] = 0;

        $data['beneficiarCount'] = 0;

        $data['totalTransactionCount'] = 0;
        $data['inprocessTransactionCount'] = 0;
        $data['initiateTransactionCount'] = 0;
        $data['paidTransactionCount'] = 0;
        $data['cancelledTransactionCount'] = 0;
        $data['failedTransactionCount'] = 0;

        $data['totalAmount'] = 0;
        $data['totalCommision'] = 0;

        $data['totalMonoovaAmount'] = 0;

        $users = DB::table('users')
                    ->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')
                    ->get();
        foreach ($users as $value)
        {
            if($value->status == 'active')
            {
                $data['activeuserCount'] = $value->total;
            }
            if($value->status == 'inactive' || $value->status == 'block')
            {
                $data['inactiveuserCount'] = $value->total;
            }
        }

        $beneficiarCount = ClientBeneficiaries::whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->count();

        $totalTransactionCount = Transaction::whereDate('transaction_created_at', '>=', $startDate)->whereDate('transaction_created_at', '<=', $endDate)->count();

        $transaction_status = Transaction::select('transaction_status',
            DB::raw('IF((transaction_status = "IN_PROCESS"), COUNT(*), "0") as in_process'), 
            DB::raw('IF((transaction_status = "INITIATE"), COUNT(*), "0") as initiate'),
            DB::raw('IF((transaction_status = "PAID"), COUNT(*), "0") as paid'),
            DB::raw('IF((transaction_status = "CANCELLED"), COUNT(*), "0") as cancelled'),
            DB::raw('IF((transaction_status = "FAILED"), COUNT(*), "0") as failed'))
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy('transaction_status')
            ->get();

        foreach($transaction_status as $value)
        {
            if($value->transaction_status == 'IN_PROCESS')
            {
                $data['inprocessTransactionCount'] = $value->in_process;
            }
            if($value->transaction_status == 'INITIATE')
            {
                $data['initiateTransactionCount'] = $value->initiate;
            }
            if($value->transaction_status == 'PAID')
            {
                $data['paidTransactionCount'] = $value->paid;
            }
            if($value->transaction_status == 'CANCELLED')
            {
                $data['cancelledTransactionCount'] = $value->cancelled;
            }
            if($value->transaction_status == 'FAILED')
            {
                $data['failedTransactionCount'] = $value->failed;
            }
        }

        $transactions = Transaction::select('source_currency',
            DB::raw('SUM(source_amount) AS totalAmount'),
            DB::raw('SUM(transaction_fee) AS totalCommision'))
            ->whereDate('transaction_created_at', '>=', $startDate)
            ->whereDate('transaction_created_at', '<=', $endDate)
            ->where('transaction_status', '=', 'PAID')
            ->first();


        $data['beneficiarCount'] = $beneficiarCount;
        $data['totalTransactionCount'] = $totalTransactionCount;
        $data['totalAmount'] = number_format($transactions->totalAmount,2).' '.$transactions->source_currency;
        $data['totalCommision'] = number_format($transactions->totalCommision,2).' '.$transactions->source_currency;
        $data['totalMonoovaAmount'] = '';
        return response()->json($data);
    }
}