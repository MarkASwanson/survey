<div id="survey"></div>

<script type="text/javascript">
	var surveyController;

	window.addEvent('domready', function() {
		require(['app/SurveyController'], function(app) {
			surveyController = ReactDOM.render(
				React.createElement(app.SurveyController, {
					question_id:	{$question.id}
				}),
				$('survey')
			);
		});
	});

	function onQuestionAnswered(response, questionFormElmt) {
		questionFormElmt.fireEvent('submitSuccess');
	}
</script>
