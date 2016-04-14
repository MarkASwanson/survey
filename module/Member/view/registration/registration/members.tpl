<table class='table'>
	<thead>
		<tr>
			<th>ID</th>
			<th>Email</th>
			<th>$nbsp;</th>
		</tr>
	</thead>
	{foreach from=$members item=member}
		<tr>
			<td>{$member->id}</td>
			<td>{$member->email}</td>
			<td><a href='/registration/delete-member/{$member->id}'>[X]</a></td>
		</tr>
	{/foreach}
</table>