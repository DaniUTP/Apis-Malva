<?php

namespace App\Jobs;

use App\Models\Reservas;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ReservaJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Reservas::where('fecha_reserva','<',DB::raw('NOW()'))->update(['estado'=>0]);
        ReservaJob::dispatch()->delay(now()->addDays(1));
    }
}
