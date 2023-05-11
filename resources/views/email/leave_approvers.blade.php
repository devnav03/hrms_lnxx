<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{$leave_type}} Approval</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="padding:5px">
    <div style="width:100%;border:1px solid grey">
        <div style="background:#f7f0f0;padding:20px">
            <p>Hii <b>{{$name}}</b>,<br/><br/> {{$applied_name}} Applied <b>{{$leave_type}}</b></p>
            <a href="{{ route('leave-approve', $leav_token) }}" style="color: white;text-decoration:none;background-color: #00d082;border-color: #00d082;font-weight: 400;text-align: center;border: 1px solid transparent;padding: 0.375rem 1rem;font-size: 0.875rem;border-radius: 0.1875rem;transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;">Approve</a>
		    <a href="{{ route('leave-reject', $leav_token) }}" style="color: white;text-decoration:none;background-color: #ff0000;border-color: #ff0000;font-weight: 400;text-align: center;border: 1px solid transparent;padding: 0.375rem 1rem;font-size: 0.875rem;border-radius: 0.1875rem;transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;">Reject</a>
        </div>
        <div style="padding-left:20px;padding-right:20px">
            </br><p><?=$reason_for?></p>
        </div>
    </div>
</body>
</html>
