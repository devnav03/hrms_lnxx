@php $form_engine = DB::select("SELECT a.id,b.form_name,b.form_column,b.master_table,a.is_required,b.data_type,b.data_length,b.pattern,b.get_where,b.form_column_id FROM `map_form_orgs` as a INNER JOIN form_engines as b on a.form_name=b.form_column WHERE organisation_id=$user_id AND b.form_category_id=$row1->id AND b.group_name='$groups->group_name' ORDER BY b.order_id ASC");@endphp
@if(!empty($form_engine))
    @foreach($form_engine as $row)
       
        @if(!empty($row->master_table))
        <div class="col-sm-3">
            <div class="form-group">
                <label>{{$row->form_name}}  @if($row->is_required==1) * @endif</label>
                <select class="form-control" style="width:100%" id="{{$row->form_column}}_id" name="{{$row->form_column}}<?php if($row1->is_multiple==1){?>[]<?php } ?>" {{$row->pattern}} @if($row->is_required==1) required @endif>
                    <?php
                    if(!empty($row->get_where)){
                        if($row->get_where == 'orgnization_id'){
                            $select = DB::table($row->master_table)->where($row->get_where,$user_id)->get();
                        }elseif($row->get_where == 'country_id'){
                            $select = DB::table($row->master_table)->where('country_id','IND')->get();
                        }
                    }else{
                        $select = DB::table($row->master_table)->get();
                    }
                    ?>
                    @if(!empty($select))
                        <option value="">--Select--</option>
                        @foreach($select as $drop)
                            <option value="<?php
                            if($row->form_column_id==1){
                                echo @$drop->id;
                            }else{
                                if(!empty($drop->source_name)){
                                    echo @$drop->source_name;
                                }elseif(!empty($drop->position_name)){
                                    echo $drop->position_name;
                                }elseif(!empty($drop->department_name)){
                                    echo $drop->department_name;
                                }elseif(!empty($drop->notice_days)){
                                    echo $drop->notice_days;
                                }elseif(!empty($drop->state_name)){
                                    echo $drop->state_name;
                                }elseif(!empty($drop->city_name)){
                                    echo $drop->city_name;
                                }elseif(!empty($drop->education_title)){
                                    echo $drop->education_title;
                                }elseif(!empty($drop->office_name)){
                                    echo $drop->office_name;
                                }elseif(!empty($drop->name)){
                                    echo $drop->name;
                            }}?>" data-id="<?=$drop->id;?>">
                            @if(!empty($drop->source_name))
                                {{@$drop->source_name}}
                            @elseif(!empty($drop->position_name))
                                {{@$drop->position_name}}
                            @elseif(!empty($drop->department_name))
                                {{@$drop->department_name}}
                            @elseif(!empty($drop->notice_days))
                                {{@$drop->notice_days}}
                            @elseif(!empty($drop->state_name))
                                {{@$drop->state_name}}
                            @elseif(!empty($drop->city_name))
                                {{@$drop->city_name}}
                            @elseif(!empty($drop->education_title))
                                {{@$drop->education_title}}
                            @elseif(!empty($drop->office_name))
                                {{@$drop->office_name}}
                            @elseif(!empty($drop->name))
                                {{@$drop->name}}
                            @endif</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        @if($row->form_name == 'Designation')
        <div class="col-sm-3">
            <div class="form-group">
                <label style="margin-bottom: 0.5rem;">Shift Name *</label>
                <div class="help-block alert" style="padding: 0px;font-size: 12px;"></div>
                    @php $user_id = Auth::user()->id; @endphp
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

        <div class="col-sm-3">
            <div class="form-group">
                <label style="margin-bottom: 0.5rem;">Monthly Salary *</label>
                <div class="help-block alert" style="padding: 0px;font-size: 12px;"></div> 
                    <input type="number" placeholder="Salary" class="form-control" style="width:100%" id="salary" name="salary" required>
                     
            </div>
        </div>

        @endif
        @else
        <?php 
            $emp_info = App\Models\User::select('id')->where('type',2)->orderBy('id', 'desc')->first();
            $array = ['employee_code'];
            $values = '';
            if(in_array($row->form_column,$array)){
                $prifix = App\Models\Setting::select('emp_prifix')->where('orgnization_id',$user_id)->first();
                if(!empty($prifix) && !empty($emp_info)){
                    $empinfo = strtoupper($prifix->emp_prifix).str_pad($emp_info->id+1, 4, "0", STR_PAD_LEFT);
                    $values = "value=".$empinfo."";
                }else{
                    if(!empty($emp_info)){
                        $empinfo = strtoupper(Auth::user()->name).str_pad($emp_info->id+1, 4, "0", STR_PAD_LEFT);
                        $values = "value=".$empinfo."";
                    }else{
                        $empinfo = strtoupper(Auth::user()->name).str_pad(1, 4, "0", STR_PAD_LEFT);
                        $values = "value=".$empinfo."";
                    }
                }
            }
        ?>
        @if($row->data_type=='select')
            <div class="col-sm-3">
                <div class="form-group">
                    <label>{{$row->form_name}} @if($row->is_required==1) * @endif</label>
                    <div class="help-block alert-{{$row->form_column}}" style="font-size: 12px;"></div>
                    <select class="form-control" style="width:100%" id="{{$row->form_column}}_id" name="{{$row->form_column}}<?php if($row1->is_multiple==1){?>[]<?php } ?>" {{$row->pattern}} @if($row->is_required==1) required @endif>
                    </select>
                </div>
            </div>
        @else
            <div class="col-sm-3">
                <div class="form-group">
                    <label>{{$row->form_name}} @if($row->is_required==1) * @endif</label>
                    <input type="{{$row->data_type}}" class="form-control" id="{{$row->form_column}}_id" name="{{$row->form_column}}<?php if($row1->is_multiple==1){?>[]<?php } ?>" {{$values}} placeholder="Enter {{$row->form_name}}" 
                    @if($row->is_required==1) required @endif 
                    @if(!empty($row->data_length)) maxlength="{{$row->data_length}}" @endif
                    <?php if(!empty($row->pattern)){ echo $row->pattern;}?> >
                    @if($row->data_type=='date')
                        @if(!empty($row->pattern))
                        <script>
                            $('#{{$row->form_column}}_id').attr('{{$row->pattern}}', new Date().toISOString().split('T')[0]);
                        </script>
                        @endif
                    @endif
                    <div class="help-block alert-{{$row->form_column}}" style="font-size: 12px;"></div>
                </div>
            </div>
            @endif
        @endif
    @endforeach
@endif