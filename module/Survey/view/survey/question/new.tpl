<h1>New Employee</h1>
<form id="employeeForm" class="NWForm:json form-type-horizontal-fullWidth" method="post" action="{url 'employees/new'}" data-nwForm-successCB="onNewEmployeeSuccess">
	{include './partials/employee-form-inputs.tpl' new=true}
</form>