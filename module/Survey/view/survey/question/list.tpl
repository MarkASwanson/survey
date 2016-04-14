<div id="employees"></div>

<script type="text/javascript">
	var employeeListController;

	require(['app/EmployeeListController'], function(app) {
		employeeListController = ReactDOM.render(
			React.createElement(app.EmployeeListController, {
				employeesUrl:	"{url employees}"
			}),
			$('employees')
		);
	});

	// require(['app/ReactCSSTransitionGroup'], function(app){
	// 	console.log(app);
	// });

	function onNewEmployeeSuccess(response) {
		$nw.getPlugin('Popup').close();
		employeeListController.addEmployee(response.employee);
	}

	function onDeleteEmployeeSuccess(elmt) {
		elmt.getParent('li').fireEvent('deleted');
	}
</script>