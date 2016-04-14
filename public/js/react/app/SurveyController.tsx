/// <reference path="../../typings/browser.d.ts" />
import * as NovumWare from "../novumware";
import {Survey as Survey} from "./Survey";
import {SurveyModel as SurveyModel} from "./Survey";
declare var NWRequest;


// =========================================== Survey Controller ==========================================
interface ISurveyControllerProps {
	surveyUrl: string;
}

interface ISurveyControllerState {
	survey: SurveyModel;
}

export class SurveyController extends React.Component<ISurveyControllerProps, ISurveyControllerState> {
	state: ISurveyControllerState = { survey: new SurveyModel() };

	private surveyStore: SurveyStore = new SurveyStore();

	componentDidMount() {
		this.surveyStore.bind('change', this.onSurveyStoreChange.bind(this));

		new NWRequest.JSON({
			url: this.props.surveyUrl,
			onSuccess: function(response) {
				this.surveyStore = response.survey;
			}.bind(this)
		});
	}

	componentWillUnmount() {
		this.surveyStore.unbind('change', this.onSurveyStoreChange.bind(this));
	}

	onSurveyStoreChange() {
		console.log('SurveyController.onSurveyStoreChange');
		this.setState({ survey: this.surveyStore.survey });
	}

	render() {
		return <Survey survey={this.state.survey} />;
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
