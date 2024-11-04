<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div class="">
            @if (isset(Configurations::getConfig('site')->imagec))
                <img src="{{ Configurations::getConfig('site')->imagec }} " class="logo-icon-2 school_logo" />
            @endif

        </div>
        <div>
            <a href="{{ route('backenddashboard') }}">
                <h4 class="logo-text">
                    {{ isset(Configurations::getConfig('site')->school_name) ? Str::limit(Configurations::getConfig('site')->school_name, 7) : 'School' }}
                </h4>
            </a>
        </div>
        <a href="javascript:;" class="toggle-btn ms-auto"> <i class="bx bx-menu"></i>
        </a>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        @foreach (Menu::getAdminMenu() as $menu)
            <li class="menu-label">{{ $menu['name'] }}</li>
            @if (count((array) $menu) > 0)
                @php
                    if (isset($menu['group'])) {
                        printMenuGroup($menu['group']);
                    }
                @endphp
                <?php if (isset($menu["menu"])) {
                    foreach ($menu["menu"] as $key => $menus) {
                        if ($menus["is_url"] == 0) { ?>
                <li>
                    <a href="{{ Route::has($menus['url']) ? route($menus['url']) : '#' }}">
                        <div class="parent-icon icon-color-{{ @$key + 1 }}">
                            <i class="{{ $menus['icon'] ? $menus['icon'] : '' }}"></i>
                        </div>
                        <div class="menu-title">{{ $menus['name'] }}</div>
                    </a>


                </li>
                <?php } else { ?>

                <li>
                    <a href="">
                        <div class="parent-icon icon-color-{{ @$key + 1 }}">
                            <i class="{{ $menus['icon'] ? $menus['icon'] : '' }}"></i>
                        </div>
                        <div class="menu-title">{{ $menus['name'] }}</div>
                    </a>

                </li>

                <?php }
                    }
                } ?>
            @endif
        @endforeach

        <?php function printMenuGroup($groups, $is_submenu = false)
        {
            foreach ($groups as $key => $group) { ?>
        <li class="{{ $is_submenu ? 'sub_menu' : '' }}">

            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon icon-color-{{ @$key + 2 }}"><i
                        class="{{ $group['icon'] ? $group['icon'] : '' }}"></i>
                </div>
                <div class="menu-title">{{ $group['name'] }}</div>
            </a>


            @if (isset($group['menu']) && count((array) $group['menu']) > 0)
                <ul>
                    <?php
                    foreach ($group["menu"] as $menus) {
                        if ($menus["is_url"] == 0) { ?>
                    <li>

                        <a href="{{ Route::has($menus['url']) ? route($menus['url']) : '#' }}"><i
                                class="bx bx-right-arrow-alt"></i>{{ $menus['name'] }}</a>



                    </li>
                    <?php } else { ?>
                    <li>

                        <a href="{{ Route::has($menus['url']) ? route($menus['url']) : '#' }}"><i
                                class="bx bx-right-arrow-alt"></i>{{ $menus['name'] }}</a>


                    </li>
                    <?php }
                    }
                    if (isset($group["group"])) {
                        printMenuGroup($group["group"], true);
                    }
                    ?>
                </ul>
            @endif
        </li>
        <?php }
        } ?>
    </ul>
    <!--end navigation-->
</div>

