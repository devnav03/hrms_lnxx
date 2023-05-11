@extends('layouts.organization.app')
@section('content')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Create Map Letter Template</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('add-map-letter-template')}}" method="POST">
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Letter Type</label>
                                    <select class="form-control" id="letter_type" name="letter_type" required>
                                        <option value="">Select</option>
                                        @if(!empty($letterMaster))
                                            @foreach($letterMaster as $row)
                                                <option value="{{$row->id}}">{{$row->letter_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Letter Template</label>
                                    <select class="form-control" id="letter_template" name="letter_template" required>
                                        <option value="">Select</option>
                                        <option value="english">English</option>
                                        <option value="Inactive">Hindi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Authorised officer</label>
                                    <select class="form-control" id="authorised_officer" name="authorised_officer" required>
                                        <option value="">Select</option>
                                        <option value="Ashutosh">Ashutosh</option>
                                        <option value="Dipanshu">Dipanshu</option>
                                    </select>
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

        <div class="row">
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Letter List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Officer Name</th>
                                    <th>Letter Name</th>
                                    <th>Template Name</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function () {
            var datatable = $('#example').dataTable({
                ajax: "{{url('ajax/user-map-letter-template-list')}}",
                columns: [
                    {data:'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {data:'letter_type'},
                    {data:'letter_template'},
                    {data:'authorised_officer'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return dateTimeFormate(data.created_at);
                        }
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return dateTimeFormate(data.updated_at);
                        }
                    }
                ]
            });
            setInterval(function(){
                $('#example').DataTable().ajax.reload(); 
            },3000);
        });
    </script>
    @endsection('content')