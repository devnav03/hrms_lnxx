@extends('layouts.user.app')
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
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class=""><?=$formcat->name;?>
                        <?php $name_id1 = str_replace(' ', '-', strtolower($formcat->name));?>
                    </h5>
                    </div>
                    <div class="card-body">
                    <div class="row">
                    <?php
                    if(!empty($catdata[0])){
                        $user_id = $organisation->user_id;
                        $form_engine = DB::select("SELECT a.id,b.form_name,b.form_column,b.master_table,a.is_required,a.editable,b.data_type,b.data_length,b.pattern,b.get_where,b.form_column_id FROM `map_form_orgs` as a INNER JOIN form_engines as b on a.form_name=b.form_column WHERE organisation_id=$user_id AND b.form_category_id=$formcat->id ORDER BY b.id ASC");
                        if(!empty($form_engine)){
                            foreach($form_engine as $row){
                                $inputvalue = $catdata[0];
                                if(in_array($row->form_column,array_keys($catdata[0]))){
                                    if($row->data_type=='select'){ ?>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>{{$row->form_name}} @if($row->is_required==1) * @endif</label>
                                            <div class="help-block alert-{{$row->form_column}}" style="font-size: 12px;"></div>
                                            <select class="form-control" style="width:100%" id="{{$row->form_column}}_id" name="{{$row->form_column}}<?php if($formcat->is_multiple==1){?>[]<?php } ?>" {{$row->pattern}}>
                                            </select>
                                        </div>
                                    </div>
                                    <?php }else{ ?>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>{{$row->form_name}}  @if($row->is_required==1) * @endif</label>
                                            <input type="{{$row->data_type}}" class="form-control" id="{{$row->form_column}}_id" name="{{$row->form_column}}<?php if($formcat->is_multiple==1){?>[]<?php } ?>" placeholder="Enter {{$row->form_name}}" value="<?=$inputvalue[$row->form_column];?>"
                                            @if($row->is_required==1) required @endif @if($row->editable!=1) readonly @endif
                                            @if(!empty($row->data_length)) maxlength="{{$row->data_length}}" @endif
                                            <?php if(!empty($row->pattern)){ echo $row->pattern;}?> >
                                            <div class="help-block alert-{{$row->form_column}}" style="font-size: 12px;"></div>
                                        </div>
                                    </div>
                                <?php } }
                            }
                        }
                    }else{ ?>
                        <div class="col-sm-12">
                            <h5 class="">( <?=$formcat->name;?> ) have no data to show</h5>
                        </div>
                    <?php } ?>
                    </div>
                    </div>
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
    <script>
        <?php if(!empty($sql)){ foreach($sql as $row2){ if($row2->is_multiple==1){  $removename = str_replace(' ', '-', strtolower($row2->name))?>
        $(document).ready(function() {
            $(".add-more-<?=$removename;?>").click(function(){   
                var html<?=$row2->id;?> = $(".after-add-more-<?=$removename;?>").html();  
                $(".after-add-more-<?=$removename;?>").last().after(html<?=$row2->id;?>);  
                $('.remove-<?=$removename;?>').html('<i class="fa fa-minus"></i>').addClass("btn btn-danger btn-sm");
                toastr.success("Added <?=$row2->name;?>");
            });
            $("body").on("click",".remove-<?=$removename;?>",function(){ 
                toastr.success("removed <?=$row2->name;?>");
                $(this).parents("#tab_logic_<?=$row2->id;?>").remove();
            });
        });
        <?php } } } ?> 
    </script>
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
                        $('#state_name_id').append('<option value="'+datas[i].name+'" data-id="'+datas[i].id+'">'+datas[i].name+'</option>');
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
                        $('#city_name_id').append('<option value="'+datas[i].name+'" data-id="'+datas[i].id+'">'+datas[i].name+'</option>');
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
                        $('#department_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].department_name+'</option>');
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
                        $('#designation_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].position_name+'</option>');
                    }
                }
            });
        }
    </script>
@endsection('content')