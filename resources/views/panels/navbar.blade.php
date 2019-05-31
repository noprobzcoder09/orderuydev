<header class="app-header navbar">
   <button class="navbar-toggler mobile-sidebar-toggler d-lg-none" type="button">
      <span class="navbar-toggler-icon"></span>
   </button>
   <a class="navbar-brand" href="#">
     <img src="/images/logo.png" alt="">
   </a>
   <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
      <span class="navbar-toggler-icon"></span>
   </button>
   <ul class="nav navbar-nav d-md-down-none mr-auto hidden hide" style="display: none;">
      <li class="nav-item px-3">
        <a class="nav-link" href="{{url('/')}}">Dashboard</a>
      </li>
      <li class="nav-item px-3">
        <a class="nav-link" href="{{url('reports')}}">Reports</a>
      </li>
    </ul>
<!--    <ul class="nav navbar-nav ml-auto">
      <li class="nav-item dropdown d-md-down-none">
        <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          <i class="icon-bell"></i><span class="badge badge-pill badge-danger">1</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
          <div class="dropdown-header text-center">
            <strong>You have 1 notifications</strong>
          </div>
          <a href="#" class="dropdown-item">
            <i class="icon-user-follow text-success"></i> New user registered
          </a>
        </div>
      </li>
   </ul> -->
  
     <ul class="nav navbar-nav ml-auto px-3 for-large-screen">
      <li>
        <input id="general-customer-search" placeholder="Search Customer here..." />
      </li>
      <li style="width: 20px;">&nbsp;</li>
      <li class="nav-item d-md-down-none">
        <a class="nav-link" href="{{url('auth/logout')}}" role="button" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-sign-out"></i> Logout
        </a>
      </li>
     </ul>

     <ul class="nav navbar-nav for-small-screen">
      <li>
        <div class="navbar-search">
          <span><i class="fa fa-search"></i></span>
          <input id="general-customer-search-mobile" style="margin-right: 10px;" placeholder="Search Customer here..." />
        </div>
        
      </li>
      <!-- <li style="width: 20px;">&nbsp;</li> -->
      <!-- <li class="nav-item d-md-down-none">
        <a class="nav-link" href="{{url('auth/logout')}}" role="button" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-sign-out"></i> Logout
        </a>
      </li> -->
     </ul>

</header>