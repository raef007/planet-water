    <div class="top_nav">
        <div class="nav_menu">
            <nav>
                @if (2 == Auth::user()->user_type)
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>
                @endif
                
                @if (1 == Auth::user()->user_type)
                <div style = 'display: inline-block; padding: 10px;'>
                    <h4> Tank Level Tracker </h4>
                </div>
                @endif
                <ul class="nav navbar-nav navbar-right">
                    <li role="presentation">
                        <a href = '{{ URL::to("logout") }}'>
                            <i class="fa fa-sign-out"></i>
                            <span> Log Out </span>
                        </a>
                    </li>
                    
                    <li role="presentation" class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-envelope-o"></i>
                            @if (0)<span class="badge bg-green">1</span>@endif
                            <span> Alerts </span>
                        </a>
                        <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                            @if(0)
                            <li>
                                <a>
                                    <span>
                                        <span>John Smith</span>
                                        <span class="time">3 mins ago</span>
                                    </span>
                                    
                                    <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                </a>
                            </li>
                            
                            
                            <li>
                                <div class="text-center">
                                    <a>
                                        <strong>See All Alerts</strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                            </li>
                            @endif
                            <li>
                                <div class="text-center">
                                    <strong>No new notification</strong>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>