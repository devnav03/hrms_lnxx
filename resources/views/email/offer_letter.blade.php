<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{$name}} - {{$mobile}}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div style="width:100%">
  
<div style="width:100%;border:1px solid grey">
        <div style="background:#f7f0f0;padding:20px">
     <!--        <p>Hii <b>{{$name}}</b>,<br/><br/>
            Please find your offer letter for the position of <b>{{$position}}</b></p> -->
            <p><b>Congratulations!</b><br/><br/>
              You have been selected for the role of <b>{{$position}}</b> in JCBL. We have sent your offer letter. Kindly check and approve</p>
            <a href="{{ route('are-you-sure-offer-letter-accept', $token) }}" style="color: white;text-decoration:none;background-color: #00d082;border-color: #00d082;font-weight: 400;text-align: center;border: 1px solid transparent;padding: 0.375rem 1rem;font-size: 0.875rem;border-radius: 0.1875rem;transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;">Accept this offer</a>
        <a href="{{ route('are-you-sure-offer-letter-reject', $token) }}" style="color: white;text-decoration:none;background-color: #ff0000;border-color: #ff0000;font-weight: 400;text-align: center;border: 1px solid transparent;padding: 0.375rem 1rem;font-size: 0.875rem;border-radius: 0.1875rem;transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;">Reject this offer</a>
        </div>
        <div style="padding-left:20px;padding-right:20px">
            </br><p> </p>
        </div>
    </div>


</div>
</body>
</html>
