<div class="col-md-12">

                                
                                <div class="fee_information ">
                                

                                    <table class="table table-striped" id="fee__items">
                                        <thead>
                                            <tr style="background-color: #2a3f54;">
                                            <th>Fee Name</th>
                                            <th>Amount</th>
                                        
                                            </tr>
                                        </thead>
                                        <tbody>
                                  
                                       
                                        @foreach (@$data->feelists as$list )
                                            <tr>
                                            <td><input type="hidden" name="fee_name[]" value="{{ $list->fee_name }}" />
                                            <input type="hidden" name="fee_id[]" value="{{ $list->fee_id }}" />
                                            {{ $list->fee_name }}
                                            </td>
                                            <td><div class="item form-group"><input required type="hidden" name="fee_amount[]" class="fee_amount form-control " readonly value="{{ $list->fee_amount }}" /></div><b style="font-size: 17px;">{{ Configurations::CurrencyFormat($list->fee_amount) }}</b></td>
                                        
                                            </tr>
                                        @endforeach

                                        @if (@$academic_fee_info)

                                        @foreach (@$academic_fee_info as $info)
                                            <tr>
                                                <td>{{ @$info->fee_name }}</td>
                                                <td><div class="item form-group">
                                                    <input required type="hidden" name="fee_amount[]" class="fee_amount form-control " readonly value="{{ $info->due_amount }}" />
                                                </div><b style="font-size: 17px;">{{ Configurations::CurrencyFormat($info->due_amount) }}</b></td>
                                            </tr>
                                        @endforeach
                                            
                                        @endif
                                        {{-- @if (@$hostel_fee_info)
                                            <tr>
                                                <td>Hostel Fee</td>
                                                <td>
                                                    <div class="hostel__Fee">
                                                        <span class="strong">Dormitory Name : {{ @$hostel_fee_info->room->dormitory->dormitory_name }}</span>
                                                        <br/>
                                                        <span class="strong">Room Number : {{ @$hostel_fee_info->room->room_number }} </span>
                                                        <br/>
                                                        <span class="strong">Join Date : {{ @$hostel_fee_info->date_of_reg }} </span>
                                                        <br/>
                                                        @php
                                                             $total_hostel=sizeof($months_hostel )* $hostel_fee_info->room->cost_per_bed;
                                                         @endphp
                                                        <span class="strong">Total Fee : {{$total_hostel  }} </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif

                                        @if (@$tranport_info)
                                            <tr>
                                                <td>Transport Fee</td>
                                                <td>
                                                    <div class="hostel__Fee">
                                                        <span class="strong">Transport Route : {{ @$tranport_info->route->from }} - {{ @$tranport_info->route->to }}</span>
                                                        <br/>
                                                        <span class="strong">Transport Stop : {{ @$tranport_info->stop->stop_name }} </span>
                                                        <br/>
                                                        <span class="strong">Bus No : {{ @$tranport_info->bus->bus_no }} </span>
                                                        <br/>
                                                        <span class="strong">Join Date : {{ @$tranport_info->date_of_reg }} </span>

                                                         <br/>
                                                         @php
                                                             $total_transport=sizeof($months_transport )* $tranport_info->stop->fare_amount;
                                                         @endphp
                                                        <span class="strong">Total Fee : {{$total_transport  }} </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif --}}
                                        
                                        
                                  

                                      

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="strong">Total Amount</td>
                                                @if (@$scholarship)
                                               
                                                <td class="strong">
                                                    <input type="hidden" name="total_amount" value="{{ $total_amount }}" class="total_amount"/>
                                                    <span class="total_amount_text">{{ Configurations::CurrencyFormat($total_amount) }}</span>
                                                </td>
                                                @else
                                                 <td class="strong">
                                                    <input type="hidden" name="total_amount" value="{{ $grand_total }}" class="total_amount"/>
                                                    <span class="total_amount_text">{{ Configurations::CurrencyFormat($grand_total) }}</span>
                                                </td>
                                                @endif
                                            
                                            
                                            </tr>
                                            @if (@$scholarship)
                                            <tr>
                                                <td class="strong">Scholarship</td>

                                                 <td class="strong">
                                                    <input type="hidden" name="scholarship" value="{{ @$scholarship }}" class="scholarship"/>
                                                    <span class="scholarship_text">{{ @$scholarship }}%</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="strong">Total Due Amount</td>

                                                 <td class="strong">
                                                    <input type="hidden" name="grand_total_scholarship" value="{{ @$grand_total }}" class="scholarship"/>
                                                    <span class="scholarship_text">{{ Configurations::CurrencyFormat(@$grand_total) }}</span>
                                                </td>
                                            </tr>
                                                
                                            @endif
                                        </tfoot>
                                    
                                    
                                    </table>

                                    
                                </div>
                            </div>