<!doctype html>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Dashboard</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="PLNQCbpXcBfFbin8zJBkmxAfxtiC6YmO1nspgwAx">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('admin/assets/images/favicon/apple-icon-57x57.png')}}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('admin/assets/images/favicon/apple-icon-60x60.png')}}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('admin/assets/images/favicon/apple-icon-72x72.png')}}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('admin/assets/images/favicon/apple-icon-76x76.png')}}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('admin/assets/images/favicon/apple-icon-114x114.png')}}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('admin/assets/images/favicon/apple-icon-120x120.png')}}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('admin/assets/images/favicon/apple-icon-144x144.png')}}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('admin/assets/images/favicon/apple-icon-152x152.png')}}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin/assets/images/favicon/apple-icon-180x180.png')}}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('admin/assets/images/favicon/android-icon-192x192.png')}}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('admin/assets/images/favicon/favicon-32x32.png')}}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('admin/assets/images/favicon/favicon-96x96.png')}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/assets/images/favicon/favicon-16x16.png')}}">
        <link rel="manifest" href="{{ asset('admin/assets/images/favicon/manifest.json')}}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">   
        <link rel="stylesheet" href="{{ asset('ui/assets/css/ui.css')}}">
        <link rel="stylesheet" href="{{ asset('admin/assets/css/admin.css')}}">         
        <link rel="stylesheet" href="{{ asset('web-form/assets/css/admin.css')}}">         
    </head>
