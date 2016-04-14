{$member.email}
<form id="memberForm" class="NWForm{if $json|default}:json{/if}" method="POST" action="{$action|default}">>
	<div>
		<label for="memberForm-email">Email</label>
		<input id="memberForm-email" type="email" placeholder="Email" name="member[email]" value="{$member.email|default}" autofocus />
	</div>
	<div>
		<label for="memberForm-password">Password</label>
		<input id="memberForm-password" type="password" placeholder="Password" name="member[password]" />
	</div>
	<div><button type="submit">Save</button></div>
</form>