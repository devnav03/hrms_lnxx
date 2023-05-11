<?php
$url = \Request::segment(1);
$var = \App\Models\Organisation::where(['user_name' => $url])->first();

\Session::forget('login_from');
\Session::start();
\Session::put('login_from', @$var->user_id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php if(!empty($var->company_name)){ echo $var->company_name; }?>|| Login</title>
  <!-- base:css -->
  <link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
  <!-- endinject -->
  <?php if(!empty($var->user_name)){ ?>
    <link rel="shortcut icon" href="{{asset('organization/logo')}}/{{$var->logo}}"/>
  <?php }else{ ?>
    <link rel="shortcut icon" href="{{asset('images/lnxxx.png')}}" />
  <?php } ?>
</head>

<body>   
<div class="container-scroller d-flex">
    <div class="container-fluid page-body-wrapper full-page-wrapper d-flex">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
            <div class="brand-logo text-center">
              <?php if(!empty($var->user_name)){ ?>
                <img src="{{asset('organization/logo')}}/{{$var->logo}}" alt="{{$var->user_name}}">
              <?php } else { ?>
                <img src="{{asset('images/lnxxx.png')}}" alt="Lnxxx">
              <?php } ?>
              </div>
              <h4>Hello! let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              @if(session()->has('organisation_inactive'))
              <p style="background: #f00; padding: 10px; border-radius: 9px; font-size: 15px;">license has been expired please renew your license!</p>
              @endif
              <form  class="pt-3" method="POST" action="{{route('login') }}">
                @csrf
                @if(!empty($var->user_name))
                <input type="hidden" name="org" value="{{ $var->id }}">
                @else
                <input type="hidden" name="org" value="">
                @endif
                <div class="form-group">
                  <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" placeholder="Username">
                    <span class="control-error">
                        @error('email')
                            <strong>{{ $message }}</strong>
                        @enderror
                    </span> 
                </div>
                <div class="form-group">
                  <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword1" placeholder="Password">
                  @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                  @enderror
                </div>
                <div class="mt-3">
                <button type="submit" style="padding: 0.6rem 3rem;"  class="btn btn-block btn-warning btn-lg font-weight-medium">SIGN IN</button>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      Keep me signed in
                    </label>
                  </div>
                  @if (Route::has('password.request'))
                    <a class="auth-link text-white" href="{{ route('password.request') }}">{{ __('Forgot Password?') }}</a>
                  @endif
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{asset('js/off-canvas.js')}}"></script>
  <script src="{{asset('js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('js/template.js')}}"></script>
  <!-- endinject -->
</body>
</html>