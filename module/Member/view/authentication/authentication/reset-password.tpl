<h1>Reset Password</h1>
<div class='spacer-large'></div>
<form id='resetPasswordForm' method='post' action="{url 'password/reset'}">
	<input type='hidden' name='resetPasswordForm[email]' value='{$email}' />
	<input type='hidden' name='resetPasswordForm[security_key]' value='{$securityKey}' />
	
	<div>
		<label for='resetPasswordForm-password'>New Password</label>
		<input id='resetPasswordForm-password' name='resetPasswordForm[password]' type='password' data-validators='required' />
	</div>
	<div>
		<label for='resetPasswordForm-passwordConfirm'>Confirm Password</label>
		<input id='resetPasswordForm-passwordConfirm' name='resetPasswordForm[password_confirm]' type='password' data-validators="required validate-match matchInput:'resetPasswordForm-password' matchName:'Password'" />
	</div>

	<div>
		<button type='submit'>Reset Password</button>
	</div>
</form>