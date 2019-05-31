<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{url('customers')}}"><i class="icon-people"></i> Customers</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{url('customers/billing-issue')}}"><i class="fa fa-warning"></i> Billing Issues</a>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket-loaded"></i> Products</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-list"></i> Plan</a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('products/plan/new')}}"><i class="icon-config"></i> Add New</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('products/plan/all-plans')}}"><i class="icon-config"></i> All Plans</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-list-ol"></i> Meals</a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('products/meals/new')}}"><i class="icon-config"></i> Add New</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('products/meals/all-meals')}}"><i class="icon-config"></i> All Meals</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('products/plan/scheduler')}}"><i class="fa fa-calendar-check-o"></i> Meal Plan Scheduler</a>
                    </li>
                </ul>
            </li>
            
            @can('manage-setup')
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-wrench"></i> Setup</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-hourglass"></i> Zone Schedules</a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('delivery/zone/timing/new')}}"><i class="icon-config"></i> Add New</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('delivery/zone/timing/all-zone-timings')}}"><i class="icon-config"></i> All Zone Schedules</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-clock"></i> Delivery Zones</a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('delivery/zone/new')}}"><i class="icon-config"></i> Add New</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('delivery/zone/all-zones')}}"><i class="icon-config"></i> All Zones</a>
                            </li>
                        </ul>
                    </li>

                     <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-compass"></i> Delivery Schedules</a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('delivery/timing/new')}}"><i class="icon-config"></i> Add New</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('delivery/timing/all-timings')}}"><i class="icon-config"></i> All Schedules</a>
                            </li>
                        </ul>
                    </li>

                     <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-ticket"></i> Coupons</a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('coupons/new')}}"><i class="icon-config"></i> Add New</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('coupons/all-coupons')}}"><i class="icon-config"></i> All Coupons</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>   
            @endcan        

            <li class="nav-item">
                <a class="nav-link" href="{{url('reports')}}"><i class="icon-docs"></i> Reports</a>
            </li>
            
            @can('manage-users')
            <li class="nav-title">
                Security
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-users"></i> Users</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('users/new')}}"><i class="icon-user-follow"></i> Add New</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('users/all-users')}}"><i class="icon-list"></i> All Users</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="javascript:void(0);"><i class="icon-settings"></i> Settings</a>
                <ul class="nav-dropdown-items">
                    @can('manage-api')
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('settings/api')}}"><i class="fa fa-plug"></i> API Settings</a>
                    </li>
                    @endcan

                    <li class="nav-item">
                        <a class="nav-link" href="{{url('users/change-password')}}"><i class="icon-key"></i> Change Password</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{url('settings/communication-settings')}}"><i class="icon-docs"></i>  Communication Settings</a>
                    </li>

                </ul>
            </li>
            @endcan
            
            <li class="nav-item for-small-screen">
                <a class="nav-link" href="{{url('auth/logout')}}" role="button" aria-haspopup="true" aria-expanded="false">
                  <i class="fa fa-sign-out"></i> Logout
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{url('audit/logs')}}"><i class="icon-list"></i> Activity Logs</a>
            </li>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
