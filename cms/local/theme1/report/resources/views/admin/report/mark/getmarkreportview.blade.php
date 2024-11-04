
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Mark Report</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/fontawesome/css/font-awesome.min.css" />
  </head>

  <style>
    .scroll-view{overflow: scroll}
  </style>
  <style type="text/css" media="print">
	@page {
		size: auto;   /* auto is the initial value */
		margin: auto;  /* this affects the margin in the printer settings */
	}
  .table-start{
    font-size: 10px !important;
  }
  .main-table{
   
    font-size: 6px !important
  }
  .pagebreak { page-break-before: always; } /* page-break-after works, as well */
  
	</style>
  <body leftmargin="0"marginheight="0"marginwidth="0"offset="0"topmargin="0">
    <center>
      @if (@$type!="get")
      {{-- <div style="text-align: right">
         <button type="button" class="btn btn-success mb-2 m-2 print_report">Print</button>
      </div>  --}}
     
        
      @endif
      <form method="post" action="{{route('savereport')}}">
        @csrf
      <div style="border: 1px solid #000">
        <input type="hidden" name="student_id" value="{{@$student_id}}"/>
        <input type="hidden" name="term_id" value="{{@$term_id}}"/>
        <input type="hidden" name="academic_year" value="{{@$acyear_id}}"/>
        <input type="hidden" name="exam_type" value="{{@$exam_type}}"/>
        <div  style="border: 1px solid #000" class="table-start">
          <table style="width: 100%; border: 1px solid #000"cellpadding="0"cellspacing="0">
            <tbody>
              <tr>
                <td>
                  <td align="center"valign="top"class="borderblue padding1"style="width: 12%">
                    <img alt="Logo"src="{{ asset(@$config->imagec) }}"width="100"/>
                  </td>
                  <td style="width: 76%; padding:4px; ">
                    <div style="">
                      <center>
                        <h1 style="font-weight: 700; width: 100%; margin: 0">
                         {{ @$config->school_name }}
                        </h1>
                        <h5 style="margin: 0; font-size: 21px; font-weight: 100">
                          {{ @$config->place }},{{ @$config->city }},{{ @$config->country }}
                        </h5>
                        <h4 style="margin: 7px 0 0 0;font-size: 18px;font-weight: 200;">
                         {{ @$config->school_phone }}-{{ @$config->school_email }}
                        </h4>
                      </center>
                    </div>
                    <h4 style=" text-align: center;color: #ff0000;margin: 7px 0 0 0;font-size: 21px;font-weight: 700;">
                      TERMINAL REPORT
                    </h4>
                  </td>
                  <td align="center"valign="top"class="borderblue padding1"style="width: 12%">
                    <img alt="Logo"src="{{@$student_info->image ? asset(@$student_info->image) : asset("assets/images/default.jpg")}}"width="100"/>
                  </td>
                </td>
              </tr>
            </tbody>
          </table>

          <table style="width: 100%;border: 1px solid black;border-collapse: collapse;">
            <tbody>
              <tr style="font-weight: 700;">
                <td style="color: #4e1489;border: 1px solid black;padding: 15px 6px 15px 6px;">
                  <span>NAME OF STUDENT:</span>
                  <span style="color: #000;margin-left: 8px;"> {{@$student_info->first_name}} {{@$student_info->last_name}}</span>
                </td>
                <td style="color: #4e1489;border: 1px solid black; padding:15px 6px 15px 6px;">
                  <span>  ADMISSION N0.:</span>
                  <span style="color: #000;"> {{@$student_info->reg_no}}</span>
                </td> 
                <td style="color: #4e1489;border: 1px solid black; padding:15px 6px 15px 6px;">
                  <span>CLASS:</span>
                  <span style="color: #000;"> {{@$student_info->class->name}}</span>
                </td>
              </tr>
            </tbody>
          </table>

          <table style="width: 100%;border: 1px solid black;border-collapse: collapse;">
            <tbody>
              <tr style="font-weight: 700;">
                <td style="color: #4e1489;border: 1px solid black;padding:15px 6px 15px 6px;">
                  <span> NUMBER IN CLASS:</span>
                  <span style="color: #000;">  {{@$student_info->roll_no}}</span>
                </td>
                <td style="color: #4e1489;border: 1px solid black;padding:15px 6px 15px 6px;">
                  <span>TERM:</span>
                  <span style="color: #000;"> {{@$selected_term}}  @if (Configurations::getConfig("site")->promotion_type ==0 && @$term_id == @$last_terms_id) /Cumulative @endif</span>
                </td> 
                <td style="color: #4e1489;border: 1px solid black;padding:15px 6px 15px 6px;">
                  <span>SESSION:</span>
                  <span style="color: #000;"> {{@$acyear}}</span>
                </td>
                <td style="color: #4e1489;border: 1px solid black;padding:15px 6px 15px 6px;">
                  <span> STATUS :</span>
                  @php
                  if($status){
                    $color =($status && $status == "Passed") ? "color: #379242" : "color:#ff0000";
                  }
              
                
                @endphp
                <span style="{{@$color}}">{{@$status}}</span>
                </td>
              </tr>
            </tbody>
          </table>
           
          <table style="width: 100%;border: 1px solid black;border-collapse: collapse;">
            <tbody>
              <tr style="font-weight: 700;">
                <td style="color: #4e1489;border: 1px solid black;padding: 15px 6px 15px 6px;">
                  <span>TOTAL MARKS OBTAINABLE:</span>
                   <input type="hidden" name="total_mark_obtainable" value="{{@$total_obtainable}}"/>
                  <span style="color: #000;">{{@$total_obtainable}}</span>
                </td>
                <td style="color: #4e1489;border: 1px solid black;padding: 15px 6px 15px 6px;">
                  <span>TOTAL MARKS OBTAINED:</span>
                  <input type="hidden" name="total_mark_obtain" value="{{@$total_obtain}}"/>
                  <input type="hidden" name="is_promotion" value="{{@$is_promotion}}"/>
                  <span style="color: #000;">{{@$total_obtain}}</span>
                </td>
                @if (@$is_promotion)
                  <td style="color: #4e1489;border: 1px solid black;padding: 15px 6px 15px 6px;">
                  <span>AVERAGE:</span>
                  <input type="hidden" name="average" value="{{@$Average}}"/>
                  <span style="color: #000;">{{@$Average}}</span>
                </td>
                @endif
                 @if (Configurations::getConfig("site")->promotion_type ==0 && @$term_id == @$last_terms_id)
                    <td style="color: #4e1489;border: 1px solid black;padding: 15px 6px 15px 6px;">
                  <span>CUMULATIVE AVERAGE:</span>
                  <input type="hidden" name="average" value="{{round(@$cumulative_total_avg)}}"/>
                  <span style="color: #000;">{{round($cumulative_total_avg)}}</span>
                </td>
                 @endif
                
                <td style="color: #4e1489;border: 1px solid black;padding: 15px 6px 15px 6px;">
                  <span>POSITION:</span>
                  <span style="color: #000;">{{@$position}}</span>
                </td>
              </tr>
            </tbody>
          </table>
                        

          <table  cellpadding="0"cellspacing="0"style="width: 100%">
            <tbody>
                <tr style="background-color: #673ab7">
                  <td>
                    <center>
                      <h2 style="font-size: 17px;color: white; margin: 7px 0">
                        CONGNITIVE DOMAIN
                      </h2>
                    </center>
                  </td>
                </tr>
            </tbody>
          </table>
          <div class="scroll-view">
          <table border="1" class="main-table" style="width: 100%;border: 1px solid black;border-collapse: collapse;">
            <tbody  align="center">
              {{-- changes --}}
               @if (Configurations::getConfig("site")->promotion_type ==0 && @$term_id == @$last_terms_id)
              <tr>
                 <td rowspan="2" style="width: 315px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489"> Subjects</td>
              <td colspan="7" style=" border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489;font-weight: 700;">{{@$selected_term}} Result</td>
                <td colspan="7" style=" border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489;font-weight: 700;">Cumulative Result</td>
               
              </tr>
              @endif
                {{-- changes --}}
              <tr>
                  @if (Configurations::getConfig("site")->promotion_type ==1 ||  @$term_id != @$last_terms_id)
                <td style="width: 315px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489"> Subjects</td>
                @endif
                @if (@$distribution)

                  @foreach (@$distribution as $distribution_data)
                        @if (str_contains(@$distribution_data['distributionname'],"Exam"))
                        <td style="vertical-align: bottom; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">{{@$distribution_data['distributionname']}}</td>
                        @else
                          <td style="width: 40px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">{{@$distribution_data['distributionname']}}</td>
                        @endif
                     
                  @endforeach
                  
                @endif
               

               
                <td style="vertical-align: bottom; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">Total</td>
                <td style="vertical-align: bottom; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">Grade</td>
                {{-- <td style="width: 60px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">Subject Position</td> --}}
                <td style="width: 50px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">Grade remark</td>
                  {{-- changes if cumulative --}}
                @if (Configurations::getConfig("site")->promotion_type ==0 && @$term_id == @$last_terms_id)

                  @foreach (@$terms as $term )
                     <td style="width: 50px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">{{$term->exam_term_name}}</td>
                  @endforeach
                
                <td style="width: 50px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">Cumulative Total</td>
                <td style="width: 50px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">Cumulative Average</td>
                <td style="width: 50px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">Grade</td>
                <td style="width: 50px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">GradeRemark</td>
                @endif
               
                  {{-- changes --}}
               
               
                
              </tr>

              <tr>
                <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;">Marks Obtainable</td>

                @if (@$distribution)

                  @foreach (@$distribution as $distribution_data)
                        @if (str_contains(@$distribution_data['distributionname'],"Exam"))
                        <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$distribution_data['originalmark']}}</td>
                        @else
                          <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$distribution_data['originalmark']}}</td>
                        @endif
                       
                            
                  @endforeach
                  
                @endif
                
               
               
                <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;"> </td>
                <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;"> </td>
                  {{-- changes --}}
                @if (Configurations::getConfig("site")->promotion_type ==0 && @$term_id == @$last_terms_id) 
                <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;"> </td>
                <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;"> </td>
                <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;"> </td>
                <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;"> </td>
                <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;"> </td>
                <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;"> </td>
                <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;"> </td>
                @endif
                  {{-- changes --}}
               
              </tr>

              @if (sizeof($mark_into))

                @foreach (@$mark_into as $info )
              <tr>
                <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px; font-weight: 700; padding-left:8px" align="start">{{@$info->subject->name}}</td>
                @foreach (@$info->distribution as $distribution_data)
                          @if (str_contains(@$distribution_data['distributionname'],"Exam"))
                          <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$distribution_data['mark']}}</td>
                          @endif
                          @if (str_contains(@$distribution_data['distributionname'],"Home Work"))
                            <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$distribution_data['mark']}}</td>
                        @endif

                          
               
                     
                  @endforeach
              
             
                <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;" data-st="{{$info}}">{{$info->total_mark}}</td>
                <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;">{{$info->grade}}</td>
                {{-- <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">12<sup>th</sup></td> --}}
                <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;">{{$info->remark}}</td>

                {{-- changes --}}
                @if (Configurations::getConfig("site")->promotion_type ==0 && @$term_id == @$last_terms_id)
                @foreach (@$terms as $data )
                @php
                  $termmark=@$mark_data[$info->subject_id];
                  $decoded=(array) $termmark;
                @endphp
                   <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;"> {{$decoded[$data->id]->mark}}</td>
                @endforeach
                  <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{$decoded['total']}} </td>
                  <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;"> {{round($decoded['avg'])}}</td>
                  <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;">{{$decoded['grade']}} </td>
                  <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;"> {{$decoded['note']}}</td>
               
                @endif
                  {{-- changes --}}
              </tr>
                @endforeach
                
              @endif

              


             


              

            </tbody>
          </table>
          </div>

          
          <table  cellpadding="0"cellspacing="0"style="width: 100%">
            <tbody>
                <tr style="background-color: #673ab7">
                  <td>
                    <center>
                      <h2 style="font-size: 17px;color: white; margin: 7px 0">REMARK'S , AFFECTIVE AND PSYCHOMOTOR DOMAIS</h2>
                    </center>
                  </td>
                </tr>
            </tbody>
          </table>
          <div class="scroll-view">
          <table style="width: 100%;">
            <tbody style="vertical-align: top">
              <tr>
                <td>

                  <table style="width: 100%;border: 1px solid black;border-collapse: collapse;">
                    <tbody>

                      <tr>
                        <td  style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">Class Teacher Remarks:</td>
                        <td  style="color: #000;padding: 10px;border: 1px solid black">
                        @if (!@$is_reportinfo)

                        <textarea name="teacher_remark" class="form-control" placeholder="teacher remarks..">{{@$reportinfo->teacher_remark}}
                        </textarea>

                        @else

                       {{@$reportinfo->teacher_remark}}
                          
                        @endif
                        </td>
                      </tr>

                      <tr>
                        <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">Vaction Date:</td>
                        <td style="color: #000;padding: 10px;border: 1px solid black">
                        @if (!@$is_reportinfo)

                          <input type="date" name="vaction_date" class="form-control" value="{{@$reportinfo->vaction_date}}"/>
                        @else

                       {{@$reportinfo->vaction_date}}
                          
                        @endif
                        </td>
                      </tr>

                      <tr>
                        <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">Resumption Date:</td>
                        <td style="color: #000;padding: 10px;border: 1px solid black">
                         @if (!@$is_reportinfo)

                          <input type="date" name="resumption_date" class="form-control" value="{{@$reportinfo->resumption_date}}"/>
                        @else

                       {{@$reportinfo->resumption_date}}
                          
                        @endif
                        </td>
                      </tr>

                    </tbody>
                  </table>

                  <table style="width: 100%;border: 1px solid black;border-collapse: collapse;">
                    <tbody>
                      <tr>
                        <td style="color: #4e1489;border: 1px solid black;padding: 21px 6px 10px 6px; vertical-align: top;">Result Analysis:(Criteria for passing)</td>
                        <td style="color: #4e1489;border: 1px solid black">

                        {!! Configurations::getConfig("site")->mark_report_message!!}
                          {{-- <ul style="color: #379242; padding-left: 20px;margin: 10px;">
                            <li>Commpulsory subjects to pass is Mathematics Or English, You Passed Mathematics | You Passed English Language</li>
                            <li>Minimum subject to offer is 12, you offered 15</li>
                            <li>MInimum subject is pased is 10, you passed 15 (pass mark is 50 )</li>
                            <li>Promotion score is 50,you scored 65.13</li>
                          </ul> --}}
                        </td>
                      </tr>

                      <tr>
                        <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">GRADING</td>
                        <td style="color: #4e1489;border: 1px solid black">

                           @if (Configurations::getGradeInfo())

                         
                         
                          <ul style="padding-left: 20px; margin: 10px;">
                             @foreach (Configurations::getGradeInfo() as $grade)
                            <li>{{$grade->mark_from}} - {{$grade->mark_upto}} : {{$grade->grade_name}} ({{$grade->grade_note}})</li>
                            @endforeach
                          </ul>
                          @endif
                        </td>
                      </tr>

                    </tbody>
                  </table>
                </td>

                <td>
                  <table style="width: 100%;border: 1px solid black;border-collapse: collapse;">
                    <thead>
                      <th style="width: 40%; text-align: start; color: #ff0000!important;border: 1px solid black;padding: 10px 0 10px 6px;">AFFECTIVE DOMAIN</th>
                      <th style="width: 12%; color: #ff0000!important;border: 1px solid black;padding: 10px 6px 10px 6px;">Excel.</th>
                      <th style="width: 12%; color: #ff0000!important;border: 1px solid black;padding: 10px 6px 10px 6px;">V.Good</th>
                      <th style="width: 12%; color: #ff0000!important;border: 1px solid black;padding: 10px 6px 10px 6px;">Good</th>
                      <th style="width: 12%; color: #ff0000!important;border: 1px solid black;padding: 10px 6px 10px 6px;">Poor</th>
                      <th style="width: 12%; color: #ff0000!important;border: 1px solid black;padding: 10px 6px 10px 6px;">V.Poor</th>
                    </thead>
                    <tbody>

                    

                      @foreach (['Neatness','Honesty','Puntuality'] as $afdomain)
                      <tr>
                      <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$afdomain}}</td>

                      @if (!@$is_reportinfo)
                        
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">
                      
                      <input type="radio" name="afdomain[{{$afdomain}}]" value="1" {{@$reportinfo->afdomain[$afdomain]==1 ? "checked" : ""}}/>
                      </td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">
                      
                      <input type="radio" name="afdomain[{{$afdomain}}]" value="2" {{@$reportinfo->afdomain[$afdomain]==2 ? "checked" : ""}}/>
                      </td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">
                      
                      <input type="radio" name="afdomain[{{$afdomain}}]" value="3" {{@$reportinfo->afdomain[$afdomain]==3 ? "checked" : ""}}/>
                      </td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">
                      
                      <input type="radio" name="afdomain[{{$afdomain}}]" value="4" {{@$reportinfo->afdomain[$afdomain]==4 ? "checked" : ""}}/>
                      </td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">
                      
                      <input type="radio" name="afdomain[{{$afdomain}}]" value="5" {{@$reportinfo->afdomain[$afdomain]==5 ? "checked" : ""}}/>
                      </td>
                      
                      @else


                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$reportinfo->afdomain[$afdomain]==1 ? '✔' : "-"}}</td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$reportinfo->afdomain[$afdomain]==2 ? '✔' : "-"}}</td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$reportinfo->afdomain[$afdomain]==3 ? '✔' : "-"}}</td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$reportinfo->afdomain[$afdomain]==4 ? '✔' : "-"}}</td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$reportinfo->afdomain[$afdomain]==5 ? '✔' : "-"}}</td>
                      @endif
                     
                     </tr>
                      @endforeach
                    
                    
                     
                    </tbody>
                  </table>
                  <div class="pagebreak"> </div>
                  <table style="width: 100%;border: 1px solid black;border-collapse: collapse;">
                    <thead>
                      <th style="width: 40%; color: #ff0000!important;border: 1px solid black!important;padding: 10px 0 10px 6px; text-align: start;">PSYCHOMOTOR DOMAIN</th>
                      <th style="width: 12%; color: #ff0000!important;border: 1px solid black!important;padding: 10px 6px 10px 6px;">Excel.</th>
                      <th style="width: 12%; color: #ff0000!important;border: 1px solid black!important;padding: 10px 6px 10px 6px;">V.Good</th>
                      <th style="width: 12%; color: #ff0000!important;border: 1px solid black!important;padding: 10px 6px 10px 6px;">Good</th>
                      <th style="width: 12%; color: #ff0000!important;border: 1px solid black!important;padding: 10px 6px 10px 6px;">Poor</th>
                      <th style="width: 12%; color: #ff0000!important;border: 1px solid black!important;padding: 10px 6px 10px 6px;">V.Poor</th>
                    </thead>
                    <tbody>

                       @foreach (['Sports','Handwritting','Musical Skills'] as $pfdomain)
                      <tr>
                      <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$pfdomain}}</td>

                      @if (!@$is_reportinfo)
                        
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">
                      
                      <input type="radio" name="pfdomain[{{$pfdomain}}]" value="1" {{@$reportinfo->pfdomain[$pfdomain]==1 ? "checked" : ""}}/>
                      </td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">
                      
                      <input type="radio" name="pfdomain[{{$pfdomain}}]" value="2" {{@$reportinfo->pfdomain[$pfdomain]==2 ? "checked" : ""}}/>
                      </td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">
                      
                      <input type="radio" name="pfdomain[{{$pfdomain}}]" value="3" {{@$reportinfo->pfdomain[$pfdomain]==3 ? "checked" : ""}}/>
                      </td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">
                      
                      <input type="radio" name="pfdomain[{{$pfdomain}}]" value="4" {{@$reportinfo->pfdomain[$pfdomain]==4 ? "checked" : ""}}/>
                      </td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">
                      
                      <input type="radio" name="pfdomain[{{$pfdomain}}]" value="5" {{@$reportinfo->pfdomain[$pfdomain]==5 ? "checked" : ""}}/>
                      </td>
                      
                      @else


                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$reportinfo->pfdomain[$pfdomain]==1 ? '✔' : "-"}}</td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$reportinfo->pfdomain[$pfdomain]==2 ? '✔' : "-"}}</td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$reportinfo->pfdomain[$pfdomain]==3 ? '✔' : "-"}}</td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$reportinfo->pfdomain[$pfdomain]==4 ? '✔' : "-"}}</td>
                      <td  align="center" style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$reportinfo->pfdomain[$pfdomain]==5 ? '✔' : "-"}}</td>
                      @endif
                     
                     </tr>
                      @endforeach

                      

                      

                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
          </div>

        </div>
      </div>
      @if (!@$is_reportinfo)
         <button class="btn btn-primary mt-3" type="submit">Save Report</button>
      @else
          <button class="btn btn-primary mt-3 edit_student_report" onclick="ReportConfig.EditreportCard()" type="button">Edit Report</button>
      @endif

      </form>
     
    </center>
  </body>

  <script>

    var button=document.querySelector(".print_report");

button.addEventListener("click",function(e){
  e.preventDefault();
	$("#invoice").show();
	$(".sidebar-wrapper").hide();
	$(".top-header").hide();
	$(".footer").hide();
	$(".box-header").hide();
	$(".radius-15").hide();
	$(".card-main").hide();
  $(".print_report").hide();
  $(".card").css("box-shadow","none");

	$('.page-content-wrapper').css('margin-left','0px');
	$('.page-content-wrapper').css('margin-top','0px');
	$(".page-wrapper").css("margin-top","0px");
	window.print();
	$("#invoice").hide();
	$(".sidebar-wrapper").show();
	$(".top-header").show();
	$(".footer").show();
	$(".box-header").show();
	$(".radius-15").show();
  $(".card-main").show();
   $(".print_report").show();
    $(".card").css("box-shadow","0 0.1rem 0.7rem rgba(0, 0, 0, 0.1)");


	$('.page-content-wrapper').css('margin-left','260px');
	$('.page-content-wrapper').css('margin-top','70px');
	$(".page-wrapper").css("margin-top","70px");
});
  </script>
</html>
