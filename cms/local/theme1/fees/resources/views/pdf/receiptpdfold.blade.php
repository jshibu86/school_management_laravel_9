
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Billing</title>
    <style>
        body {
  margin: 0;
  box-sizing: border-box;
  color: #000;
  font-size: 14px;
}
    </style>
  </head>
  <body
    leftmargin="0"
    marginheight="0"
    marginwidth="0"
    offset="0"
    topmargin="0"
  >
    <center>
      <div style=" border: 1px solid #000">
        <table style="width: 100%" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <td>
                <table style="width: 100%;padding: 0 50px">
                  <tbody>
                    <tr>
                      <td
                        align="center"
                        valign="top"
                        class="borderblue padding1"
                        style="width: 12%"
                      >
                         <img alt="Logo" src="{{ public_path(@$config->imagec) }}" width="100"/>
                      </td>
                      <td style="width: 88%; padding-top: 0">
                        <center>
                          <h1 style="font-weight: 700; width: 100%; margin: 0">
                             {{ @$config->school_name }}
                          </h1>
                          <h5
                            style="margin: 0; font-size: 21px; font-weight: 100"
                          >
                            {{ @$config->place }},{{ @$config->city }},{{ @$config->country }}
                          </h5>
                          <h4
                            style="
                              margin: 7px 0 0 0;
                              font-size: 18px;
                              font-weight: 200;
                            "
                          >
                            Ph No.: {{@$config->school_phone }}
                          </h4>
                          <h4
                            style="
                              margin: 7px 0 0 0;
                              font-size: 18px;
                              font-weight: 200;
                            "
                          >
                            E-Mail: {{ @$config->school_email }}
                          </h4>
                        </center>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>

            <tr>
              <td>
                <table cellpadding="0" cellspacing="0" style="width: 100%">
                  <tbody>
                    <tr style="background-color: #c7c7c7">
                      <td colspan="2">
                        <center>
                          <h2 style="font-size: 20px; margin: 7px 0">
                            FEE RECEIPT
                          </h2>
                        </center>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <table>
                          <tbody>
                            <tr>
                              <td>
                                <h3
                                  style="
                                    font-weight: 500;
                                    margin: 0;
                                    padding-left: 5px;
                                  "
                                >
                                  Receipt No
                                </h3>
                              </td>
                              <td>
                                <h3 style="font-weight: 500; margin: 0">
                                  :752
                                </h3>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h3
                                  style="
                                    font-weight: 500;
                                    margin: 0;
                                    padding-left: 5px;
                                  "
                                >
                                  Adm No
                                </h3>
                              </td>
                              <td>
                                <h3 style="font-weight: 500; margin: 0">
                                  :{{ @$student_info->reg_no }}
                                </h3>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h3
                                  style="
                                    font-weight: 500;
                                    margin: 0;
                                    padding-left: 5px;
                                  "
                                >
                                  Name
                                </h3>
                              </td>
                              <td>
                                <h3 style="font-weight: 500; margin: 0">
                                  :{{ @$student_info->full_name }}
                                </h3>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h3
                                  style="
                                    font-weight: 500;
                                    margin: 0;
                                    padding-left: 5px;
                                  "
                                >
                                  Father Name
                                </h3>
                              </td>
                              <td>
                                <h3 style="font-weight: 500; margin: 0">
                                  :{{ @$student_info->parentname }}
                                </h3>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h3
                                  style="
                                    font-weight: 500;
                                    margin: 0;
                                    padding-left: 5px;
                                  "
                                >
                                  Installment
                                </h3>
                              </td>
                              <td>
                                <h3 style="font-weight: 500; margin: 0">
                                  :{{ @$term_name }}
                                </h3>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>

                      <td>
                        <table>
                          <tbody>
                            <tr>
                              <td>
                                <h3 style="font-weight: 500; margin: 0">
                                  Date
                                </h3>
                              </td>
                              <td>
                                <h3 style="font-weight: 500; margin: 0">
                                  :{{ date("Y-m-d") }}
                                </h3>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h3 style="font-weight: 500; margin: 0">
                                  Session
                                </h3>
                              </td>
                              <td>
                                <h3 style="font-weight: 500; margin: 0">
                                  :{{ @$academic }}
                                </h3>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h3
                                  style="
                                    font-weight: 500;
                                    margin-top: 45px;
                                    margin-bottom: 0;
                                  "
                                >
                                  Class
                                </h3>
                              </td>
                              <td>
                                <h3
                                  style="
                                    font-weight: 500;
                                    margin-top: 45px;
                                    margin-bottom: 0;
                                  "
                                >
                                  :{{@$student_info->classname  }} - {{ @$student_info->sectionname }}
                                </h3>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
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
                    border: 1px solid black;
                    border-collapse: collapse;
                  "
                >
                  <thead>
                    <tr style="background-color: #c7c7c7">
                      <th
                        style="
                          border: 1px solid #b9b9b9;
                          padding-left: 8px;
                          width: 40px;
                        "
                        align="start"
                      >
                        SI. No
                      </th>
                      <th
                        style="
                          border: 1px solid #b9b9b9;
                          text-align: start;
                          padding-left: 14px;
                        "
                      >
                        Description
                      </th>
                      <th style="border: 1px solid #b9b9b9" align="end">Due</th>
                      <th style="border: 1px solid #b9b9b9" align="end">Con</th>
                      <th style="border: 1px solid #b9b9b9" align="end">
                        Paid
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td
                        style="border: 1px solid #c7c7c7; padding-left: 3px"
                        align="center"
                      >
                        1
                      </td>
                      <td style="border: 1px solid #c7c7c7; padding-left: 8px">
                        Tuition Fee
                      </td>
                      <td
                        style="border: 1px solid #c7c7c7; padding-right: 8px"
                        align="end"
                      >
                        {{ @$paid_amount }}
                      </td>
                      <td
                        style="border: 1px solid #c7c7c7; padding-right: 6px"
                        align="end"
                      >
                        0
                      </td>
                      <td
                        style="border: 1px solid #c7c7c7; padding-right: 8px"
                        align="end"
                      >
                       {{ @$paid_amount }}
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
                  style="width: 100%; margin-top: 78px"
                >
                  <tbody>
                    <tr style="background-color: #c7c7c7">
                      <td colspan="4">
                        <center>
                          <h2 style="font-size: 20px; margin: 7px 0">
                            PAY MODE INFORMATION
                          </h2>
                        </center>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding: 6px">Pay Mode</td>
                      <td>{{ @$paymethod }}</td>
                      <td>Date</td>
                      <td>{{ date("Y-m-d") }}</td>
                    </tr>
                    {{-- <tr>
                      <td style="padding: 6px">Bank</td>
                      <td>Tamilnad Mercantile Bank</td>
                      <td>Number</td>
                      <td>45589421</td>
                    </tr> --}}
                    <tr style="width: 100%; background-color: #b9b9b9">
                      <td colspan="3">
                        <h4
                          style="
                            font-size: 17px;
                            padding-left: 8px;
                            margin: 8px 0 8px 0;
                          "
                        >
                          Total
                        </h4>
                      </td>
                      <td>
                        <h4 style="font-size: 17px; margin: 8px 0 8px 0">
                         {{ @$paid_amount }}
                        </h4>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>

            {{-- <tr>
              <td>
                <table
                  style="
                    width: 100%;
                    border: 1px solid black;
                    border-collapse: collapse;
                    margin-top: 58px;
                  "
                >
                  <tbody>
                    
                    <tr
                      style="
                        width: 100%;
                        border: 1px solid black;
                        border-collapse: collapse;
                      "
                    >
                      <td colspan="2" style="padding-left: 8px" align="start">
                        <h3
                          style="
                            font-size: 17px;
                            font-weight: 500;
                            margin: 7px 0;
                          "
                        >
                          Total in Words: {{ @$words }}
                        </h3>
                      </td>
                    </tr>
                    
                  </tbody>
                </table>
              </td>
            </tr> --}}
          </tbody>

          <tfoot>
            <tr>
              <td>
                <h3 style="margin-top: 76px; margin-bottom: 8px">
                  This is computer generated Receipt. Does not required
                  signature.
                </h3>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </center>
  </body>
</html>
