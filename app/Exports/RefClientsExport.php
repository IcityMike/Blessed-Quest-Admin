<?php
namespace App\Exports;

use App\Models\User;
use App\Models\Settings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class RefClientsExport implements FromView, ShouldAutoSize
{
	private $status = null;
    private $userId = null;

    public function __construct($status = null,$userId = null)
    {
        $this->status = $status;
        $this->userId = $userId;
    }
    public function view(): View
    {
        $clients = User::where('reffered_by',$this->userId)->orderBy('id','desc');
        
        if($this->status)
        {
            $clients->where('status', $this->status);
        }
        $clients = $clients->get();

        return view('refferal.clients.excelTemplate', [
            'clients' => $clients
        ]);
    }
}
?>