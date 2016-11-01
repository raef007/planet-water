    <!-- LEFT NAVIGATION -->
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Tank Level Tracker!</span></a>
                </div>

                <div class="clearfix"></div>
                
                <!-- LEFT NAVIGATION MENU -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <h3>Today is {{ date('F d') }}</h3>
                        <ul class="nav side-menu">
                        
                            <li><a href = '{{ URL::To("admin/transaction-log") }}'><i class="fa fa-home"></i> Transaction Log </a> </li>
                            
                            <li><a><i class="fa fa-users"></i> Customer <span class="fa fa-chevron-down"></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ URL::To('admin/customer/list') }}">Customer List</a></li>
                                    <li><a href="{{ URL::To('admin/add/customer') }}">Add Customer</a></li>
                                </ul>
                            </li>
                            
                            <li><a href = '{{ URL::To("admin/edit/vehicle/1") }}'><i class="fa fa-truck"></i> Vehicle </a> </li>
                            
                            <li><a><i class="fa fa-newspaper-o"></i> Delivery <span class="fa fa-chevron-down"></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ URL::To('admin/delivery-planner/1') }}">Planner</a></li>
                                    <li><a href="{{ URL::To('admin/delivery-summary/1') }}">Summary</a></li>
                                </ul>
                            </li>
                            
                            <li><a href = '{{  URL::to("logout") }}'><i class="fa fa-sign-out"></i> Log Out </a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Left Navigation Footer Menu -->
                <div class="sidebar-footer hidden-small">
                    
                </div>
            </div>
        </div>