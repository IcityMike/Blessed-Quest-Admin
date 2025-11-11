<?php
namespace App\Exports;

use App\Models\ActivityLog;
use App\Models\Settings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ActivityLogExport implements FromView, ShouldAutoSize
{
    private $startDate = null;
    private $endDate = null;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    
    public function view(): View
    {
        return view('admin.activity_log.excelTemplate', [
            'logHistory' => ActivityLog::getHistory($this->startDate, $this->endDate)
        ]);
    }

}
?>