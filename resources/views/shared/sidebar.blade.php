<div class="bhr__sidebar p-0">
    <div class="row">
        <div class="col-3 bhr__sidebar__side-panel">
            <div class="bhr_sidebar_navlist">
                <ul class="bhr_sidebar_list-top">
                    <li>
                        <i data-feather="search" class="text-white icon-sm"></i>
                    </li>
                    <li>
                        <i data-feather="calendar" class="text-white icon-sm"></i>
                    </li>
                    <li>
                        <i data-feather="user" class="text-white icon-sm"></i>
                    </li>
                    <li>
                        <i data-feather="message-circle" class="text-white icon-sm"></i>
                    </li>
                    <li>
                        <i data-feather="file" class="text-white icon-sm"></i>
                    </li>
                </ul>
                <ul class="bhr_sidebar_list-bottom">
                    <li>
                        <i data-feather="settings" class="text-white icon-sm"></i>
                    </li>
                    <li class="p-2 ms-1 mb-0 mt-2">
                        <div class="bhr__user__avater">
                            <img src="{{ asset('/assets/images/avatar-1.jpg') }}" alt="">
                        </div>
                    </li>
                    <li class="side-panel-toggle">
                        <i data-feather="align-left" class="text-white icon-sm"></i>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-9 p-0 bhr__sidebar__menubar">
            <div class="bhr__menu-list">
                <ul>
                    <li class="menu-item">
                        <a href="">
                            <i data-feather="layout" class="icon-sm"></i>
                            <span class="ms-1">Dashboard</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::is('/') ? 'active' : null }}">
                        <a href="">
                            <i data-feather="users" class="icon-sm"></i>
                            <span class="ms-1">Users</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="">
                            <i data-feather="briefcase" class="icon-sm"></i>
                            <span class="ms-1">Departments</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="">
                            <i data-feather="users" class="icon-sm"></i>
                            <span class="ms-1">Employee</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="">
                            <i data-feather="activity" class="icon-sm"></i>
                            <span class="ms-1">Activities</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="">
                            <i data-feather="check-circle" class="icon-sm"></i>
                            <span class="ms-1">Holidays</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="">
                            <i data-feather="clock" class="icon-sm"></i>
                            <span class="ms-1">Events</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="">
                            <i data-feather="credit-card" class="icon-sm"></i>
                            <span class="ms-1">Payroll</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="">
                            <i data-feather="user" class="icon-sm"></i>
                            <span class="ms-1">Accounts</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="">
                            <i data-feather="info" class="icon-sm"></i>
                            <span class="ms-1">Report</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
