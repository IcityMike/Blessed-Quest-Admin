<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReceivedTransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\TransactionHistory;
use App\Models\Subscription;
use App\Models\Subscription_user;
use App\Exports\TransactionsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Helpers\Common;
use App\Models\User;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use DB;

class TransactionsController extends Controller
{
    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Display list client users page
    ***/
    public function index()
    {
        return view('admin.transactions.index');
    }

    public function list(Request $request)
    {
        $transactions = '';
        $transactions = Transaction::select('id','user_name','transaction_amount','transaction_created_at','source_currency','destination_currency','transaction_status','transaction_amount','transaction_id','created_at','subscription_id')->orderByDesc('id')
          ->get();
        
        return datatables()->of($transactions)
            ->editColumn('subscription_name', function ($transactions) 
            {
                $subscription = Subscription_user::select('id','title','sub_title','user_id')->where('id',$transactions->subscription_id)->first();

                return @$subscription->title;
            })
            ->editColumn('transaction_status', function ($transactions) 
            {
                return '<span style="color:'.Common::getTransactionStatusColor()[$transactions->transaction_status].'">'.config('settings.nium_transaction_status')[$transactions->transaction_status].'</span>';
            })
            ->editColumn('transaction_amount', function ($transactions) 
            {
               
                return number_format(@$transactions->transaction_amount,2);
            })
            ->editColumn('transaction_created_at', function ($transactions) 
            {
                return date('d/m/Y H:i:s',strtotime(@$transactions->transaction_created_at));
            })
            ->escapeColumns([])
            ->addColumn('action', function ($transactions) 
            {
                $view_url = route('admin.transactions.view', $transactions->id);
                return '<a class="action-view btn btn-primary btn-action-icon action-edit edit-btn-clr" href="'.$view_url.'"><span class="eye-icon"></span></a>';
            })
          ->make(true);
    }

    public function view($transactionId)
    {
        $transaction = Transaction::where('id',$transactionId)->first();
        $transaction_status_histories = TransactionHistory::select('transaction_id','status','status_timestamp')->where('transaction_id',@$transaction->transaction_id)->whereNotIn('status',['IN_PROCESS','PAID'])->get();
   
        $userData = User::where('id',@$transaction->user_id)->first();

        $subscription_userGet = Subscription_user::where('id',@$transaction->subscription_id)->first();
        @$subscription_service = explode(",", @$subscription_userGet->services);
        return view('admin.transactions.view',compact('transaction','transaction_status_histories','subscription_userGet','subscription_service','userData'));
    }

    public function RecievedIndex(Request $request)
    {
        return view('admin.transactions.Recievedindex');
    }

}