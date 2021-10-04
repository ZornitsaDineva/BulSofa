

<!-- Logo -->
<a href="{{url('/dashboard')}}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b></b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>BulSofa.com </b></span>
</a>

<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{asset('assets/img/avatar5.png')}}" class="user-image" alt="User Image">
                    <span class="hidden-xs">Administrator</span>
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header">
                        <img src="{{asset('assets/img/avatar5.png')}}" class="img-circle" alt="User Image">

                        <p>
                            Administrator - BulSofa.com
                            <small></small>
                        </p>
                    </li>
                    <!-- Menu Body -->                    
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        
                        <div class="pull-right">
                            <a href="{{url('/admin/logout')}}" class="btn btn-default btn-flat">Sign out</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

</nav>