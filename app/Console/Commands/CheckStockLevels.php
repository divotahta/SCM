<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\NotificationController;

class CheckStockLevels extends Command
{
    protected $signature = 'stocks:check-levels';
    protected $description = 'Check stock levels and create notifications for low stock items';

    public function handle()
    {
        $controller = new NotificationController();
        $notifications = $controller->checkStockLevels();

        $this->info('Stock levels checked. Created ' . count($notifications) . ' notifications.');
    }
} 