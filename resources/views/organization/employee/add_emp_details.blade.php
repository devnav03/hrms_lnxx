@extends('layouts.organization.app')
@section('content')
<style>
    .emp_head_h{
        height: 59px !important;
    }
    hr{
        margin: 7px -46px 19px;
        border: 0;
        border-top: 1px solid #c9c7c7;
    }
    @media only screen and (max-width: 768px) {
        #output{
            width: 4rem !important;
            height: 8rem !important;
            border-radius: 0.25rem !important;
            object-fit: contain !important;
            max-height: 47px !important;
            max-width: 9rem !important;
            margin-top: -32px !important;
            float: right !important;
        }
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <form class="forms-sample" action="{{url('add-employees')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card" id="employee_details">
                        <div class="card-header emp_head_h">
                            <div class="row">
                                <div class="col-md-10">
                                <h5 class="">Employee Information</h5>
                                </div>
                                <div class="col-md-2">
                                    <img id="output" />
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Employee Code</label>
                                        <input type="text" class="form-control" id="emp_code" name="emp_code" placeholder="Employee Code" required>
                                    </div>
                                </div>
                                @if(!empty($form_engine))
                                    @foreach($form_engine as $row)
                                        @if(!empty($row->master_table))
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>{{$row->form_name}}  @if($row->is_required==1) * @endif</label>
                                                <select class="form-control form-control-sm" id="{{$row->form_column}}_id" name="{{$row->form_column}}">
                                                    <?php $var = DB::table($row->master_table)->get();?>
                                                    @if(!empty($var))
                                                        <option value="">Select {{$row->form_name}}</option>
                                                        @foreach($var as $row)
                                                            <option value="{{$row->id}}">
                                                            @if(!empty($row->source_name))
                                                                {{@$row->source_name}}
                                                            @elseif(!empty($row->position_name))
                                                                {{@$row->position_name}}
                                                            @elseif(!empty($row->notice_days))
                                                                {{@$row->notice_days}}
                                                            @endif</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>{{$row->form_name}} @if($row->is_required==1) * @endif</label>
                                                <input type="{{$row->data_type}}" class="form-control" id="{{$row->form_column}}_id" name="{{$row->form_column}}" placeholder="Enter {{$row->form_name}}" 
                                                @if($row->is_required==1) required @endif 
                                                @if(!empty($row->data_length)) maxlength="{{$row->data_length}}" @endif
                                                <?php if(!empty($row->pattern)){ echo $row->pattern;}?> >
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
    <script>
    var loadFile = function(event) {
        document.getElementById('output').setAttribute("style",
            "width: 8rem;height: 8rem;border-radius: 0.25rem;object-fit: contain;max-height: 51px;max-width: 10rem;margin-top: -9px;"
            );
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('output');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
    </script>
    <script>
    $("#next_button").on('click', function() {
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var gender = $('#gender').val();
        var date_of_birth = $('#date_of_birth').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var salary = $('#salary').val();
        var profile_pic = $('#profile_pic').val();
        var source_id = $('#source_id').val();
        var notice_id = $('#notice_id').val();
        var designation_id = $('#designation_id').val();
        if (first_name === '') {
            toastr.error("Please Enter First Name");
        } else if (last_name === '') {
            toastr.error("Please Enter Last Name");
        } else if (gender === '') {
            toastr.error("Please Enter Gender");
        } else if (date_of_birth === '') {
            toastr.error("Please Enter Date Of Birth");
        } else if (email === '') {
            toastr.error("Please Enter Email");
        } else if (password === '') {
            toastr.error("Please Enter Password");
        } else if (salary === '') {
            toastr.error("Please Enter Salary");
        } else if (profile_pic === '') {
            toastr.error("Please Enter Profile Pic");
        } else if (source_id === '') {
            toastr.error("Please Select Source Name");
        } else if (notice_id === '') {
            toastr.error("Please Select Notice");
        } else if (designation_id === '') {
            toastr.error("Please Select Designation");
        } else {
            $('#employee_details').hide();
            $('#employee_contact').show();
        }
    });
    $("#next_button_contact").on('click', function() {
        var mobile = $('#mobile').val();
        var state_id = $('#state_id').val();
        var city_id = $('#city_id').val();
        var address = $('#address').val();
        var pincode = $('#pincode').val();

        if (mobile === '') {
            toastr.error("Please Enter Mobile No");
        } else if (state_id === '') {
            toastr.error("Please Enter State");
        } else if (city_id === '') {
            toastr.error("Please Enter City");
        } else if (address === '') {
            toastr.error("Please Enter Address");
        } else if (pincode === '') {
            toastr.error("Please Enter Pincode");
        } else {
            $('#employee_details').hide();
            $('#employee_contact').hide();
            $('#employee_education').show();
        }
    });
    $("#back_button_contact").on('click', function() {
        $('#employee_details').show();
        $('#employee_contact').hide();
    });
    $("#back_button_education").on('click', function() {
        $('#employee_contact').show();
        $('#employee_education').hide();
    });
    $("#next_button_education").on('click', function() {
        $('#employee_education').hide();
        $('#employee_bank').show();
    });
    $("#next_button_bank").on('click', function() {
        $('#employee_bank').hide();
        $('#employee_company').show();
    });
    $("#back_button_bank").on('click', function() {
        $('#employee_education').show();
        $('#employee_bank').hide();
    });
    $("#back_button_company").on('click', function() {
        $('#employee_bank').show();
        $('#employee_company').hide();
    });
    $("#next_button_company").on('click', function() {
        $('#employee_company').hide();
        $('#employee_document').show();
    });
    $("#back_button_document").on('click', function() {
        $('#employee_company').show();
        $('#employee_document').hide();
    });

    function get_state_id(id) {
        $('#city_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-city')}}",
            data: {
                state_id: id
            },
            success: function(xhr) {
                var datas = xhr.data;
                for (var i = 0; i < datas.length; i++) {
                    $('#city_id').append('<option value="' + datas[i].cityID + '">' + datas[i].cityName +
                        '</option>');
                }
            }
        });
    }
    </script>
    <script>
    $('.multi-field-wrapper').each(function() {
        var $wrapper = $('.multi-fields', this);
        $(".add-field", $(this)).click(function(e) {
            $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('')
                .focus();
        });
        $('.multi-field .remove-field', $wrapper).click(function() {
            if ($('.multi-field', $wrapper).length > 1)
                $(this).parent('.multi-field').remove();
        });
    });
    </script>
    <script>
        $('.multi-field-company').each(function() {
            var $wrapper = $('.multi-fields-company', this);
            $(".add-field-company", $(this)).click(function(e) {
                $('.multi-field-company:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
            });
            $('.multi-field-company .remove-field-company', $wrapper).click(function() {
                if ($('.multi-field-company', $wrapper).length > 1)
                    $(this).parent('.multi-field-company').remove();
            });
        });
    </script>
    <script>
        $('.multi-field-document').each(function() {
            var $wrapper = $('.multi-fields-document', this);
            $(".add-field-document", $(this)).click(function(e) {
                $('.multi-field-document:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
            });
            $('.multi-field-document .remove-field-document', $wrapper).click(function() {
                if ($('.multi-field-document', $wrapper).length > 1)
                    $(this).parent('.multi-field-document').remove();
            });
        });
    </script>
    @endsection('content')