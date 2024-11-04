 <!--header-->
 <header class="top-header">
     <nav class="navbar navbar-expand">
         <div class="left-topbar d-flex align-items-center">
             <a href="javascript:;" class="toggle-btn">
                 <i class="bx bx-menu"></i>
             </a>
         </div>

         <div class="right-topbar ms-auto">
             <ul class="navbar-nav">


                 <li class="nav-item dropdown dropdown-lg">
                     <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="javascript:;"
                         data-bs-toggle="dropdown">
                         @php
                             $user = cms\core\user\Models\UserModel::find(User::getUser()->id);
                         @endphp
                         <i class="bx bx-bell vertical-align-middle"></i>
                         <span class="msg-count">{{ $user->unreadNotifications()->count() }}</span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-end">
                         <a href="javascript:;">
                             <div class="msg-header">
                                 <h6 class="msg-header-title">{{ $user->unreadNotifications()->count() }} New</h6>
                                 <p class="msg-header-subtitle">
                                     Notifications
                                 </p>
                             </div>
                         </a>

                         <div class="header-notifications-list">

                             @forelse ($user->unreadNotifications  as $notification)
                                 <a class="dropdown-item" href="javascript:;">
                                     <div class="d-flex align-items-center">
                                         <div class="notify bg-light-primary text-primary">
                                             <i class="bx bx-group"></i>
                                         </div>
                                         <div class="flex-grow-1">
                                             <h6 class="msg-name">
                                                 {{ $notification->data['created_by'] == User::getUser()->id ? 'You' : ucfirst(cms\core\user\Models\UserModel::getUserName($notification->data['created_by'])) }}<span
                                                     class="msg-time float-end">{{ $notification->created_at->diffForHumans() }}</span>
                                             </h6>
                                             <p class="msg-info"> {{ $notification->data['notify_msg'] }}</p>
                                         </div>
                                     </div>
                                 </a>
                             @empty

                                 <a href="#">
                                     <div class="text-center msg-footer">
                                         No Notifications
                                     </div>
                                 </a>
                             @endforelse

                         </div>
                         @if ($user->unreadNotifications()->count() > 0)
                             <a href="{{ route('readNotifications') }}">
                                 <div class="text-center msg-footer">
                                     Mark as Read
                                 </div>
                             </a>
                         @endif
                     </div>
                 </li>
                 {{-- cart --}}
                 @if (Session::get('ACTIVE_GROUP') == 'Student')
                     <li class="nav-item dropdown dropdown-lg">
                         <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                             href="javascript:;" data-bs-toggle="dropdown">

                             <i class='bx bx-cart-alt vertical-align-middle'></i>

                             <span class=" msg-count" id="cart-count">0</span>
                         </a>
                         <div class="dropdown-menu dropdown-menu-end">
                             <a href="javascript:;">
                                 <div class="msg-header">
                                     <h6 class="msg-header-title">Cart</h6>
                                     <p class="msg-header-subtitle">

                                     </p>
                                 </div>
                             </a>
                             <div class="header-notifications-list cart-item-list">



                             </div>




                             <a href="{{ route('order.create') }}" class="checkout-cart">
                                 <div class="text-center msg-footer cart-message">
                                     Checkout
                                 </div>
                             </a>

                         </div>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link wallet-title " href="#" title="wallet">

                             <i class='bx bx-wallet-alt'></i>
                             <span>{{ number_format(Configurations::ActiveStudentWallet(), 2) }} ₦</span>

                         </a>

                     </li>
                 @endif
                 @if (Session::get('ACTIVE_GROUP') == 'Super Admin')
                     {{-- cart end --}}
                     <li class="nav-item">
                         <a class="nav-link " href="{{ route('logs') }}" title="logs" target="_blank">

                             <i class="bx bx-file"></i>

                         </a>

                     </li>
                 @endif


                 <li class="nav-item">
                     @if (Route::has('website'))
                         <a class="nav-link " href="{{ route('website') }}" title="website" target="_blank">

                             <i class="bx bx-globe"></i>

                         </a>
                     @endif
                 </li>
                 <li class="nav-item dropdown dropdown-user-profile">
                     <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                         data-bs-toggle="dropdown">
                         <div class="d-flex user-box align-items-center">
                             <div class="user-info">
                                 <p class="user-name mb-0">{{ User::getUser()->username }}</p>

                             </div>

                             <img class="user-img"
                                 src="{{ User::getUser()->images != '' ? User::getUser()->images : asset('assets/images/default.jpg') }}"
                                 alt="">
                         </div>
                     </a>
                     <div class="dropdown-menu dropdown-menu-end">
                         <a class="dropdown-item" href="{{ route('profile') }}"><i class="bx bx-user"></i><span>Edit
                                 Profile</span></a>
                         <a class="dropdown-item" href="javascript:;"><i class="bx bx-cog"></i><span>Settings</span></a>



                         <div class="dropdown-divider mb-0"></div>
                         <a class="dropdown-item" href="{{ route('log_out_from_admin') }}"><i
                                 class="bx bx-power-off"></i><span>Logout</span></a>
                     </div>
                 </li>
                 {{-- <li class="nav-item dropdown dropdown-language">
            <a
              class="nav-link dropdown-toggle dropdown-toggle-nocaret"
              href="javascript:;"
              data-bs-toggle="dropdown"
            >
              <div class="lang d-flex">
                <div><i class="flag-icon flag-icon-um"></i></div>
                <div><span>En</span></div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
              <a class="dropdown-item" href="javascript:;"
                ><i class="flag-icon flag-icon-de"></i
                ><span>German</span></a
              >
              <a class="dropdown-item" href="javascript:;"
                ><i class="flag-icon flag-icon-fr"></i
                ><span>French</span></a
              >
              <a class="dropdown-item" href="javascript:;"
                ><i class="flag-icon flag-icon-um"></i
                ><span>English</span></a
              >
              <a class="dropdown-item" href="javascript:;"
                ><i class="flag-icon flag-icon-in"></i><span>Hindi</span></a
              >
              <a class="dropdown-item" href="javascript:;"
                ><i class="flag-icon flag-icon-cn"></i
                ><span>Chinese</span></a
              >
              <a class="dropdown-item" href="javascript:;"
                ><i class="flag-icon flag-icon-ae"></i
                ><span>Arabic</span></a
              >
            </div>
          </li> --}}
             </ul>
         </div>
     </nav>
 </header>
 <!--end header-->
