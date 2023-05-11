@extends('layouts.organization.app')
@section('content')
<style>
    /* Remove default bullets */
ul, #myUL {
  list-style-type: none;
}

/* Remove margins and padding from the parent ul */
#myUL {
  margin: 0;
  padding: 0;
}
.caret {
  cursor: pointer;
  user-select: none;
}
.caret::before {
  content: "\25B6";
  color: black;
  display: inline-block;
  margin-right: 6px;
  font-size: 12px;
}
.caret-down::before {
  transform: rotate(90deg);
}
.nested {
  display: none;
}
.active {
  display: block;
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
                                <h5 class="" id="getCameraSerialNumbers">Shift Details</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                <!--     <th>Shift Pattern</th> -->
                                    <th>Shift Name</th>
                                    <th>Holiday</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($result))
                                    @foreach($result as $rows)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                     <!--        <td>{{$rows->shift_type}}</td> -->
                                            <td>{{$rows->shift_name}}</td>
                                            <td>
                                                <?php $holidays = json_decode($rows->holidays);
                                                $count = count($holidays);
                                                    for($i=0;$i<$count;$i++){
                                                        foreach($holidays[$i] as $key=>$val){ ?>
                                                            <ul id="myUL">
                                                                <li><span class="caret">{{$key}}</span>
                                                                    <ul class="nested">
                                                                        <?php $count1 = count($val); for($j=0;$j < $count1;$j++){?>
                                                                            <li>{{$val[$j]}}</li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        <?php }
                                                    }
                                                ?>
                                            </td>
                                            <td>{{date_format(date_create($rows->created_at),"d-M-Y H:i")}}</td>
                                            <td>
                                                <a href="{{url('add-shift',$rows->id)}}" class="text-primary mx-2"><i class="fa fa-edit"></i></a>
                                                <a href="{{url("shift-master-delete",$rows->id)}}" class="text-danger delete-button"><i class="fa fa-trash"></i></a>
                                            </td>
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
    <script>
        $(document).ready(function () {
            var datatable = $('#examples').dataTable();
        });
    </script>
    <script>
        var toggler = document.getElementsByClassName("caret");
        var i;

        for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("caret-down");
        });
        }
    </script>
@endsection('content')