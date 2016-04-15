/// <reference path="../../typings/browser.d.ts" />
import * as NovumWare from "../novumware";
import {QuestionModel} from "./Question";
import {SubmissionStatModel} from "./SubmissionStats";
import {SubmissionStats} from "./SubmissionStats";
declare var NWRequest;


// =========================================== SubmissionStats Controller ==========================================
interface ISubmissionStatsControllerProps {
	question_id: number;
	question?: QuestionModel;
}

interface ISubmissionStatsControllerState {
	question: QuestionModel;
	submissionStats: SubmissionStatModel[];
}

export class SubmissionStatsController extends React.Component<ISubmissionStatsControllerProps, ISubmissionStatsControllerState> {
	state: ISubmissionStatsControllerState = {
		question: new QuestionModel(),
		submissionStats: []
	};

	private submissionStatsStore: SubmissionStatsStore = new SubmissionStatsStore();

	componentDidMount() {
		this.submissionStatsStore.bind('change', this.onSubmissionStatsStoreChange.bind(this));

		// possibly load the question
		if (this.props.question.id) this.submissionStatsStore.question = this.props.question;
		else {
			new NWRequest.JSON({
				url: '/questions/' + this.props.question_id,
				onSuccess: (response) => { this.submissionStatsStore.question = response.question; }
			});
		}

		// load the submission stats
		new NWRequest.JSON({
			url: '/questions/' + this.props.question_id + '/submission-stats',
			onSuccess: (response)=>{ this.submissionStatsStore.submissionStats = response.submissionStats; }
		});
	}

	componentWillUnmount() {
		this.submissionStatsStore.unbind('change', this.onSubmissionStatsStoreChange.bind(this));
	}

	onSubmissionStatsStoreChange() {
		console.log('SubmissionStatsController.onSubmissionStatsStoreChange');
		this.setState({
			question: this.submissionStatsStore.question,
			submissionStats: this.submissionStatsStore.submissionStats
		});
	}

	render() {
		return (
			<div>
				<h1>Check Out The Results!</h1>	   
				<h2>{this.state.question.question_text}</h2>
				<SubmissionStats question={this.state.question} submissionStats={this.state.submissionStats} />
	        </div>
        )
	}
}


// =========================================== SubmissionStats Store ==========================================
class SubmissionStatsStore extends NovumWare.AbstractStore {
	// question_id: number;

	// === QuestionModel ===
	private _question: QuestionModel = new QuestionModel();
	get question() { return this._question; }
	set question(questionData) {
		if (questionData instanceof QuestionModel) this._question = questionData;
		else this._question = new QuestionModel(questionData);
		this._question.bind('change', this.onQuestionChange.bind(this));
		this.trigger('change');
	}
	onQuestionChange() {
		console.log('SubmissionStatsStore.onQuestionChange');
		this.trigger('change');
	}

	// === SubmissionStatModels ===
	private _submissions: SubmissionStatModel[] = [];
	get submissionStats() { return this._submissions; }
	set submissionStats(submissionStatsData) {
		this._submissions = [];
		for (var submissionStatData of submissionStatsData) {
			var submissionStat = new SubmissionStatModel(submissionStatData);
			submissionStat.bind('change', this.onSubmissionStatsChange.bind(this));
			this._submissions.push(submissionStat);
		}

		// sort submission stats based on answer order
		this._submissions.sort((a: SubmissionStatModel, b: SubmissionStatModel) => { return a.answer_order - b.answer_order; });

		this.trigger('change');
	}
	onSubmissionStatsChange() {
		console.log('SubmissionStatsStore.onSubmissionStatsChange');
		this.trigger('change');
	}
}
