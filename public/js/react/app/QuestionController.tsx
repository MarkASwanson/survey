/// <reference path="../../typings/browser.d.ts" />
import * as NovumWare from "../novumware";
import {Question as Question} from "./Question";
import {QuestionModel as QuestionModel} from "./Question";
declare var $nw;
declare var NWRequest;


// =========================================== Question Controller ==========================================
interface IQuestionControllerProps {
	questionUrl: string;
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
			url: this.props.questionUrl,
			onSuccess: function(response) {
				this.questionStore.question = response.question;
			}.bind(this)
		});

		$nw.initContainer(ReactDOM.findDOMNode(this));
	}

	componentWillUnmount() {
		this.questionStore.unbind('change', this.onQuestionStoreChange.bind(this));
	}

	onQuestionStoreChange() {
		console.log('QuestionController.onQuestionStoreChange');
		this.setState({ question: this.questionStore.question });
	}

	render() {
		return (
			<div>
				<h1>Stop!  Answer me this question...</h1>	   
		        <Question question={this.state.question} />
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
