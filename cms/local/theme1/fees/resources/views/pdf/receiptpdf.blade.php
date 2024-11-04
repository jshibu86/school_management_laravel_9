<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Billing</title>
     </head>

  <style>
    /* ========== Fees Bill1============ */

      
    .school {
      text-align: left;
      border: none;
      color:{{isset(Configurations::getConfig('site')->receipt_color) ? Configurations::getConfig('site')->receipt_color: "#e87117"  }};
      font-weight: 600;
      font-size: 18px;
    }
    .sub_title {
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
      padding-bottom: 13px;
      padding-top: 10px;
    }

    .sub_title2 {
      color: #000;
      font-weight: 600;
      text-align: right;
      border: none;
    }

    .sub_titlep {
      color: #000;
      font-weight: 600;
      text-align: center;
      padding-left: 15px;
      border: none;
      padding-bottom: 5px;
      padding-top: 5px;
      text-align: right;
    }
    .sub_titlep1 {
      text-align: right;
    }
    .sub_datap1 {
      color: #5e6470;
      font-weight: 600;
      border: none;
      text-align: right;
      padding-bottom: 19px;
    }
 
    .sub_data1 {
      color: #5e6470;
      font-weight: 600;
      text-align: left;
      padding-left: 5px;
      padding-bottom: 19px;
      border: none;
      border-right: 1px solid #b4b7bd;
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
    .sub_main1 {
      border-top: 1px solid #b4b7bd;
      border-right: 1px solid #b4b7bd;
    }
    .sub_main1p {
      border-right: none;
    }
    .sub_main2 {
      border-bottom: 1px solid #b4b7bd;
      padding-bottom: 10px;
    }
    .sub_main3 {
      border-top: 1px solid #b4b7bd;
    }
    .fee {
      border-bottom: 1px solid #b4b7bd;
     
    }
    .sub_fee{
      padding-top: 65px;
    }

    /* ========== Fees Bill3============ */

    .sub_title3 {
      color: #000;
      font-weight: 600;
      text-align: left;
      border: none;
      padding-top: 10px;
      padding-bottom: 15px;
    }

    .sub_success {
     color: #0caf60;
    font-weight: 600;
    font-size: 17px;
    background-color: #e7f7ef;
    padding: 10px; 
    }
    .sub_amountdue {
     color:{{isset(Configurations::getConfig('site')->receipt_color) ? Configurations::getConfig('site')->receipt_color: "#e87117"  }};
     font-weight: 600;
      font-size: 14px;
      text-align: left;
      padding-top: 10px;
      padding-bottom: 10px;
      border-top: 1px solid {{isset(Configurations::getConfig('site')->receipt_color) ? Configurations::getConfig('site')->receipt_color: "#e87117"  }};
      border-bottom: 1px solid {{isset(Configurations::getConfig('site')->receipt_color) ? Configurations::getConfig('site')->receipt_color: "#e87117"  }};
    }
    .sub_amountdue1 {
      color: {{isset(Configurations::getConfig('site')->receipt_color) ? Configurations::getConfig('site')->receipt_color: "#e87117"  }};
      font-weight: 600;
      font-size: 14px;
      text-align: right;
      padding-top: 10px;
      padding-bottom: 10px;
      border-top: 1px solid {{isset(Configurations::getConfig('site')->receipt_color) ? Configurations::getConfig('site')->receipt_color: "#e87117"  }};
      border-bottom: 1px solid {{isset(Configurations::getConfig('site')->receipt_color) ? Configurations::getConfig('site')->receipt_color: "#e87117"  }};
    }
      


    /* ========== Fees Bill3============ */

    /* ========== Table Border============ */

    .sub_width1 {
      width: 40%;
      border-right: 1px solid #b4b7bd;
    }
    .sub_width2 {
      border-right: 1px solid #b4b7bd;
    }
  

    .fees_bill_table {
      width: 700px;
      margin-left: auto;
      margin-right: auto;
    }

    .fees_bill1 {
      border-left: none;
      border-right: none;
      margin-top: 7px;
      border-bottom: none;
      width: 100%;
    }
    .fees_bill2 {
      margin-top: 15px;
      width: 100%;
      border-collapse: collapse;
    }
    .fees_bill3 {
      width: 40%;
      margin-left: auto;
      border-collapse: collapse;
    }

    /* ========== Table Border============ */
  </style>

<body 
    <center>
      <div class="fees_bill_table">
        <table class="fees_bill1" border="1" align="center">
          <tr>
            <th rowspan="4" class="panda">
              <img
                class="pandaimage"
                src="{{ public_path(@$image) }}"
                width="100"
                height="100"
              />
            </th>

            <td class="school"> {{ @$config->school_name }}</td>
            <td class="sub_title" colspan="3">Fee Receipt::{{@$fee_info->bill_no }}</td>
          </tr>
          <tr>
            <td class="school_data1" colspan="2">{{$current_url}}</td>
            <td class="school_data2" colspan="2">Business Address</td>
          </tr>
          <tr>
            <td class="school_data1" colspan="2">{{ @$config->school_email }}</td>
            <td class="school_data2" colspan="2"> {{ @$config->place }},{{ @$config->city }},{{ @$config->country }}</td>
          </tr>
          <tr>
            <td class="school_data1" colspan="2">{{@$config->school_phone }}</td>
            <td class="school_data2" colspan="2"></td>
          </tr>
        </table>
        <table class="fees_bill2">
          <tr></tr>

          <tr height="30"></tr>

          <tr style="width: 100%">
            <td class="sub_title1 sub_main1" colspan="1">Student Name</td>
            <td class="sub_title1 sub_main1" colspan="2">Student ID</td>
            <td class="sub_titlep sub_main1 sub_main1p" colspan="2">
              Parent Name
            </td>
          </tr>
          <tr>
            <td class="sub_data1" colspan="1">{{ @$student_info->full_name }}</td>
            <td class="sub_data1" colspan="2">{{ @$student_info->reg_no }}</td>
            <td class="sub_datap1 sub_titlep1" colspan="2">{{ @$student_info->parentname }}</td>
          </tr>

          <tr style="width: 30%">
            <td class="sub_title1 sub_width1" colspan="1">
              Paymont Month/Year
            </td>
            <td class="sub_title1 sub_width2" colspan="2">
              Kind of Payment/Amount
            </td>
            <td class="sub_title1 sub_titlep1" colspan="2">
              Total Annual Payment
            </td>
          </tr>
          <tr>
            <td class="sub_data1 sub_main2" colspan="1">
              {{ @$pay_month}}/{{ @$pay_year }}
            </td>
            <td class="sub_data1 sub_main2" colspan="2">
            
              {{ @$paymethod }}/<span>N</span>{{ @$paid_amount }}
            </td>
            <td class="sub_datap1 sub_main2 sub_titlep1" colspan="2">
              <span>N</span>{{ @$paid_amount }}
            </td>
          </tr>
          <tr>
            <td class="sub_title1 sub_fee" colspan="3">Receipt Date/Session</td>
            <td class="sub_title2 sub_fee" colspan="2">{{ date("Y-m-d") }}/{{ @$academic }}</td>
          </tr>
          <tr>
            <td class="sub_title1 sub_fee" colspan="3">Fee</td>
            <td class="sub_title2 sub_fee" colspan="2">Line Total</td>
          </tr>
          <tr>
            <td class="sub_title1 sub_main3 fee" colspan="3">Tution Fee</td>
            <td class="sub_data2 sub_main3 fee" colspan="2">
              <span style="font-family: DejaVu Sans;"><span>N</span>{{ @$paid_amount }}
              {{-- {{ Configurations::CurrencyFormat(@$paid_amount)}} --}}
            </td>
          </tr>

        </table>
        <table class="fees_bill3">
          <tr>
            <td class="sub_title3" colspan="3">Subtotal</td>
            <td class="sub_data2" colspan="1"> <span>N</span>{{ @$paid_amount }}</td>
          </tr>

          <tr>
            <td class="sub_title3" colspan="3">Status</td>
            <td class="sub_data2 "><span class="sub_success">Successful</span> </td>
          </tr>

          <tr>
            <td class="sub_amountdue" colspan="3">Amount Due</td>
            <td class="sub_amountdue1" colspan="1">
              <span>NGN{{ @$paid_amount }}</span>
            </td>
          </tr>
        </table>
      </div>
    </center>
  </body>
</html>
