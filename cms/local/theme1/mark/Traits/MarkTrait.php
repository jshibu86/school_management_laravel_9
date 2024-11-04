<?php
namespace cms\mark\Traits;
use Carbon\carbon;
use cms\mark\Models\GradeSystemModel;
use cms\mark\Models\GradeModel;
use cms\mark\Models\MarkModel;
use Configurations;
use Exception;
use Illuminate\Support\Facades\DB;

trait MarkTrait
{
    private $date;
    private $time;
    public function __construct()
    {
        [$date, $time] = Configurations::getcurrentDateTime();
        $this->date = $date;
        $this->time = $time;
    }
    public function Getgradefrommark($mark)
    {
        $grade_system = Configurations::getConfig("mark")->grade_system;
        // dd($mark);

        $grade = GradeModel::where("grade_sys_name_id", $grade_system)
            ->where("mark_from", "<=", (int) $mark)
            ->where("mark_upto", ">=", (int) $mark)
            ->first();
        if ($grade) {
            return [
                $grade->grade_name,
                $grade->grade_point,
                $grade->grade_note,
            ];
        }
    }

    public function ProcessmarkEntry($request, $data)
    {
        //dd($this->date);
        //dd(Configurations::getcurrentDateTime());
        //dd($request->all(), $data);
        try {
            DB::beginTransaction();
         
            foreach ($data as $student_id => $markdata) {
                # code...
                // dd($markdata->distribution);
                $mark = new MarkModel();
                $mark->academic_year = $request->academic_year;
                $mark->term_id = $request->academic_term;
                $mark->class_id = $request->class_id;
                $mark->section_id = $request->section_id;
                $mark->subject_id = $request->subject_id;
                $mark->entry_date = $this->date;
                $mark->entry_time = $this->time;
                $mark->student_id = $student_id;
                $mark->distribution = $markdata->distribution;
                $mark->is_present = $markdata->present;
                $mark->grade = $markdata->grade;
                $mark->point = $markdata->point;
                $mark->remark = $markdata->note;
                $mark->total_mark = $markdata->total;
                $mark->save();
            }
            DB::commit();
            return $message = "Successfull Mark Added";
        } catch (\Exception $e) {
            DB::rollBack();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );

            throw new Exception($message);
        }
    }

    public function avgBasedStatus($percentage)
    {
        $promotion_percantage = Configurations::getConfig("site")
            ->promotion_percentage;

        if ((int) $percentage >= (int) $promotion_percantage) {
            return "<span class='text-success'>Pass</span>";
        } else {
            return "<span class='text-danger'>Failed</span>";
        }
    }
}
