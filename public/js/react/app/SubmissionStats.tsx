/// <reference path="../../typings/browser.d.ts" />
import * as NovumWare from "../novumware";
import {QuestionModel} from "./Question";
declare var NWRequest;


// =========================================== SubmissionStats ==========================================
interface ISubmissionStatsProps {
	question: QuestionModel;
	submissionStats: SubmissionStatModel[];
}

interface ISubmissionStatsState {}

export class SubmissionStats extends React.Component<ISubmissionStatsProps, ISubmissionStatsState> {
	render() {
		var totalSubmissionCount = 0;
		for (var submissionStat of this.props.submissionStats) totalSubmissionCount += submissionStat.count;

		var submissionStatRows = this.props.submissionStats.map((submissionStat) => {
			var isCorrectRow = (submissionStat.answer_id == this.props.question.correct_answer_id);
			var percent = Math.round(submissionStat.count / totalSubmissionCount * 100);

			return (
				<li key={submissionStat.id} className={((isCorrectRow)?'positive':'negative')+' borderless'}>
					<strong>({submissionStat.count}) </strong>
					<div className="fullBar">
						<div className="percentBar" style={{ width: percent + '%' }}></div>
						<span className="percentText">{percent}%</span>
					</div>
					<span className="answerText"><i className={((isCorrectRow)?'icon-checkmark':'icon-cancel')+' no-float'}></i> {submissionStat.answer_text}</span>
				</li>
			);
		});

		return (
			<ul className="submissionStats list-style-none">
				{submissionStatRows}
			</ul>
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
