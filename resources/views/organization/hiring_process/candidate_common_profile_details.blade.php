@extends('layouts.organization.app')
@section('content')
<style>
    .lable-danger{
        background-color: #d9534f;
        color: #fff;
        padding: 0.2em 0.6em 0.3em;
        border-radius: 0.8em;
        font-size: 14px;
        white-space: nowrap;
    }
    .lable-success{
        background-color: #5cb85c;
        color: #fff;
        padding: 0.2em 0.6em 0.3em;
        border-radius: 0.8em;
        font-size: 14px;
        white-space: nowrap;
    }
    a:hover {
        color: #fff;
        text-decoration: none;
    }
    #candidate_data td{
        border: 1px solid #80808036 !important;
    }
    .tbl-border th{
        border: 1px solid #80808036 !important;
    }
    @media (min-width: 992px){
        .modal-lg, .modal-xl {
            max-width: 1000px;
        }
    }
    .dropdown .dropdown-menu{
        box-shadow: 0px 1px 15px 1px rgb(0 0 0 / 35%);
    }
</style>





<div class="main-panel">
    <div class="content-wrapper">
         <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Candidate Profile Details</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Manager Name</th>
                                    <th>Gender</th>
                                    <th>Candidate Email</th>
                                    <th>HR Email</th> 
                                    <th>Request Date</th> 
                                    <th>Action</th> 
                                   
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($result))
                                @foreach($result as $row)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$row->candidate_name}} </td>
                                    <td>{{$row->position_name}} </td>
                                    <td>{{$row->manager_name}}</td>
                                    <td>{{ucwords($row->candidate_gender)}} </td>
                                    <td>{{$row->candidate_email}}</td>
                                    <td>{{$row->hr_email}}</td>
                                     
                                    <td>{{date_format(date_create($row->created_at),"d-M-Y")}}</td>
                                    <td><a href="#" data-toggle="modal" data-target="#myModal" onclick="show_data('{{$row->id}}');"><i class="fa fa-eye"></i></a></td>           
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="myModal" class="modal fade" role="dialog" style="width:100%!important;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Full Candidate Details::</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body">
                    
                    <!-- ----------------START ATTACHED DOCUMENT ------------------ -->
                 <div class="table-responsive">   
                    <table class="table tbl-border">
                        <thead>
                            <tr>
                                <th scope="col"><b>Name</b></th>
                                <th scope="col"><b>Position</b></th>
                                <th scope="col"><b>Manager Name</b></th>
                                <th scope="col"><b>Gender</b></th>
                                <th scope="col"><b>Email</b></th>
                                <th scope="col"><b>Mobile No</b></th>
                                <th scope="col"><b>Salary</b></th>
                                <th scope="col"><b>HR Email</b></th>
                                <th scope="col"><b>Offer Release Date</b></th>
                                <th scope="col"><b>Hiring Status</b></th>
                            </tr>
                        </thead>
                        <tbody id="candidate_data">
                          
                        </tbody>
                    </table>   
                 </div>          
                    <!-- ------------------END ATTACHED DOCUMENT ------------------ -->
                </div>
                 
            </div>
        </div>
    </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

<script>
 
    var loadFile = function(event) {
        document.getElementById('output').setAttribute("style",
            "width: 8rem;height: 8rem;border-radius: 0.25rem;object-fit: contain;max-height: 51px;max-width: 10rem;margin-top: -9px;"
            );
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('output');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
</script>

 
 
 
<script>
    $(document).ready(function () {
        var datatable = $('#examples').dataTable({
        dom: 'Bfrtip',
        buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        });
    });
 
</script>
<script>
    function show_data(id) {
        //spinner.show();
        $.ajax({
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-candidate-full-profile')}}/"+id,
            data: {
                id: id
            },
            success:function(xhr){
                    if(xhr.status==200){ 
                      var datas = xhr.data;
                     for (var i = 0; i < datas.length; i++) {

                        if(datas[i].hiring_status=='1')
                        {
                            var hiringstatus='Offer Letter Send';
                        }

                        if(datas[i].hiring_status=='4')
                        {
                            var hiringstatus='Process For eVisa Approval';
                        }

                        var html='<tr>'+
                        '<td>'+datas[i].candidate_name+'</td>'+
                        '<td>'+datas[i].position_name+'</td>'+
                        '<td>'+datas[i].manager_name+'</td>'+                
                        '<td>'+datas[i].candidate_gender+'</td>'+
                        '<td>'+datas[i].candidate_email+'</td>'+
                        '<td>'+datas[i].candidate_mobile+'</td>'+
                        '<td>'+datas[i].candidate_salary+'</td>'+
                        '<td>'+datas[i].hr_email+'</td>'+
                        '<td>'+datas[i].created_at+'</td>'+
                        '<td>'+hiringstatus+'</td>'+
                        '</td></tr>';
                        $('#candidate_data').html(html);
                        //$('#leave_id').html('<span class="btn btn-info btn-sm" data-dismiss="modal">Close</span>');
                       // spinner.hide();
                      }  
                    }
                }
        });
    }
</script>

@endsection('content')