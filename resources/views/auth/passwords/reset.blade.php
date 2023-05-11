<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Password Reset|| Login</title>
    <link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>
<body>
    <div class="container-scroller d-flex">
        <div class="container-fluid page-body-wrapper full-page-wrapper d-flex">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo text-center">
                            </div>
                            <h4>Hello! let's get started</h4>
                            <h6 class="font-weight-light">{{ __('Reset Password') }}</h6>
                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif
                            <form class="pt-3" method="POST" action="{{route('password.update')}}">
                                @csrf
                                <input type="hidden" name="token" value="{{$token}}">
                                <div class="form-group">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$email ?? old('email')}}" required autocomplete="email" readonly>
                                    <span class="control-error">
                                        @error('email')
                                        <strong>{{ $message }}</strong>
                                        @enderror
                                    </span>
                                </div>
                                <div class="form-group">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="{{__('Password')}}" autocomplete="new-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input id="password-confirm" type="password" class="form-control " name="password_confirmation" placeholder="{{__('Confirm Password')}}" required autocomplete="new-password">
                                </div>
                                <div class="mt-3">
                                    <button type="submit" style="padding: 0.6rem 3rem;" class="btn btn-block btn-warning btn-lg font-weight-medium">{{__('Reset Password')}}</button>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <a class="auth-link text-white" href="{{url('lnxx')}}">{{__('Sign in to an existing account?')}}</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
    <script src="{{asset('js/off-canvas.js')}}"></script>
    <script src="{{asset('js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('js/template.js')}}"></script>
</body>
</html>