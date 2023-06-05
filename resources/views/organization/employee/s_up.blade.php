<div class="row">
@if(!empty($form_engine))
    @foreach($form_engine as $row)
        @if(!empty($row->master_table))
        <div class="col-sm-3">
            <div class="form-group">
                <label>{{$row->form_name}}  @if($row->is_required==1) * @endif</label>
                <select class="form-control" style="width:100%" id="{{$row->form_column}}_id" name="{{$row->form_column}}<?php if($form_category->is_multiple==1){?>[]<?php } ?>" {{$row->pattern}} @if($row->is_required==1) required @endif>
                    <?php
                    if(!empty($row->get_where)){
                        if($row->get_where == 'orgnization_id'){
                            $select = DB::table($row->master_table)->where($row->get_where,$user_data->id)->get();
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
                                echo $drop->id;
                            }else{
                                if(!empty($drop->source_name)){
                                    echo @$drop->source_name;
                                }elseif(!empty($drop->notice_days)){
                                    echo $drop->notice_days;
                                }elseif(!empty($drop->name)){
                                    echo $drop->name;
                            }}?>" data-id="<?=$drop->id;?>" 
                            @if(in_array($row->form_column,$array_data))
                                @if(!empty($updatedata[$row->form_column])) @if($updatedata[$row->form_column]==$drop->id) selected @endif @endif
                            @else
                                @if(!empty($drop->name))
                                    @if(!empty($updatedata[$row->form_column])) @if($updatedata[$row->form_column]==$drop->name) selected @endif @endif
                                @endif
                                @if(!empty($drop->source_name))
                                    @if(!empty($updatedata[$row->form_column])) @if($updatedata[$row->form_column]==$drop->source_name) selected @endif @endif
                                @endif
                                @if(!empty($drop->notice_days))
                                    @if(!empty($updatedata[$row->form_column])) @if($updatedata[$row->form_column]==$drop->notice_days) selected @endif @endif
                                @endif
                            @endif>
                            @if(!empty($drop->source_name))
                                {{@$drop->source_name}}
                            @elseif(!empty($drop->position_name))
                                {{@$drop->position_name}}
                            @elseif(!empty($drop->department_name))
                                {{@$drop->department_name}}
                            @elseif(!empty($drop->notice_days))
                                {{@$drop->notice_days}}
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
        @else
            @if($row->data_type=='select')
            <div class="col-sm-3">
                <div class="form-group">
                    <label>{{$row->form_name}} @if($row->is_required==1) * @endif</label>
                    <div class="help-block alert-{{$row->form_column}}" style="font-size: 12px;"></div>
                    <select class="form-control" style="width:100%" id="{{$row->form_column}}_id" name="{{$row->form_column}}<?php if($form_category->is_multiple==1){?>[]<?php } ?>" {{$row->pattern}} @if($row->is_required==1) required @endif>
                    @if($row->form_column=='state_name')
                        @if(!empty($updatedata[$row->form_column]))
                            @php $select = DB::table('states')->where('name',$updatedata[$row->form_column])->get(); @endphp
                            @foreach($select as $sel)
                                <option value="{{$sel->name}}" data-id="{{$sel->id}}">{{$sel->name}}</option>
                            @endforeach
                        @endif
                    @endif
                    @if($row->form_column=='city_name')
                        @if(!empty($updatedata[$row->form_column]))
                            @php $select = DB::table('cities')->where('name',$updatedata[$row->form_column])->get(); @endphp
                            @foreach($select as $sel)
                                <option value="{{$sel->name}}" data-id="{{$sel->id}}">{{$sel->name}}</option>
                            @endforeach
                        @endif
                    @endif
                    </select>
                </div>
            </div>
            @else
            <div class="col-sm-3">
                <div class="form-group">
                    <label>{{$row->form_name}} @if($row->is_required==1) * @endif</label>
                    <input type="{{$row->data_type}}" @if($row->form_column=='profile') onchange="loadFile(event)" @endif class="form-control" id="{{$row->form_column}}_id" name="{{$row->form_column}}<?php if($form_category->is_multiple==1){?>[]<?php } ?>" @if(!empty($updatedata[$row->form_column])) value="{{$updatedata[$row->form_column]}}" @endif placeholder="Enter {{$row->form_name}}" 
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
                                <option @if($shift_in->shift_id == $shifts_data->id) selected @endif value="{{$shifts_data->id}}">{{$shifts_data->shift_name}}</option>
                            @endforeach
                        @endif
                    </select>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label style="margin-bottom: 0.5rem;">Monthly Salary *</label>
                <div class="help-block alert" style="padding: 0px;font-size: 12px;"></div> 
                    <input type="number" value="{{ $user_in->salary }}" placeholder="Salary" class="form-control" style="width:100%" id="salary" name="salary" required>
                     
            </div>
        </div>

        @endif

    @endforeach
    @if(!empty($updatedata['profile']))
    <div class="col-sm-3">
        <img id="output" src="{{asset($updatedata['profile'])}}" style="width:80px;height:70px">
    </div>
    @endif
@endif
</div>