/// <reference path="../../typings/browser.d.ts" />
import * as NovumWare from "../novumware";
import {QuestionModel as QuestionModel} from "./Question";
declare var NWRequest;


// =========================================== SubmissionStats Controller ==========================================
interface ISubmissionStatsControllerProps {
	question_id: number;
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

		// load the question
		new NWRequest.JSON({
			url: '/questions/'+this.props.question_id,
			onSuccess: (response)=>{ this.submissionStatsStore.question = response.question; }
		});

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
		var totalSubmissionCount = 0;
		for (var submissionStat of this.state.submissionStats) totalSubmissionCount += submissionStat.count;

		var submissionStats = this.state.submissionStats.map((submissionStat)=>{
			var percent = Math.round(submissionStat.count / totalSubmissionCount * 100);
			return (
				<li key={submissionStat.id}>
					<strong>({submissionStat.count})</strong>
					<div className="fullBar">
						<div className="percentBar" style={{width:percent+'%'}}></div>
						<span className="percentText">{percent}%</span>
					</div>
					<span className="answerText">{submissionStat.answer_text}</span>
				</li>
	        );
		});

		return (
			<div>
				<h1>Check Out The Results!</h1>	   
				<h2>{this.state.question.question_text}</h2>
				<ul className="submissionStats list-style-none">
					{submissionStats}
				</ul>
	        </div>
        )
	}
}


// =========================================== Submission Stat Model ==========================================
export class SubmissionStatModel extends NovumWare.AbstractModel {
	id: number;
	answer_id: number;
	answer_text: string;
	count: number;

	constructor(data?) {
		super();
		if (data) this.updateData(data);
	}

	updateData(data) {
		if (data.id) this.id = Number(data.id);
		if (data.answer_id) this.answer_id = Number(data.answer_id);
		if (data.answer_text) this.answer_text = data.answer_text;
		if (data.count) this.count = Number(data.count);
		super.updateData(data);
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
		this.trigger('change');
	}
	onSubmissionStatsChange() {
		console.log('SubmissionStatsStore.onSubmissionStatsChange');
		this.trigger('change');
	}
}
