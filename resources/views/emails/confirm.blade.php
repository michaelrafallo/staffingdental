<div style="text-align:center;">
This is an automated notification. Please do not reply, as this email account is not monitored.
</div>

<div style="width:80%;margin:20px auto;border:1px solid #9E9E9E;padding:20px;">
<img src="{{ $logo }}"> 

<p>Hi <b>{{ $name }}</b>,</p>

<p>Thank you for registering with {{ $site_name }}. 
We look forward to helping you with your career while assisting our patients.  
To complete your registration, we need to verify your email address.</p>

<div style="text-align:center;">
<a href="{{ URL::route('frontend.confirmation', ['token' => $token]) }}" style="background: #001475;
    background-image: -webkit-linear-gradient(top,#287fb5,#001475);
    background-image: -moz-linear-gradient(top,#287fb5,#001475);
    background-image: -ms-linear-gradient(top,#287fb5,#001475);
    background-image: -o-linear-gradient(top,#287fb5,#001475);
    background-image: linear-gradient(to bottom,#287fb5,#001475);
    border-radius: 28px!important;
    font-family: Arial;
    color: #ffffff!important;
    font-size: 1em!important;
    text-decoration: none;
    border: 0!important;
    line-height: 30px;
    padding: 10px 30px;
    display: inline-block;
    text-align: center;">Confirm Email Address</a>   
</div>


<p>Thank you very much.</p>

<p>All best,</p>

<p><b>{{ $site_name }}</b></p>
</div>
