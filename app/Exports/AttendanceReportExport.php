<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class AttendanceReportExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct(
        $allDates,
        $allDatesInMonth,
        $current_academic_year_info
    ) {
        $this->allDates = $allDates;
        $this->allDatesInMonth = $allDatesInMonth;
        $this->current_academic_year_info = $current_academic_year_info;
    }
    public function view(): View
    {
        return view("report::admin.report.attendance.excelexport", [
            "allDates" => $this->allDates,
            "calender" => $this->allDatesInMonth,
            "current_academic_year_info" => $this->current_academic_year_info,
        ]);
    }
}
