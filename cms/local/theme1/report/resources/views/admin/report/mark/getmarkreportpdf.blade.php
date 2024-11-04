
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Mark Report</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/fontawesome/css/font-awesome.min.css" />
  </head>

  <style>
    .scroll-view{overflow: scroll}
  </style>
 
  <body >
    <div style="border: 1px solid #000">
     
      <div  style="border: 1px solid #000" class="table-start">
        <table style="width: 100%; border: 1px solid #000">
          <tbody>
            <tr>
              <td>
                <td align="center"valign="top"class="borderblue padding1"style="width: 12%">
                  <img alt="Logo"src="{{ public_path(@$image) }}"width="100"/>
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
                  <img alt="Logo"src="{{ public_path(@$student_info->image) }}"width="100"/>
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
                <span style="color: #000;"> {{@$term_name}}  @if ($promotion_type ==0 && @$term_id == @$last_terms_id) /Cumulative @endif</span>
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
                <span style="color: #000;">{{@$mark_info->total_mark_obtainable}}</span>
              </td>
              <td style="color: #4e1489;border: 1px solid black;padding: 15px 6px 15px 6px;">
                <span>TOTAL MARKS OBTAINED:</span>
                <span style="color: #000;">{{@$mark_info->total_mark_obtain}}</span>
              </td>
              @if (@$mark_info->is_promotion == 1)
                <td style="color: #4e1489;border: 1px solid black;padding: 15px 6px 15px 6px;">
                <span>AVERAGE:</span>
                <span style="color: #000;">{{@$mark_info->average}}</span>
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
            
              @if ($promotion_type ==0 && @$term_id == @$last_terms_id)
              <tr>
                <td rowspan="2"style="width: 215px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489"> Subjects</td>
              <td colspan = "7" style="  border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489;font-weight: 700;">{{@$term_name}} Result</td>
                <td colspan="7" style=" border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489;font-weight: 700;">Cumulative Result</td>
              
              </tr>
              @endif
              
              <tr>
                  @if ($promotion_type ==1 ||  @$term_id != @$last_terms_id)
                <td style="width: 215px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489"> Subjects</td>
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
              
                <td style="width: 50px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">Grade remark</td>
                
                @if ($promotion_type ==0 && @$term_id == @$last_terms_id)

                  @foreach (@$terms as $term_data )
                    <td style="width: 50px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">{{$term_data->exam_term_name}}</td>
                  @endforeach
                
                <td style="width: 50px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">Cumulative Total</td>
                <td style="width: 50px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">Cumulative Average</td>
                <td style="width: 50px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">Grade</td>
                <td style="width: 50px; border: 1px solid black;padding: 10px 6px 10px 6px; color: #4e1489">GradeRemark</td>
                @endif
              
                  
              
              
                
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
                @if ($promotion_type ==0 && @$term_id == @$last_terms_id) 
                 <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;"> </td>
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
              
                <td style="color: #379242;border: 1px solid black;padding: 10px 6px 10px 6px;">{{$info->remark}}</td>

                
                @if ($promotion_type ==0 && @$term_id == @$last_terms_id)
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
               

                  <table style="width: 100%;border: 1px solid black;border-collapse: collapse;">
                    <tbody>

                      <tr>
                        <td  style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">Class Teacher Remarks:</td>
                        <td  style="color: #000;padding: 10px;border: 1px solid black">
                      

                      {{@$mark_info->teacher_remark}}
                          
                       
                        </td>
                      </tr>

                      <tr>
                        <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">Vaction Date:</td>
                        <td style="color: #000;padding: 10px;border: 1px solid black">
                      

                      {{@$mark_info->vaction_date}}
                          
                      
                        </td>
                      </tr>

                      <tr>
                        <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">Resumption Date:</td>
                        <td style="color: #000;padding: 10px;border: 1px solid black">
                       

                      {{@$mark_info->resumption_date}}
                          
                       
                        </td>
                      </tr>

                    </tbody>
                  </table>

                  <table style="width: 100%;border: 1px solid black;border-collapse: collapse;">
                    <tbody>
                      <tr>
                        <td style="color: #4e1489;border: 1px solid black;padding: 21px 6px 10px 6px; vertical-align: top;">Result Analysis:(Criteria for passing)</td>
                        <td style="color: #4e1489;border: 1px solid black">

                        {!! $mark_report_message!!}
                      
                        </td>
                      </tr>

                      <tr>
                        <td style="color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">GRADING</td>
                        <td style="color: #4e1489;border: 1px solid black">

                          @if ($grade_info)

                        
                        
                          <ul style="padding-left: 20px; margin: 10px;">
                            @foreach ($grade_info as $grade)
                            <li>{{$grade->mark_from}} - {{$grade->mark_upto}} : {{$grade->grade_name}} ({{$grade->grade_note}})</li>
                            @endforeach
                          </ul>
                          @endif
                        </td>
                      </tr>

                    </tbody>
                  </table>
                </tr>

                <tr>
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

                     


                      <td  align="center" style="font-family: DejaVu Sans, sans-serif;color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$mark_info->afdomain[$afdomain]==1 ? '✔' : "-"}}</td>
                      <td  align="center" style="font-family: DejaVu Sans, sans-serif;color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$mark_info->afdomain[$afdomain]==2 ? '✔' : "-"}}</td>
                      <td  align="center" style="font-family: DejaVu Sans, sans-serif;color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$mark_info->afdomain[$afdomain]==3 ? '✔' : "-"}}</td>
                      <td  align="center" style="font-family: DejaVu Sans, sans-serif;color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$mark_info->afdomain[$afdomain]==4 ? '✔' : "-"}}</td>
                      <td  align="center" style="font-family: DejaVu Sans, sans-serif;color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$mark_info->afdomain[$afdomain]==5 ? '✔' : "-"}}</td>
                     
                    
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

                    


                      <td  align="center" style="font-family: DejaVu Sans, sans-serif;color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$mark_info->pfdomain[$pfdomain]==1 ? '✔' : "-"}}</td>
                      <td  align="center" style="font-family: DejaVu Sans, sans-serif;color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$mark_info->pfdomain[$pfdomain]==2 ? '✔' : "-"}}</td>
                      <td  align="center" style="font-family: DejaVu Sans, sans-serif;color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$mark_info->pfdomain[$pfdomain]==3 ? '✔' : "-"}}</td>
                      <td  align="center" style="font-family: DejaVu Sans, sans-serif;color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$mark_info->pfdomain[$pfdomain]==4 ? '✔' : "-"}}</td>
                      <td  align="center" style="font-family: DejaVu Sans, sans-serif;color: #4e1489;border: 1px solid black;padding: 10px 6px 10px 6px;">{{@$mark_info->pfdomain[$pfdomain]==5 ? '✔' : "-"}}</td>
                     
                    
                    </tr>
                      @endforeach

                      

                      

                    </tbody>
                  </table>
               
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  
  </body>

  
</html>
