/// <reference path="../../typings/browser.d.ts" />
import * as NovumWare from "../novumware";
import {AnswerModel} from "./Answer";
declare var NWRequest;
declare var $nw;


// =========================================== Question ==========================================
interface IQuestionProps {
	question: QuestionModel;
	submitSuccessAction?: () => void;
}

interface IQuestionState {}

export class Question extends React.Component<IQuestionProps, IQuestionState> {
	componentDidMount() {
		$nw.initContainer(ReactDOM.findDOMNode(this));
		var domElmt = ReactDOM.findDOMNode(this);
		var formElmt = domElmt.getElement('form');
		formElmt.addEvent('submitSuccess', this.onSubmitSuccess.bind(this));
	}

	handleAnswerSelect(radio:HTMLInputElement) {
		this.props.question.updateData({
			selected_answer_id: radio.value
		});
	}

	onSubmitSuccess() {
		console.log('Question.onSubmitSuccess');
		if (this.props.submitSuccessAction) this.props.submitSuccessAction();
	}

	public render() {
		var answers = this.props.question.answers.map(function(answer) {
			return <Answers key={answer.id} answer={answer} selectAction={this.handleAnswerSelect.bind(this) } />;
		}, this);

		return (
	        <div>
				<h2>{this.props.question.question_text}</h2>
				<form className="NWForm:json" method="post" action={'/questions/' + this.props.question.id + '/submit'} data-nwform-successcb="onQuestionAnswered">
					<React.addons.CSSTransitionGroup component="ul" className="list-style-none" transitionName="fade" transitionEnterTimeout={500} transitionLeaveTimeout={500}>
						{answers}
					</React.addons.CSSTransitionGroup>
					<button type="submit" disabled={this.props.question.selected_answer_id ? '' : 'disabled'}>
						{this.props.question.selected_answer_id ? 'Submit (only if you\'re super sure)' : 'Choose Wisely!'}
					</button>
				</form>
			</div>
		);
	}
}


// =========================================== Answers ==========================================
interface IAnswersProps {
	key: any;
	answer: AnswerModel;
	selectAction: (radio: HTMLInputElement)=>void;
}

interface IAnswersState { }

class Answers extends React.Component<IAnswersProps, IAnswersState> {
	handleChange(event:Event) {
		var inputElmt = event.target as HTMLInputElement;
		this.props.selectAction(inputElmt);
	}

	public render() {
		return (
			<li><input type="radio" name="answer_id" value={this.props.answer.id} onChange={this.handleChange.bind(this) } /> {this.props.answer.answer_text}</li>
        );
	}
}


// =========================================== Question Model ==========================================
export class QuestionModel extends NovumWare.AbstractModel {
	id: number;
	question_text: string;
	correct_answer_id: number;
	selected_answer_id: number;

	private _answers: AnswerModel[];
	get answers(): AnswerModel[] {
		if (!this.id) return [];
		if (!this._answers) {
			console.log('QuestionModel fetching answers');
			new NWRequest.JSON({
				url: '/questions/'+this.id+'/answers',
				onSuccess: (response)=>{ this.answers = response.answers; }
			});
			this._answers = [];
		}
		return this._answers;
	}
	set answers(answers) {
		for (var answer of answers){
			var newAnswer = new AnswerModel(answer);
			newAnswer.bind('change', this.onAnswersChange.bind(this));
			this._answers.push(newAnswer);
		}
		
		// sort answers
		this._answers.sort((a: AnswerModel, b: AnswerModel) => { return a.order - b.order; });
		
		this.trigger('change');
	}

	onAnswersChange() {
		console.log('QuestionModel.onAnswersChange');
		this.trigger('change');
	}

	constructor(data?) {
		super();
		if (data) this.updateData(data);
	}

	updateData(data) {
		if (data.id) this.id = Number(data.id);
		if (data.question_text) this.question_text = data.question_text;
		if (data.correct_answer_id) this.correct_answer_id = data.correct_answer_id;
		if (data.selected_answer_id) this.selected_answer_id = data.selected_answer_id;
		super.updateData(data);
	}
}
