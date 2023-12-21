<h3>Hi {{ $mailData['name'] }},</h3>
<h4>Did you forget your password? Don't worry!</h4>
<h4>You can create a new password by clicking in the link below.</h4>
<a href="{{ route('password.reset') }}" class="forgot-password">Recover Password</a>
<h5>-------</h5>
<h5>Travly support team</h5>
