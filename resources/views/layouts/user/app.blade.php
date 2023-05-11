@php $user_profile = App\Models\EmployeeInfo::select('employee_code','datas')->where('user_id',Auth::user()->id)->whereNotNull('employee_code')->first();
    if(!empty($user_profile)){
        $users_data = json_decode($user_profile->datas);
    }
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('organization/logo')}}/{{$organisation->logo}}" />

    <style>
        .navbar{
            background-image:none !important;
            background-color: #135cbb;
            height: 60px;
        }
        .navbar-height {
            background-image: none !important;
            background-color: #ffffff;
            height: 26px;
            padding-top: 0.2rem !important;
            padding-bottom: 0.4rem !important;
        }
        .sidebar .nav .sidebar-category {
            margin: -0.8rem 2.125rem 0.4rem 2.125rem !important;
        }
        .navbar .navbar-menu-wrapper {
            transition: width 0.25s ease;
            -webkit-transition: width 0.25s ease;
            -moz-transition: width 0.25s ease;
            -ms-transition: width 0.25s ease;
            color: #ffffff;
            margin-left: 1.437rem;
            margin-right: 1.437rem;
            width: 100%;
            height: 0px;
        }
        .side-logo{
            width: 4rem !important;
            margin-left: -30px !important;
        }
        .name-set{
            background-color: #5cb85c;
            border-radius: 15px;
            padding: 0.2em 0.6em 0.3em;
            margin-top: 2rem;
        }
        .dropdown .dropdown-toggle:after {
            margin-bottom: 4px !important;
        }
        .img-circle{
            max-width: 3rem;
            max-height: 3rem;
            margin-right: 5px;
            border-radius: 50%;
        }
        @media only screen and (max-width: 768px) {
            .mob-logo {
                display: block !important;
            }
            .clockdate-wrapper{
                display: none;
            }
        }
        @media screen and (max-width: 991px){
            .sidebar-offcanvas {
                top: 97px !important;
            }
        }
    </style>
    
</head>

