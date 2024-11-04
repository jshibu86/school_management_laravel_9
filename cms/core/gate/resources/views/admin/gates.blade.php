@extends('layout::admin.master')

@section('style')

@endsection

@section('body')
<div class="card">
    <div class="card-body">
        {{ Form::open(array('role' => 'form', 'route'=>array('save_roles_from_admin'), 'method' => 'post', 'class' => 'form-horizontal form-label-left', 'id' => 'role-form')) }}
        <div class="card-title btn_style">
            <h4 class="mb-0">Permissions</h4>
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => '' , 'value' => 'role_save' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            
        </div>
        <hr/>
            <div class="row">
                <div class="col-3">
                    <div
                    class="nav flex-column nav-pills"
                    id="v-pills-tab"
                    role="tablist"
                    aria-orientation="vertical"
                     >
                     @foreach($groups as $group)
                     <a
                     class="nav-link {{($loop->iteration == 1) ? 'active' : ''}}"
                     id="v-pills-{{str_replace(' ','',$group->group)}}-tab"
                     data-bs-toggle="pill"
                     href="#group-{{str_replace(' ','',$group->group)}}"
                     role="tab"
                     aria-controls="v-pills-{{str_replace(' ','',$group->group)}}"
                     aria-selected="{{($loop->iteration == 1) ? 'true' : 'false'}}"
                     
                     >{{$group->group}}</a
                     >

                     {{-- <li class="{{($loop->iteration == 1) ? 'active' : ''}}"><a href="#group-{{str_replace(' ','',$group->group)}}" data-toggle="tab">{{$group->group}}</a></li> --}}
                    @endforeach
                    </div>
                </div>
                <div class="col-9">
                   
                  

                    <div class="tab-content" id="v-pills-tabContent">
                        @foreach($groups as $group)

                        <div
                          class="tab-pane fade {{($loop->iteration == 1) ? 'show active' : ''}}"
                          id="group-{{str_replace(' ','',$group->group)}}"
                          role="tabpanel"
                          aria-labelledby="v-pills-{{str_replace(' ','',$group->group)}}-tab"
                        >

                        <div class="row">
                            @foreach($module as $key => $value)
                                @if(count((array) $value->permissions)!=0)
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <fieldset>
                                        <legend class="bg-primary px-1 text-white aligen-center">{{ucfirst($value->name)}}</legend>
                                        @foreach($value->permissions as $values)
                                        <input type="hidden" id="role-hidden-{{$group->group.'-'.$values->id}}" name="role[{{$group->id}}][{{$values->id}}]" value="0" />
                                        {!! Form::checkbox('role['.$group->id.']['.$values->id.']', '1', (@$permission[$group->id][$values->id]==1) ? true : false, array('id'=>'role-'.$group->group.'-'.$values->id)) !!}
                                        <label for="role-{{$group->group.'-'.$values->id}}">{{$values->name}}</label> <br />
                                        @endforeach
                                    </fieldset>
                                </div>
                                @endif
                            @endforeach
                        </div>

                        </div>
                        @endforeach
                    </div>

                    {{Form::close()}}
                </div>
            </div>
        
    </div>
</div>
       
     
        

@endsection