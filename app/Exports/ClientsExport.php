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

class ClientsExport implements FromView, ShouldAutoSize
{
	private $status = null;

    public function __construct($status = null)
    {
        $this->status = $status;
    }
    public function view(): View
    {
        $clients = User::orderBy('id','desc');
        
        if($this->status)
        {
            $clients->where('status', $this->status);
        }
        $clients = $clients->get();
        return view('admin.clients.excelTemplate', [
            'clients' => $clients
        ]);
    }
}
?>