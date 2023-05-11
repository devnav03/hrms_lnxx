@extends('layouts.organization.app')
@section('content')
<style>
    .update_data td{
        border: 1px solid #80808036 !important;
        line-height:0;
    }
    .tbl-border th{
        border: 1px solid #80808036 !important;
    }
    .tbl-border thead tr th{
        color: black;
        font-weight: 600;
        line-height:0;
    }
    #hidden_div{
        display: none;
    }
    .btn-pend{
        padding: 0.5rem 0.81rem;
        background-color: #ffbf36;
        border-radius: 3px;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <a href="{{url('assets-pending-request')}}" class="btn btn-info btn-sm mb-2"><i class="fa fa-arrow-left"></i> Go Back</a>
            </div>
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Update Assets Status</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{url('update-assets-status')}}" method="POST">
                            @csrf
                            <table class="table tbl-border">
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Assets Type</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                    <tr></tr>
                                </thead>
                                <tbody class="update_data">
                                    <tr>
                                        <td>{{$assets->name}} - {{$assets->employee_code}}</td>
                                        <td>{{date_format(date_create($assets->start_date),"d-M-Y")}}</td>
                                        <td>{{date_format(date_create($assets->end_date),"d-M-Y")}}</td>
                                        <td>{{$assets->assets_name}}</td>
                                        <td><button class="status_checks btn-xs btn @if($assets->status=='Approve') btn-outline-success @else btn-outline-danger @endif">{{$assets->status}}</button></td>
                                        <td>{{$assets->description}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row mt-3">
                                <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                    <label>Update Status</label>
                                    <input type="hidden" name="id" value="{{$assets->id}}">
                                    <select name="chng_status" id="chng_status" class="form-control">
                                        <option value="Pending" @if($assets->status=='Pending') selected @endif>Pending</option>
                                        <option value="Reject"  @if($assets->status=='Reject') selected @endif>Reject</option>
                                        <option value="Approve"  @if($assets->status=='Approve') selected @endif>Approve</option>
                                    </select>
                                </div>
                                <div class="col-md-8 form-group">
                                    <label>Assets Description By Admin</label>
                                    <textarea rows="2" name="admin_description" class="form-control"></textarea>
                                </div>
                                <div id="hidden_div" class="col-md-12"  style="display:none;">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label>Avalible Assets</label>
                                            <select name="avaliable_assets" class="form-control">
                                                <option value="">--Select--</option>
                                                    @if(!empty($avaliable_asset)) @foreach($avaliable_asset as $orws)
                                                    <option value="{{$orws->id}}">{{$orws->name}}</option>
                                                    @endforeach @endif
                                                    <!--<option value="3">UPS (4GB DDR 4) (2022)</option>-->
                                                    <!--<option value="4">Mobile (Zebronic Web Cam) (001)</option>-->
                                                    <!--<option value="5">Cables (Zebronic Web Cam) (002)</option>-->
                                                    <!--<option value="6">Mouse (Zebronic Web Cam 03) (003)</option>-->
                                                    <!--<option value="7">Monitor (Zebronic Web Cam 04) (004)</option>-->
                                                    <!--<option value="8">UPS (16gb DDR3) (001)</option>-->
                                            </select>
                                        </div>
                                            <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label>Start Date</label>
                                            <input type="date" name="app_from_date" placeholder="yy-mm-dd" autocomplete="off" id="datepicker" class="form-control">
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 form-group">
                                            <label>To Date</label>
                                            <input type="date" name="app_to_date" placeholder="yy-mm-dd" autocomplete="off" id="datepicker2" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-sm mr-2">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    
    <script>
		$('#chng_status').on('change', function() {
            if(this.value == "Approve") {
                $('#hidden_div').show();
            }else{
                $('#hidden_div').hide();
            }
        });
	</script>
@endsection('content')