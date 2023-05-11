@extends('layouts.organization.app')
@section('content')
<style>
    .holiday{
        background: #eee;
        text-align: center;
        font-weight: 500;
        line-height: 1.2;
        padding:0.5rem;
    }
    .shift-ty{
        background: #eee;
        text-align: center;
        font-weight: 500;
        line-height: 1.2;
        padding:0.5rem;
    }
    .table-bordered{
        border: 1px solid #dee2e6 !important;
    }
    .table-bordered th, .table-bordered td {
        border: 1px solid #dee2e6;
    }
    .table thead th {
        vertical-align: bottom;
        border-bottom: 1px solid #dee2e6;
        padding:0.75rem;
    }
    .table tbody td {
        padding:0.75rem;
    }
    .thead-col{
        background-color: #80808029;
    }
    .holid-check{
        position: absolute;
        margin: -1px 0px 0px 4px !important;
    }
    .form-check-label{
        font-size: 1rem;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Shift</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{url('add-shift')}}" method="POST">
                            @csrf
                            <div class="row">
                               <!--  <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Shift Pattern *</label>
                                        <select class="form-control" name="shift_type" onchange="get_shift_type(this.value)" required>
                                            <option value="">--Select--</option>
                                            <option value="Daily" <?php //if(!empty($update->shift_type)){ if($update->shift_type=='Daily'){ echo 'selected';}}?>>Daily</option>
                                            <option value="Weekly" <?php// if(!empty($update->shift_type)){ if($update->shift_type=='Weekly'){ echo 'selected';}}?>>Weekly</option>
                                            <option value="Flexible" <?php //if(!empty($update->shift_type)){ if($update->shift_type=='Flexible'){ echo 'selected';}}?>>Flexible</option>
                                        </select>
                                    </div>
                                </div> -->
                                <input type="hidden" value="Daily" name="shift_type">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Shift Name *</label>
                                        <input type="text" class="form-control" id="shift_name" name="shift_name" placeholder="Enter Shift Name" value="<?php echo !empty($update->shift_name)? $update->shift_name:'';?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <h5 class="holiday">Holidays</h5>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead class="thead-col">
                                            <tr>
                                                <th class="text-center">Select Holidays</th>
                                                <th class="text-center">Week of Month</th>
                                            </tr>
                                        </thead>
  <?php $week_of_month=['First','Second','Third','Fourth','Fifth'];
                                        $count = count($week_of_month);?>                                       
@if($shift_update)
<tbody>
  <?php
    $days = json_decode($update->holidays);
    $monday = [];
if($days) {
    if(isset($days[0]->MONDAY)) {
    $monday = $days[0]->MONDAY;
    } else if(isset($days[1]->MONDAY)) {
    $monday = $days[1]->MONDAY; 
    } else if(isset($days[2]->MONDAY)) {
    $monday = $days[2]->MONDAY;
    } else if(isset($days[3]->MONDAY)) {
    $monday = $days[3]->MONDAY;
    } else if(isset($days[4]->MONDAY)) {
    $monday = $days[4]->MONDAY;
    } else if(isset($days[5]->MONDAY)) {
    $monday = $days[5]->MONDAY;
    } else if(isset($days[6]->MONDAY)) {
    $monday = $days[6]->MONDAY;
    }
}

?> 
   <tr>
      <td>
         <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-7">
               <label class="form-check-label holid-check"><input name="days[]" type="checkbox" value="MONDAY" class="checkbox_1 1-Mon" @if(count($monday) != 0)) checked="" @endif id="1-Mon">MONDAY</label>
            </div>
         </div>
      </td>
      <td class="text-center">
         <div>
            <span class="mx-2"><label class="form-check-label"><input type="checkbox" name="MONDAY[]" value="First" class="checkboxsecond1 1_Mon" id="1_Mon_1" @if(count($monday) != 0)) @if(in_array('First', $monday)) checked="" @endif @else  disabled="disabled" @endif > First</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="MONDAY[]" value="Second" class="checkboxsecond1 1_Mon" id="1_Mon_2" @if(count($monday) != 0)) @if(in_array('Second', $monday)) checked="" @endif @else  disabled="disabled" @endif > Second</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="MONDAY[]" value="Third" class="checkboxsecond1 1_Mon" id="1_Mon_3" @if(count($monday) != 0)) @if(in_array('Third', $monday)) checked="" @endif @else  disabled="disabled" @endif > Third</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="MONDAY[]" value="Fourth" class="checkboxsecond1 1_Mon" id="1_Mon_4" @if(count($monday) != 0)) @if(in_array('Fourth', $monday)) checked="" @endif @else  disabled="disabled" @endif > Fourth</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="MONDAY[]" value="Fifth" class="checkboxsecond1 1_Mon" id="1_Mon_5" @if(count($monday) != 0)) @if(in_array('Fifth', $monday)) checked="" @endif @else  disabled="disabled" @endif > Fifth</label>
            </span>
         </div>
      </td>
   </tr>