<body>
    <div id="loader"></div>
    <div class="container-scroller d-flex">
        <!-- partial:./partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <!-- <li class="nav-item sidebar-category">
                    <p>Dashboard</p>
                    <span></span>
                </li> -->

                <li class="nav-item sidebar-category"><a class="navbar-brand brand-logo" href="{{route('home')}}"><img src="{{asset('organization/logo')}}/{{$organisation->logo}}" alt="logo" style="width:100px" /></a></li>
                <!-- <li class="nav-item sidebar-category">
                    <p>Dashboard</p>
                    <span></span> -->

                <li class="nav-item">
                    <a class="nav-link" href="{{route('home')}}">
                        <i class="mdi mdi-view-quilt menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                        <!-- <div class="badge badge-info badge-pill">2</div> -->
                    </a>
                </li>

                <!-- <li class="nav-item sidebar-category"><a class="navbar-brand brand-logo" href="{{route('home')}}"><img src="{{asset('organization/logo')}}/{{$organisation->logo}}" alt="logo" style="width:100px" /></a></li>
                <li class="nav-item sidebar-category">
                    <p>Dashboard</p>
                    <span></span> -->
                </li>

                
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#user_profile" aria-expanded="false"
                        aria-controls="user_profile">
                        <i class="mdi mdi-account menu-icon"></i>
                        <span class="menu-title">Profile</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="user_profile">
                        <ul class="nav flex-column sub-menu">
                            <?php
                            $select = DB::table('form_engine_categories')->select('name')->where('orgnization_id',$organisation->user_id)->get();
                            if(!empty($select)){
                                foreach($select as $rows){
                                    $url = str_replace(' ','-',strtolower($rows->name));
                                    echo '<li class="nav-item"> <a class="nav-link" href="'.url('self/'.$url).'">'.$rows->name.'</a></li>';
                                }
                            }?>
                            @php
                                $email = \encrypt(auth()->user()->email);
                                $mobile = \encrypt(auth()->user()->mobile);
                                $date = date('d-m-y H:i');
                                $dt = new \DateTime($date);
                                $tz = new \DateTimeZone('Asia/Kolkata'); 
                                $dt->setTimezone($tz);
                                $date = $dt->format('d-m-y H:i');
                                $time = \encrypt($date);
                                $url="https://vztor.in/lead-management-system/".$email."/".$time."/".$mobile."";
                            @endphp

                            <!--<li class="nav-item"> <a target="_blank" class="nav-link" href="{{$url}}">Login LNXX</a></li> -->

                            <!--<li class="nav-item"> <a class="nav-link" href="{{route('add.contact')}}">Contact Details</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="{{route('add.education')}}">Education Details</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="{{route('add.bank')}}">Bank Details</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="{{route('add.company')}}">Company Details</a></li>-->
                            <!--<li class="nav-item"> <a class="nav-link" href="{{route('add.document')}}">Document Details</a></li>-->
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('attendance-list')}}">
                        <i class="mdi mdi-bookmark-check menu-icon"></i><span class="menu-title">Attendance</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#leave_management" aria-expanded="false"
                        aria-controls="leave_management">
                        <i class="mdi mdi-bookmark-check menu-icon"></i>
                        <span class="menu-title">Leave Management</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="leave_management">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="{{url('take-leave')}}">Leave Request</a></li>
                            <li class="nav-item"> <a class="nav-link" href="{{url('leave-history')}}">Leave History</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="mdi mdi-view-headline menu-icon"></i>
                        <span class="menu-title">Performance Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#timesheet_management" aria-expanded="false"
                        aria-controls="timesheet_management">
                        <i class="mdi mdi-bookmark-check menu-icon"></i>
                        <span class="menu-title">Timesheet Management</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="timesheet_management">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="{{url('add-timesheet')}}">Add Timesheet</a></li>
                            <li class="nav-item"> <a class="nav-link" href="{{url('view-timesheet')}}">View Timesheet</a></li>
                        </ul>
                    </div>
                </li>
		<?php
                $Auth = auth()->user();
                 $hiring = DB::select("SELECT count(a.id) as id FROM `hiring_approvals` as a INNER JOIN interview_hiring_status as b on a.status_id=b.id INNER JOIN emp_details as c on a.candidate_id=c.id WHERE a.organisation_id=$Auth->organisation_id AND a.approved_by=0 AND a.employee_id in ($Auth->id) ORDER BY a.id ASC");?>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('candidate-hiring-request')}}?check_status=Pending">
                        <i class="fa fa-users menu-icon"></i>
				<span class="menu-title">Candidate Hiring Request </span> <div class="badge badge-warning badge-pill">{{@$hiring[0]->id}}</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('assets-request')}}">
                        <i class="mdi mdi-bookmark-check menu-icon"></i><span class="menu-title">Assets Request</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="mdi mdi-cash menu-icon"></i>
                        <span class="menu-title">Payroll Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="mdi mdi-bell menu-icon"></i>
                        <span class="menu-title">Announcement</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('notification-history')}}">
                        <i class="mdi mdi-account-alert menu-icon"></i>
                        <span class="menu-title">Notification</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="mdi mdi-file menu-icon"></i>
                        <span class="menu-title">Report</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <nav class="navbar-height col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
                <marquee style="color:red;">
                    <h6>
                    Hello {{ auth()->user()->name ?? '' }}
                    </h6>
                </marquee>
            </nav>
            <!-- partial:./partials/_navbar.html -->
            <nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
                
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                        data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>
                    <div class="navbar-brand-wrapper">
                        <a class="navbar-brand brand-logo" href="{{route('home')}}"><img src="{{asset('organization/logo')}}/{{$organisation->logo}}" alt="logo" class="mob-logo side-logo" style="width:100px;display:none" /></a>
                        <a class="navbar-brand brand-logo-mini" href="{{route('home')}}"><img src="{{asset('organization/logo')}}/{{$organisation->logo}}" style="width:100px alt="logo" /></a>
                    </div>
                    <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1">Welcome back, {{ auth()->user()->name ?? '' }}</h4>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item">
                            <div id="clockdate">
                                <div class="clockdate-wrapper">
                                    <div id="clock"></div>
                                    <div id="date"></div>
                                </div>
                            </div>
                        </li>

                        <!-- <li class="nav-item dropdown mr-1">
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
                        </li> -->

                        <li class="nav-item dropdown mr-2">
                            @if(!empty($users_data->profile))
                                <img src="{{asset($users_data->profile)}}" class="img-circle" alt="profile"></img>
                            @else
                                <img src="{{asset('organization/logo')}}/{{$organisation->logo}}" class="img-circle" alt="profile" />
                            @endif

                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                                <span class="nav-profile-name name-set">{{ auth()->user()->name ?? '' }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                                aria-labelledby="profileDropdown">
                                <a class="dropdown-item">
                                    <i class="mdi mdi-settings text-primary"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="{{ route('user-log-out') }}">
                                    <i class="mdi mdi-logout text-primary"></i>
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                    <input type="hidden" name="username_redirect" value="{{$organisation->user_name}}">
                                </form>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                        data-toggle="offcanvas">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
                <!-- <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
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
                                <img src="{{asset('organization/logo')}}/{{$organisation->logo}}" alt="profile" />
                                <span class="nav-profile-name">{{ auth()->user()->name ?? '' }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                                aria-labelledby="profileDropdown">
                                <a class="dropdown-item">
                                    <i class="mdi mdi-settings text-primary"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="mdi mdi-logout text-primary"></i>
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                    <input type="hidden" name="username_redirect" value="{{$organisation->user_name}}">
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
                </div> -->
            </nav>
            @yield('content')
            <!-- content-wrapper ends -->
            <!-- partial:./partials/_footer.html -->
            <footer class="footer">
                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex justify-content-center justify-content-sm-between">
                            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © {{ auth()->user()->name ?? '' }} 2020</span>
                            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Distributed By:
                                <a href="https://www.themewagon.com/" target="_blank">{{ auth()->user()->name ?? '' }}</a></span>
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript">
    $(function() {
        var start = moment().subtract(29, 'days');
        var end = moment();
        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#from_date_val').val(start.format('YYYY-MM-DD'));
            $('#to_date_val').val(end.format('YYYY-MM-DD'));
        }
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        },cb);
        cb(start, end);
    });
    </script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
    <script>
    var firebaseConfig = {
        apiKey: "AIzaSyDykzd8-vk962Im23P0fXCi6oB8D0Vp2ms",
        authDomain: "notification-94417.firebaseapp.com",
        projectId: "notification-94417",
        storageBucket: "notification-94417.appspot.com",
        messagingSenderId: "119121087610",
        appId: "1:119121087610:web:b172e73a7a128122b6bfc3",
        measurementId: "G-ZFC7QHJFNQ"
    };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    @if(request()->is('home'))
    function startFCM(messaging) {
        messaging.requestPermission().then(function () {
                return messaging.getToken()
            }).then(function (response) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: '{{url("ajax/store-token")}}',
                    type: 'POST',
                    data: {token: response},
                    dataType: 'JSON',
                    success: function (response) {},
                    error: function (error) {},
                });
            }).catch(function (error) {
            alert(error);
        });
    }
    startFCM(messaging);
    @endif
    messaging.onMessage(function (payload) {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(title, options);
    });
    </script>
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