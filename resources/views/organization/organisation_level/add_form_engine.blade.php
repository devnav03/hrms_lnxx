@extends('layouts.organization.app')
@section('content')
<?php
    $users = auth()->user();
?>
<style>
    .required-font{
        font-size: 0.9rem !important;
    }
    .check-font{
        font-size: 0.9rem !important;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <form class="forms-sample" action="{{url('save-form-engine')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card" id="employee_details">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-10">
                                <h5 class="">Form Engine</h5>
                                </div>
                                <div class="col-md-2">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <label class="check-font"><input type="checkbox" id="select_all" class=""> <b>Select all</b></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                            <?php 
                                if(!empty($updt)){
                                    foreach($updt as $update){
                                        $datas[]    =$update->form_name;
                                        $datas1[]   =$update->required_name;
                                    }
                                }
                                $sr=0;
                                $sql = DB::table('form_engine_categories')->select('id','name','is_multiple')->where('orgnization_id',$users->id)->get();
                                if(!empty($sql)){ foreach($sql as $row){?>
                                    <div class="col-sm-12 mb-3">
                                        <div class="card-header">
                                            <strong><?=$row->name;?></strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                            <?php $group = DB::table('form_engines')->select('group_name')->where('orgnization_id',Auth::user()->id)->where('form_category_id',$row->id)->groupBy('group_name')->get();
                                            if(!empty($group)){
                                                foreach($group as $groups){ ?>
                                                    <div class="col-sm-12 mt-2" style="border-bottom:1px solid grey;">
                                                        <div class="row">
                                                            <?php 
                                                            $tables = DB::table('form_engines')->select('id','form_name','form_column','group_name')->where('orgnization_id',Auth::user()->id)->where('form_category_id',$row->id)->where('group_name',$groups->group_name)->orderBy('group_name', 'ASC')->orderBy('id', 'ASC')->get();
                                                            foreach($tables as $rows){ $serial = $sr++; ?>
                                                                <div class="col-sm-4 mb-3">
                                                                    <label style="font-weight: 600;">{{$rows->form_name}}</label>
                                                                    <br/>
                                                                    <label class="check-font"><input name="yes[]" type="checkbox" class="checkbox cls-{{$rows->form_column}}" value="{{$rows->form_column}}"
                                                                    <?php if(!empty($datas)){ if(in_array($rows->form_column,$datas)){ echo 'checked'; } }?> /> Yes</label>&nbsp;&nbsp;&nbsp;
                                                                    <label class="{{$rows->form_column}}-required-font" 
                                                                    <?php if(!empty($datas1)){ if(in_array($rows->form_column.'_required', @$datas1)){ echo ''; } }else{ echo 'style="display:none"';}?> ><input name="{{$rows->form_column}}" type="checkbox" class="checkbox" value="{{$rows->form_column}}" <?php echo @CheckRequired($users,$rows->form_column);?>/> Required</label>&nbsp;&nbsp;&nbsp;
                                                                    <label class="{{$rows->form_column}}-editable-font" 
                                                                    <?php if(!empty($datas1)){ if(in_array($rows->form_column.'_editable', @$datas1)){ echo ''; } }else{ echo 'style="display:none"';}?> ><input name="{{$rows->form_column}}_editable" type="checkbox" class="checkbox" value="{{$rows->form_column}}" <?php echo @CheckEditable($users,$rows->form_column)?> /> Editable</label>
                                                                </div>
                                                            <?php }?>
                                                        </div>
                                                    </div>
                                                <?php }
                                            } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } } ?>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
    <?php $tablesx = DB::table('form_engines')->select('form_column')->where('orgnization_id',$users->id)->orderBy('group_name', 'ASC')->get();?>
    @if(!empty($tablesx))
        @foreach($tablesx as $rowata)
        <script type="text/javascript">
            $('#select_all').on('click',function(){
                if(this.checked){
                    $('.checkbox').each(function(){
                        this.checked = true;
                        $('.{{$rowata->form_column}}-required-font').show();
                        $('.{{$rowata->form_column}}-editable-font').show();
                    });
                }else{
                    $('.checkbox').each(function(){
                        this.checked = false;
                        $('.{{$rowata->form_column}}-required-font').hide();
                        $('.{{$rowata->form_column}}-editable-font').hide();
                    });
                }
            });
            $('.cls-{{$rowata->form_column}}').on('click', function(){
                if($(this).hasClass("checked")){
                    $(this).removeClass('checked');
                    $('.{{$rowata->form_column}}-required-font').hide();
                    $('.{{$rowata->form_column}}-editable-font').hide();
                }else{
                    $(this).addClass('checked');
                    $('.{{$rowata->form_column}}-required-font').show();
                    $('.{{$rowata->form_column}}-editable-font').show();
                }
            });
        </script>
        @endforeach
    @endif
    <script type="text/javascript">
        $(document).ready(function(){
            $('.checkbox').on('click',function(){
                if($('.checkbox:checked').length == $('.checkbox').length){
                    $('#select_all').prop('checked',true);
                }else{
                    $('#select_all').prop('checked',false);
                }
            });
        });
    </script>
    <?php 
    function CheckEditable($users,$form_name){
        $form = DB::table('map_form_orgs')->select('editable')->where('organisation_id',$users->id)->where('form_name',$form_name)->first();
        if(!empty($form->editable)){
            return 'checked';
        }else{
            return ;
        }
    }
    function CheckRequired($users,$form_name){
        $form = DB::table('map_form_orgs')->select('is_required')->where('organisation_id',$users->id)->where('form_name',$form_name)->first();
        if(!empty($form->is_required)){
            return 'checked';
        }else{
            return ;
        }
    }
    ?>
    @endsection('content')