<?php
    $days = json_decode($update->holidays);
    $tuesday = [];
if($days) {
    if(isset($days[0]->TUESDAY)) {
    $tuesday = $days[0]->TUESDAY;
    } else if(isset($days[1]->TUESDAY)) {
    $tuesday = $days[1]->TUESDAY; 
    } else if(isset($days[2]->TUESDAY)) {
    $tuesday = $days[2]->TUESDAY;
    } else if(isset($days[3]->TUESDAY)) {
    $tuesday = $days[3]->TUESDAY;
    } else if(isset($days[4]->TUESDAY)) {
    $tuesday = $days[4]->TUESDAY;
    } else if(isset($days[5]->TUESDAY)) {
    $tuesday = $days[5]->TUESDAY;
    } else if(isset($days[6]->TUESDAY)) {
    $tuesday = $days[6]->TUESDAY;
    }
}

?> 
   <tr>
      <td>
         <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-7">
               <label class="form-check-label holid-check"><input name="days[]" type="checkbox" value="TUESDAY" class="checkbox_2 2-Tue" @if(count($tuesday) != 0)) checked="" @endif id="2-Tue">
               TUESDAY</label>
            </div>
         </div>
      </td>
      <td class="text-center">
         <div>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="TUESDAY[]" value="First" class="checkboxsecond2 2_Tue" id="2_Tue_1" @if(count($tuesday) != 0)) @if(in_array('First', $tuesday)) checked="" @endif @else  disabled="disabled" @endif > First</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="TUESDAY[]" value="Second" class="checkboxsecond2 2_Tue" id="2_Tue_2" @if(count($tuesday) != 0)) @if(in_array('Second', $tuesday)) checked="" @endif @else  disabled="disabled" @endif > Second</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="TUESDAY[]" value="Third" class="checkboxsecond2 2_Tue" id="2_Tue_3" @if(count($tuesday) != 0)) @if(in_array('Third', $tuesday)) checked="" @endif @else  disabled="disabled" @endif > Third</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="TUESDAY[]" value="Fourth" class="checkboxsecond2 2_Tue" id="2_Tue_4" @if(count($tuesday) != 0)) @if(in_array('Fourth', $tuesday)) checked="" @endif @else  disabled="disabled" @endif > Fourth</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="TUESDAY[]" value="Fifth" class="checkboxsecond2 2_Tue" id="2_Tue_5" @if(count($tuesday) != 0)) @if(in_array('Fifth', $tuesday)) checked="" @endif @else  disabled="disabled" @endif > Fifth</label>
            </span>
         </div>
      </td>
   </tr>
<?php
    $days = json_decode($update->holidays);
    $wednesday = [];
if($days) {
    if(isset($days[0]->WEDNESDAY)) {
    $wednesday = $days[0]->WEDNESDAY;
    } else if(isset($days[1]->WEDNESDAY)) {
    $wednesday = $days[1]->WEDNESDAY; 
    } else if(isset($days[2]->WEDNESDAY)) {
    $wednesday = $days[2]->WEDNESDAY;
    } else if(isset($days[3]->WEDNESDAY)) {
    $wednesday = $days[3]->WEDNESDAY;
    } else if(isset($days[4]->WEDNESDAY)) {
    $wednesday = $days[4]->WEDNESDAY;
    } else if(isset($days[5]->WEDNESDAY)) {
    $wednesday = $days[5]->WEDNESDAY;
    } else if(isset($days[6]->WEDNESDAY)) {
    $wednesday = $days[6]->WEDNESDAY;
    }
}

