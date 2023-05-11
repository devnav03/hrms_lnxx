@extends('layouts.organization.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Assets Our Vendor</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('assets-type')}}" method="POST">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Office *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <select class="form-control" id="office_id" name="office_id" required onchange="get_office_id();">
                                        @if(!empty($office))
                                            <option value="">--Select--</option>
                                            @foreach($office as $row)
                                                <option value="{{$row->id}}" data-id="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Department *</label>
                                    <select class="form-control" id="department_id" name="department_id" required onchange="get_designation();">
                                        @if(!empty($department))
                                            <option value="">--Select--</option>
                                            @foreach($department as $row1)
                                                <option value="{{$row1->id}}" data-id="{{$row1->id}}" @if(!empty($update->department_id)) @if($update->department_id==$row1->id) selected @endif @endif>{{$row1->department_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Address *</label>
                                    <input type="text" name="address" id="address" class="form-control" placeholder="Enter Address" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Contact No. Person 1 *</label>
                                    <input type="text" name="number1" id="number1" class="form-control" placeholder="Enter Contact No. Person 1" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Contact No. Person 2 *</label>
                                    <input type="text" name="number2" id="number2" class="form-control" placeholder="Enter Contact No. Person 2" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Contact No. Person 3 *</label>
                                    <input type="text" name="number3" id="number3" class="form-control" placeholder="Enter Contact No. Person 3" required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label>Description *</label>
                                    <textarea rows="2" name="description" class="form-control" required=""></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Vendor List</h5>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Office Name</th>
                                    <th>Department Name</th>
                                    <th>Address</th>
                                    <th>Contact1</th>
                                    <th>Contact2</th>
                                    <th>Contact3</th>
                                    <th>Email</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                                <tr></tr>
                            </thead>
                            <tbody id="">
                                <tr>
                                    <td>1</td>
                                    <td>Shailers Solution Pvt Ltd</td>
                                    <td>IT</td>
                                    <td>Noida Sector 18</td>
                                    <td>8896130379</td>
                                    <td>9465266715</td>
                                    <td>9654361756</td>
                                    <td>shailersolutions.com</td>
                                    <td>It Company</td>
                                    <td>
                                        <a href="#" class="text-primary"><i class="fa fa-pencil"></i></a>
                                        <a href="#" class="text-danger delete-button"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>EI Network Pvt Ltd</td>
                                    <td>Sales</td>
                                    <td>delhi</td>
                                    <td>7034256158</td>
                                    <td>8735164852</td>
                                    <td>8534256795</td>
                                    <td>ei@gmail.com</td>
                                    <td>Sales Company</td>
                                    <td>
                                        <a href="#" class="text-primary"><i class="fa fa-pencil"></i></a>
                                        <a href="#" class="text-danger delete-button"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
   
    <script>
        function get_office_id() {
            var department_id = $('#office_id option:selected').data('id');
            $('#department_id').empty();
            $('#designation_id').empty();
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                url: "{{url('ajax/get-department-name')}}",
                data: {
                    department_id: department_id
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#department_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#department_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                    }
                }
            });
        }
        function get_designation() {
            var office_id = $('#office_id option:selected').data('id');
            var department_id = $('#department_id option:selected').data('id');
            $('#position_id').empty();
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                url: "{{url('ajax/get-designation')}}",
                data: {
                    office_id: office_id,
                    department_id: department_id,
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#position_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#position_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].position_name+'</option>');
                    }
                }
            });
        }
    </script>
    <script>
        $(document).ready(function () {
            var datatable = $('#examples').dataTable();
        });
    </script>
@endsection('content')