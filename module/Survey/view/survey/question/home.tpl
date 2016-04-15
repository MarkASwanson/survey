<div id="survey"></div>

<script type="text/javascript">
	var surveyController;

	// require(['app/QuestionController'], function(app) {
	// require(['app/SubmissionStatsController'], function(app) {
	require(['app/SurveyController'], function(app) {
		surveyController = ReactDOM.render(
			React.createElement(app.SurveyController, {
			// React.createElement(app.QuestionController, {
			// React.createElement(app.SubmissionStatsController, {
				// submissionStatsUrl:	"{url 'questions/get/submission-stats' [id=>$question.id]}"
				question_id:	{$question.id}
				// questionUrl:	"{url 'questions/get' [id=>$question.id]}"
			}),
			$('survey')
		);
	});

	function onQuestionAnswered(response, questionFormElmt) {
		questionFormElmt.fireEvent('submitSuccess');
	}
</script>

<style type="text/css">
	#survey {
		text-align:	center;
	}

	#survey form {
		display:	inline-block;
	}

	#survey form button {
		margin-top:		10px;
		margin-left:	16px;
	}

	.submissionStats {
		display:	inline-block;
		text-align:	left
	}

	.submissionStats .fullBar {
		background-color:	#CECBD2;
		width:				150px;
		height:				20px;
		display:			inline-block;
		vertical-align:		middle;
		border:				1px;
		border-radius:		2px;
		overflow:			hidden;
		margin:				0px 5px;
		position:			relative;
		text-align:			center;
	}

	.submissionStats .percentBar {
		background-color:	#F4BB5F;
		position:			absolute;
		left:				0px;
		top:				0px;
		bottom:				0px;
	}

	.submissionStats .percentText {
		font-size:		0.8em;
		margin-bottom:	3px;
		display:		inline-block;
		vertical-align:	middle;
	}

	/*.submissionStats .answerText {
		white-space:	nowrap;
		text-overflow:	ellipsis;
	}*/
</style>