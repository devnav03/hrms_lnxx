@extends('layouts.organization.app')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Template Master</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Email Template *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <label class="switch">
                                        <input type="checkbox" id="email_template" onclick="email_setting(this.value)" value="{{$get_email_data->email_template}}"  <?php if($get_email_data->email_template == 1){echo "checked";} ?> name="email_template">
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>SMS Template *</label>
                                    <label class="switch">
                                        <input type="checkbox" id="sms_template" onclick="sms_setting(this.value)" value="{{$get_sms_data->sms_template}}"  <?php if($get_sms_data->sms_template == 1){echo "checked";} ?> name="sms_template">
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Notification Template *</label>
                                    <label class="switch">
                                        <input type="checkbox" id="notification_template" onclick="notification_setting(this.value)" value="{{$get_notification_data->notification_template}}"  <?php if($get_notification_data->notification_template == 1){echo "checked";} ?> name="notification_template">
                                        <div class="slider round"></div>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

<script>
    function email_setting(val){
        if(val == 0){
            email_template = 1;
        }
        if(val == 1){
            email_template = 0;
        }
        $.ajax({
            type:'GET',
            url:"{{url('ajax/email-template-status')}}",
            data:{email_template:email_template},
            success:function(){
                location.reload();
            }                   
        });
    }
</script>
<script>
    function sms_setting(val){
        if(val == 0){
            sms_template = 1;
        }
        if(val == 1){
            sms_template = 0;
        }
        $.ajax({
            type:'GET',
            url:"{{url('ajax/sms-template-status')}}",
            data:{sms_template:sms_template},
            success:function(){
                location.reload();
            }                   
        });
    }
</script>
<script>
    function notification_setting(val){
        if(val == 0){
            notification_template = 1;
        }
        if(val == 1){
            notification_template = 0;
        }
        $.ajax({
            type:'GET',
            url:"{{url('ajax/notification-template-status')}}",
            data:{notification_template:notification_template},
            success:function(){
                location.reload();
            }                   
        });
    }
</script>




<!-- <script>
    $(document).ready(function () {
        var switchStatus = false;
        $("#togBtn").on('change', function() {
            if ($(this).is(':checked')) {
                switchStatus = $(this).is(':checked');
                alert(switchStatus);// To verify
            }
            else {
            switchStatus = $(this).is(':checked');
            alert(switchStatus);// To verify
            }
        });
    });
</script> -->

@endsection('content')