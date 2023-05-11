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
    form{
        position:relative;
    }
    form:after{
        position:absolute;
        height:5px;
        width:100%;
        background:white;
        content:"";
        left:0px;
        bottom:40px;
        z-index:1;
    }
    .line_bootom{
        border-bottom: 1px solid #dfd3d3;
        margin-bottom: 15px;
        margin-top: 15px;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card" id="employee_details">
                    <div class="card-header emp_head_h">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <?php $sql = DB::table('form_engine_categories')->select('id','name','is_multiple')->where('orgnization_id',auth()->user()->id)->get();
                            $sr=1; if(!empty($sql)){ foreach($sql as $row){ $serial = $sr++; $name_id = str_replace(' ', '-', strtolower($row->name));?>
                            <li class="nav-item" style="line-height: 2.1">
                                <a class="nav-link 
                                <?php if(!empty($_GET['page'])){
                                    $page = $_GET['page'];
                                    if($page==$name_id){
                                        echo 'active';
                                    }
                                }else{
                                    if($serial==1){ echo 'active';}
                                }?>" id="<?=$name_id;?>-tab" data-toggle="tab" href="#<?=$name_id;?>" role="tab" aria-controls="<?=$name_id;?>" aria-selected="true"><?=$row->name;?></a>
                            </li>
                            <?php } } ?>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <?php $sr1=1; if(!empty($sql)){ foreach($sql as $row1){ $serial = $sr1++; $name_id1 = str_replace(' ', '-', strtolower($row1->name));?>
                                <div class="tab-pane fade <?php if(!empty($_GET['page'])){
                                    $page = $_GET['page'];
                                    if($page==$name_id1){
                                        echo 'show active';
                                    }
                                }else{
                                    if($serial==1){ echo 'show active';}
                                }?>" id="<?=$name_id1;?>" role="tabpanel" aria-labelledby="<?=$name_id1;?>-tab">
                                <form action="{{url('save-'.$name_id1)}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="forms_name" value="{{$row1->name}}">
                                    <input type="hidden" name="emp_code" value="{{$emp_code}}">
                                    <div class="after-add-more-<?=$name_id1;?>">
                                    <div id="tab_logic_<?=$row1->id;?>">
                                    @php $user_id = Auth::user()->id;@endphp
                                    @php $group = DB::select("SELECT b.group_name FROM `map_form_orgs` as a INNER JOIN form_engines as b on a.form_name=b.form_column WHERE organisation_id=$user_id AND b.form_category_id=$row1->id GROUP BY b.group_name"); @endphp
                                    @if(!empty($group))
                                        @foreach($group as $groups)
                                        <div class="row">
                                            @include('organization.employee.form_engine')
                                        </div>
                                        
                                        <div class="line_bootom"></div>
                                        @endforeach
                                    @endif
                                    <?php if($row1->is_multiple==1){?>
                                        <div class="change-data remove-<?=$name_id1;?>"></div>
                                    <?php } ?>
                                    </div>
                                    
                                    </div>
                                    <?php if($row1->is_multiple==1){?>
                                    <a class="btn btn-success btn-sm add-more-<?=$name_id1;?>">+ Add More</a>
                                    <?php } ?>
                                @if($name_id1 == 'employee-details')
                                <div class="row">
                                      <div class="col-sm-3">
                                        <div class="form-group">
                                          <label style="margin-bottom: 0.5rem;">* Shift Name</label>
                                          <div class="help-block alert" style="padding: 0px;font-size: 12px;"></div>
                                          @php $user_id = Auth::user()->id;@endphp
                                          @php 
                                          $shifts_result= DB::select("SELECT `id`,`shift_name` FROM `shift_masters` WHERE orgnization_id=$user_id");
                                          @endphp 
                                          <select class="form-control" style="width:100%" id="shift_id" name="shift_id" required>
                                            <option>Select Shift</option>
                                            @if(!empty($shifts_result))
                                            @foreach($shifts_result as $shifts_data)
                                            <option value="{{$shifts_data->id}}">{{$shifts_data->shift_name}}</option>
                                            @endforeach
                                            @endif
                                          </select>
                                        </div>
                                      </div>
                                    </div>
                                @endif

                                <?php 
                                if(auth()->user()->id == 29){

                                $seg=Request::segment(1);    
                                if($seg=='add-employeess'){?> 
                                <label><b>Login to Lnxx ?</b></label>
                                <input type="checkbox" value="1" name="lnxx_login"> &nbsp;&nbsp;
                                <?php } } ?>


                                    <button type="submit" class="btn btn-primary btn-sm mr-2 employee_button">Submit</button>
                                </form>
                                </div>
                            <?php } } ?>
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
                    $('#department_id').append('<option value="">--Select--</option>');
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
                    $('#designation_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#designation_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].position_name+'</option>');
                    }
                }
            });
            $('#notice_period_id').empty();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-notice-period')}}",
                data: {
                    office_id: office_id_id,
                    department_id: department_id,
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#notice_period_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#notice_period_id').append('<option value="'+datas[i].notice_days+'" data-id="'+datas[i].id+'">'+datas[i].notice_days+'</option>');
                    }
                }
            });
        }
    </script>
    @endsection('content')