?>   
   <tr>
      <td>
         <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-7">
               <label class="form-check-label holid-check"><input name="days[]" type="checkbox" value="WEDNESDAY" class="checkbox_3 3-Wed" @if(count($wednesday) != 0)) checked="" @endif id="3-Wed">
               WEDNESDAY</label>
            </div>
         </div>
      </td>
      <td class="text-center">
         <div>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="WEDNESDAY[]" value="First" class="checkboxsecond3 3_Wed" id="3_Wed_1" @if(count($wednesday) != 0)) @if(in_array('First', $wednesday)) checked="" @endif @else  disabled="disabled" @endif > First</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="WEDNESDAY[]" value="Second" class="checkboxsecond3 3_Wed" id="3_Wed_2" @if(count($wednesday) != 0)) @if(in_array('Second', $wednesday)) checked="" @endif @else  disabled="disabled" @endif > Second</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="WEDNESDAY[]" value="Third" class="checkboxsecond3 3_Wed" id="3_Wed_3" @if(count($wednesday) != 0)) @if(in_array('Third', $wednesday)) checked="" @endif @else  disabled="disabled" @endif > Third</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="WEDNESDAY[]" value="Fourth" class="checkboxsecond3 3_Wed" id="3_Wed_4" @if(count($wednesday) != 0)) @if(in_array('Fourth', $wednesday)) checked="" @endif @else  disabled="disabled" @endif > Fourth</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="WEDNESDAY[]" value="Fifth" class="checkboxsecond3 3_Wed" id="3_Wed_5" @if(count($wednesday) != 0)) @if(in_array('Fifth', $wednesday)) checked="" @endif @else  disabled="disabled" @endif > Fifth</label>
            </span>
         </div>
      </td>
   </tr>
<?php
    $days = json_decode($update->holidays);
    $thursday = [];
if($days) {
    if(isset($days[0]->THURSDAY)) {
    $thursday = $days[0]->THURSDAY;
    } else if(isset($days[1]->THURSDAY)) {
    $thursday = $days[1]->THURSDAY; 
    } else if(isset($days[2]->THURSDAY)) {
    $thursday = $days[2]->THURSDAY;
    } else if(isset($days[3]->THURSDAY)) {
    $thursday = $days[3]->THURSDAY;
    } else if(isset($days[4]->THURSDAY)) {
    $thursday = $days[4]->THURSDAY;
    } else if(isset($days[5]->THURSDAY)) {
    $thursday = $days[5]->THURSDAY;
    } else if(isset($days[6]->THURSDAY)) {
    $thursday = $days[6]->THURSDAY;
    }
}

?>   
   <tr>
      <td>
         <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-7">
               <label class="form-check-label holid-check"><input name="days[]" type="checkbox" value="THURSDAY" class="checkbox_4 4-Thu" @if(count($thursday) != 0)) checked="" @endif id="4-Thu">
               THURSDAY</label>
            </div>
         </div>
      </td>
      <td class="text-center">
         <div>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="THURSDAY[]" value="First" class="checkboxsecond4 4_Thu" id="4_Thu_1" @if(count($thursday) != 0)) @if(in_array('First', $thursday)) checked="" @endif @else  disabled="disabled" @endif > First</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="THURSDAY[]" value="Second" class="checkboxsecond4 4_Thu" id="4_Thu_2" @if(count($thursday) != 0)) @if(in_array('Second', $thursday)) checked="" @endif @else  disabled="disabled" @endif > Second</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="THURSDAY[]" value="Third" class="checkboxsecond4 4_Thu" id="4_Thu_3" @if(count($thursday) != 0)) @if(in_array('Third', $thursday)) checked="" @endif @else  disabled="disabled" @endif > Third</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="THURSDAY[]" value="Fourth" class="checkboxsecond4 4_Thu" id="4_Thu_4" @if(count($thursday) != 0)) @if(in_array('Fourth', $thursday)) checked="" @endif @else  disabled="disabled" @endif > Fourth</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="THURSDAY[]" value="Fifth" class="checkboxsecond4 4_Thu" id="4_Thu_5" @if(count($thursday) != 0)) @if(in_array('Fifth', $thursday)) checked="" @endif @else  disabled="disabled" @endif > Fifth</label>
            </span>
         </div>
      </td>
   </tr>
<?php
    $days = json_decode($update->holidays);
    $friday = [];
