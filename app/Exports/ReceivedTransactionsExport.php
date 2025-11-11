<?php
namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\MonoovaAddMoneyTransaction;
use App\Models\Settings;
use Carbon\Carbon;

class ReceivedTransactionsExport implements FromView, ShouldAutoSize
{
	private $fromDate = null;
    private $toDate = null;

    public function __construct($fromDate = null, $toDate = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }
    public function view(): View
    {
        
        $transactions = MonoovaAddMoneyTransaction::orderBy('id','desc');
        
        if($this->fromDate)
        {
            $fromDate = Carbon::createFromFormat('d/m/Y', $this->fromDate)->format('Y-m-d H:i:s');
            $transactions->where('created_at','>=', $fromDate);
        }
        if($this->toDate)
        {
            $toDate = Carbon::createFromFormat('d/m/Y', $this->toDate)->format('Y-m-d H:i:s');
            $transactions->where('created_at','<=', $toDate);
        }
        
        $transactions = $transactions->get();
        
        return view('admin.transactions.ReceivedTransactionexcelTemplate', [
            'transactions' => $transactions
        ]);
    }
}
?>