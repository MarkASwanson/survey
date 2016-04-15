/// <reference path="../../typings/browser.d.ts" />
import * as NovumWare from "../novumware";
import {AnswerModel} from "./Answer";
declare var NWRequest;


// =========================================== Question ==========================================
interface IQuestionProps {
	question: QuestionModel;
}

interface IQuestionState {}

export class Question extends React.Component<IQuestionProps, IQuestionState> {
	handleAnswerSelect(radio:HTMLInputElement) {
		this.props.question.updateData({
			selected_answer_id: radio.value
		});
	}

	public render() {
		var answers = this.props.question.answers.map(function(answer) {
			return <Answers key={answer.id} answer={answer} selectAction={this.handleAnswerSelect.bind(this) } />;
		}, this);

		return (
	        <div>
				<h2>{this.props.question.question_text}</h2>
				<form className="NWForm:json" method="post" action={'/questions/'+this.props.question.id+'/submit'}>
					{answers}
					<div><button type="submit" disabled={this.props.question.selected_answer_id ? '' : 'disabled'}>
						{this.props.question.selected_answer_id ? 'Submit (only if you\'re super sure)' : 'Choose Wisely!'}
					</button></div>
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
			<div>
				<input type="radio" name="answer_id" value={this.props.answer.id} onChange={this.handleChange.bind(this) } /> {this.props.answer.answer_text}
			</div>
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
				onSuccess: function(response) { this.answers = response.answers; }.bind(this)
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