if($days) {
    if(isset($days[0]->FRIDAY)) {
    $friday = $days[0]->FRIDAY;
    } else if(isset($days[1]->FRIDAY)) {
    $friday = $days[1]->FRIDAY; 
    } else if(isset($days[2]->FRIDAY)) {
    $friday = $days[2]->FRIDAY;
    } else if(isset($days[3]->FRIDAY)) {
    $friday = $days[3]->FRIDAY;
    } else if(isset($days[4]->FRIDAY)) {
    $friday = $days[4]->FRIDAY;
    } else if(isset($days[5]->FRIDAY)) {
    $friday = $days[5]->FRIDAY;
    } else if(isset($days[6]->FRIDAY)) {
    $friday = $days[6]->FRIDAY;
    }
}

?>

   <tr>
      <td>
         <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-7">
               <label class="form-check-label holid-check"><input name="days[]" type="checkbox" value="FRIDAY" class="checkbox_5 5-Fri" @if(count($friday) != 0)) checked="" @endif id="5-Fri">
               FRIDAY</label>
            </div>
         </div>
      </td>
      <td class="text-center">
         <div>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="FRIDAY[]" value="First" class="checkboxsecond5 5_Fri" id="5_Fri_1" @if(count($friday) != 0)) @if(in_array('First', $friday)) checked="" @endif @else  disabled="disabled" @endif > First</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="FRIDAY[]" value="Second" class="checkboxsecond5 5_Fri" id="5_Fri_2" @if(count($friday) != 0)) @if(in_array('Second', $friday)) checked="" @endif @else  disabled="disabled" @endif > Second</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="FRIDAY[]" value="Third" class="checkboxsecond5 5_Fri" id="5_Fri_3" @if(count($friday) != 0)) @if(in_array('Third', $friday)) checked="" @endif @else  disabled="disabled" @endif > Third</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="FRIDAY[]" value="Fourth" class="checkboxsecond5 5_Fri" id="5_Fri_4" @if(count($friday) != 0)) @if(in_array('Fourth', $friday)) checked="" @endif @else  disabled="disabled" @endif > Fourth</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="FRIDAY[]" value="Fifth" class="checkboxsecond5 5_Fri" id="5_Fri_5" @if(count($friday) != 0)) @if(in_array('Fifth', $friday)) checked="" @endif @else  disabled="disabled" @endif > Fifth</label>
            </span>
         </div>
      </td>
   </tr>
<?php
    $days = json_decode($update->holidays);
    $saturday = [];
if($days) {
    if(isset($days[0]->SATURDAY)) {
    $saturday = $days[0]->SATURDAY;
    } else if(isset($days[1]->SATURDAY)) {
    $saturday = $days[1]->SATURDAY; 
    } else if(isset($days[2]->SATURDAY)) {
    $saturday = $days[2]->SATURDAY;
    } else if(isset($days[3]->SATURDAY)) {
    $saturday = $days[3]->SATURDAY;
    } else if(isset($days[4]->SATURDAY)) {
    $saturday = $days[4]->SATURDAY;
    } else if(isset($days[5]->SATURDAY)) {
    $saturday = $days[5]->SATURDAY;
    } else if(isset($days[6]->SATURDAY)) {
    $saturday = $days[6]->SATURDAY;
    }
}

?>

   <tr>
      <td>
         <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-7">
               <label class="form-check-label holid-check"><input name="days[]" type="checkbox" value="SATURDAY" class="checkbox_6 6-Sat" @if(count($saturday) != 0)) checked="" @endif id="6-Sat">
               SATURDAY</label>
            </div>
         </div>
      </td>
      <td class="text-center">
         <div>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="SATURDAY[]" value="First" class="checkboxsecond6 6_Sat" id="6_Sat_1" @if(count($saturday) != 0)) @if(in_array('First', $saturday)) checked="" @endif @else  disabled="disabled" @endif > First</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="SATURDAY[]" value="Second" class="checkboxsecond6 6_Sat" id="6_Sat_2" @if(count($saturday) != 0)) @if(in_array('Second', $saturday)) checked="" @endif @else  disabled="disabled" @endif > Second</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="SATURDAY[]" value="Third" class="checkboxsecond6 6_Sat" id="6_Sat_3" @if(count($saturday) != 0)) @if(in_array('Third', $saturday)) checked="" @endif @else  disabled="disabled" @endif > Third</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="SATURDAY[]" value="Fourth" class="checkboxsecond6 6_Sat" id="6_Sat_4" @if(count($saturday) != 0)) @if(in_array('Fourth', $saturday)) checked="" @endif @else  disabled="disabled" @endif > Fourth</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="SATURDAY[]" value="Fifth" class="checkboxsecond6 6_Sat" id="6_Sat_5" @if(count($saturday) != 0)) @if(in_array('Fifth', $saturday)) checked="" @endif @else  disabled="disabled" @endif > Fifth</label>
            </span>
         </div>
      </td>
   </tr>

