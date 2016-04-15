/// <reference path="../../typings/browser.d.ts" />
import * as NovumWare from "../novumware";
import {SubmissionStatsController as SubmissionStatsController} from "./SubmissionStatsController";
import {QuestionController as QuestionController} from "./QuestionController";
declare var NWRequest;

// =========================================== Survey Controller ==========================================
interface ISurveyControllerProps {
	question_id: number;
}

interface ISurveyControllerState {}

export class SurveyController extends React.Component<ISurveyControllerProps, ISurveyControllerState> {
	state: ISurveyControllerState = {};

	componentDidMount() {
		// new NWRequest.JSON({
		// 	url: this.props.surveyUrl,
		// 	onSuccess: function(response) {
		// 		this.surveyStore = response.survey;
		// 	}.bind(this)
		// });
	}

	render() {
		var submissionStatsController = <SubmissionStatsController question_id={this.props.question_id} />;
		var questionController = <QuestionController question_id={this.props.question_id} />;
		return (
	        <div>
	        	{questionController}
	       	</div>
        );
	}
}


// =========================================== Survey Store ==========================================
// class SurveyStore extends NovumWare.AbstractStore {
// 	private _survey: SurveyModel = new SurveyModel();
// 	get survey() { return this._survey; }
// 	set survey(survey:SurveyModel) {
// 		this._survey = survey;
// 		this.trigger('change');
// 	}

// 	onSurveyChange() {
// 		console.log('SurveyStore.onSurveyChange');
// 		this.trigger('change');
// 	}
// }
