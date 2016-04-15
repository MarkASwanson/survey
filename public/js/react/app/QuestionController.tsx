/// <reference path="../../typings/browser.d.ts" />
import * as NovumWare from "../novumware";
import {Question} from "./Question";
import {QuestionModel} from "./Question";
declare var NWRequest;


// =========================================== Question Controller ==========================================
interface IQuestionControllerProps {
	question_id: number;
	submitSuccessAction?: (question:QuestionModel) => void;
}

interface IQuestionControllerState {
	question: QuestionModel;
}

export class QuestionController extends React.Component<IQuestionControllerProps, IQuestionControllerState> {
	state: IQuestionControllerState = { question: new QuestionModel() };

	private questionStore: QuestionStore = new QuestionStore();

	componentDidMount() {
		this.questionStore.bind('change', this.onQuestionStoreChange.bind(this));

		new NWRequest.JSON({
			url: '/questions/' + this.props.question_id,
			onSuccess: (response)=>{ this.questionStore.question = response.question; }
		});
	}

	componentWillUnmount() {
		this.questionStore.unbind('change', this.onQuestionStoreChange.bind(this));
	}

	onQuestionStoreChange() {
		console.log('QuestionController.onQuestionStoreChange');
		this.setState({ question: this.questionStore.question });
	}

	handleSubmitSuccess() {
		console.log('QuestionController.handleSubmitSuccess');
		if (this.props.submitSuccessAction) this.props.submitSuccessAction(this.state.question);
	}

	render() {
		return (
			<div>
				<h1>Stop!  Answer me this question...</h1>	   
				<Question question={this.state.question} submitSuccessAction={this.handleSubmitSuccess.bind(this)} />
	        </div>
        )
	}
}


// =========================================== Question Store ==========================================
class QuestionStore extends NovumWare.AbstractStore {
	private _question: QuestionModel = new QuestionModel();
	get question() { return this._question; }
	set question(question) {
		if (question instanceof QuestionModel) this._question = question;
		else this._question = new QuestionModel(question);
		this._question.bind('change', this.onQuestionChange.bind(this));
		this.trigger('change');
	}

	onQuestionChange() {
		console.log('QuestionStore.onQuestionChange');
		this.trigger('change');
	}
}