<?php
    $days = json_decode($update->holidays);
    $sunday = [];
if($days) {
    if(isset($days[0]->SUNDAY)) {
    $sunday = $days[0]->SUNDAY;
    } else if(isset($days[1]->SUNDAY)) {
    $sunday = $days[1]->SUNDAY; 
    } else if(isset($days[2]->SUNDAY)) {
    $sunday = $days[2]->SUNDAY;
    } else if(isset($days[3]->SUNDAY)) {
    $sunday = $days[3]->SUNDAY;
    } else if(isset($days[4]->SUNDAY)) {
    $sunday = $days[4]->SUNDAY;
    } else if(isset($days[5]->SUNDAY)) {
    $sunday = $days[5]->SUNDAY;
    } else if(isset($days[6]->SUNDAY)) {
    $sunday = $days[6]->SUNDAY;
    }
}

?>

   <tr>
      <td>
         <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-7">
               <label class="form-check-label holid-check"><input name="days[]" type="checkbox" value="SUNDAY" class="checkbox_7 7-Sun" @if(count($sunday) != 0)) checked="" @endif id="7-Sun">
               SUNDAY</label>
            </div>
         </div>
      </td>

      <td class="text-center">
         <div>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="SUNDAY[]" value="First" class="checkboxsecond7 7_Sun" id="7_Sun_1" @if(count($sunday) != 0)) @if(in_array('First', $sunday)) checked="" @endif @else  disabled="disabled" @endif > First</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="SUNDAY[]" value="Second" class="checkboxsecond7 7_Sun" id="7_Sun_2" @if(count($sunday) != 0)) @if(in_array('Second', $sunday)) checked="" @endif @else  disabled="disabled" @endif> Second</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="SUNDAY[]" value="Third" class="checkboxsecond7 7_Sun" id="7_Sun_3" @if(count($sunday) != 0)) @if(in_array('Third', $sunday)) checked="" @endif @else  disabled="disabled" @endif> Third</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="SUNDAY[]" value="Fourth" class="checkboxsecond7 7_Sun" id="7_Sun_4" @if(count($sunday) != 0)) @if(in_array('Fourth', $sunday)) checked="" @endif @else  disabled="disabled" @endif> Fourth</label>
            </span>
            <span class="mx-2">
            <label class="form-check-label"><input type="checkbox" name="SUNDAY[]" value="Fifth" class="checkboxsecond7 7_Sun" id="7_Sun_5" @if(count($sunday) != 0)) @if(in_array('Fifth', $sunday)) checked="" @endif @else  disabled="disabled" @endif> Fifth</label>
            </span>
         </div>
      </td>
   </tr>
