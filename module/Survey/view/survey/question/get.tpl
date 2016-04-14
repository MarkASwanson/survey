<ol class="breadcrumbs">
	<li><a href="{url orders}">Orders</a></li>
	<li>{$order.number}</li>
</ol>

<h1>Order</h1>

<ul class="list-style-none">
	{include './partials/order-form.tpl' action="{url 'orders/get/edit' [id=>$order.id]}"}
</ul>

<div class="panel">
	<h1>
		Jobs
		<small><a class="NWPopup btn" href="{url 'orders/get/add-job' [id=>$order.id]}">Add Job</a></small>
	</h1>
	<table id="jobs" class="table-style-condensed">
		<thead>
			<tr>
				<th class="align-left">Name</th>
				<th>Number</th>
				<th>Quantity</th>
				<th>Hours</th>
				<th>Resource Cost</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			{foreach $order.jobs job}
				{include './partials/job-row.tpl'}
			{/foreach}
		</tbody>
	</table>
</div>

<script type="text/javascript">
	function onAddProductSuccess(responseTree, responseElements, responseHTML, responseJavaScript) {
		// var addedCost = Number($('job').getLast('tbody tr').getElement('.total_cost .value').get('text'));
		// var bor_costElmt = $('bor_cost').getElement('.value');
		// bor_costElmt.set('text', (Number(bor_costElmt.get('text'))+addedCost).toFixed(2));

		$nw.getPlugin('Popup').close();
	}
</script>