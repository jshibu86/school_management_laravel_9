<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="{{((User::getUser()->images!='') ? User::getUser()->images : asset('assets/images/default.jpg') )}}" alt="">{{User::getUser()->username}}
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="javascript:;"> Profile</a></li>
                        
                       
                        <li><a href="{{route('log_out_from_admin')}}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                    </ul>
                </li>

                <li role="presentation" class="dropdown">
                    @php
                    $user=cms\core\user\Models\UserModel::find(User::getUser()->id)
                     @endphp
                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-envelope-o"></i>
                        <span class="badge bg-green">{{ $user->unreadNotifications()->count() }}</span>
                    </a>
                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                        {{-- <li>
                            <a>
                                <span class="image"><img src="#" alt="Profile Image" /></span>
                                <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                            </a>
                        </li> --}}

                       


                        @forelse ($user->unreadNotifications  as $notification ) 
                        <li>
                            <a>
                                {{-- <span class="image"><img src="#" alt="Profile Image" /></span>
                                <span> --}}
                        <span>
                            {{  
                            $notification->data['created_by'] ==User::getUser()->id ? "You":ucfirst(cms\core\user\Models\UserModel::getUserName($notification->data['created_by']))}}
                        </span>
                         
                          <span class="time">{{ $notification->created_at->diffForHumans() }}</span>
                        </span>
                                <span class="message">
                          {{ $notification->data['notify_msg'] }}
                        </span>
                            </a>
                        </li>
                        

                        @empty

                        <li>You have No New Notifications</li>

                        @endforelse
                      
                        
                       @if ($user->unreadNotifications()->count() > 0)
                       <li>
                        <div class="text-center">
                            <a href="{{ route("readNotifications") }}">
                                <strong>Mark as Read</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </li>
                       @endif
                       
                    </ul>
                </li>
                <li>
                    <a href="{{route('logs')}}" class="info-number" target="_blank">
                        <i class="fa fa-history"></i>
                       
                    </a>
                </li>
                <li>
                    <a href="{{route('website')}}" class="info-number" target="_blank">
                        <i class="fa fa-globe"></i>
                       
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->