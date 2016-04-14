<div id="survey"></div>

<script type="text/javascript">
	var surveyController;

	require(['app/QuestionController'], function(app) {
		surveyController = ReactDOM.render(
			React.createElement(app.QuestionController, {
				questionUrl:	"{url 'questions/get' [id=>$question.id]}"
			}),
			$('survey')
		);
	});

	// function onNewEmployeeSuccess(response) {
	// 	$nw.getPlugin('Popup').close();
	// 	employeeListController.addEmployee(response.employee);
	// }

	// function onDeleteEmployeeSuccess(elmt) {
	// 	elmt.getParent('li').fireEvent('deleted');
	// }
</script>
