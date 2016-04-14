<h1>My Account</h1>

<div id='myAccount-basicInfo'>
	<h2>Basic Information</h2>
	<div><label>Email</label> {$member.email}</div>
	<div><label>Password</label> <a href="{url 'password/forgot'}">Change Password</a></div>
</div>

<div id='myAccount-addresses'>
	<h2>Addresses <small><a href="{url 'account/addresses/new'}"><button class='new'>New Address</button></a></small></h1>
	{foreach $member.addresses address}
		<div class='address'>
			<h1>{$address.name} <a href="{url 'account/addresses/delete' [addressId=>$address.id]}" class='icon-trash'></a> <a href="{url 'account/addresses/edit' [addressId=>$address.id]}" class='icon-edit'></a></h1>
			<div class='address-content'>
				{$address.address}<br />
				{$address.city}, {$address.region} {$address.postal_code}<br />
				{$address.country}
			</div>
		</div>
	{foreachelse}
		<p class='notice'>You don't have any addresses yet...</p>
	{/foreach}
</div>
