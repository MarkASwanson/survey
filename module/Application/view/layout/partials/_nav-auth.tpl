<ul class="nav nav-type-horizontal nav-auth">
	{if $loggedInMember|default}
		<li><a href="{url logout}">logout</a></li>
	{else}
		<li><a href="{url login}">login</a></li>
	{/if}
</ul>