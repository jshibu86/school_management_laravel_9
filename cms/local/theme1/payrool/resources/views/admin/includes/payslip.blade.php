<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Billing</title>
  </head>
      <style>
        /* ========== Fees Bill1============ */
       
        /* @font-face {
            font-family: 'poppins';
            font-weight: normal;
            font-style: normal;        
            src: url("theme/vendors/font-poppins/fonts/poppins-regular.ttf") format('truetype');
        }
                body {
            font-family: 'poppins', sans-serif;
        } */
       

        .school {
          /* font-family: "Poppins", sans-serif;  */
          margin: auto;
          border: none;
          color:{{isset(Configurations::getConfig('site')->salary_receipt_color)? Configurations::getConfig('site')->salary_receipt_color : "#e87117"}};
          font-weight: 600;
          font-size: 18px;
        }
        .sub_title {
          /* font-family: "Poppins", sans-serif;  */
          color: #000;
          font-weight: 600;
          text-align: right;
          border: none;
          padding-top: 10px;
          padding-bottom: 10px;
        }

        .school_data1 {
          color: #5e6470;
          font-weight: 600;
          text-align: left;
          border: none;
          text-align: left;
        }
        .school_data2 {
          color: #5e6470;
          font-weight: 600;
          text-align: right;
          border: none;
        }

        /* ========== Fees Bill1============ */

        /* ========== Fees Bill2============ */

        .sub_title1 {
          color: #000;
          font-weight: 600;
          text-align: left;
          padding-left: 5px;
          border: none;
          padding-bottom: 18px;       
          padding-top: 5px;
        }

        .sub_title2 {
          color: #000;
          font-weight: 600;
          text-align: right;
          border: none;
          padding-bottom: 18px;      
          padding-top: 5px;
        }

        .sub_titlep {
          color: #000;
          font-weight: 600;
          text-align: center;
          padding-left: 15px;
          border: none;
          padding-bottom: 5px;
          padding-top: 5px;
          text-align: end;
        }
        .sub_titlep1 {
          text-align: end;
        }

        .sub_datap1 {
          border: none;
          text-align: end;
          color:#ffffff ;
          font-weight: 600;
          font-size: 17px;
          background-color: {{isset(Configurations::getConfig('site')->salary_receipt_color)? Configurations::getConfig('site')->salary_receipt_color : "#e87117"}};
          -webkit-print-color-adjust: exact;

        }
        .school_data {
          color: #5e6470;
          font-weight: 600;
          text-align: left;
          border: none;
          text-align: center;
        }

        .sub_data1 {
          color: #5e6470;
          font-weight: 600;
          text-align: left;
          padding-left: 5px;
          padding-bottom: 15px;
          border: none;
            }
        .sub_data2 {
          color: #5e6470;
          font-weight: 600;
          text-align: right;
          border: none;
        }

        .panda {
          border: none;
          width: 10%;
        }
    
        .sub_main1p {
          border-right: none;
        }
        .sub_main2 {
         padding-bottom: 6px;
         padding-left: 7px;
        }
         /* ========== Fees Bill3============ */

        .border_bottom{
          border-bottom: 1px solid  #b4b7bd;
        }
      /* ========== Fees Bill3============ */


        /* ========== Fees Bill4============ */

        .sub_title3 {
          color: #000;
          font-weight: 600;
          text-align: left;
          border: none;
          padding-top: 10px;
          padding-bottom: 35px;
        }

        .sub_success {
          color: #0caf60;
          font-weight: 600;
          font-size: 14px;
          background-color: #e7f7ef;
          padding: 10px; 
          }
        .sub_amountdue {
          color: {{isset(Configurations::getConfig('site')->salary_receipt_color)? Configurations::getConfig('site')->salary_receipt_color : "#e87117"}};
          font-weight: 600;
          font-size: 14px;
          text-align: left;
          padding-top: 10px;
          padding-bottom: 10px;
          border-top: 1px solid {{isset(Configurations::getConfig('site')->salary_receipt_color)? Configurations::getConfig('site')->salary_receipt_color : "#e87117"}};
          border-bottom: 1px solid {{isset(Configurations::getConfig('site')->salary_receipt_color)? Configurations::getConfig('site')->salary_receipt_color : "#e87117"}};
        }
        .sub_amountdue1 {
          color:{{isset(Configurations::getConfig('site')->salary_receipt_color)? Configurations::getConfig('site')->salary_receipt_color : "#e87117"}};
          font-weight: 600;
          font-size: 14px;
          text-align: right;
          padding-top: 10px;
          border-top: 1px solid {{isset(Configurations::getConfig('site')->salary_receipt_color)? Configurations::getConfig('site')->salary_receipt_color : "#e87117"}};
          border-bottom: 1px solid {{isset(Configurations::getConfig('site')->salary_receipt_color)? Configurations::getConfig('site')->salary_receipt_color : "#e87117"}};
        }

        /* ========== Fees Bill4============ */


        /* ========== Fees Bill5============ */

        .sub_font{
          color: #5e6470;
          font-weight: 600;
           }
           .sub_font1{
          color: #5e6470;
          font-weight: 600;
          padding-bottom: 12px;
           }

      .sub_title4 {
          color: #000;
          font-weight: 600;
          text-align: left;
          width: 38%;
            
        }

   /* ========== Fees Bill5============ */

   /* ========== Table Border============ */

        .sub_width1 {
          width: 40%;
          /* border-right: 1px solid #5e6470; */
        }
      
        .table2 {
          margin-left: auto;
          margin-right: auto;
        }

        .fees_bill_table {
          width: 100%;
          margin-left: auto;
          margin-right: auto;
        }

        .fees_bill1 {
          border-left: none;
          border-right: none;
          margin-top: 15px;
          border-bottom: none;
          width: 100%;
        }
        .fees_bill2 {
          width: 100%;
          border-collapse: collapse;
          /* margin-bottom: 25px; */
        }

        .fees_bill3 {
          width: 100%;
          border-collapse: collapse;
            margin-top: 70px;  
        }

        .fees_bill4 {
          width: 40%;
          margin-left: auto;
          border-collapse: collapse;
        }

        .fees_bill5 {
          width: 100%;
        }
      
        /* ========== Table Border============ */
      </style>

      <body>
        <div class="fees_bill_table">
          <table class="fees_bill1" align="center">
            <tr>
              <th rowspan="4" class="panda">
                <img
                  class="pandaimage"
                  src="{{ public_path(@$image) }}"
                  width="100"
                  height="100"
                />
              </th>

              <td class="school"> {{Configurations::getConfig("site")->school_name}}</td>
              <td class="sub_title" colspan="3">Salary Slip[
                @foreach (@$salery_payment_details as $payments)

                   {{$payments->payslip_no}}
                @endforeach
              ]

              </td>
            </tr>
            <tr> 
         

              <td class="school_data1" colspan="2">{{$current_url}}</td>
              <td class="school_data2" colspan="2">Business Address</td> 

           
            </tr>
            <tr>
              <td class="school_data1" colspan="2">{{Configurations::getConfig("site")->school_email}}</td>
              <td class="school_data2" colspan="2"> {{Configurations::getConfig("site")->place}}, {{Configurations::getConfig("site")->city}} , {{Configurations::getConfig("site")->country}}</td>
            </tr>
            <tr>
              <td class="school_data1" colspan="2">{{Configurations::getConfig("site")->school_phone}}</td>
              <td class="school_data2" colspan="2"></td>
            </tr>
          </table>

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



          <table class="fees_bill2">
            <tr></tr>

            <tr height="30"></tr>

            <tr style="width: 100%">
              <td class="sub_title1 " colspan="1">Payment to</td>
              <td class="sub_title1 " colspan="2">Staff ID</td>
              <td class="sub_titlep  sub_main1p" colspan="2">
             </td>
            </tr>
            <tr>
              <td class="sub_data1" colspan="1">{{$user->name}}</td>

              @foreach (@$teacher as $teachers) 

              <td class="sub_data1" colspan="2">{{$teachers->employee_code}}</td>
              @endforeach

              <td class="sub_titlep1" colspan="2"></td>
            </tr>

            <tr style="width: 30%">
              <td class="sub_title1 sub_width1" colspan="1">Role</td>
              <td class="sub_title1" colspan="2">
                Receipt Date/Session
              </td>
               <td class="sub_title1 sub_titlep1" colspan="2">Amount Paid</td> 
            </tr>
            
            <tr>
              <td class="sub_data1 sub_main2" colspan="1">Teacher</td>
              <td class="sub_data1 sub_main2" colspan="2">
                @foreach (@$salery_payment_details as $payments)
                  
                {{$payments->payment_date}}    
                @break             
              @endforeach 
            </td> 
              <td class="sub_datap1 sub_main2 " colspan="2" style="width: 13%;
              text-align: left;">
              <span>NGN</span>{{$basic-($tax+$employee_pension)}}
              </td>
            </tr>
            <tr height="40"></tr>
          </table>        
    
          <table class="fees_bill3">
            <tr>
              <td class="sub_title1 border_bottom">#</td>
              <td class="sub_title1 border_bottom">TITLE/DESCRIPTION</td>
              <td class="sub_title2 border_bottom">EARNINGS(NGN)</td>
              <td class="sub_title2 border_bottom">DEDUCTIONS(NGN)</td>
            </tr>

        
            <tr>
              <td class="sub_title1 border_bottom">1</td>
              <td class="sub_title1 border_bottom">Basic ({{$basic_per}}%)</td>
              <td class="sub_data2 border_bottom">{{@$salery_payment->basic_salery - @$viewDeduction}}</td>
              <td class="sub_data2 border_bottom"></td>
            </tr>
            @else
            <tr>
              <td class="sub_title1 border_bottom">1</td>
              <td class="sub_title1 border_bottom">Basic ({{$basic_per}}%)</td>
              <td class="sub_data2 border_bottom">{{@$grade->basic_salery - @$grade->total_deduction}}</td>
              <td class="sub_data2 border_bottom"></td>
            </tr>
            @endif

            @if (@$view)
                      
            @foreach (@$salery_payment->particulars as $particular)
       
            <tr>
              <td class="sub_title1 border_bottom">2</td>
              <td class="sub_title1 border_bottom">{{Configurations::getParticular($particular['particular_id'])}} ({{$particular['deduction_per']}}%)</td>
              <td class="sub_data2 border_bottom">{{$particular['deduction_amount']}}</td>
              <td class="sub_data2 border_bottom"></td>
            </tr>
            @endforeach
            @else

             @foreach (@$grade->particulars as $particular)

            <tr>
              <td class="sub_title1 border_bottom">3</td>
              <td class="sub_title1 border_bottom"><span>NG</span>{{Configurations::getParticular($particular['particular_id'])}}</td>
              <td class="sub_data2 border_bottom">{{$particular['deduction_amount']}}</td>
              <td class="sub_data2 border_bottom"></td>
            </tr>
            @endforeach
            @endif

          
        
            <tr>
              <td class="sub_title1 border_bottom">5</td>
              <td class="sub_title1 border_bottom">Tax(7%)</td>
              <td class="sub_data2 border_bottom"></td>
              <td class="sub_data2 border_bottom">{{$tax}}</td>
            </tr>

            <tr>
              <td class="sub_title1 border_bottom">7</td>
              <td class="sub_title1 border_bottom">Employer Pension(10%)</td>
              <td class="sub_data2 border_bottom">{{$employer_pension}}</td>
              <td class="sub_data2 border_bottom"></td>
            </tr>


            <tr>
              <td class="sub_title1 border_bottom">6</td>
              <td class="sub_title1 border_bottom">Employee Pension(8%)</td>
              <td class="sub_data2 border_bottom"></td>
              <td class="sub_data2 border_bottom">{{$employee_pension}}</td>
            </tr>

          
            <tr>
              <td class="sub_title1" colspan="2">Total</td>
              <td class="sub_data2">{{$basic-($tax+$employee_pension)}}</td>
              <td class="sub_data2"></td>
            </tr>
          </table>

          <table class="fees_bill4">
            <tr>
              <td class="sub_title3" colspan="3">Status</td>
              <td class="sub_data2 "><span class="sub_success">Successful</span></td>
            </tr>

            <tr>
              <td class="sub_amountdue" colspan="3">Amount Paid</td>
              <td class="sub_amountdue1" colspan="1">
                <span>NGN</span>{{$basic-($tax+$employee_pension)}}
              </td>
            </tr>
          </table>
          <table class="fees_bill5">
            <tr>
              <td class="sub_font1" colspan="4">Thank You</td>
            </tr>
            <tr>
              <td class="sub_font1 colspan="4">PAYMENT INFO</td>
            </tr>

            {{-- <tr>
              <td class="sub_title4" >Account Name</td>
              <td class="sub_title4" >Bank Name</td>
              <td class="sub_title4" >Staff Code</td>
              <td class="sub_title4">Account</td>              
             </tr>
             <tr>
              <td class="sub_font" >Bolaji Deji</td>
              <td class="sub_font" >IOB BANK</td>
              <td class="sub_font" >ABCDXXXXXXX</td>
              <td class="sub_font" >37689994589888</td>              
             </tr> --}}
             
          </table>
        </div>
      </body>
    </html>
  </head>
</html>
