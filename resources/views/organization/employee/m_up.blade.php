
@if(!empty($updatedata))
@php $count = count(array_values($updatedata)[0]); @endphp
@for($i=0;$i < $count;$i++)
<div class="after-add-more-{{$form_category->id}}">
    <div id="tab_logic_{{$form_category->id}}">
        <div class="row">
            @if(!empty($form_engine))
                @foreach($form_engine as $row)
                    @if(!empty($row->master_table))
                        <div class="col-sm-3">
                            <div class="form-group">
                            <label>{{$row->form_name}}  @if($row->is_required==1) * @endif</label>
                            <select class="form-control" style="width:100%" id="{{$row->form_column}}_id" name="{{$row->form_column}}[]" {{$row->pattern}} @if($row->is_required==1) required @endif>
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
                                            }elseif(!empty($drop->education_title)){
                                                echo $drop->education_title;
                                            }elseif(!empty($drop->name)){
                                                echo $drop->name;
                                        }}?>" data-id="<?=$drop->id;?>" <?php if(!empty($updatedata[$row->form_column][$i])){ if($updatedata[$row->form_column][$i]==@$drop->education_title || $updatedata[$row->form_column][$i]==@$drop->name){ echo 'selected';}} ?>>
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
                                        @elseif(!empty($drop->education_title))
                                            {{@$drop->education_title}}
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
                                <select class="form-control" style="width:100%" id="{{$row->form_column}}_id" name="{{$row->form_column}}[]" {{$row->pattern}} @if($row->is_required==1) required @endif>
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
                                <input type="{{$row->data_type}}" @if($row->form_column=='profile') onchange="loadFile(event)" @endif class="form-control" id="{{$row->form_column}}_id" name="{{$row->form_column}}[]" placeholder="Enter {{$row->form_name}}" value="<?php echo !empty($updatedata[$row->form_column][$i]) ? $updatedata[$row->form_column][$i]:'';?>"
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
        </div>
        @if($form_category->is_multiple==1)
            <div class="change-data remove-{{$form_category->id}} btn btn-danger btn-sm"><i class="fa fa-minus"></i></div>
        @endif
    </div>
</div>
@if($i>0)
    <a class="btn btn-success btn-sm add-more-{{$form_category->id}}">+ Add More</a>
@endif
@endfor
@else
<div class="after-add-more-{{$form_category->id}}">
    <div id="tab_logic_{{$form_category->id}}">
        <div class="row">
            @if(!empty($form_engine))
                @foreach($form_engine as $row)
                    @if(!empty($row->master_table))
                        <div class="col-sm-3">
                            <div class="form-group">
                            <label>{{$row->form_name}}  @if($row->is_required==1) * @endif</label>
                            <select class="form-control" style="width:100%" id="{{$row->form_column}}_id" name="{{$row->form_column}}[]" {{$row->pattern}}>
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
                                            }elseif(!empty($drop->education_title)){
                                                echo $drop->education_title;
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
                                        @elseif(!empty($drop->office_name))
                                            {{@$drop->office_name}}
                                        @elseif(!empty($drop->education_title))
                                            {{@$drop->education_title}}
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
                                <select class="form-control" style="width:100%" id="{{$row->form_column}}_id" name="{{$row->form_column}}[]" {{$row->pattern}}>
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
                                <input type="{{$row->data_type}}" @if($row->form_column=='profile') onchange="loadFile(event)" @endif class="form-control" id="{{$row->form_column}}_id" name="{{$row->form_column}}[]" placeholder="Enter {{$row->form_name}}" value=""
                                @if($row->is_required==1) required @endif 
                                @if(!empty($row->data_length)) maxlength="{{$row->data_length}}" @endif
                                <?php if(!empty($row->pattern)){ echo $row->pattern;}?> >
                                <div class="help-block alert-{{$row->form_column}}" style="font-size: 12px;"></div>
                            </div>
                        </div>
                        @endif
                    @endif
                @endforeach
            @endif
        </div>
        @if($form_category->is_multiple==1)
            <div class="change-data remove-{{$form_category->id}} btn btn-danger btn-sm"><i class="fa fa-minus"></i></div>
        @endif
    </div>
</div>
<a class="btn btn-success btn-sm add-more-{{$form_category->id}}">+ Add More</a>
@endif