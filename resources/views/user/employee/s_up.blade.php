<div class="row">
@if(!empty($form_engine))
    @foreach($form_engine as $row)
        @if(!empty($row->master_table))
        <div class="col-sm-3">
            <div class="form-group">
                <label>{{$row->form_name}}  @if($row->is_required==1) * @endif</label>
                <select class="form-control" style="width:100%" id="{{$row->form_column}}_id" name="{{$row->form_column}}" {{$row->pattern}} @if($row->editable==0) readonly @endif>
                    <?php
                    if(!empty($row->get_where)){
                        if($row->get_where == 'orgnization_id'){
                            $select = DB::table($row->master_table)->where($row->get_where,$user_data->organisation_id)->get();
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
                            @if(isset($updatedata))
                                @if($updatedata[$row->form_column]==$drop->id) selected @endif
                            @endif     
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
                    <select class="form-control" style="width:100%" id="{{$row->form_column}}_id" name="{{$row->form_column}}"  {{$row->pattern}} @if($row->editable==0) readonly @endif>
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
                    @if($row->data_type=='file')
                        <span class="{{$row->form_column}}_id"><input type="hidden" name="{{$row->form_column}}" @if(!empty($updatedata[$row->form_column])) value="{{$updatedata[$row->form_column]}}" @endif></span>
                    @endif
                    <input type="{{$row->data_type}}" @if($row->form_column=='profile') onchange="loadFile(event)" @endif class="form-control" id="{{$row->form_column}}_id" name="{{$row->form_column}}" @if(!empty($updatedata[$row->form_column])) value="{{$updatedata[$row->form_column]}}" @endif placeholder="Enter {{$row->form_name}}" 
                    @if($row->data_type!='file') @if($row->is_required==1) required @endif @endif 
                    @if(!empty($row->data_length)) maxlength="{{$row->data_length}}" @endif
                    <?php if(!empty($row->pattern)){ echo $row->pattern;}?> @if($row->editable== 1) readonly @endif>
                    @if($row->data_type=='date')
                        @if(!empty($row->pattern))
                        <script>
                            $('#{{$row->form_column}}_id').attr('{{$row->pattern}}', new Date().toISOString().split('T')[0]);
                        </script>
                        @endif
                    @endif
                    <div class="help-block alert-{{$row->form_column}}" style="font-size: 12px;"></div>
                    @if($row->data_type=='file') @if(!empty($updatedata[$row->form_column])) <a href="{{asset($updatedata[$row->form_column])}}" class="download-link" download="">Download</a> @endif @endif
                    <script>
                        $(document).ready(function() {
                            $('#{{$row->form_column}}_id').change(function() {
                                $('.{{$row->form_column}}_id').empty();
                            });
                        });
                    </script>
                </div>
            </div>
            @endif
        @endif
    @endforeach
    @if(!empty($updatedata['profile']))
    <div class="col-sm-3">
        <img id="output" src="{{asset($updatedata['profile'])}}" style="width:80px;height:70px">
    </div>
    @endif
@endif
</div>