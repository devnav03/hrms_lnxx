<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf_token" content="{{csrf_token()}}" />
    <title>{{ auth()->user()->name ?? '' }}</title>
    <!-- base:css -->
    
    <link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link href="{{asset('css/toastr.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('images/lnxxx.png')}}" />
</head>

<body>
<div id="loader"></div>
    <div class="container-scroller d-flex">
        <!-- partial:./partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item sidebar-category">
                    <p>Dashboard</p>
                    <span></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('home')}}">
                        <i class="mdi mdi-view-quilt menu-icon"></i>
                        <span class="menu-title">Admin Dashboard</span>
                        <div class="badge badge-info badge-pill">2</div>
                    </a>
                </li>
                <li class="nav-item sidebar-category">
                    <p>Organisation</p>
                    <span></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-expanded="false"
                        aria-controls="ui-basic" href="{{route('add.organization')}}">
                        <i class="mdi mdi-palette menu-icon"></i>
                        <span class="menu-title">Organisation Master</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false"
                        aria-controls="ui-basic">
                        <i class="mdi mdi-view-headline menu-icon"></i>
                        <span class="menu-title">Module Access Permission</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="ui-basic">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="#">Employee Management</a>
                            </li>
                            <li class="nav-item"> <a class="nav-link" href="#">Visitor Management System</a></li>
                            <li class="nav-item"> <a class="nav-link" href="#">Conference management system</a></li>
                            <li class="nav-item"> <a class="nav-link" href="#">Inventory Management System</a></li>
                            <li class="nav-item"> <a class="nav-link" href="#">Support Ticket Management System</a></li>
                            <li class="nav-item"> <a class="nav-link" href="#">Management System with Flow & Approval</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="mdi mdi-message-text menu-icon"></i>
                        <span class="menu-title">Notice Board</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="mdi mdi-cash-multiple menu-icon"></i>
                        <span class="menu-title">Payment</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="mdi mdi-settings menu-icon"></i>
                        <span class="menu-title">Setting</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:./partials/_navbar.html -->
            <nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                        data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>
                    <div class="navbar-brand-wrapper">
                        <a class="navbar-brand brand-logo" href="{{route('home')}}"><img src="{{asset('images/lnxxx.png')}}" alt="logo" style="width:100px" /></a>
                        <a class="navbar-brand brand-logo-mini" href="{{route('home')}}"><img src="{{asset('images/lnxxx.png')}}" alt="logo" style="width:100px" /></a>
                    </div>
                    <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1">Welcome, {{ auth()->user()->name ?? '' }}</h4>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item">
                            <div id="clockdate">
                                <div class="clockdate-wrapper">
                                    <div id="clock"></div>
                                    <div id="date"></div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown mr-1">
                            <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center"
                                id="messageDropdown" href="#" data-toggle="dropdown">
                                <i class="mdi mdi-calendar mx-0"></i>
                                <span class="count bg-info">2</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                                aria-labelledby="messageDropdown">
                                <p class="mb-0 font-weight-normal float-left dropdown-header">Messages</p>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <img src="images/faces/face4.jpg" alt="image" class="profile-pic">
                                    </div>
                                    <div class="preview-item-content flex-grow">
                                        <h6 class="preview-subject ellipsis font-weight-normal">David Grey
                                        </h6>
                                        <p class="font-weight-light small-text text-muted mb-0">
                                            The meeting is cancelled
                                        </p>
                                    </div>
                                </a>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <img src="images/faces/face2.jpg" alt="image" class="profile-pic">
                                    </div>
                                    <div class="preview-item-content flex-grow">
                                        <h6 class="preview-subject ellipsis font-weight-normal">Tim Cook
                                        </h6>
                                        <p class="font-weight-light small-text text-muted mb-0">
                                            New product launch
                                        </p>
                                    </div>
                                </a>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <img src="images/faces/face3.jpg" alt="image" class="profile-pic">
                                    </div>
                                    <div class="preview-item-content flex-grow">
                                        <h6 class="preview-subject ellipsis font-weight-normal"> Johnson
                                        </h6>
                                        <p class="font-weight-light small-text text-muted mb-0">
                                            Upcoming board meeting
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown mr-2">
                            <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center"
                                id="notificationDropdown" href="#" data-toggle="dropdown">
                                <i class="mdi mdi-email-open mx-0"></i>
                                <span class="count bg-danger">1</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                                aria-labelledby="notificationDropdown">
                                <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-success">
                                            <i class="mdi mdi-information mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <h6 class="preview-subject font-weight-normal">Application Error</h6>
                                        <p class="font-weight-light small-text mb-0 text-muted">
                                            Just now
                                        </p>
                                    </div>
                                </a>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-warning">
                                            <i class="mdi mdi-settings mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <h6 class="preview-subject font-weight-normal">Settings</h6>
                                        <p class="font-weight-light small-text mb-0 text-muted">
                                            Private message
                                        </p>
                                    </div>
                                </a>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-info">
                                            <i class="mdi mdi-account-box mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <h6 class="preview-subject font-weight-normal">New user registration</h6>
                                        <p class="font-weight-light small-text mb-0 text-muted">
                                            2 days ago
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                        data-toggle="offcanvas">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
                <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
                    <ul class="navbar-nav mr-lg-2">
                        <li class="nav-item nav-search d-none d-lg-block">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search Here..." aria-label="search"
                                    aria-describedby="search">
                            </div>
                        </li>
                    </ul>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                                <img src="{{asset('images/lnxxx.png')}}" alt="profile" />
                                <span class="nav-profile-name">{{ auth()->user()->name ?? '' }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                                aria-labelledby="profileDropdown">
                                <a class="dropdown-item">
                                    <i class="mdi mdi-settings text-primary"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="{{ route('log-out') }}">
                                    <i class="mdi mdi-logout text-primary"></i>
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                    <input type="hidden" name="username_redirect" value="login">
                                </form>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link icon-link">
                                <i class="mdi mdi-plus-circle-outline"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link icon-link">
                                <i class="mdi mdi-web"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link icon-link">
                                <i class="mdi mdi-clock-outline"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            @yield('content')
            <!-- content-wrapper ends -->
            <!-- partial:./partials/_footer.html -->
            <footer class="footer">
                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex justify-content-center justify-content-sm-between">
                            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                            {{config('global.COMPANY')}} {{date('Y')}}</span>
                            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block"> By:
                                <a href="{{config('global.COMPANY_URL')}}" target="_blank">{{config('global.COMPANY')}}</a></span>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- base:js -->
    <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <script src="{{asset('vendors/chart.js/Chart.min.js')}}"></script>
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="{{asset('js/off-canvas.js')}}"></script>
    <script src="{{asset('js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('js/template.js')}}"></script>
    <!-- endinject -->
    <script src="{{asset('js/dashboard.js')}}"></script>
    <!-- End custom js for this page-->
    <script src="{{asset('js/file-upload.js')}}"></script>
    <script src="{{asset('js/toastr.min.js')}}"></script>
    <script src="{{asset('js/custome.js')}}"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    @if(!empty(session('success')))
    <script type="text/javascript">
        toastr.success("{{session('success')}}");
    </script>
    @endif
    @if(!empty(session('error')))
    <script type="text/javascript">
        toastr.error("{{session('error')}}");
    </script>
    @endif
</body>

</html>