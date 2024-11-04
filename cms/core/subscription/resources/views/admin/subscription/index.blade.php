@extends('layout::admin.master')

@section('title','schoolmanagement')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
    </style>
@endsection

@section('body')  
<div class="container-fluid">    
    <div class="row" >
        <div class="card-title btn_style">  
            <h4 class="mb-0">Subscription Management </h4>
            @if (CGate::allows("admin-user"))
                <a href="{{route('subscription.create')}}" class="btn btn-primary btn-sm m-1  px-3"><i class='fa fa-plus'></i> Create Account</a>           
            @endif                        
        </div>  
    @if (Session::get("ACTIVE_GROUP") == "Super Admin")   
    
        @php 
            $cards = [ 
                        ['title'=>'Total Admin', 'value'=>'15', 'image'=> asset('assets/images/revenue_icon.png')],
                        ['title'=>'Total Active Admin', 'value'=>'14', 'image'=> asset('assets/images/school_icon.png')],
                        ['title'=>'Total Inactive Admin', 'value'=>'1', 'image'=> asset('assets/images/active_sub.png')],   
                    ];
        @endphp
    @foreach($cards as $card)
      <div class="col-10 col-lg-3">
        <div class="card radius-15 overflow-hidden">
          <div class="card-body">         
            <div class="d-flex align-items-center"> 
              <div class="ms-2 font-25"> 
                <span class="rounded-circle p-2 d-inline-block">
                  <img src="{{ $card['image']}}" alt="logo" style="max-width: 30px; max-height: 30px;" > 
                </span>
              </div>           
              <div class="ms-2 font-18">    
                <p class="feild mb-0 font-weight-bold text-info text-truncate">{{ $card['title'] }}</p>
                <h5 class="mb-0">{{ $card['value'] }}</h5>  
              <div>     
            </div>                          
          </div>  
        </div>                           
      </div>
      </div>
      </div>    

    @endforeach  

        <!-- section for select options -->            
        <div class="row d-flex align-items-start">          
            <div class="col-6 d-flex flex-fill">                              
                <div class="p-2">                          
                    {!! Form::select('all_status', ['inactive' => 'Inactive','active' => 'Active'],'default', 
                        ['class' => 'form-select form-select-sm', 'placeholder' => 'Show: All Status']) 
                    !!}
                </div>        
                <div class="p-2 ">                           
                    {!! Form::select('subscription_status', ['subscribed' => 'Subscribed','expired' => 'Expired'],'default', 
                        ['class' => 'form-select form-select-sm', 'placeholder' => 'Subscription Status']) 
                    !!}
                </div>

                <div class="p-2">                         
                    {!! Form::select('approval_status', ['approved' => 'Approved','pending' => 'Pending','denied' => 'Denied'],'default', 
                            ['class' => 'form-select form-select-sm', 'placeholder' => 'Approval Status']) 
                    !!}  
                </div>
            </div>
       
            <div class="col-6 d-flex align-items-end">
                <div class="p-2 ">                        
                    {!! Form::date('fromdate', 'default', ['class' => 'form-control','style' => 'width: 150px;', 'placeholder' => 'Select Date']) !!}
                </div>        
                <div class="p-2 ">                          
                    {!! Form::date('fromdate', 'default', ['class' => 'form-control', 'style' => 'width: 150px;', 'placeholder' => 'Select Date']) !!}
                </div>        
                <div class="p-2">                          
                    <a href="{{route('schoolmanagement.create')}}" class="btn btn-outline-primary"><i class='fa fa-chevron-down'></i> Download</a>           
                </div>      
            </div>          
        </div>
 
  @endif    
  <hr/>
  <div class="card radius-15">
    <div class="card-body">
        <div class="row">
    <div class="table-responsive">
        <table id="example" class="table table-striped table-bordered" style="width:80%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>School Name</th>
                    <th>Phone No</th>
                    <th>Subscription Plan</th>
                    <th>Billing Cycle</th>
                    <th>Student Count</th>
                    <th>Date</th>
                    <th class="noExport">Status</th>
                    <th class="noExport">Subscribe Status</th>
                    <th class="noExport">Approval Status</th>                        
                    <th class="noExport">Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>        
        </table>
    </div>
</div>
</div>
</div>
</div>
</div>
@endsection

@section('script')
<script>
    window.statuschange='{{route('get_schoolmanagement_data_from_admin')}}';
    $('document').ready(function(){

        var element = $("#example");
        var url =  '{{route('get_schoolmanagement_data_from_admin')}}';
        var column = [
          
            {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
            { data: 'schoolname', name: 'schoolname', width: '15%' },
            { data: 'phoneno', name: 'phoneno', width: '20%' },
            { data: 'subscplan', name: 'subscplan', width: '10%', className: 'textcenter' },
            { data: 'billcycle', name: 'billcycle' , className: 'textcenter' },
            { data: 'group', name: 'group' , className: 'textcenter',searchable: false, sortable: false, },
            { data: 'users.status', name: 'id', searchable: false, sortable: false, className: 'textcenter',render : function(data, type, row, meta)
                    {
                        if(row['id']!=1)
                        {
                            return `<label class="switch">
                        <input type="checkbox" id=${row['id']} ${row['status']=="Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                        <span class="slider round"></span>
                      </label>`;
                        }else{
                            return "";
                        }
                        
                    }
                  },

            { data: 'action', name: 'users.id', searchable: false, sortable: false, className: 'textcenter'}
        ];
        var csrf = '{{ csrf_token() }}';

        var options  = {
            //order : [ [ 6, "desc" ] ],
            //lengthMenu: [[100, 250, 500], [100, 250, 500]]
            button : [
               
                {
                    name : "Trash",
                    url : "{{route('user_action_from_admin',-1)}}"
                },
                {
                    name : "Delete",
                    url : "{{route('user.destroy',1)}}",
                    method : "DELETE"
                }
            ],

        }


        dataTable(element,url,column,csrf,options);

    });
</script>
    <script>
        $(function(){
            $("input[data-bootstrap-switch]").each(function(){
              $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        });
        </script>
         

@endsection

