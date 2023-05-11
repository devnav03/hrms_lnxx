<div style="width: 340px; margin: 0 auto;background: #f3f3f3;">
<center style="background: #1877f2;margin-top: 45px;padding: 10px;">
<img src="{{asset('organization/logo')}}/{{$org->logo}}" alt="logo" style="width:100px" />
</center>    
<p style="font-size: 16px; font-family: sans-serif;padding: 0px 15px 15px 15px; line-height: 36px;">Sorry to know that you are rejecting the offer from JCBL &nbsp;
<a style="text-decoration: none;" href="{{ route('offer-letter-reject', $id) }}">Confirm to Reject (Click here)</a></p>
</div>