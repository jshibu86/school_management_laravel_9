<?php
/*
 * get countries data
 */
Route::post("get-report-data", "ReportController@getData")->name(
    "get_report_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-report/{action?}",
    "ReportController@statusChange"
)->name("report_action_from_admin");
/*
 * resource controller
 */

Route::any("getmarkreport", "ReportController@Getmarkreport")->name(
    "Getmarkreport"
);

Route::post("savemarkreport", "ReportController@savereport")->name(
    "savereport"
);

Route::any("broadsheet-report", "ReportController@broadsheet")->name(
    "broadsheet"
);

Route::any("dompdf", "ReportController@dompdf")->name("dompdf");

// student report module

Route::any("studentreport", "StudentReportController@index")->name(
    "studentreport"
);

//classreport

Route::any("classreport", "ClassReportController@index")->name("classreport");

//leavereport

Route::any("leavereport", "LeaveReportController@index")->name("leavereport");

// payroll
Route::any("payrollreport", "PayrollReportController@index")->name(
    "payrollreport"
);
Route::any(
    "payrollbulkprint",
    "PayrollReportController@payrollbulkprint"
)->name("payrollbulkprint");

Route::any(
    "payrolltotalamount",
    "PayrollReportController@payrolltotalamount"
)->name("payrolltotalamount");

Route::any(
    "studentidcard/bulk/{type?}",
    "StudentReportController@StudentIdcard"
)->name("StudentIdcard");

// transport report
Route::any("transportreport", "TransportReportController@index")->name(
    "transportreport"
);

//hostel report

Route::any("hostel", "HostelReportController@index")->name("hostelreport");

// attendance report
Route::any("attendancereport", "AttendanceReportController@index")->name(
    "attendancereport"
);

// attendance report
Route::any("tuckshopreport", "TuckShopController@index")->name(
    "tuckshopreport"
);

Route::any(
    "certificate/bulk/{type?}",
    "StudentReportController@StudentCertificate"
)->name("certificatebulk");

Route::any("certificate", "StudentReportController@StudentCertificate")->name(
    "certificatebulkreport"
);
Route::any(
    "certificateconfguration",
    "StudentReportController@StudentCertificateConfiguration"
)->name("certificateconfigurations");

Route::post(
    "configurationstore",
    "StudentReportController@storeconfigurations"
)->name("configurationsstore");

Route::any("gradereportoverall", "GradeReportController@index1")->name(
    "gradereportoverall"
);

Route::any("gradereportsubject", "GradeReportController@index1")->name(
    "gradereportsubject"
);

Route::post('gradestudentreport', "GradeReportController@studentReportView")->name(
    "grade_student_report_view"
);
Route::post('gradestudentsubjectreport', "GradeReportController@studentSubjectReportView")->name(
    "grade_student_subject_report_view"
);
Route::get('gradestudentreport_data', "GradeReportController@studentReportData")->name(
    "gradestudentreport_data"
);
// Route::any("gradereportsubject", "GradeReportController@index2")->name(
//     "gradereportsubject"
// );

Route::get('getsubject_percentage',"GradeReportController@getSubjectPercentage")->name("getsubject_percentage");

Route::get('getexam_report_result',"GradeReportController@getExamResult")->name("getexam_report_result");