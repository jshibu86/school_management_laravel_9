<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PayrollSheduleExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($schedule_data = [])
    {
        $this->schedule_data = $schedule_data;
    }
    public function view(): View
    {
        //dd($this->schedule_data, "from");

        return view("payrool::schedule.export", [
            "schedule_data" => $this->schedule_data,
        ]);
    }
}
