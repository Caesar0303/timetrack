<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ShiftCard;

class ResetCardStatus extends Command
{
    protected $signature = 'reset:card-status';
    protected $description = 'Reset card status to 0 every day';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        ShiftCard::where('status', '!=', 0)->update(['status' => 0]);
        $this->info('Card statuses have been reset to 0.');
    }
}
