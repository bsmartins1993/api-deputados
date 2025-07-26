<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('despesas:importar-todos')->daily(); // executa todo dia à meia-noite
        // Ou use ->hourly(), ->everyMinute(), etc., conforme sua necessidade
    }
}
