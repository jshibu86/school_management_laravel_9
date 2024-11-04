<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Employee</title>
    
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/fontawesome/css/font-awesome.min.css" />
    <style>
      @media screen,print {
      .head_color{
          background-color: #c7c7c7 !important
        }
      }
    </style>
  </head>
  <body
  style="font-family: roboto"
    leftmargin="0"
    marginheight="0"
    marginwidth="0"
    offset="0"
    topmargin="10px"
  >
    <center>
      <div style="width: 650px; border: 5px solid #000; padding:30px">
        <table style="width: 100%" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <td>
                <table style="padding: 0 50px;margin: auto;">
                  <tbody>
                    <tr>
                     
                      <td style="width: 100%; padding-top: 0">
                        <center>
                          <h1 style="font-weight: 700; width: 100%; margin: 0">
                            {{Configurations::getConfig("site")->school_name}}
                          </h1>
                        </center>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>

            <tr>
              <td>
                <table cellpadding="0" cellspacing="0" style="width: 100%;margin-bottom: 15px;">
                  <tbody>
                    <tr class="head_color">
                      <td colspan="2">
                          <h2 style="font-size: 20px; margin: 12px 0"></h2>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:5px; font-weight: 500; margin: 0;">
                        Phone 
                      </td>
                      <td style="padding:5px;font-weight: 500; margin: 0">
                          {{Configurations::getConfig("site")->school_phone}}
                      </td>
                    </tr>
                    
                    <tr>
                      <td style="padding:5px;font-weight: 500; margin: 0;">
                        Email
                      </td>
                      <td style="padding:5px;font-weight: 500; margin: 0;">
                       {{Configurations::getConfig("site")->school_email}}
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:5px;font-weight: 500; margin: 0;">
                        Address
                      </td>
                      <td style="padding:5px;font-weight: 500; margin: 0;">
                        {{Configurations::getConfig("site")->place}}, {{Configurations::getConfig("site")->city}} , {{Configurations::getConfig("site")->country}}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>

            <tr>
              <td>
                <table
                  cellpadding="0"
                  cellspacing="0"
                  style="width: 100%;"
                >
                  <tbody>
                    <tr class="head_color">
                      <td colspan="4"  style="padding:5px;">
                          <h2 style="font-size: 20px; margin: 7px 0">
                            Employee Details
                          </h2>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding: 5px">Employee Name</td>
                      <td style="padding:5px;">{{$user->name}}</td>
                      <td style="padding:5px;">Email</td>
                      <td style="padding:5px;">{{$user->email}}</td>
                    </tr>
                    <tr>
                      <td style="padding: 5px">Phone</td>
                      <td style="padding:5px;">{{$user->mobile}}</td>
                    </tr>
                    
                    <tr>
                      <td style="padding: 5px">Salary Month</td>
                      <td style="padding:5px;">{{@$grade->basic_salery}}</td>
                      
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
           

            <tr>
              <td>
                <table
                  style="
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 15px;
                  "
                >
                  <tbody>
                    <tr class="head_color" style="width: 100%;text-align: center">
                      <td colspan="3" style="padding:5px;">
                        <h4
                          style="
                            font-size: 17px;
                            margin: 8px 0 8px 0;
                            text-align: left;
                          "
                        >
                        Particulars
                        </h4>
                      </td>
                      <td style="padding:5px;">
                        <h4 style="font-size: 17px; margin: 8px 0 8px 0">
                          Advance
                        </h4>
                      </td>
                      <td style="padding:5px;">
                        <h4 style="font-size: 17px; margin: 8px 0 8px 0">
                          Amount (N)
                        </h4>
                      </td>
                    </tr>
                    @if (@$view)
                    @php
                      $viewDeduction=0;
                      $basic_per=100;
                     foreach (@$salery_payment->particulars as $particular)
                     {
                      $viewDeduction+=$particular['deduction_amount'];
                      $basic_per-=$particular['deduction_per'];
                     }
                        
                    @endphp
                      <tr>
                      <td colspan="3" style="padding:5px;">Basic ({{$basic_per}}%)</td>
                      <td style="text-align: center;padding:5px">0</td>
                      <td style="text-align: center;padding:5px">{{@$salery_payment->basic_salery - @$viewDeduction}}</td>
                    </tr>
                    @else
                      <tr>
                      <td colspan="3" style="padding:5px;">Basic</td>
                      <td style="text-align: center;padding:5px">0</td>
                      <td style="text-align: center;padding:5px">{{@$grade->basic_salery - @$grade->total_deduction}}</td>
                    </tr>
                    @endif
                   

                    @if (@$view)
                      
                      @foreach (@$salery_payment->particulars as $particular)
                      
                    <tr>
                      <td colspan="3" style="padding:5px;">{{Configurations::getParticular($particular['particular_id'])}} ({{$particular['deduction_per']}}%)</td>
                      <td style="text-align: center;padding:5px">0</td>
                      <td style="text-align: center;padding:5px">{{$particular['deduction_amount']}}</td>
                    </tr>
                    @endforeach
                    @else

                     @foreach (@$grade->particulars as $particular)
                    <tr>
                      <td colspan="3" style="padding:5px;">{{Configurations::getParticular($particular['particular_id'])}}</td>
                      <td style="text-align: center;padding:5px">0</td>
                      <td style="text-align: center;padding:5px">{{$particular['deduction_amount']}}</td>
                    </tr>
                    @endforeach
                    @endif               
                     
                    <tr>
                      <td colspan="3" style="padding:5px;font-weight: bold;">Subtotal</td>
                      <td></td>
                      <td style="text-align: center;padding:5px;font-weight: bold;">{{@$grade->basic_salery}}</td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>

            <tr>
              <td>
                <table
                  style="
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 15px;
                  "
                >
                  <tbody>
                    <tr class="head_color" style="width: 100%;text-align: center">
                      <td colspan="2" style="padding:5px;">
                        <h4
                          style="
                            font-size: 17px;
                            margin: 8px 0 8px 0;
                            text-align: left;
                          "
                        >
                        Deductions
                        </h4>
                      </td>
                   
                      <td style="padding:5px;">
                        <h4 style="font-size: 17px; margin: 8px 0 8px 0">
                          
                        </h4>
                      </td>
                    </tr>
                    @php

                    if(@$view)
                    {
                      
                      $basic=$salery_payment->basic_salery - $viewDeduction;
                      $tax=$salery_payment->deduction['tax_per'] == 0 ? $salery_payment->deduction['tax_amount'] :$basic*$salery_payment->deduction['tax_per']/100;
                      
                      $employer_pension=$basic*$salery_payment->deduction['employer_pension_per']/100;
                      $employee_pension=$basic*$salery_payment->deduction['employee_pension_per']/100;
                    }else{
                      $basic=$grade->basic_salery - $grade->total_deduction;
                      $tax=$basic*7/100;
                      $employer_pension=$basic*8/100;
                      $employee_pension=$basic*8/100;
                    }
                        
                    @endphp
                    <tr>
                      <td colspan="2" style="padding:5px;">Tax(7%)</td>
                      <td style="text-align: center;padding:5px;">{{$tax}}</td>
                    </tr>
                    <tr>
                      <td colspan="2" style="padding:5px;">Employer Pension (10%)(This is not added as deduction)</td>
                      <td style="text-align: center;padding:5px;">{{$employer_pension}}</td>
                    </tr>
                    <tr>
                      <td colspan="2" style="padding:5px;">Employee Pension (8%)</td>
                      <td style="text-align: center;padding:5px;">{{$employee_pension}}</td>
                    </tr>
                    <tr class="head_color" style="width: 100%;">
                      <td colspan="2" style="padding:5px;font-weight: bold;text-align: start">Net Salary</td>
                      <td style="text-align: center;padding:5px;font-weight: bold;">{{$basic-($tax+$employee_pension)}}</td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>

          <tfoot>
            <tr>
              <td>
                <h3 style="margin-top: 50px; margin-bottom: 8px;display: flex;align-items: flex-end;">
                   <span style="margin-right: 10px;">Employer's Signature:</span><span><hr style="width:150px;margin: 0;"></span>
                </h3>
              </td>
            </tr>
            <tr>
              <td>
                <h3 style="margin-top: 50px; margin-bottom: 30px;display: flex;align-items: flex-end;">
                   <span style="margin-right: 10px;">Employer's Signature:</span><span><hr style="width:150px;margin: 0;"></span>
                </h3>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </center>
  </body>
</html>
