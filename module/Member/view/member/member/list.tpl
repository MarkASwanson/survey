<h1>
	Members
	<a class="btn" href="{url 'members/new'}">New Member</a>
</h1>

<ol class="list-style-striped">
	{foreach $members member}
		<li>
			<a href="{url 'members/get' [id=>{$member.id}]}">{$member.email}</a>
			<a class="NWDialog NWLink icon-trash" href="{url 'members/get/delete' [id=>$member.id]}"></a>
			<a class="icon-edit" href="{url 'members/get/edit' [id=>$member.id]}"></a>
		</li>
	{/foreach}
</ol>