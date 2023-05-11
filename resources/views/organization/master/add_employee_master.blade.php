@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Employee Master</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('employee-master')}}" method="POST">
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" maxlength="20" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="First Name" maxlength="20" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" onkeyup="CheckEmail(this.value);" maxlength="30" required>
                                    <div class="help-block checking_email" style="font-size: 12px;"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="text" class="form-control" id="password" name="password" placeholder="Password" maxlength="30" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="">Select</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function CheckEmail(email) {
            $('.organisation_button').removeAttr("disabled");
            var check_container = $('.checking_email');
            var check_input = email;
            if (check_input == '') {
                check_container.empty();
                return false;
            }
            check_container.removeClass('text-danger').removeClass('text-primary').html(
                '<span id="loading"> Checking <span>.</span><span>.</span><span>.</span></span>');
            if (check_input.length >= 4) {
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                    },
                    url: "{{url('ajax/check-email')}}",
                    data: "email=" + check_input,
                    success: function(data) {
                        if (data.status == 200) {
                            check_container.html('<i class="fa fa-check"></i> ' + data.message).removeClass(
                                'text-danger').addClass('text-primary');
                            $('.employee_button').removeAttr("disabled");
                        } else if (data.status == 404) {
                            check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass(
                                'text-primary').addClass('text-danger');
                            $('.employee_button').attr("disabled", true);
                        } else if (data.status == 401) {
                            check_container.html('<i class="fa fa-remove"></i> ' + data.message).removeClass(
                                'text-primary').addClass('text-danger');
                            $('.employee_button').attr("disabled", true);
                        }
                    }
                });
            }
        }
    </script>
@endsection('content')