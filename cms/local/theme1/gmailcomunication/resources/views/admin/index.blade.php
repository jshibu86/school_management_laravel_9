@extends('layout::admin.master')

@section('title','gmailcomunication')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
        .nav_div .nav-pills .nav-link.active,  .nav_div .nav-pills .show > .nav-link ,.external_sent.active,.nav-pills .show >.external_sent{
            background-color:#673ab7 !important;
            color:#fff !important;
        }
        .nav_group_div .nav-pills .nav-link.active,  .nav_group_div .nav-pills .show > .nav-link {
            background-color:unset !important;
            .grp_edit_span{
                display:inline-flex !important;
                gap:5px;
            }  
        }
        .nav_div .nav-link ,.nav_group_div .nav-link, .external_sent{
           color: unset !important;
        }
        .nav_div .nav-pills .nav-link:hover, .external_sent:hover{
            background-color:#e1d5f5 !important;
            color:#673ab7 !important;
        }
        .badge {
            line-height: unset !important;
    
        }
        .remove{
          margin-left:130px;
        }
        .form-check_input{
            width:1.7em !important;
            height:1.7em !important;
            border-radius: .25em;
        }
        .indication_radius{
            border-radius:2px;
        }
        .list_item_content::before {
            content: "";
            display: inline-block;
            width: 7px;
            height: 7px;
            background-color: black;
            margin-right: 9px;
        }
        .nav_group_div .nav-pills .nav-item:hover .grp_edit_span{
            
                display:block !important;
                gap:5px;            
        }
        .btn-primary {
            color: #fff !important;
            background-color: #673ab7 !important;
            border-color: #673ab7 !important;
        }
        .box-header button, .box-header i, .box-header a {
            font-size: 19px !important;
        }
        .recipients_select .select2-container--bootstrap4 .select2-selection{
            border:none !important;
        }
        .gmail_decripition{
            border:none !important;
            background-color:#F5F5F5;
        }
        .gmail_decripition:focus-visible {
            border: none !important;
            outline: unset;
        }
        .icon_img{
            width:24px !important;
            height:24px !important;
            cursor: pointer;
        }
        .outline_none:focus{
            box-shadow:unset !important;
        }
        .border-none{
            border:none !important;
        }
        .accordion-button:focus {
            border-color: unset !important;
            box-shadow: unset !important;
        }
        .accordion-button:not(.collapsed) {
            color:unset !important;
            background-color : unset !important;
        }
        .group_content_scroll{
            height:400px;
            overflow-y:scroll;
            overflow-x: hidden;
        }
        .nav_div{
            height:200px;
            overflow-y: scroll;
            overflow-x: hidden;
        }
        .nav_div:hover::-webkit-scrollbar-thumb{
           
                display:block;
            
        }
        .nav_div::-webkit-scrollbar-track
        {
           
            /* -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); */
            border-radius: 10px;
            background-color: #FFF;
        }
        .nav_div::-webkit-scrollbar
        { 
            
            width: 6px;
            background-color: #FFF;
        }
        .nav_div::-webkit-scrollbar-thumb
        {
            display:none;
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: #673ab7;;
        }
        /* .nav_group_div{
            height:200px !important;
            overflow-y: scroll !important;
            overflow-x: hidden !important;
        }
        .nav_group_div:hover::-webkit-scrollbar-thumb{
           
                display:block;
            
        }
        .nav_group_div::-webkit-scrollbar-track
        {
           
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            border-radius: 10px;
            background-color: #FFF;
        }
        .nav_group_div::-webkit-scrollbar
        {           
            width: 6px;
            background-color: #FFF;
        }
        .nav_group_div::-webkit-scrollbar-thumb
        {
            display:block;
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: #2a2a7c;;
        } */

        .replay_content_scroll{
            height:300px;
            overflow-y: scroll;
            overflow-x: hidden;
        }
        .group_content_scroll{
            height:400px;
            overflow-y:scroll;
            overflow-x: hidden;
        }
        .group_content_scroll:hover::-webkit-scrollbar-thumb,.replay_content_scroll:hover::-webkit-scrollbar-thumb{
            display:block;
        }
        .group_content_scroll::-webkit-scrollbar-track, .replay_content_scroll::-webkit-scrollbar-track
        {
            /* -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); */
            border-radius: 10px;
            background-color: #FFF;
        }

        .group_content_scroll::-webkit-scrollbar,.replay_content_scroll::-webkit-scrollbar
        {
            width: 8px;
            background-color: #FFF;
        }

        .group_content_scroll::-webkit-scrollbar-thumb, .replay_content_scroll::-webkit-scrollbar-thumb
        {
            display:none;
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: #8888;;
        }
        .pagination {
            align-items: center;
            justify-content: end !important;
           
        }
        .btn:focus {
            outline: 0;
            box-shadow: unset !important;
        }
        .group_edit_icons {
            vertical-align: -55% !important;
            cursor: pointer;
        }
      
        .img_height{
            height:100px !important;
        }

        /* message image and file content */

        .exam_information {
            background-color: #D9D9D9;
            width: 47%;
            margin: auto;
            padding: 10px;
        }
        .homework__data{
            text-align: center
        }
        .attachment a:hover{
            color: white
        }
        .container_attachment {
            display: flex;      
            flex-wrap: wrap;
            float:left;
            padding-left: unset !important;
        }

        .card_attachment {
            position: relative;
            width: 150px;
            height:150px;
            background: radial-gradient(#111 50%, #000 100%);
            overflow: hidden;
            cursor: pointer;
            
        }

        .img {
            max-width: 100%;
            height:100%;
            display: block; 
        }

        .card_attachment img {
            transform: scale(1.3);
            transition: 0.3s ease-out;
        }

        .card_attachment:hover img {
            transform: scale(1.1) ;
            opacity: 0.3;
        }

        .overlay {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 100%;

            top:30px;
            text-align: center;
            color: #fff;
        }



        .link-a {
            display: inline-block;
            border: solid 2px white;
            color: #fff;
            margin-top: 30px;
            padding: 5px 5px;
            border-radius: 5px;
            transform: translateY(30px);
            opacity: 0;
            transition: all .3s ease-out 0.4s;
        }
        .link-b {
            display: inline-block;
            border: solid 2px white;
            color: #fff;
            margin-top: 30px;
            padding: 5px 5px;
            border-radius: 5px;
            transform: translateY(30px);
            opacity: 0;
            transition: all .3s ease-out 0.4s;
        }

        .overlay .link-a:hover {
            background: #fff;
            color:#000;
        }
        .overlay .link-b:hover {
            background: #fff;
            color:#000;
        }
        .card_attachment:hover .overlay .link-a {
            opacity: 1;
            transform: translateY(0);
        }
        .card_attachment:hover .overlay .link-b {
            opacity: 1;
            transform: translateY(0);
        
        }
        .scrollable_teacher {
                height: 400px;
                overflow-y: auto; 
                overflow-x:hidden;
                border: 1px solid #ccc; 
                padding: 10px; 
        }
        .scrollable_student {
                height: 280px;
                overflow-y: auto; 
                overflow-x:hidden;
                border: 1px solid #ccc; 
                padding: 10px; 
        } 
        .nav_li:hover .d-md-flex .delete_icon{
            display:block !important;
            cursor: pointer;
        }   
        .search-bar {
            position: relative;
            width: 50%;
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 20px 10px 40px;
            border-radius: 20px;
            border: 1px solid #ccc;
        }

        .search-bar .fa-search {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #ccc;
        }

        .text_overflow{
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            display: block; /* Ensure the element is a block element */
        }
     
    
        @media(max-width:1439px){
            .card_left{
                width:30% !important;
            }
            .card_right{
                width:65% !important;
            }
        }
    </style>
@endsection
@section('body') 
  <div class="x_content">
    <p class="fw-bold" style="font-size:32px;">Communication</p>
    <div class="d-flex gap-4">
        <div class="card p-3 card_left" style="width:20%">
            <div class="card-body">
                <h5>My Email</h5>
                <ul class="nav nav-pills mb-3 " type="square" id="pills-tab" role="tablist">
                    <li class="nav-item w-100" role="presentation"> 
                        <a class="nav-link btn btn-primary compose_btn btn-lg w-100 @if(session('active_tab') == 'compose') active @endif" style="font-size:15px;"  id="pills-compose-tab" data-bs-toggle="pill"
                         href="#pills-compose" role="tab" aria-controls="pills-compose" aria-selected="true">+ Compose</a>
                        {{-- <button type="button" class="btn btn-primary compose_btn btn-lg w-100">+ Compose</button>  --}}
                    </li>
                </ul>
                <div class="my-4">
                  
                    <div class="nav_div mt-3">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item w-100" role="presentation"> <a class="nav-link row gap-4 mb-1  @if(session('active_tab') !== 'compose' && session('active_tab') !== 'external_compose') active @endif" style="font-size:15px;"  id="pills-inbox-tab" data-bs-toggle="pill"
                                    href="#pills-inbox" role="tab" aria-controls="pills-inbox" aria-selected="true">
                                    <span class="w-75"><i class="fa fa-inbox me-3"></i>Inbox</span><span class="w-25 text-end"style="float:right">{{@$inbox_messages->total()}}</span></a>
                            </li>
                      
                            <li class="nav-item w-100" role="presentation"> <a class="nav-link row mb-1 @if(session('active_tab'))@if(session('active_tab') == 'sent') active @endif @endif" style="font-size:15px;"  id="pills-starred-tab" data-bs-toggle="pill"
                                href="#pills-starred" role="tab" aria-controls="pills-starred" aria-selected="true">
                                <span class="w-75"><i class="fa fa-star-o me-3"></i>Starred</span><span class="w-25 text-end" style="float:right">{{@$starred_messages->total()}}</span></a>
                           </li>
                           <li class="nav-item w-100" role="presentation"> <a class="nav-link row mb-1" style="font-size:15px;"  id="pills-sent-tab" data-bs-toggle="pill"
                               href="#pills-sent" role="tab" aria-controls="pills-sent" aria-selected="true">
                               <span class="w-75"><i class="fa fa-paper-plane me-3"></i>Sent</span><span class="w-25 text-end" style="float:right">{{@$sent_messages->total()}}</span></a>
                          </li>
                          <li class="nav-item w-100" role="presentation"> <a class="nav-link row mb-1" style="font-size:15px;"  id="pills-draft-tab" data-bs-toggle="pill"
                              href="#pills-draft" role="tab" aria-controls="pills-draft" aria-selected="true">
                              <span class="w-75"><i class="fa fa-pencil me-3" aria-hidden="true"></i>Draft</span><span class="w-25 text-end" style="float:right">{{@$draft_messages->total()}}</span></a>
                          </li>
                          <li class="nav-item w-100" role="presentation"> <a class="nav-link row mb-1" style="font-size:15px;"  id="pills-bin-tab" data-bs-toggle="pill"
                            href="#pills-bin" role="tab" aria-controls="pills-bin" aria-selected="true">
                            <span class="w-75"><i class="fa fa-trash me-3" aria-hidden="true"></i></i>Bin</span><span class="w-25 text-end" style="float:right">{{@$bin_messages->total()}}</span></a>
                          </li>
                
                        </ul>
                    </div>
                </div> 
                <div class="my-4">
                    <h5>External Email</h5>
                    <ul class="nav nav-pills mb-3 " type="square" id="pills-tab" role="tablist">
                        <li class="nav-item w-100" role="presentation"> 
                            <a class="nav-link btn btn-primary external_compose_btn btn-lg w-100 @if(session('active_tab') == 'external_compose') active @endif" style="font-size:15px;"  id="pills-compose-tab" data-bs-toggle="pill"
                             href="#pills-external-compose" role="tab" aria-controls="pills-external-compose" aria-selected="true">+ Compose</a>
                            {{-- <button type="button" class="btn btn-primary compose_btn btn-lg w-100">+ Compose</button>  --}}
                        </li>
                        <li class="nav-item w-100 mt-3" role="presentation"> <a class="nav-link mb-1 external_sent" style="font-size:15px;"  id="pills-external-sent-tab" data-bs-toggle="pill"
                            href="#pills-external-sent" role="tab" aria-controls="pills-external-sent" aria-selected="true">
                            <span class="w-75"><i class="fa fa-paper-plane me-3"></i>Sent</span><span class="w-25 text-end" style="float:right">{{@$external_sent_messages->total()}}</span></a>
                       </li>
                    </ul>
                </div> 
                <div class="my-4">
                    <h5>Group</h5>
                    <div class="nav_group_div">
                        <ul class="nav nav-pills ms-2" type="square" id="pills-tab" role="tablist">
                            @if(@$groups)
                            @foreach(@$groups as $group)
                            <li class="nav-item w-100 d-flex gap-2 mb-2" role="presentation"> 
                                <a class="nav-link row group_link" style="font-size:15px;" data-group-id ="{{$group->id}}" id="pills-{{$group->id}}-tab" data-bs-toggle="pill"
                                    href="#pills-{{$group->id}}" role="tab" aria-controls="pills-{{$group->id}}" aria-selected="true">                                    
                                        <span class="list_item_content">{{$group->title}}</span>
                                </a>
                                @if($group->creater == $user_id)
                                <span class="d-none grp_edit_span" style="float:right;">
                                    <i class="fa fa-pencil fa-lg group_edit group_edit_icons" id="{{$group->id}}" aria-hidden="true"></i> 
                                    <i class="fa fa-trash fa-lg group_delete group_edit_icons" id="{{$group->id}}" aria-hidden="true"></i>
                               
                                </span>
                                @endif
                            </li>
                            @endforeach
                             @endif      
                        </ul>
                        @if($eligible_role == "yes")
                        <button type="button" class="btn opacity-50" id="create_group" name="create_group">
                            <span class="me-3"><i class="fa fa-plus" aria-hidden="true"></i></span><span class="">Create New Group</span></a> 
                        </button>
                        @endif
                    </div>
                </div>
            </div>
             
        </div>
        <div class="card w-75 p-3 card_right">
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade  @if(session('active_tab') !== 'compose' && session('active_tab') !== 'external_compose' && session('active_tab') !== 'sent') active show @endif" id="pills-inbox" role="tabpanel" aria-labelledby="pills-inbox-tab">
                        <form class="">
                            <div class="search-bar">
                                <input type="text" id="searchInput" data-type="inbox" class="searchInput" placeholder="Search mail">                              
                            </div>
                       
                            <div class="content mt-2" id="inbox-messages-container">
                                @include('gmailcomunication::admin.messages.inbox_messages', ['inbox_messages' => $inbox_messages,'senter_roles'=>$senter_roles,'starred_ids'=>$starred_ids])
                            </div> 
                           
                       </form>
                    </div>
                    <div class="tab-pane fade" id="pills-starred" role="tabpanel" aria-labelledby="pills-starred-tab">
                        <form class="">
                            <div class="search-bar">
                                <input type="text" id="searchInput" data-type="starred" class="searchInput" placeholder="Search mail">                              
                            </div>
                       
                            
                                <div class="content mt-2" id="starred-messages-container" data-container="#starred-messages-container" data-type="starred">
                                    @include('gmailcomunication::admin.messages.starred_messages', ['starred_messages' => $starred_messages,'starred_senter_roles'=>$starred_senter_roles])
                                </div> 
                       
                           
                       </form>
                    </div>
                    <div class="tab-pane fade @if(session('active_tab'))@if(session('active_tab') == 'sent')active show @endif @endif" id="pills-sent" role="tabpanel" aria-labelledby="pills-sent-tab">
                        <div class="">
                            <div class="search-bar">
                                <input type="text" id="searchInput" data-type="sent" class="searchInput" placeholder="Search mail">                              
                            </div>                      
                            <div class="content mt-2" id="sent-messages-container">
                                @include('gmailcomunication::admin.messages.sent_messages', ['sent_messages' => $sent_messages])
                            </div>                        
                        </div>
                    </div>
                    
                    <div class="tab-pane fade @if(session('active_tab') == 'compose') active show @endif" id="pills-compose" role="tabpanel" aria-labelledby="pills-compose-tab">
                        {{ Form::open(array('role' => 'form', 'route'=>array('individual_message'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left gmail_form', 'id' => 'gmailcomunication-form','novalidate' => 'novalidate')) }}
                        <div class="row">
                            <div class="col-11">
                                <h4>Compose</h4>
                            </div>
                            
                            {{-- <div class="box-header with-border col-1 mar-bottom20 border py-2 d-flex gap-2" style="background-color:#FAFBFD; border-radius:17px;">
                                <i class="fa fa-user fa-2x " aria-hidden="true"></i>
                                <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i>
                                <i class="fa fa-trash fa-2x" aria-hidden="true"></i>
                            </div> --}}
                        </div>

                        <div class="d-flex gap-3 border-bottom pb-2 ">
                            @php
                              $user_info = $users->where('id',$user_id)->first();
                            @endphp
                            @if($user_info->images == null)
                             <img class="user-img" src="http://127.0.0.1:8000/assets/images/default.jpg" alt="" name="user_image">
                            @else
                             <img class="user-img" src="{{asset($user_info->images)}}" alt="" name="user_image">
                            @endif
                            <p class="fw-bold" style="margin-block-start: 1em !important; margin-block-end: 1em !important ;">{{$user_info->name}}</p>
                        </div>
                       <div class="form-group border-bottom recipients_select pb-3 d-flex gap-1 mt-4">
                          <label for="to_users" class="form-label" style="font-size:20px; font-weight:100;">To: </label> 
                              <div class="w-25">
                                {{ Form::select('to_users_group',@$eligibele_receptiants_group,@$member_id ,array('id'=>"to_users_group",'data-id'=>'to_users','class' => 'recipients_group form-select single-select outline_none form-control','required' => 'required', )) }}
                              </div>
                                {{ Form::select('to_users[]',@$eligibele_receptiants,@$member_id ,array('id'=>"to_users",'class' => 'recipients form-select multiple-select outline_none form-control', 'size'=>"3",'required' => 'required',"multiple" )) }}
                       </div>
                       <div class="form-group border-bottom recipients_select pb-3 d-flex gap-1 mt-4">
                            <label for="gmail_subject" class="" style="font-size:20px; font-weight:100;">Subject: </label>
                            {{ Form::text('gmail_subject', @$member_id ,
                            array('id'=>"gmail_subject",'class' => ' form-control fw-bold outline_none','required' => 'required','style'=>'border: none !important;' )) }}
                       </div>

                       <div class="container" style="background-color:#F5F5F5; border-radius:16px;">
                          <div class="form-group mt-5 pt-2" style="background-color:#F5F5F5;">
                            {{-- {{ Form::textarea('gmail_description', @$data->group_description, ['id' => 'gmail_description', 'class' => 'gmail_decripition w-100','placeholder'=>'write your message here....' ,'required' => 'required', 'rows' => 10]) }} --}}
                            @include('layout::widget.ckeditor',['name'=>'gmail_description','id'=>'gmail_description','class'=>'gmail_decripition w-100','content'=>@$data->homework_description ?@$data->homework_description : old("homework_description") ])
                          </div>
                          <div class="row">
                            <div class="img_view my-2">                                       
                                <div class="viewimgdiv d-flex gap-2"></div>                                          
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex gap-2">
                                    <div>
                                        <input type="file" name="compose_message_img[]" id="message_img" class="message_img" accept=".jpg,.png,.jpeg,.pdf" style="display: none;" multiple max="3">
                                        <label for="message_img" class="icon_img">
                                            <img src="{{ asset('assets/icon_images/Draft Toolbar Icons5.png') }}" class="icon_img" alt="Upload Image">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">    
                                <button type="submit" class="btn btn-primary gmail_message_sent ms-2" name="submit" value="send" style="float:right;">Send <i class="fa fa-paper-plane ms-1" style="font-size:14px;" aria-hidden="true"></i></button>
                                <button type="submit" class="btn btn-secondary gmail_message_sent" name="submit" value="draft" style="float:right;">Draft <i class="fa fa-clipboard ms-1" style="font-size:14px;" aria-hidden="true"></i></button>
                            </div>
                          </div>
                       </div>
                       {{Form::close()}}
                    </div>
                    <div class="tab-pane fade" id="pills-draft" role="tabpanel" aria-labelledby="pills-draft-tab">
                        <div class="">
                            <div class="search-bar">
                                <input type="text" id="searchInput" data-type="draft" class="searchInput" placeholder="Search mail">                              
                            </div>                        
                            <div class="content mt-2" id="draft-messages-container">
                                @include('gmailcomunication::admin.messages.draft_messages', ['draft_messages' => $draft_messages])
                            </div>                       
                       </div>
                     
                    </div>
                    <div class="tab-pane fade" id="pills-bin" role="tabpanel" aria-labelledby="pills-bin-tab">
                        <form class="">
                            <div class="search-bar">
                                <input type="text" id="searchInput" data-type="bin" class="searchInput" placeholder="Search mail">                              
                            </div>
                       
                            <div class="content mt-2" id="draft-messages-container">
                                @include('gmailcomunication::admin.messages.bin_messages', ['bin_messages' => $bin_messages])
                            </div>   
                           
                       </form>
                    </div>

                    <div class="tab-pane fade @if(session('active_tab') == 'external_compose') active show @endif" id="pills-external-compose" role="tabpanel" aria-labelledby="pills-external-compose-tab">
                        {{ Form::open(array('role' => 'form', 'route'=>array('external_message'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left external_gmail_form', 'id' => 'gmailcomunication-form','novalidate' => 'novalidate')) }}
                        <div class="row">
                            <div class="col-11">
                                <h4>External Message</h4>
                            </div>
                            
                            {{-- <div class="box-header with-border col-1 mar-bottom20 border py-2 d-flex gap-2" style="background-color:#FAFBFD; border-radius:17px;">
                                <i class="fa fa-user fa-2x " aria-hidden="true"></i>
                                <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i>
                                <i class="fa fa-trash fa-2x" aria-hidden="true"></i>
                            </div> --}}
                        </div>

                        <div class="d-flex gap-3 border-bottom pb-2 ">
                            @php
                              $user_info = $users->where('id',$user_id)->first();
                            @endphp
                            @if($user_info->images == null)
                             <img class="user-img" src="http://127.0.0.1:8000/assets/images/default.jpg" alt="" name="user_image">
                            @else
                             <img class="user-img" src="{{asset($user_info->images)}}" alt="" name="user_image">
                            @endif
                            <p class="fw-bold" style="margin-block-start: 1em !important; margin-block-end: 1em !important ;">{{$user_info->name}}</p>
                        </div>
                       <div class="form-group border-bottom recipients_select pb-3 d-flex gap-1 mt-4">
                          <label for="to_users" class="form-label" style="font-size:20px; font-weight:100;">To: </label>
                          <div class="w-25">
                            {{ Form::select('to_users_group',@$eligibele_receptiants_group,@$member_id ,array('id'=>"external_to_users_group", 'data-id'=>'to_users_external','class' => 'recipients_group form-select single-select outline_none form-control','required' => 'required', )) }}
                          </div>
                          {{ Form::select('to_users[]',@$eligibele_receptiants,@$member_id ,array('id'=>"to_users_external",'class' => 'external_recipients form-select multiple-select outline_none form-control', 'size'=>"3",'required' => 'required',"multiple" )) }}
                       </div>
                       <div class="form-group border-bottom recipients_select pb-3 d-flex gap-1 mt-4">
                            <label for="gmail_subject" class="" style="font-size:20px; font-weight:100;">Subject: </label>
                            {{ Form::text('gmail_subject', @$member_id ,
                            array('id'=>"gmail_subject",'class' => ' form-control fw-bold outline_none','required' => 'required','style'=>'border: none !important;' )) }}
                       </div>

                       <div class="container" style="background-color:#F5F5F5; border-radius:16px;">
                          <div class="form-group mt-5 pt-2" style="background-color:#F5F5F5;">                      
                            @include('layout::widget.ckeditor',['name'=>'gmail_message','id'=>'gmail_message','class'=>'gmail_decripition w-100','content'=>@$data->homework_description ?@$data->homework_description : old("homework_description") ])
                          </div>
                          <div class="row">
                            <div class="img_view my-2">                                       
                                <div class="viewimgdiv d-flex gap-2"></div>                                          
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex gap-2">
                                    <div>
                                        <input type="file" name="message_img[]" id="gmail_message_img" class="message_img" accept=".jpg,.png,.jpeg,.pdf" style="display: none;" multiple max="3">
                                        <label for="gmail_message_img" class="icon_img">
                                            <img src="{{ asset('assets/icon_images/Draft Toolbar Icons5.png') }}" class="icon_img" alt="Upload Image">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">    
                                <button type="submit" class="btn btn-primary gmail_message_sent ms-2" name="submit" value="send" style="float:right;">Send <i class="fa fa-paper-plane ms-1" style="font-size:14px;" aria-hidden="true"></i></button>
                              
                            </div>
                          </div>
                       </div>
                       {{Form::close()}}
                    </div>
                    <div class="tab-pane fade" id="pills-external-sent" role="tabpanel" aria-labelledby="pills-external-sent-tab">
                        <div class="">
                            <div class="search-bar">
                                <input type="text" id="searchInput" data-type="sent" class="searchInput" placeholder="Search mail">                              
                            </div>                      
                            <div class="content mt-2" id="external-sent-messages-container">
                                @include('gmailcomunication::admin.messages.external_sent_messages', ['external_sent_messages' => $external_sent_messages])
                            </div>                        
                        </div>
                    </div>
                    @if(@$groups)
                        @foreach(@$groups as $group)
                            <div class="tab-pane fade" id="pills-{{$group->id}}" role="tabpanel" aria-labelledby="pills-{{$group->id}}-tab">
                                <div class="mb-3">
                                        <h4>Group</h4>
                                </div>   
                                <div class="d-flex gap-3 border-bottom pb-2 ">
                                    <img class="user-img" src="{{asset($group->image)}}" alt="" name="group_image">
                                    <div>
                                        <div class="accordion" id="accordionExample">
                                            <div class="accordion-item border-none">
                                                <h6 class="accordion-header" id="headingTwo">
                                                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                           {{$group->title}}
                                                        </button>
                                                </h6>
                                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                    @php
                                                      $members = $group_receptiants->where('gmail_group_id',$group->id);
                                                      $usernames = $members->pluck('username.name')->implode(',');
                                                    @endphp
                                                        <div class="accordion-body">
                                                            <strong>Description: </strong> <span>{{$group->descripition}}</span><br>
                                                            <strong>Members: </strong> <span>{{$usernames}}</span>
                                                        </div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                                @php
                                    if($gmail_group_messages){
                                      $group_messages = $gmail_group_messages->where('gmail_group_id',$group->id);
                                    }
                                    $count = $group_messages->count();
                                @endphp
                                @if($count>="3")
                                <div class="group_content{{$group->id}} group_content_scroll">
                                 @else
                                 <div class="group_content{{$group->id}}">
                                    @endif  
                                    <input type="hidden" id="count" value="{{$count}}">
                                    @if(@$group_messages)
                                        @foreach($group_messages as $message)
                                          @if($message->userid !== $user_id)
                                            <div class="container">
                                                <div class="row mt-5">
                                                    <div class="col-1 align-self-end" >
                                                        @if($message->username->images == null)
                                                          <img class="user-img" src="http://127.0.0.1:8000/assets/images/default.jpg" alt="" name="user_image">
                                                        @else
                                                          <img class="user-img" src="{{asset($message->username->images)}}" alt="" name="user_image">
                                                        @endif
                                                    </div>
                                                    <div class="col-9 p-3" style="background-color: #F5F5F5; border-radius:16px;">
                                                        <p>{!!$message->message!!}</p>
                                                        @if($message->files !==null)
                                                             @php
                                                               $file_paths = json_decode($message->files);
                                                             @endphp
                                                            <div class="row">
                                                                @foreach($file_paths as $key => $path)
                                                                <div class="col-md-3">
                                                                    <div class="card_attachment">
                                                                        @php
                                                                        $file_extension = pathinfo($path, PATHINFO_EXTENSION);
                                                                        @endphp
                                                                        @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif")
                                                                        <img src="{{ asset($path) }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                        @elseif ($file_extension == 'mp4' || $file_extension == 'avi' || $file_extension == 'mov')  
                                                                        <img src="{{ asset('assets/sample/images.png') }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                        @elseif($file_extension == 'mp3') 
                                                                        <img src="{{ asset('assets/sample/istockphoto-1244097573-612x612.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                        @else
                                                                        <img src="{{ asset('assets/sample/file.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                        @endif
                                                                        <div class="overlay">
                                                                            <a href="{{$path}}" class="link-b btn bg-white text-dark nav-link"target="_blank"><i class="fa fa-eye"></i></a>
                                                                            <a href="{{ @$path }}" class="link-b btn bg-white text-dark" download="{{ @$path }}"><i class="fa fa-download"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>                                                        
                                                        @endif 
                                                        <div class="row mt-5">
                                                            <div class="col-6">
                                                                <p class="fw-bold">{{$message->username->name}}</p>
                                                            </div>
                                                            <div class="col-6">
                                                                <p style="float:right;"><span>{{$message->time}} </span><button type="button" class="info_btn border-none" style="background-color: #F5F5F5;"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                          @else  
                                            <div class="container">
                                                <div class="row mt-5">
                                                <div class="col-2"></div>
                                                    <div class="col-9 p-3" style="background-color: #F5F5F5;border-radius:16px;">
                                                        <p>{!!$message->message!!}</p>
                                                        @if($message->files !==null)
                                                             @php
                                                               $file_paths = json_decode($message->files);
                                                             @endphp
                                                            <div class="row">
                                                                @foreach($file_paths as $key => $path)
                                                                <div class="col-md-3">
                                                                    <div class="card_attachment">
                                                                        @php
                                                                        $file_extension = pathinfo($path, PATHINFO_EXTENSION);
                                                                        @endphp
                                                                        @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif")
                                                                        <img src="{{ asset($path) }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                        @elseif ($file_extension == 'mp4' || $file_extension == 'avi' || $file_extension == 'mov')  
                                                                        <img src="{{ asset('assets/sample/images.png') }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                        @elseif($file_extension == 'mp3') 
                                                                        <img src="{{ asset('assets/sample/istockphoto-1244097573-612x612.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                        @else
                                                                        <img src="{{ asset('assets/sample/file.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                        @endif
                                                                        <div class="overlay">
                                                                            <a href="{{$path}}" class="link-b btn bg-white text-dark nav-link"target="_blank"><i class="fa fa-eye"></i></a>
                                                                            <a href="{{ @$path }}" class="link-b btn bg-white text-dark" download="{{ @$path }}"><i class="fa fa-download"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>                                                        
                                                        @endif 
                                                        <div class="row mt-5">
                                                            <div class="col-6">
                                                                <p><span>{{$message->time}} </span><button type="button" class="info_btn border-none" style="background-color: #F5F5F5;"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button></p>
                                                            </div>
                                                            <div class="col-6">
                                                                <p class="fw-bold" style="float:right;">{{$message->username->name}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-1 align-self-end" style="float:right;">
                                                        @if($message->username->images == null)
                                                          <img class="user-img" src="http://127.0.0.1:8000/assets/images/default.jpg" alt="" name="user_image">
                                                        @else
                                                          <img class="user-img" src="{{asset($message->username->images)}}" alt="" name="user_image">
                                                        @endif
                                                    </div>
                                                    
                                                </div>
                                            </div> 
                                          @endif  
                                        @endforeach
                                    @endif                        
                                </div>
                                <div class="container" style="background-color:#F5F5F5;border-radius:16px;">
                                    <div class="form-group mt-5 pt-2" style="background-color:#F5F5F5;">
                                        <input type="hidden" name="group_id" id="group_id" value="{{$group->id}}">
                                        {{-- {{ Form::textarea('gmail_group_message'.$group->id, @$data->group_description, ['id' => 'gmail_group_message'.$group->id, 'class' => 'gmail_decripition w-100','placeholder'=>'write your message here....' ,'required' => 'required', 'rows' => 4]) }} --}}
                                        @include('layout::widget.messageckeditor',['name'=>'gmail_group_message'.$group->id,'id'=>'gmail_group_message'.$group->id,'class'=>'gmail_decripition w-100','content'=>@$data->homework_description ?@$data->homework_description : old("homework_description") ])
                                    </div>
                                    <div class="row">
                                        <div class="img_view my-2">                                       
                                            <div class="viewimg{{$group->id}}div d-flex gap-2"></div>                                          
                                        </div>
                                        <div class="col-6 mb-3">
                                          
                                            <div class="d-flex gap-2">
                                              
                                                <div>
                                                    <input type="file" name="message_img[]" id="message_img{{$group->id}}" class="message_img" data-id="{{$group->id}}" style="display: none;" multiple max="3">
                                                    <label for="message_img{{$group->id}}" class="icon_img">
                                                        <img src="{{ asset('assets/icon_images/Draft Toolbar Icons5.png') }}" class="icon_img" alt="Upload Image">
                                                    </label>
                                                </div>
                                               
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <button type="button" class="btn btn-primary send_msg" onclick="GmailCommunicationConfig.GroupMessage(this.id)" id="{{$group->id}}" style="float:right;">Send <i class="fa fa-paper-plane ms-1" style="font-size:14px;" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if(@$sent_messages_list)
                        @foreach($sent_messages_list as $message)
                            <div class="tab-pane fade" id="pills-sent{{$message->id}}" role="tabpanel" aria-labelledby="pills-sent-tab{{$message->id}}">
                             
                                    <button type="button" class="btn btn-danger close-sent" data-id="{{$message->id}}" style="float:inline-end" aria-label="Close">
                                        Back
                                    </button>  
                                    <div class="d-flex gap-3 border-bottom pb-2 mt-4">
                                        @php
                                            if($message->senter->images !== null){
                                            $image = url($message->senter->images);
                                            }
                                            else{
                                            $image = "http://127.0.0.1:8000/assets/images/default.jpg";
                                            }
                                        @endphp
                                        <img class="user-img" src="{{$image}}" alt="" name="user_image">
                                        <p class="fw-bold" style="margin-block-start: 1em !important; margin-block-end: 1em !important ;">{{$message->senter->name}}</p>
                                    </div>
                                    @php
                                     $recivers = $reciver_details->where('message_id',$message->id)->pluck('user_id');
                                    @endphp
                                    <div class="form-group border-bottom recipients_select pb-3 d-flex gap-1 mt-4">
                                        <label for="to_users" class="form-label" style="font-size:20px; font-weight:100;">To: </label>
                                        {{ Form::select('senter_id[]',@$eligibele_receptiants,@$recivers ,array('id'=>"senter_id".$message->id,'class' => 'recipients form-select multiple-select outline_none form-control', 'size'=>"3",'required' => 'required','placeholder' => 'Select Users',"multiple" )) }}
                                    </div>
                                    <div class="form-group border-bottom recipients_select pb-3 d-flex gap-1 mt-4">
                                            <label for="gmail_subject" class="" style="font-size:20px; font-weight:100;">Subject: </label>
                                            <span style="font-size:20px">{{@$message->subject}}</span>
                                    </div>
                                    <div class="inbox_message{{$message->id}} replay_content_scroll">
                                        <input type="hidden" name="message_type" id="message_type{{$message->id}}" value="1">
                                        <div class="container p-3 mt-2" style="background-color: #F5F5F5;">
                                            <div class="inbox_message_div">
                                                <p style="font-size:16px">{!!$message->message!!}</p>
                                                @if($message->files !==null)
                                                    @php
                                                        $file_paths = json_decode($message->files);
                                                    @endphp
                                                    <div class="row">
                                                        @foreach($file_paths as $key => $path)
                                                            <div class="col-md-3">
                                                                <div class="card_attachment">
                                                                    @php
                                                                    $file_extension = pathinfo($path, PATHINFO_EXTENSION);
                                                                    @endphp
                                                                    @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif")
                                                                    <img src="{{ asset($path) }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                    @elseif ($file_extension == 'mp4' || $file_extension == 'avi' || $file_extension == 'mov')  
                                                                    <img src="{{ asset('assets/sample/images.png') }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                    @elseif($file_extension == 'mp3') 
                                                                    <img src="{{ asset('assets/sample/istockphoto-1244097573-612x612.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                    @else
                                                                    <img src="{{ asset('assets/sample/file.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                    @endif
                                                                    <div class="overlay">
                                                                        <a href="{{$path}}" class="link-b btn bg-white text-dark"target="_blank"><i class="fa fa-eye"></i></a>
                                                                        <a href="{{ @$path }}" class="link-b btn bg-white text-dark" download="{{ @$path }}"><i class="fa fa-download"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>                                                        
                                                @endif 
                                            </div>                                       
                                        </div>
                                        @if($sent_messages_maping)
                                            @php
                                            $replay_messages =$sent_messages_maping->where('message_id',$message->id);
                                            @endphp
                                            @foreach($replay_messages as $message_map)
                                                @if($message_map->senter !== $user_id)
                                                    <div class="container">
                                                        <div class="row mt-5">
                                                            <div class="col-1 align-self-end" >
                                                              @if($message_map->senter_info)
                                                                    @if($message_map->senter_info->images == null)
                                                                        <img class="user-img" src="http://127.0.0.1:8000/assets/images/default.jpg" alt="" name="user_image">
                                                                    @else
                                                                        <img class="user-img" src="{{asset($message_map->senter_info->images)}}" alt="" name="user_image">
                                                                    @endif
                                                              @endif  
                                                            </div>
                                                            <div class="col-9 p-3" style="background-color: #F5F5F5; border-radius:16px;">
                                                                <p>{!!$message_map->message!!}</p>
                                                                @if($message_map->files !==null)
                                                                    @php
                                                                        $file_paths = json_decode($message_map->files);
                                                                    @endphp
                                                                    <div class="row">
                                                                        @foreach($file_paths as $key => $path)
                                                                        <div class="col-md-3">
                                                                            <div class="card_attachment">
                                                                                @php
                                                                                $file_extension = pathinfo($path, PATHINFO_EXTENSION);
                                                                                @endphp
                                                                                @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif")
                                                                                <img src="{{ asset($path) }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                                @elseif ($file_extension == 'mp4' || $file_extension == 'avi' || $file_extension == 'mov')  
                                                                                <img src="{{ asset('assets/sample/images.png') }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                                @elseif($file_extension == 'mp3') 
                                                                                <img src="{{ asset('assets/sample/istockphoto-1244097573-612x612.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                                @else
                                                                                <img src="{{ asset('assets/sample/file.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                                @endif
                                                                                <div class="overlay">
                                                                                    <a href="{{asset($path)}}" class="link-b btn bg-white text-dark"target="_blank"><i class="fa fa-eye"></i></a>
                                                                                    <a href="{{ @$path }}" class="link-b btn bg-white text-dark" download="{{ @$path }}"><i class="fa fa-download"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>                                                        
                                                                @endif 
                                                                <div class="row mt-5">
                                                                    <div class="col-6">
                                                                        <p class="fw-bold">{{$message_map->senter_info->name}}</p>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <p style="float:right;"><span>{{$message_map->formatted_date}} </span><button type="button" class="info_btn border-none" style="background-color: #F5F5F5;"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else  
                                                <div class="container">
                                                    <div class="row mt-5">
                                                    <div class="col-2"></div>
                                                        <div class="col-9 p-3" style="background-color: #F5F5F5;border-radius:16px;">
                                                            <p>{!!$message_map->message!!}</p>
                                                            @if($message_map->files !==null)
                                                                @php
                                                                    $file_paths = json_decode($message_map->files);
                                                                @endphp
                                                                <div class="row">
                                                                    @foreach($file_paths as $key => $path)
                                                                    <div class="col-md-3">
                                                                        <div class="card_attachment">
                                                                            @php
                                                                            $file_extension = pathinfo($path, PATHINFO_EXTENSION);
                                                                            @endphp
                                                                            @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif")
                                                                            <img src="{{ asset($path) }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                            @elseif ($file_extension == 'mp4' || $file_extension == 'avi' || $file_extension == 'mov')  
                                                                            <img src="{{ asset('assets/sample/images.png') }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                            @elseif($file_extension == 'mp3') 
                                                                            <img src="{{ asset('assets/sample/istockphoto-1244097573-612x612.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                            @else
                                                                            <img src="{{ asset('assets/sample/file.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                            @endif
                                                                            <div class="overlay">
                                                                                <a href="{{$path}}" class="link-b btn bg-white text-dark nav-link"target="_blank"><i class="fa fa-eye"></i></a>
                                                                                <a href="{{ @$path }}" class="link-b btn bg-white text-dark" download="{{ @$path }}"><i class="fa fa-download"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>                                                        
                                                            @endif 
                                                            <div class="row mt-5">
                                                                <div class="col-6">
                                                                    <p><span>{{$message_map->formatted_date}} </span><button type="button" class="info_btn border-none" style="background-color: #F5F5F5;"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button></p>
                                                                </div>
                                                                <div class="col-6">
                                                                    <p class="fw-bold" style="float:right;">{{$message_map->senter_info->name}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-1 align-self-end" style="float:right;">
                                                            @if($message_map->senter_info->images == null)
                                                                <img class="user-img" src="http://127.0.0.1:8000/assets/images/default.jpg" alt="" name="user_image">
                                                            @else
                                                                <img class="user-img" src="{{asset($message_map->senter_info->images)}}" alt="" name="user_image">
                                                            @endif
                                                        </div>
                                                        
                                                    </div>
                                                </div> 
                                                @endif  
                                            @endforeach
                                        @endif
                                    </div>       
                                    <div class="container" style="background-color:#F5F5F5; border-radius:16px;">
                                        <div class="form-group mt-5 pt-2" style="background-color:#F5F5F5;">
                                            {{-- {{ Form::textarea('gmail_description', @$data->group_description, ['id' => 'gmail_description', 'class' => 'gmail_decripition w-100','placeholder'=>'write your message here....' ,'required' => 'required', 'rows' => 10]) }} --}}
                                            @include('layout::widget.messageckeditor',['name'=>'gmail_individual_message'.$message->id,'id'=>'gmail_individual_message'.$message->id,'class'=>'gmail_decripition w-100','content'=>""])
                                        </div>
                                        <div class="row">
                                            <div class="img_view my-2">                                       
                                                <div class="viewreplayimg{{$message->id}}div d-flex gap-2"></div>                                          
                                            </div>
                                            <div class="col-6 mb-3">
                                                <div class="d-flex gap-2">
                                                    <div>
                                                        <input type="file" name="replay_message_img[]" id="replay_message_img{{$message->id}}" class="replay_message_img" accept=".jpg,.png,.jpeg,.pdf"  data-id ="{{$message->id}}" style="display: none;" multiple max="3">
                                                        <label for="replay_message_img{{$message->id}}" class="icon_img">
                                                            <img src="{{ asset('assets/icon_images/Draft Toolbar Icons5.png') }}" class="icon_img" alt="Upload Image">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <button type="button" class="btn btn-primary replaing_message" data-id="{{$message->id}}" style="float:right;">Reply <i class="fa fa-paper-plane ms-1" style="font-size:14px;" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </div>
                               
                            </div>
                        @endforeach  
                    @endif

                    @if(@$inbox_messages_view)
                            @php
                            $receiver = $users->where('id',$user_id)->first();
                            @endphp
                        @foreach($inbox_messages_view as $message)
                            <div class="tab-pane fade" id="pills-inbox{{$message->id}}" role="tabpanel" aria-labelledby="pills-inbox-tab{{$message->id}}">
                                {{-- {{ Form::open(array('role' => 'form', 'route'=>array('individual_message'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'gmailcomunication-form','novalidate' => 'novalidate')) }} --}}
                            
                                    <button type="button" class="btn btn-danger close_inbox" data-id="{{$message->id}}" style="float:inline-end" aria-label="Close">
                                        Back
                                    </button>  
                                    <button type="button" class="btn btn-danger close_starred" style="display:none;float:inline-end" data-id="{{$message->id}}" style="float:inline-end;" aria-label="Close">
                                        Back
                                    </button>  
                                    <div class="d-flex gap-3 border-bottom pb-2 mt-4">
                                        @php
                                            if($receiver->images !== null){
                                            $image = url($receiver->images);
                                            }
                                            else{
                                            $image = "http://127.0.0.1:8000/assets/images/default.jpg";
                                            }
                                        @endphp
                                        <img class="user-img" src="{{$image}}" alt="" name="user_image">
                                        <p class="fw-bold" style="margin-block-start: 1em !important; margin-block-end: 1em !important ;">{{$receiver->name}}</p>
                                    </div>
                                    <div class="form-group border-bottom recipients_select pb-3 d-flex gap-1 mt-4">
                                        <label for="to_users" class="" style="font-size:20px; font-weight:100;">From: </label>
                                        <span style="font-size:20px">{{@$message->senter->email}}</span>
                                        <input type="hidden" name="senter_id" id="senter_id{{$message->id}}" value="{{@$message->senter->id}}">
                                    </div>
                                    <div class="form-group border-bottom recipients_select pb-3 d-flex gap-1 mt-4">
                                            <label for="gmail_subject" class="" style="font-size:20px; font-weight:100;">Subject: </label>
                                            <span style="font-size:20px">{{@$message->subject}}</span>
                                            {{-- <input type="hidden" name="gmail_subject" id="gmail_subject" value="{{@$message->subject}}"> --}}
                                    </div>  
                                        
                                    <div class="inbox_message{{$message->id}} replay_content_scroll">
                                        <input type="hidden" name="message_type" id="message_type{{$message->id}}" value="1">
                                        <div class="container p-3 mt-2" style="background-color: #F5F5F5;">
                                            <div class="inbox_message_div">
                                                <p style="font-size:16px">{!!$message->message!!}</p>
                                                @if($message->files !==null)
                                                    @php
                                                        $file_paths = json_decode($message->files);
                                                    @endphp
                                                    <div class="row">
                                                        @foreach($file_paths as $key => $path)
                                                            <div class="col-md-3">
                                                                <div class="card_attachment">
                                                                    @php
                                                                    $file_extension = pathinfo($path, PATHINFO_EXTENSION);
                                                                    @endphp
                                                                    @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif")
                                                                    <img src="{{ asset($path) }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                    @elseif ($file_extension == 'mp4' || $file_extension == 'avi' || $file_extension == 'mov')  
                                                                    <img src="{{ asset('assets/sample/images.png') }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                    @elseif($file_extension == 'mp3') 
                                                                    <img src="{{ asset('assets/sample/istockphoto-1244097573-612x612.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                    @else
                                                                    <img src="{{ asset('assets/sample/file.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                    @endif
                                                                    <div class="overlay">
                                                                        <a href="{{$path}}" class="link-b btn bg-white text-dark"target="_blank"><i class="fa fa-eye"></i></a>
                                                                        <a href="{{ @$path }}" class="link-b btn bg-white text-dark" download="{{ @$path }}"><i class="fa fa-download"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>                                                        
                                                @endif 
                                            </div>                                       
                                        </div>
                                        @if($inbox_messages_maping)
                                            @php
                                            $replay_messages =$inbox_messages_maping->where('message_id',$message->id);
                                            @endphp
                                            @foreach($replay_messages as $message_map)
                                                @if($message_map->senter !== $user_id)
                                                    <div class="container">
                                                        <div class="row mt-5">
                                                            <div class="col-1 align-self-end" >
                                                                @if($message_map->senter_info->images == null)
                                                                    <img class="user-img" src="http://127.0.0.1:8000/assets/images/default.jpg" alt="" name="user_image">
                                                                @else
                                                                    <img class="user-img" src="{{asset($message_map->senter_info->images)}}" alt="" name="user_image">
                                                                @endif
                                                            </div>
                                                            <div class="col-9 p-3" style="background-color: #F5F5F5; border-radius:16px;">
                                                                <p>{!!$message_map->message!!}</p>
                                                                @if($message_map->files !==null)
                                                                    @php
                                                                        $file_paths = json_decode($message_map->files);
                                                                    @endphp
                                                                    <div class="row">
                                                                        @foreach($file_paths as $key => $path)
                                                                        <div class="col-md-3">
                                                                            <div class="card_attachment">
                                                                                @php
                                                                                $file_extension = pathinfo($path, PATHINFO_EXTENSION);
                                                                                @endphp
                                                                                @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif")
                                                                                <img src="{{ asset($path) }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                                @elseif ($file_extension == 'mp4' || $file_extension == 'avi' || $file_extension == 'mov')  
                                                                                <img src="{{ asset('assets/sample/images.png') }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                                @elseif($file_extension == 'mp3') 
                                                                                <img src="{{ asset('assets/sample/istockphoto-1244097573-612x612.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                                @else
                                                                                <img src="{{ asset('assets/sample/file.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                                @endif
                                                                                <div class="overlay">
                                                                                    <a href="{{$path}}" class="link-b btn bg-white text-dark"target="_blank"><i class="fa fa-eye"></i></a>
                                                                                    <a href="{{ @$path }}" class="link-b btn bg-white text-dark" download="{{ @$path }}"><i class="fa fa-download"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>                                                        
                                                                @endif 
                                                                <div class="row mt-5">
                                                                    <div class="col-6">
                                                                        <p class="fw-bold">{{$message_map->senter_info->name}}</p>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <p style="float:right;"><span>{{$message_map->formatted_date}} </span><button type="button" class="info_btn border-none" style="background-color: #F5F5F5;"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else  
                                                <div class="container">
                                                    <div class="row mt-5">
                                                    <div class="col-2"></div>
                                                        <div class="col-9 p-3" style="background-color: #F5F5F5;border-radius:16px;">
                                                            <p>{!!$message_map->message!!}</p>
                                                            @if($message_map->files !==null)
                                                                @php
                                                                    $file_paths = json_decode($message_map->files);
                                                                @endphp
                                                                <div class="row">
                                                                    @foreach($file_paths as $key => $path)
                                                                    <div class="col-md-3">
                                                                        <div class="card_attachment">
                                                                            @php
                                                                            $file_extension = pathinfo($path, PATHINFO_EXTENSION);
                                                                            @endphp
                                                                            @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif")
                                                                            <img src="{{ asset($path) }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                            @elseif ($file_extension == 'mp4' || $file_extension == 'avi' || $file_extension == 'mov')  
                                                                            <img src="{{ asset('assets/sample/images.png') }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                                                                            @elseif($file_extension == 'mp3') 
                                                                            <img src="{{ asset('assets/sample/istockphoto-1244097573-612x612.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                            @else
                                                                            <img src="{{ asset('assets/sample/file.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                                                                            @endif
                                                                            <div class="overlay">
                                                                                <a href="{{$path}}" class="link-b btn bg-white text-dark nav-link"target="_blank"><i class="fa fa-eye"></i></a>
                                                                                <a href="{{ @$path }}" class="link-b btn bg-white text-dark" download="{{ @$path }}"><i class="fa fa-download"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>                                                        
                                                            @endif 
                                                            <div class="row mt-5">
                                                                <div class="col-6">
                                                                    <p><span>{{$message_map->formatted_date}} </span><button type="button" class="info_btn border-none" style="background-color: #F5F5F5;"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button></p>
                                                                </div>
                                                                <div class="col-6">
                                                                    <p class="fw-bold" style="float:right;">{{$message_map->senter_info->name}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-1 align-self-end" style="float:right;">
                                                            @if($message_map->senter_info->images == null)
                                                                <img class="user-img" src="http://127.0.0.1:8000/assets/images/default.jpg" alt="" name="user_image">
                                                            @else
                                                                <img class="user-img" src="{{asset($message_map->senter_info->images)}}" alt="" name="user_image">
                                                            @endif
                                                        </div>
                                                        
                                                    </div>
                                                </div> 
                                                @endif  
                                            @endforeach
                                        @endif
                                    </div>    
                                    <div class="container" style="background-color:#F5F5F5; border-radius:16px;">
                                        <div class="form-group mt-5 pt-2" style="background-color:#F5F5F5;">
                                            {{-- {{ Form::textarea('gmail_description', @$data->group_description, ['id' => 'gmail_description', 'class' => 'gmail_decripition w-100','placeholder'=>'write your message here....' ,'required' => 'required', 'rows' => 10]) }} --}}
                                            @include('layout::widget.messageckeditor',['name'=>'gmail_individual_message'.$message->id,'id'=>'gmail_individual_message'.$message->id,'class'=>'gmail_decripition w-100','content'=>""])
                                        </div>
                                        <div class="row">
                                            <div class="img_view my-2">                                       
                                                <div class="viewreplayimg{{$message->id}}div d-flex gap-2"></div>                                          
                                            </div>
                                            <div class="col-6 mb-3">
                                                <div class="d-flex gap-2">
                                                    <div>
                                                        <input type="file" name="replay_message_img[]" id="replay_message_img{{$message->id}}" class="replay_message_img" accept=".jpg,.png,.jpeg,.pdf"  data-id ="{{$message->id}}" style="display: none;" multiple max="3">
                                                        <label for="replay_message_img{{$message->id}}" class="icon_img">
                                                            <img src="{{ asset('assets/icon_images/Draft Toolbar Icons5.png') }}" class="icon_img" alt="Upload Image">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <button type="button" class="btn btn-primary replaing_message" data-id="{{$message->id}}" style="float:right;">Reply <i class="fa fa-paper-plane ms-1" style="font-size:14px;" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                {{-- {{Form::close()}} --}}
                            </div>
                        @endforeach  
                    @endif

                    @if(@$draft_messages_list)
                        @foreach($draft_messages_list as $message)
                            <div class="tab-pane fade" id="pills-draft{{$message->id}}" role="tabpanel" aria-labelledby="pills-draft-tab{{$message->id}}">
                                {{ Form::open(array('role' => 'form', 'route'=>array('individual_message'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'gmailcomunication-form','novalidate' => 'novalidate')) }}
                                    <button type="button" class="btn btn-danger close-draft" data-id="{{$message->id}}" style="float:inline-end" aria-label="Close">
                                        Back
                                    </button>  
                                    <input type="hidden" name="draft_id" value="{{@$message->id}}">
                                    <div class="d-flex gap-3 border-bottom pb-2 mt-4">
                                        @php
                                            if($message->senter->images !== null){
                                            $image = url($message->senter->images);
                                            }
                                            else{
                                            $image = "http://127.0.0.1:8000/assets/images/default.jpg";
                                            }
                                        @endphp
                                        <img class="user-img" src="{{$image}}" alt="" name="user_image">
                                        <p class="fw-bold" style="margin-block-start: 1em !important; margin-block-end: 1em !important ;">{{$message->senter->name}}</p>
                                    </div>
                                    @php
                                     $draft_recivers =$draft_reciver_details->where('message_id',$message->id)->pluck('user_id');
                                    @endphp
                                    <div class="form-group border-bottom recipients_select pb-3 d-flex gap-1 mt-4">
                                        <label for="to_users" class="form-label" style="font-size:20px; font-weight:100;">To: </label>
                                        {{ Form::select('to_users[]',@$eligibele_receptiants,@$draft_recivers ,array('id'=>"to_users".$message->id,'class' => 'recipients form-select multiple-select outline_none form-control', 'size'=>"3",'required' => 'required','placeholder' => 'Select Users',"multiple" )) }}
                                    </div>
                                    <div class="form-group border-bottom recipients_select pb-3 d-flex gap-1 mt-4">
                                            <label for="gmail_subject" class="" style="font-size:20px; font-weight:100;">Subject: </label>
                                            {{ Form::text('gmail_subject', @$message->subject ,
                                            array('id'=>"gmail_subject".$message->id,'class' => ' form-control fw-bold outline_none','required' => 'required','style'=>'border: none !important;' )) }}
                                    </div>
                                       
                                    <div class="container" style="background-color:#F5F5F5; border-radius:16px;">
                                        <div class="form-group mt-5 pt-2" style="background-color:#F5F5F5;">
                                          {{-- {{ Form::textarea('gmail_description', @$data->group_description, ['id' => 'gmail_description', 'class' => 'gmail_decripition w-100','placeholder'=>'write your message here....' ,'required' => 'required', 'rows' => 10]) }} --}}
                                          @include('layout::widget.messageckeditor',['name'=>'gmail_description','id'=>'gmail_description'.$message->id,'class'=>'gmail_decripition w-100','content'=>@$message->message ?@$message->message : old("homework_description") ])
                                        </div>
                                        <div class="img_view my-2 d-flex"> 
                                            @if($message->files !==null)
                                                @php
                                                    $file_paths = json_decode($message->files);
                                                @endphp
                                                <div class="d-flex">   
                                                                                                    
                                                    @foreach($file_paths as $key => $path)

                                                    <input type="hidden" name="old_paths[]" id ="old_paths{{$message->id}}{{$loop->index}}" value="{{$path}}">
                                                
                                                        <div class="container" id="draft_img_container{{$message->id}}{{$loop->index}}">
                                                            @php
                                                            $file_extension = pathinfo($path, PATHINFO_EXTENSION);
                                                            @endphp
                                                            @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif")
                                                            <img src="{{ asset($path) }}" id="msg_file_img{{$loop->index}}"  width="150px" height="100px" class="img-thumbnail draft-images{{$message->id}} img_height messagecompose{{$loop->index}}"></img>                                                                 
                                                            @elseif ($file_extension == 'mp4' || $file_extension == 'avi' || $file_extension == 'mov')                                   
                                                            <img src="{{ asset('assets/sample/images.png') }}" id="msg_file_img{{$loop->index}}"  width="150px" height="100px" class="draft-images{{$message->id}} img-thumbnail img_height messagecompose{{$loop->index}}"></img>
                                                            @elseif($file_extension == 'mp3') 
                                                            <img src="{{ asset('assets/sample/istockphoto-1244097573-612x612.jpg') }}" id="msg_file_img{{$loop->index}}" width="150px" height="100px" class="draft-images{{$message->id}} img-thumbnail img_height messagecompose{{$loop->index}}">
                                                            @else
                                                            <img src="{{ asset('assets/sample/file.jpg') }}"  id="msg_file_img{{$loop->index}}"  width="150px" height="100px" class="draft-images{{$message->id}} img-thumbnail img_height messagecompose{{$loop->index}}">
                                                            @endif
                                                            <span class="draft_remove_img back_to remove"  data-draft_id ="{{$message->id}}" id="remove_img{{$message->id}}" data-index="{{$loop->index}}">X</span>
                                                        </div>
                                                    
                                                
                                                    @endforeach
                                                </div>                                                        
                                             @endif                                       
                                            <div class="viewimgdiv d-flex gap-2">                                                     
                                            </div>                                          
                                        </div>
                                        <div class="row">
                                          
                                            <div class="col-md-6 mb-3">
                                                <div class="d-flex gap-2">
                                                    <div>
                                                        <input type="file" name="compose_message_img[]" id="message_img{{$message->id}}" data-message_id = "{{$message->id}}" accept=".jpg,.png,.jpeg,.pdf" class="message_img" style="display: none;" multiple max="3">                                   
                                                        <label for="message_img{{$message->id}}" class="icon_img">
                                                            <img src="{{ asset('assets/icon_images/Draft Toolbar Icons5.png') }}" class="icon_img" alt="Upload Image">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">    
                                                <button type="submit" class="btn btn-primary gmail_message_sent ms-2" name="submit" value="draft_send" style="float:right;">Send <i class="fa fa-paper-plane ms-1" style="font-size:14px;" aria-hidden="true"></i></button>                                          
                                            </div>
                                        </div>
                                    </div>
                                {{Form::close()}}
                            </div>
                        @endforeach  
                    @endif

                
                </div>
            </div>
           
        </div>
    </div>
   
  </div>
  <div class="modal fade" id="create_group_model" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered form">

        <div class="modal-content">

            <div class="modal-body assigen_parent_body">

                    <div class="group_form position-relative">
                        some

                    </div>
                    <div class="modal-footer position-absolute top-0 end-0">
                        @if (Session::get('ACTIVE_GROUP') == 'Super Admin')
                            {{-- <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button> --}}
                        @endif
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                        <i class="fa fa-times-circle fs-2" style="color: red" data-bs-dismiss="modal"
                            aria-hidden="true"></i>


                    </div>
            </div>




        </div>
    </div>
  </div>  
@endsection
@section('script')

<script type="module">
    function notify_script(title, text, type, hide) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            hide: hide,
            styling: 'fontawesome'
        })
    }

    window.creategroup = '{{route('create_group_model')}}';
    window.editgroup = '{{route('edit_group_model')}}';
    window.deletegroup = '{{route('delete_group')}}';
    window.gmail_message = '{{route('group_message')}}'
    window.replay_message = '{{route('individual_message')}}'
    window.deletemessage = '{{route('delete_messages')}}';
    window.receptiants = '{{route('receptiants')}}';
    window.fileUrl = "{{ asset('assets/sample/file.jpg') }}";
    window.videoUrl = "{{asset('assets/sample/images.png')}}";
    window.audioUrl = "{{asset('assets/sample/istockphoto-1244097573-612x612.jpg')}}";
    // window.sectionurl = '{{ route('section.index') }}';
    // window.classurl = '{{ route('schooltype.index') }}';
    // window.getstudentperformanceinfo = "{{ route('studentperformance') }}";
    // window.fees_paid_report = "{{route('fees_payment')}}";
    // window.fees_reminder = "{{route('fees_reminder')}}";
   
    // AttendanceConfig.AttendanceInit(notify_script);
    // AcademicConfig.Leaveinit(notify_script);
    // //grade -- Class,Section List
    // PromotionConfig.PromotionInit(notify_script);
    //ReportConfig.ReportInit(notify_script);
    // FeeStructureConfig.FeeStructureInit(notify_script);
    GmailCommunicationConfig.GmailCommunicationInit(notify_script);
    //grade chart
    // Account.AccountInit();
   
    // window.student_overall_report_info = "{{ route('grade_student_report_view') }}"
    // ReportConfig.getStudentsMarkinfo(id,academic_year,position,term);
</script>  
<script>
          $(document).ready(function() {
            console.log("Document ready!");
            $(".searchInput").on("input", function() {
                console.log('its key up');
                var value = $(this).val().toLowerCase();
                var type = $(this).attr('data-type');
                if(type == "inbox"){
                    $("#inbox-messages-container .nav-item").filter(function() {
                      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                }
                else if(type == "sent"){
                    $("#sent-messages-container .nav-item").filter(function() {
                      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                }
                else if(type == "starred"){
                    $("#starred-messages-container .nav-item").filter(function() {
                      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                }
                else if(type == "draft"){
                    $("#draft-messages-container .nav-item").filter(function() {
                      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                }
                else if(type == "bin"){
                    $("#bin-messages-container .nav-item").filter(function() {
                      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    }); 
                }
                else {
                    $("#external-sent-messages-container .nav-item").filter(function() {
                      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    }); 
                }

               
            });
        
        });
</script>
<script>
  $(document).on('click', '.pagination a', function(e) {
    e.preventDefault(); 
    var url = $(this).attr('href');
    var row = $(this).closest('.row');
    var type = row.attr('data-group');
    fetchMessages(url, type);
  });


    $(document).on('click', '.nav-link', function(e) {
        e.preventDefault(); 
        var url = $(this).attr('href');
        var container = $($(this).attr('data-container')); 
        var type = $($(this).attr('data-type'));
        // Append page=1 parameter to the URL
        var pageUrl = url.includes('?') ? url + '&page=1' : url + '?page=1';
        fetchContent(pageUrl, container, type); 
    });



    function fetchMessages(url, type) {
        $.ajax({
            url: url,
            type: 'GET',
            data: {type: type}, // Specify the request type as 'GET'
            success: function(data) {
                if (type == "sent") {
                    $('#sent-messages-container').html(data);
                } else if (type == "inbox") {
                    $('#inbox-messages-container').html(data); 
                } else if (type == "starred") {
                    $('#starred-messages-container').html(data);
                } else if (type == "draft") {
                    $('#draft-messages-container').html(data);
                } else if (type == "bin") {
                    $('#bin-messages-container').html(data);
                } else{
                    $('#external-sent-messages-container').html(data);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }


    function fetchContent(url, container, type) {
        $.ajax({
            url: url,
            type: 'GET', // Specify the request type as 'GET'
            success: function(data) {
                container.html(data);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }



    
  
    $('.nav-link').on('click',function(){
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
    });
</script>
<script>
 $(document).ready(function() {
    // Function to scroll to the bottom of .group_content_scroll
   
    function scrollToBottom(id) {
        var $groupContent = $('.group_content' + id);
        if ($groupContent.length) {
            console.log('Found element:', $groupContent);

            // Log the inner HTML to ensure there's content
            console.log('Content:', $groupContent.html());

            // Ensure the content is loaded before scrolling
            setTimeout(function() {
                // Force layout recalculation
                $groupContent[0].offsetHeight; // Trigger reflow

                var scrollHeight = $groupContent.prop('scrollHeight');
                console.log('Scroll Height before setting scrollTop:', scrollHeight);

                // Set the scrollTop to the scrollHeight
                $groupContent.scrollTop(scrollHeight);
                console.log('Scroll Top set to:', $groupContent.scrollTop());

                // Check scrollTop after a timeout
                setTimeout(function() {
                    console.log('Scroll Top after timeout:', $groupContent.scrollTop());
                }, 100);
            }, 1000); // Adjust delay as necessary to ensure content is loaded
        } else {
            console.log('Element not found for id:', id);
        }
    }



    // Scroll to the bottom when the page loads

    $('.group_link').on('click',function(){
        console.log("group link");
        var id = $(this).attr('data-group-id');
        console.log(id);
        scrollToBottom(id);
    });
 });

</script>
@endsection
@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}
    
   
@endsection