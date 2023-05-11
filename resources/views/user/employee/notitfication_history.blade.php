@extends('layouts.user.app')
@section('content')
<style>
    .label-info{
        background-color: #5cb85c;
    }
    .label-danger{
        background-color: red;
    }
    .show-amazing{
        background: #ffffff;
        border-color: #bcb1b1;
        font-size: 12px;
        color: #000000!important;
        padding: 1px 4px;
        text-align: left;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <form action="" method="POST" class="row">
        @csrf
            <div class="col-md-4 form-group">
                <label><b>Select Date</b></label>
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                    <input type="hidden" id="from_date_val" name="from_date">
                    <input type="hidden" id="to_date_val" name="to_date">
                </div>
            </div>
            <div class="col-md-2 form-group" style="margin-top: 30px;">
                <button type="submit" class="btn btn-primary btn-sm mr-2"><i class="fa fa-search"></i> Search</button>
            </div>
        </form>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-9 col-6">
                                <h5 class="">Notification History</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="exampless" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <body>
                                @if(!empty($result))
                                    @foreach($result as $rows)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$rows->title}}</td>
                                            <td>{{$rows->description}}</td>
                                            <td>{{date_format(date_create($rows->created_at),"d-M-Y H:i")}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </body>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>

$(document).ready(function () {
    var datatable = $('#exampless').dataTable({
    dom: 'Bfrtip',
    buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    });
});
</script>
@endsection('content')