<body>    
<!-- <div id="app" class="anonymous-layout-container"> -->
<div id="app">
<div class="navbar-top">
        <div class="navbar-top-left">
            <div class="brand-logo"><a href="{{route('home')}}"><img
                        src="{{ asset('admin/assets/images/logo.svg')}}"
                        alt="Krayin CRM"></a></div>
        </div>
        <div class="navbar-top-right">
            <div class="quick-create"><span class="button dropdown-toggle"><i class="icon plus-white-icon"></i></span>
                <div class="dropdown-list bottom-right">
                    <div class="quick-link-container">
                        <div class="quick-link-item"><a
                                href="#">
                                <i class="icon lead-icon"></i> <span>Lead</span></a>
                        </div>
                        <div class="quick-link-item">
                            <a  href="#">
                                <i  class="icon quotation-icon"></i> 
                                <span>Quote</span>
                            </a>
                        </div>
                        <div class="quick-link-item">
                            <a  href="#">
                                <i class="icon mail-icon"></i> 
                                <span>Email</span>
                            </a>
                        </div>
                        <div class="quick-link-item">
                            <a  href="#">
                                <i class="icon person-icon"></i> 
                                <span>Person</span>
                            </a>
                        </div>
                        <div class="quick-link-item">
                            <a href="#">
                                <i class="icon organization-icon"></i> 
                                <span>Organization</span>
                            </a>
                        </div>
                        <div class="quick-link-item">
                            <a href="#">
                                <i class="icon product-icon"></i> 
                                <span>Product</span>
                            </a>
                        </div>
                        <div class="quick-link-item">
                            <a href="#">
                                <i class="icon attribute-icon"></i> 
                                <span>Attribute</span>
                            </a>
                        </div>
                        <div class="quick-link-item">
                            <a href="#">
                                <i class="icon role-icon"></i> 
                                <span>Role</span>
                            </a>
                            </div>
                        <div class="quick-link-item">
                            <a href="#">
                                <i class="icon user-icon"></i> 
                                <span>User</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-info">
                <div class="dropdown-toggle">
                    <div class="avatar"><span class="icon avatar-icon"></span></div>
                    <div class="info"><span class="howdy">Howdy!</span> <span class="user">Example</span></div> <i
                        class="icon ellipsis-icon"></i>
                </div>
                <div class="dropdown-list bottom-right">
                    <div class="dropdown-container">
                        <ul>
                            <li><a href="#">My
                                    Account</a></li>
                            <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Sign Out') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar-left open">
        <ul class="menubar">
            <li class="menu-item active has-tooltip" data-original-title="Dashboard"><a
                    href="#"><i
                        class="icon sprite dashboard-icon"></i> <span class="menu-label">Dashboard</span></a></li>
            <li class="menu-item has-tooltip" data-original-title="Leads"><a
                    href="#"><i class="icon sprite leads-icon"></i> 
                    <span class="menu-label">Leads</span>
                </a>
            </li>
            <li class="menu-item has-tooltip" data-original-title="Quotes">
                <a href="{{route('organisation.index')}}">
                    <i class="icon sprite quotes-icon"></i> 
                    <span class="menu-label">Organisation Registration</span>
                </a>
            </li>
            <li class="menu-item has-tooltip" data-original-title="Quotes">
                <a href="{{route('module.index')}}">
                    <i class="icon sprite quotes-icon"></i> 
                    <span class="menu-label">Module Permission</span>
                </a>
            </li>
            <li title="Mail" onclick="myFunction()" class="menu-item">
                <a href="#">
                    <i class="icon sprite emails-icon"></i> 
                    <span class="menu-label">Mail</span>
                </a>
                    <ul class="sub-menubar" id = "panel">
                        <li class="sub-menu-item"><a
                                href="#"><span
                                    class="menu-label">Compose</span></a></li>
                        <li class="sub-menu-item"><a
                                href="#"><span
                                    class="menu-label">Inbox</span></a></li>
                        <li class="sub-menu-item"><a
                                href="#"><span
                                    class="menu-label">Draft</span></a></li>
                        <li class="sub-menu-item"><a
                                href="#"><span
                                    class="menu-label">Outbox</span></a></li>
                        <li class="sub-menu-item"><a
                                href="#"><span
                                    class="menu-label">Sent</span></a></li>
                        <li class="sub-menu-item"><a
                                href="#"><span
                                    class="menu-label">Trash</span></a></li>
                    </ul>
            </li>
            <li class="menu-item has-tooltip" data-original-title="Activities">
                <a href="#">
                    <i class="icon sprite activities-icon"></i> 
                    <span class="menu-label">Activities</span>
                </a>
            </li>
            <li title="Contacts" class="menu-item">
                <a href="#">
                    <i class="icon sprite phone-icon"></i> 
                    <span class="menu-label">Contacts</span>
                </a>
                <ul class="sub-menubar">
                    <li class="sub-menu-item">
                        <a href="#">
                            <span class="menu-label">Persons</span>
                        </a>
                    </li>
                    <li class="sub-menu-item">
                        <a href="#">
                            <span class="menu-label">Organizations</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item has-tooltip" data-original-title="Products">
                <a href="#"><i class="icon sprite products-icon"></i> 
                <span class="menu-label">Products</span></a>
            </li>
            <li class="menu-item has-tooltip" data-original-title="Settings">
                <a href="#">
                    <i class="icon sprite settings-icon"></i> 
                    <span class="menu-label">Settings</span>
                </a>
            </li>
            <li class="menu-item has-tooltip" data-original-title="Configuration">
                <a href="#">
                    <i class="icon sprite tools-icon"></i> 
                    <span class="menu-label">Configuration</span>
                </a>
            </li>
        </ul>
        <div class="menubar-bottom"><span class="icon menu-fold-icon"></span></div>
    </div>
    </div>
        
      
            @yield('content')
        
            
</body>
<script type="text/javascript">
        window.flashMessages = [];



        window.serverErrors = [];

    </script>

    <script type="text/javascript"
        src="{{ asset('admin/assets/js/admin.js')}}"></script>
    <script type="text/javascript"
        src="{{ asset('ui/assets/js/ui.js')}}"></script>

    <script>
        $(() => {
            $('input').keyup(({ target }) => {
                if ($(target).parent('.has-error').length) {
                    $(target).parent('.has-error').addClass('hide-error');
                }
            });

            $('button').click(() => {
                $('.hide-error').removeClass('hide-error');
            });

            $(":input[name=email]").focus();
        });
        function myFunction() {
           document.getElementById("panel").style.display = "block";
        }
    </script>
    
</html>
