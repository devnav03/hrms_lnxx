<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{$name}} - </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div style="width:100%">
  
<div style="width:100%;border:1px solid grey">
        <div style="background:#f7f0f0;padding:20px">
          <p>Dear <b>{{$name}}</b>,<br/> 
            You have received an appointment for medical test on date <b> {{date('d M, Y H:i', strtotime($appointment_time))}} </b> at <b>{{ $place }}</b>.<br><br>
            Kindly reach timely and get it done.<br><br> Thanks
          </p>
             
             </br>
             @if($comments)
            <p><b>Notes:</b> {{$comments}} </p>.
             @endif    

        </div>
        <div style="padding-left:20px;padding-right:20px">
           
        </div>
    </div>


</div>
</body>
</html>
