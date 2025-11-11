<?php
namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\Transaction;
use App\Models\Settings;
use Carbon\Carbon;

class TransactionsExport implements FromView, ShouldAutoSize
{
	private $fromDate = null;
    private $toDate = null;
    private $status = null;	

    public function __construct($fromDate = null, $toDate = null, $status = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->status = $status;
    }
    public function view(): View
    {
        
        $transactions = Transaction::orderBy('id','desc');
        if($this->status)
        {
            $transactions->where('transaction_status', $this->status);
        }
        if($this->fromDate)
        {
            $fromDate = Carbon::createFromFormat('d/m/Y', $this->fromDate)->format('Y-m-d H:i:s');
            // $transactions->where('created_at','>=', $fromDate);
            $transactions->where('transaction_created_at','>=', $fromDate);
        }
        if($this->toDate)
        {
            $toDate = Carbon::createFromFormat('d/m/Y', $this->toDate)->format('Y-m-d H:i:s');
            // $transactions->where('created_at','<=', $toDate);
            $transactions->where('transaction_created_at','<=', $toDate);
        }
        
        $transactions = $transactions->get();

        return view('admin.transactions.excelTemplate', [
            'transactions' => $transactions
        ]);
    }
}
?>