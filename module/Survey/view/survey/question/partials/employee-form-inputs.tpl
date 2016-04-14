<div>
	<label for="employeeForm-first_name">First Name</label>
	<input id="employeeForm-first_name" type="text" placeholder="First Name" name="employee[first_name]" value="{$employee.first_name|default}" />
</div>
<div>
	<label for="employeeForm-last_name">Last Name</label>
	<input id="employeeForm-last_name" type="text" placeholder="Last Name" name="employee[last_name]" value="{$employee.last_name|default}" />
</div>
<div><button type="submit">Save</button></div>