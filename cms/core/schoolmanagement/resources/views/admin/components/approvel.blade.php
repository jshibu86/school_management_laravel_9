@foreach ($approved_access_users_get as $accessuser)
    <img src="{{ $accessuser->images ? asset($accessuser->images) : asset('assets/images/default.jpg') }}"
        style="width:50px" />

    <p>{{ $accessuser->username }} - {{ $accessuser->name }} - {{ $accessuser->email }}</p>

    {{-- display what action done every users --}}

    @php        
        if (sizeof(@$school_information->approvels)) {
            $approvel_action = @$school_information->approvels->where('user_id', $accessuser->id)->first();

            if (!$approvel_action) {
                $approvel_action = null;
            }
        } else {            
            $approvel_action = null;
        }
        $onboard_status = $approvel_action->status ?? null;
        $isloggedUser = @$loggedUserid == $accessuser->id;
        $schoolId = @$school_information->id ?? null;
    @endphp

    @if ($approvel_action)       
        @if( @$isloggedUser && ($onboard_status == 'pending'))
            <button onclick="approveSection('approve', {{ $schoolId }})" class="btn btn-success btn-sm m-1 px-5">Confirm</button>
            <button onclick="approveSection('deny', {{ $schoolId }})" class="btn btn-danger btn-sm m-1 px-5">Deny</button>
        @endif
        @if ((@$loggedUserid != $accessuser->id) && ($onboard_status == 'pending'))
            <button class="btn btn-warning btn-sm m-1 px-5">Pending</button>
        @elseif ($onboard_status == 'approved')
            <button class="btn btn-success btn-sm m-1 px-5">Confirmed</button>
        @elseif ($onboard_status == 'denied')
            <div class="d-flex align-items-center"> <!-- Flex container for alignment -->
                <button class="btn btn-danger btn-sm m-1 px-5">Denied</button>
                <div class="dropdown m-1"> <!-- Added margin to space the dropdown -->
                    <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="three-dots-horizontal">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-light">                    
                        <li>
                            <a class="dropdown-item text-success" href="#" onclick="approveSection('approve', {{ $schoolId }})">Confirm</a>
                        </li>
                    </ul>                
                </div>
            </div>
        @endif
    @elseif (@$isloggedUser)    
        <button onclick="approveSection('approve', {{ $schoolId }})" class="btn btn-success btn-sm m-1 px-5">Confirm</button>       
        <button onclick="approveSection('deny', {{ $schoolId }})" class="btn btn-danger btn-sm m-1 px-5">Deny</button>
    @endif
    
@endforeach
