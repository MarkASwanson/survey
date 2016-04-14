<h1>Login</h1>

<div id='login-container'>
	<div id='loginForm-container'>
		<form id='loginForm' method='post'>
			<div>
				<label for='loginForm-email' class='required'>Email</label>
				<input id='loginForm-email' name='loginForm[email]' type='email' placeholder="Email" data-validators="required validate-email"/>
			</div>
			<div>
				<label for='loginForm-password' class='required'>Password</label>
				<input id='loginForm-password' name='loginForm[password]' type='password' placeholder="Password" data-validators="required" />
			</div>
			<div><p><a class='NWLink' href="{url 'password/forgot'}">I forgot my password</a></p></div>
			<div><button type='submit'>Login</button></div>
		</form>
	</div>
</div>