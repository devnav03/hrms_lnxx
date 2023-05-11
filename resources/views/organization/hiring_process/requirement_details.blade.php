@extends('layouts.organization.app')
@section('content')
<style>
    .rowstyle{border:1px solid #dadada;
       border-radius: 5px;
       padding: 2%;
    }
    .card-paragraph{
        font-size: 14px;
        line-height: 1.57142857;
        font-weight: 600;
        margin-bottom: 12px;
    }
    .card-paragraph a{
        color: #3b5998;
        text-decoration: none;
    }
    .card-items{
        font-size: 12px;
        line-height: 16px;
        letter-spacing: .5px;
        text-transform: uppercase;
        color: #8A8A8A;
        display: flex;
        font-family: Inter,sans-serif;
        font-weight: 500;
    }
    .card-header-title{
        font-size: 18px;
        line-height: 1.33333333;
        font-weight: 600;
    }
    .border-0 {
        border-radius: 0px;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Requirement Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Job Title *</label>
                                            <input type="hidden" name="upd_id" class="form-control" value="{{Request::segment(2)}}">
                                            <select class="form-control" id="job_title" name="job_title" onchange="job_data()" required>
                                                @if(!empty($job_title))
                                                    <option value="">--Select--</option>
                                                    @foreach($job_title as $search)
                                                        <option value="{{$search->job_title}}">{{$search->job_title}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Minimum Salary *</label>
                                            <select class="form-control" id="minimum_salary" name="minimum_salary" onchange="job_data()" required>
                                                @if(!empty($minimum_salary))
                                                    <option value="">--Select--</option>
                                                    @foreach($minimum_salary as $search)
                                                        <option value="{{$search->minimum_salary}}">{{$search->minimum_salary}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Maximum Salary *</label>
                                            <select class="form-control" id="maximum_salary" name="maximum_salary" onchange="job_data()" required>
                                                @if(!empty($maximum_salary))
                                                    <option value="">--Select--</option>
                                                    @foreach($maximum_salary as $search)
                                                        <option value="{{$search->maximum_salary}}">{{$search->maximum_salary}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div id="finalResult_job">
                                @if(!empty($requirements))
                                    @foreach($requirement as $reqDetails)
                                        <div class="rowstyle shadow mb-5">
                                            <div class="row">
                                                <div class="col-md-12 col-12">
                                                    <h4 class="card-header-title"><b>{{$reqDetails->job_title}}</b></h4>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="card-paragraph"><i class="fa fa-briefcase"></i> &nbsp;<a>{{$reqDetails->office_name}}</a></div>
                                                    <div class="card-paragraph"><i class="fa fa-map-marker"></i> &nbsp;{{$reqDetails->address}}</div>
                                                    <div class="card-paragraph">{{$reqDetails->office_name}} {{$reqDetails->job_type}} In-office</div>
                                                    <div class="row">
                                                        <div class="col-md-4 col-8">
                                                            <div class="card-items">
                                                                <i class="fa fa-rupee" aria-hidden="true"></i>&nbsp;CTC
                                                            </div>
                                                            <div class="card-paragraph">
                                                            {{$reqDetails->minimum_salary}} To {{$reqDetails->maximum_salary}} </div>
                                                        </div>
                                                        <div class="col-md-4 col-8">
                                                            <div class="card-items">
                                                                <i class="fa fa-rupee" aria-hidden="true"></i>&nbsp;Minimum Salary
                                                            </div>
                                                            <div class="card-paragraph">{{$reqDetails->minimum_salary}}</div>
                                                        </div>
                                                        <div class="col-md-4 col-8">
                                                            <div class="card-items">
                                                                <i class="fa fa-rupee" aria-hidden="true"></i>&nbsp;Maximum Salary
                                                            </div>
                                                            <div class="card-paragraph">{{$reqDetails->maximum_salary}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                            <div class="d-flex">
                                                <div class="w-30">
                                                    <a href class="float-right enrol btn btn-info btn-sm" target="_blank" data-toggle="modal" data-target="#myModal" data-id="{{$reqDetails->id}}" onclick="showData({{$reqDetails->id}});">View Details &nbsp;&nbsp;<i class="fa fa-angle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                </div>
                                <div class="text-center"><a id="proceed_prev" class="btn btn-secondary btn-sm border-0">&laquo; Previous</a><a id="proceed_next" class="btn btn-primary btn-sm border-0">Next &raquo;</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Job Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                    <div class="modal-body">

                    <div class="rowstyle shadow mb-5">
                        <div class="row">
                            <div class="col-md-10 col-8">
                                <h4 class="card-header-title"><b class="job_title"></b></h4>
                            </div>
                            <div class="col-md-2 col-4">
                                <img src="http://localhost/hrms-crm/organization/logo/lnxxx.png"style="width: 70px;position: absolute;top: -10px;">
                            </div>
                            <div class="col-md-12">
                                <div class="card-paragraph"><i class="fa fa-briefcase"></i> &nbsp;<a
                                target="_blank" href="https://www.samtechinfonet.com/" class="office_name"></a></div>
                                <div class="card-paragraph address"><i class="fa fa-map-marker"></i> &nbsp;</div>
                                <div class="card-paragraph job_type">In-office</div>
                                <div class="row">
                                    <div class="col-md-4 col-8">
                                        <div class="card-items">
                                            <i class="fa fa-rupee" aria-hidden="true"></i>&nbsp;CTC
                                        </div>
                                        <div class="card-paragraph">10,000 To 20,000 </div>
                                    </div>
                                    <div class="col-md-4 col-8">
                                        <div class="card-items">
                                            <i class="fa fa-rupee" aria-hidden="true"></i>&nbsp;Minimum Salary
                                            &nbsp;<a href="#" data-toggle="tooltip" data-placement="right" title="Fixed pay is the fixed component of the CTC"><i style="font-size: 15px;" class="fa fa-question" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="card-paragraph minimum_salary"></div>
                                    </div>
                                    <div class="col-md-4 col-8">
                                        <div class="card-items">
                                            <i class="fa fa-rupee" aria-hidden="true"></i>&nbsp;Maximum Salary &nbsp;<a href="#" data-toggle="tooltip" data-placement="right" title="Variable pay includes performance based cash incentives and bonuses"><i style="font-size: 15px;" class="fa fa-question" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="card-paragraph maximum_salary"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <hr style="margin: 30px -23px 25px;">
                                <h4 class="card-header-title"><b>Skills Required</b></h4>
                                <p style="font-size: 13px;">HTML<br>CSS<br>HTML5<br>Bootstrap<br>Jquery<br></p>
                            </div>
                            <div class="col-md-12 col-12">
                                <h4 class="card-header-title"><b>Job Description</b></h4>
                                <span class="description" style="font-size: 13px;"></span>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>


    </div>

<script>
$(document).ready(function(){
    var offset = 0;
    var spinner = $('#loader');
    spinner.show();
    loadCurrentPage(offset);
    $("#proceed_next, #proceed_prev").click(function(){
        offset = ($(this).attr('id')=='proceed_next')?offset+5:offset-5;
        if (offset < 0){
            offset = 0;
        }else{
            loadCurrentPage(offset);
        }
    });
    $("#job_title,#minimum_salary,#maximum_salary").change(function(){
        loadCurrentPage(offset);
    });
    spinner.hide();
});
</script>
<script>
function loadCurrentPage(offset){
    $('#finalResult_job').empty('');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
        url: "{{url('ajax/fetch-requirement-details')}}?offset="+offset+"",
        type: 'POST',
        data:{
            job_title:$('#job_title').val(),
            minimum_salary:$('#minimum_salary').val(),
            maximum_salary:$('#maximum_salary').val()
        },
        cache: true,
        success: function(xhr) {
            var datas = xhr.data;
            if(datas.length>0){
                PageData(datas);
            }else{
                DataNotFound();
            }
        }
    });
}
</script>
<script>
function DataNotFound(){
    $('#finalResult_job').html('<div class="rowstyle shadow mb-5"><div class="row text-center"><h3>Data not Found</h3></div></div>');
}
function PageData(datas){
    var html='';
    for (var i=0; i < datas.length; i++) {
        html += '<div class="rowstyle shadow mb-5">'+
        '<div class="row">'+
            '<div class="col-md-12 col-12"><h4 class="card-header-title"><b>'+datas[i].job_title+'</b></h4></div>'+
            '<div class="col-md-12">'+
                '<div class="card-paragraph"><i class="fa fa-briefcase"></i> &nbsp;<a>'+datas[i].office_name+'</a></div>'+
                '<div class="card-paragraph"><i class="fa fa-map-marker"></i> &nbsp;'+datas[i].address+'</div>'+
                '<div class="card-paragraph">'+datas[i].office_name+' '+datas[i].job_type+' In-office</div>'+
                '<div class="row">'+
                    '<div class="col-md-4 col-8">'+
                        '<div class="card-items"><i class="fa fa-rupee" aria-hidden="true"></i>&nbsp;CTC</div>'+
                        '<div class="card-paragraph">'+datas[i].minimum_salary+' To '+datas[i].maximum_salary+' </div>'+
                    '</div>'+
                    '<div class="col-md-4 col-8">'+
                        '<div class="card-items"><i class="fa fa-rupee" aria-hidden="true"></i>&nbsp;Minimum Salary</div>'+
                        '<div class="card-paragraph">'+datas[i].minimum_salary+'</div>'+
                    '</div>'+
                    '<div class="col-md-4 col-8">'+
                        '<div class="card-items"><i class="fa fa-rupee" aria-hidden="true"></i>&nbsp;Maximum Salary</div>'+
                        '<div class="card-paragraph">'+datas[i].maximum_salary+'</div>'+
                    '</div>'+
                '</div>'+
            '</div><hr>'+
        '</div>'+
        '<div class="d-flex">'+
            '<div class="w-30">'+
                '<a href class="float-right enrol btn btn-info btn-sm" target="_blank" data-toggle="modal" data-target="#myModal" onclick="showData('+datas[i].id+');">View Details &nbsp;&nbsp;<i class="fa fa-angle-right"></i></a>'+
            '</div>'+
        '</div>'+
    '</div>';
    }
    $('#finalResult_job').html(html);
}
</script>
<script type="text/javascript">
    function showData(id){
        var spinner = $('#loader');
        spinner.show();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
            type: 'POST',
            url : "{{url('ajax/view-job-details')}}", 
            data: {id:id},
            async : false,
            success:function(xhr){
                if(xhr.status==200){
                    $('.job_title').text(xhr.data.job_title);
                    $('.office_name').text(xhr.data.office_name);
                    $('.address').text(xhr.data.address);
                    $('.job_type').text(xhr.data.job_type);
                    $('.minimum_salary').text(xhr.data.minimum_salary);
                    $('.maximum_salary').text(xhr.data.maximum_salary);
                    $('.description').html(xhr.data.description);
                    spinner.hide();
                }
            }
        });
    }
</script>
@endsection('content')