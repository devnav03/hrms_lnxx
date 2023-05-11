<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{$user_name}}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div style="width:100%">
  <p>Hlo <b>{{$name}}</b> your interview schedule @ {{$meeting_date}} {{$from}} {{$to}}, on <b>{{$meeting_type}}</b><br/></p>
  <div style="border:solid 1px #dadce0;border-radius:8px;padding:10px 32px;text-align:left;vertical-align:top"><?=$meeting_description;?></div>
</div>
</body>
</html>
