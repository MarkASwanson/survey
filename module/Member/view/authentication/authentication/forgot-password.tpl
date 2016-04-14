<h1>Forgot Password</h1>
<div class='spacer-large'></div>
<p>Please enter your email and we will send you a link to change your password:</p>
<form id='forgotPasswordForm' method='post' action="{url 'password/forgot'}">
	<div>
		<label for='forgotPasswordForm-email'>Email</label>
		<input type='text' id='forgotPasswordForm-email' name='forgotPasswordForm[email]' />
	</div>
	<div>
		<button type='submit'>Submit</button>
	</div>
</form>
