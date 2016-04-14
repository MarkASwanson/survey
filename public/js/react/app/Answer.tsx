/// <reference path="../../typings/browser.d.ts" />
import * as NovumWare from "../novumware";


// =========================================== Answer ==========================================
interface IAnswerProps {
	answer: AnswerModel;
}

interface IAnswerState {}

export class Answer extends React.Component<IAnswerProps, IAnswerState> {
	public render() {
		return (
	        <div>
				{this.props.answer.answer_text}
			</div>
		);
	}
}


// =========================================== Answer Model ==========================================
export class AnswerModel extends NovumWare.AbstractModel {
	id: number;
	question_id: number;
	answer_text: string;

	constructor(data?) {
		super();
		if (data) this.updateData(data);
	}

	updateData(data) {
		if (data.id) this.id = Number(data.id);
		if (data.question_id) this.question_id = Number(data.question_id);
		if (data.answer_text) this.answer_text = data.answer_text;
		super.updateData(data);
	}
}
