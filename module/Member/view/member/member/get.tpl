<ol class="breadcrumbs">
	<li><a href="{url members}">Members</a></li>
	<li>{$member.email}</li>
</ol>

<h1>
	{$member.email}
	<a class="btn" href="{url 'members/get/edit' [id=>$member.id]}">Edit</a>
</h1>

<ul class="list-style-none">
	{foreach $member->toArray() value property}
		<li><label>{$property}</label> {$value}</li>
	{/foreach}
</ul>