</tbody>
@else

                                        <tbody>
                                       
                                        @if(!empty($data_days))
                                        @foreach($data_days as $days)
                                        <tr>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-7">
                                                        <label class="form-check-label holid-check"><input name="days[]" type="checkbox" value="{{$days->name}}" class="checkbox_{{$days->id}} {{$days->id}}-{{$days->shot_name}}" id="{{$days->id}}-{{$days->shot_name}}">
                                                        {{$days->name}}</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div>
                                                    <?php for($i=0; $i < $count;$i++){?>
                                                        <span class="mx-2">
                                                            <label class="form-check-label"><input type="checkbox" name="{{$days->name}}[]" value="<?=@$week_of_month[$i];?>" class="checkboxsecond{{$days->id}} {{$days->id}}_{{$days->shot_name}}" id="{{$days->id}}_{{$days->shot_name}}_{{$i+1}}" disabled> <?=@$week_of_month[$i];?></label>
                                                        </span>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                        </tbody>
@endif                                        
                                    </table>
                                </div>
                            </div>
                            <div class="row after-add-more-shift">
                                <div class="col-sm-12 mt-4">
                <div class="form-group">
                    <h5 class="shift-ty header_change">Shift Details</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Type of Shift*</label>
                            <div class="d-flex">
                                <label class="w-50"><input type="radio" name="type_of_shift1[]" class="mx-1" @if($shift_update) @if($shift_update->type_of_shift == 'Day Shift') checked  @endif @else checked @endif value="Day Shift"> Day Shift</label>
                                <label class="w-50"><input type="radio" class="mx-1" name="type_of_shift1[]" @if($shift_update) @if($shift_update->type_of_shift == 'Night Shift')  checked  @endif @endif value="Night Shift"> Night Shift</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3"></div>
                    <p class="alert alert-info"><strong style="font-size: 15px;">*Note :</strong> Night Shift will include 12:00 AM in b/w the in time and out time</p>
                  <!--   <div class="col-sm-12">
                        <div class="form-group">
                            <label>Continuous Double Shift</label>
                            <div class="form-check"><label class="switch">
                                <input name="continuous_double_shift[]" type="checkbox" value="1" class="continuous_double_shift"><span class="slider round"></span></label>
                            </div>
                        </div>
                    </div> -->
                 <!--    <div class="col-md-12">
                        <div class="form-group">
                            <label>Variable Shift</label>
                            <div class="form-check"><label class="switch">
                                <input name="variable_shift[]" value="1" type="checkbox" class="variable_shift"><span class="slider round"></span></label>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            @if($update)
                <input type="hidden" name="edit_id" value="{{ $update->id }}">
            @else
                <input type="hidden" name="edit_id" value="0">
            @endif
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>In Time*</label>
                            <input type="time" value="{{ @$shift_update->in_time }}" class="form-control in_time" name="in_time[]" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Out Time*</label>
                            <input type="time" value="{{ @$shift_update->out_time }}" class="form-control out_time" name="out_time[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Break Start Time*</label>
                            <input type="time" value="{{ @$shift_update->break_start_time }}" class="form-control break_start_time" name="break_start_time[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Break End Time*</label>
                            <input type="time" value="{{ @$shift_update->break_end_time }}" class="form-control break_end_time" name="break_end_time[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>In Time Relaxation*</label>
                            <input type="time" value="{{ @$shift_update->in_time_relaxation }}" class="form-control in_time_relaxation" name="in_time_relaxation[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Out Time Relaxation*</label>
                            <input type="time" value="{{ @$shift_update->out_time_relaxation }}" class="form-control out_time_relaxation" name="out_time_relaxation[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Minimum Present Duration (min)*</label>
                            <input type="number" class="form-control minimum_pres_dur" min="1" name="min_present_duration[]" value="{{ @$shift_update->min_present_duration }}" onkeyup="CheckMinimumPresent(this.value)" required>
                            <label class="text-info minimum-half-time-duration" style="display:none"><strong>Minimum Half Time Duration (min)* <span class="text-primary mx-4 half-time-duration"> 0</span></strong></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Enable Half Day</label>
                            <div class="form-check"><label class="switch">
                                <input name="enable_half_day[]" @if($shift_update) @if($shift_update->enable_half_day == 1) checked="" @endif @endif class="enable_half_day" onchange="HalfDayEnabled()" value="1" type="checkbox"><span class="slider round"></span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                                <!-- <div class="col-sm-12 mt-4">
                                    <div class="form-group">
                                        <h5 class="shift-ty header_change">Daily Shift Details, Duration: 9.0 Hrs, Break Duration: 0.0 Min</h5>
                                    </div>
                                </div> -->
                                <!-- <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Type of Shift*</label>
                                                <div class="d-flex">
                                                    <label class="w-50"><input type="radio" name="type_of_shift1[]" class="mx-1" value="Day Shift" <?php// if(!empty($update->type_of_shift)){ if($update->type_of_shift=='Day Shift'){ echo 'checked';}}?>> Day Shift</label>
                                                    <label class="w-50"><input type="radio" class="mx-1" name="type_of_shift1[]" <?php// if(!empty($update->type_of_shift)){ if($update->type_of_shift=='Night Shift'){ echo 'checked';}}?> value="Night Shift"> Night Shift</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3"></div>
                                        <p class="alert alert-info"><strong style="font-size: 15px;">*Note :</strong> Night Shift will include 12:00 AM in b/w the in time and out time</p>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Continuous Double Shift</label>
                                                <div class="form-check"><label class="switch">
                                                    <input name="continuous_double_shift[]" type="checkbox" value="1" class="continuous_double_shift"><span class="slider round"></span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Variable Shift</label>
                                                <div class="form-check"><label class="switch">
                                                    <input name="variable_shift[]" value="1" type="checkbox" class="variable_shift"><span class="slider round"></span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <!-- <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>In Time*</label>
                                                <input type="time" class="form-control in_time" name="in_time[]" value="<?php// echo !empty($shift_update->in_time)? $shift_update->in_time:'';?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Out Time*</label>
                                                <input type="time" class="form-control out_time" name="out_time[]" value="<?php //echo !empty($shift_update->out_time)? $shift_update->out_time:'';?>" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Break Start Time*</label>
                                                <input type="time" class="form-control break_start_time" name="break_start_time[]" value="<?php// echo !empty($shift_update->break_start_time)? $shift_update->break_start_time:'';?>" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Break End Time*</label>
                                                <input type="time" class="form-control break_end_time" name="break_end_time[]" value="<?php //echo !empty($shift_update->break_end_time)? $shift_update->break_end_time:'';?>" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>In Time Relaxation*</label>
                                                <input type="time" class="form-control in_time_relaxation" name="in_time_relaxation[]" value="<?php //echo !empty($shift_update->in_time_relaxation)? $shift_update->in_time_relaxation:'';?>"  required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Out Time Relaxation*</label>
                                                <input type="time" class="form-control out_time_relaxation"" name="out_time_relaxation[]" value="<?php //echo !empty($shift_update->out_time_relaxation)? $shift_update->out_time_relaxation:'';?>" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Minimum Present Duration (min)*</label>
                                                <input type="number" class="form-control minimum_pres_dur" name="min_present_duration[]" onkeyup="CheckMinimumPresent(this.value)" value="<?php //echo !empty($shift_update->min_present_duration)? $shift_update->min_present_duration:'';?>" required>
                                                <label class="text-info minimum-half-time-duration" style="display:none"><strong>Minimum Half Time Duration (min)* <span class="text-primary mx-4 half-time-duration"> 0</span></strong></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Enable Half Day</label>
                                                <div class="form-check"><label class="switch">
                                                    <input name="enable_half_day[]" class="enable_half_day" onchange="HalfDayEnabled()" value="1" value="<?php //echo !empty($shift_update->enable_half_day)? $shift_update->enable_half_day:'';?>" type="checkbox"><span class="slider round"></span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!empty($data_days))
    @foreach($data_days as $days)
    
    <script type="text/javascript">
        $('#{{$days->id}}-{{$days->shot_name}}').on('click',function(){
            if(this.checked){
                $('.{{$days->id}}_{{$days->shot_name}}').each(function(){
                    this.checked = true;
                });
                <?php for($j=0; $j < $count;$j++){ ?>
                    $('#<?=$days->id.'_'.$days->shot_name.'_'.$j+1;?>').attr('disabled',false);
                <?php } ?>
            }else{
                <?php for($j=0; $j < $count;$j++){?>
                    $('#<?=$days->id.'_'.$days->shot_name.'_'.$j+1;?>').attr('disabled',true);
                <?php } ?>
            }
        });
        $(document).ready(function(){
            $('.checkboxsecond{{$days->id}}').on('click',function(){
                if($('#{{$days->id}}_{{$days->shot_name}}_1:checked').length==0 && $('#{{$days->id}}_{{$days->shot_name}}_2:checked').length==0 && $('#{{$days->id}}_{{$days->shot_name}}_3:checked').length==0 && $('#{{$days->id}}_{{$days->shot_name}}_4:checked').length==0 && $('#{{$days->id}}_{{$days->shot_name}}_5:checked').length==0){
                    $('.checkbox_{{$days->id}}').prop('checked',false);
                    $('.checkboxsecond{{$days->id}}').attr('disabled',true);
                }else{
                    $('.checkbox_{{$days->id}}').prop('checked',true);
                    
                }
            });
        });
    </script>
    <script>
        function CheckMinimumPresent(min){
            $('.half-time-duration').text(min)
        }
        function HalfDayEnabled() {
            if ($('.enable_half_day').is(':checked')) {
                $('.minimum-half-time-duration').show();
            }else{
                $('.minimum-half-time-duration').hide();
            }
        }
        function get_shift_type(type){
            $(".after-add-more-shift").html('');
            // var tp='';
            // if(type=='Weekly'){
            //     tp = type;
            // }else{
            //     tp = 'Daily';
            // }
            $.get("{{url('ajax/get-shift-type')}}?type="+type+"", function(data){
                $(".after-add-more-shift").html(data);
            });
        }
    </script>
    
    @endforeach
@endif
@endsection('content')