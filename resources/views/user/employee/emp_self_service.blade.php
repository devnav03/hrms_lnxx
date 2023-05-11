@extends('layouts.user.app')
@section('content')
<style>
.emp_head_h {
    height: 59px !important;
}
hr {
    margin: 7px -46px 19px;
    border: 0;
    border-top: 1px solid #c9c7c7;
}
@media only screen and (max-width: 768px) {
    #output {
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
@php $array_data=['office_id','department','designation']; @endphp
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Update {{$form_category->name}}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{url('save-emp-updated-profile')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="forms_name" value="{{$form_category->name}}">
                            <input type="hidden" name="emp_code" value="{{$emp_code}}">
                            @if(!empty($employee_info->update_data))
                                @php $updatedata = json_decode($employee_info->update_data,true);@endphp
                            @endif
                            @if($form_category->is_multiple==1)
                                @include('user.employee.m_up')
                            @else
                                @include('user.employee.s_up')
                            @endif
                            <button type="submit" class="btn btn-primary btn-sm mr-2 employee_button">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    function CheckEmail(email) {
        $('.organisation_button').removeAttr("disabled");
        var check_container = $('.alert-email');
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
    @if($form_category->is_multiple==1)
    <script>
    $(document).ready(function() {
        $(".add-more-{{$form_category->id}}").click(function() {
            var html{{$form_category->id}} = $(".after-add-more-{{$form_category->id}}").html();
            $(".after-add-more-{{$form_category->id}}").last().after(html{{$form_category->id}});
            $('.remove-{{$form_category->id}}').html('<i class="fa fa-minus"></i>').addClass("btn btn-danger btn-sm");
            toastr.success("Added {{$form_category->name}}");
        });
        $("body").on("click", ".remove-{{$form_category->id}}", function() {
            toastr.success("removed {{$form_category->name}}");
            $(this).parents("#tab_logic_{{$form_category->id}}").remove();
        });
    });
    </script>
    @endif
    <script>
    function get_country_id() {
        var country_id = $('#country_name_id option:selected').data('id');
        $('#state_name_id').empty();
        $('#city_name_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-state')}}",
            data: {
                country_id: country_id
            },
            success: function(xhr) {
                var datas = xhr.data;
                for (var i = 0; i < datas.length; i++) {
                    $('#state_name_id').append('<option value="' + datas[i].name + '" data-id="' + datas[i]
                        .id + '">' + datas[i].name + '</option>');
                }
            }
        });
    }
    </script>

    <script>
    function get_state_id() {
        var state_id = $('#state_name_id option:selected').data('id');
        $('#city_name_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-city')}}",
            data: {
                state_id: state_id
            },
            success: function(xhr) {
                var datas = xhr.data;
                for (var i = 0; i < datas.length; i++) {
                    $('#city_name_id').append('<option value="' + datas[i].name + '" data-id="' + datas[i]
                        .id + '">' + datas[i].name + '</option>');
                }
            }
        });
    }
    </script>
    <script>
    function get_office_id() {
        var department_id = $('#office_id_id option:selected').data('id');
        $('#department_id').empty();
        $('#designation_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-department-name')}}",
            data: {
                department_id: department_id
            },
            success: function(xhr) {
                var datas = xhr.data;
                $('#department_id').append('<option value="">Select Department</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#department_id').append('<option value="' + datas[i].id + '" data-id="' + datas[i]
                        .id + '">' + datas[i].department_name + '</option>');
                }
            }
        });
    }

    function get_designation() {
        var office_id_id = $('#office_id_id option:selected').data('id');
        var department_id = $('#department_id option:selected').data('id');
        $('#designation_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-designation')}}",
            data: {
                office_id: office_id_id,
                department_id: department_id,
            },
            success: function(xhr) {
                var datas = xhr.data;
                $('#designation_id').append('<option value="">Select Designation</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#designation_id').append('<option value="' + datas[i].id + '" data-id="' + datas[i]
                        .id + '">' + datas[i].position_name + '</option>');
                }
            }
        });
    }
    </script>
    <script>
    var loadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('output');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
    </script>
    @endsection('content')