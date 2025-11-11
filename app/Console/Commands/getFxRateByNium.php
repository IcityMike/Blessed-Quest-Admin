<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Traits\UserPushNotificationTrait;
use App\Models\InsuranceUploadedDocument;
use App\Helpers\Common;
use App\Models\Settings;
use App\Models\Admin;
use Carbon\Carbon;
use DateTime;

class getFxRateByNium extends Command
{
    use UserPushNotificationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-fx-rate-by-nium';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get latest rate form nium';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Common::getUpdateFxRate();
    }
}
