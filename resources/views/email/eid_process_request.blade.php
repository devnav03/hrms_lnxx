<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{$name}} </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div style="width:100%">
  
<div style="width:100%;border:1px solid grey">
        <div style="background:#f7f0f0;padding:20px">
            <p>Dear <b>{{$managername}}</b>,<br/> 
            You have received a request to process the E-ID of <b> {{$name}} ({{ $position_name }}) </b>.<br>
        </p>
             
             </br>
            <p><b>Notes:</b> {{$comments}} </p>.
            <a href="#" style="color: white;text-decoration:none;background-color: #00d082;border-color: #00d082;font-weight: 400;text-align: center;border: 1px solid transparent;padding: 0.375rem 1rem;font-size: 0.875rem;border-radius: 0.1875rem;transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;">Click here to see candidate profile</a>
                

        </div>
        <div style="padding-left:20px;padding-right:20px">
           
        </div>
    </div>


</div>
</body>
</html>
