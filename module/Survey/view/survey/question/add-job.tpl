<h1>New Job</h1>
<form id="jobForm" class="NWForm form-type-horizontal-fullWidth" method="POST" action="{url 'orders/get/add-job' [id=>$order.id]}">
	<input type="hidden" name="job[order_id]" value="{$order.id}" />
	{include 'order/job/partials/job-form-inputs.tpl' new=true products=$products}
</form>

{* <ul class="list-style-none list-style-striped">
	{foreach $products product}
		<li><a onclick="addProductToOrder({$product.id}, {$order.id})">{$product.name}</a></li>
	{/foreach}
</ul> *}

{literal}<script type="text/javascript">
	function addProductToOrder(productId, orderId) {
		var options = {
			url:	"{/literal}{url 'orders/get/add-job' [id=>$order.id]}{literal}",
			method:	"POST",
			data:	{
				job: {
					order_id:	orderId,
					product_id:	productId
				}
			},
			onSuccess: onAddProductSuccess,
			append:	$('jobs').getElement('tbody')
		}

		new NWRequest.HTML(options);
	}
</script>{/literal}