<?php
namespace App\Exports;

use App\Models\SupportTicket;
use App\Models\SupportTicketCategory;
use App\Models\Settings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class SupportTicketExport implements FromView, ShouldAutoSize
{
	private $fromDate = null;
    private $toDate = null;
    private $status = null;	
    private $category = null;	

    public function __construct($fromDate = null, $toDate = null, $status = null,$category = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->status = $status;
        $this->category = $category;
    }
    public function view(): View
    {
        
        $supportTickets = SupportTicket::with(['category','ticketStatus','client'])->orderBy('id','desc');
        if($this->status)
        {
            $supportTickets->where('status', $this->status);
        }
        if($this->category){
        	$supportTickets->where('category_id', $this->category);
        }
        if($this->fromDate)
        {
            $fromDate = Carbon::createFromFormat('d/m/Y', $this->fromDate)->format('Y-m-d H:i:s');
            $supportTickets->where('created_at','>=', $fromDate);
        }
        if($this->toDate)
        {
            $toDate = Carbon::createFromFormat('d/m/Y', $this->toDate)->format('Y-m-d H:i:s');
            $supportTickets->where('created_at','<=', $toDate);
        }
        $supportTickets = $supportTickets->get();
        return view('admin.contact.excelTemplate', [
            'supportTickets' => $supportTickets
        ]);
    }
}
?>