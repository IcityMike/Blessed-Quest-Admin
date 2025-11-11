<?php
namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\MonoovaToNiumTransactionHistory;
use App\Models\Settings;
use Carbon\Carbon;

class AddMoneyTransactionsExport implements FromView, ShouldAutoSize
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
        
       
    }
}
?>