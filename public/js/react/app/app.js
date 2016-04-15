var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
};
/**
 * MicroEvent - to make any js object an event emitter (server or browser)
 *
 * - pure javascript - server compatible, browser compatible
 * - dont rely on the browser doms
 * - super simple - you get it immediatly, no mistery, no magic involved
 *
 * - create a MicroEventDebug with goodies to debug
 *   - make it safer to use
*/
var MicroEvent = (function () {
    function MicroEvent() {
        this.events = [];
    }
    MicroEvent.prototype.bind = function (event, fct) {
        this.events[event] = this.events[event] || [];
        this.events[event].push(fct);
    };
    MicroEvent.prototype.unbind = function (event, fct) {
        if (event in this.events === false)
            return;
        this.events[event].splice(this.events[event].indexOf(fct), 1);
    };
    MicroEvent.prototype.trigger = function (event) {
        var args = [];
        for (var _i = 1; _i < arguments.length; _i++) {
            args[_i - 1] = arguments[_i];
        }
        if (event in this.events === false)
            return;
        for (var i = 0; i < this.events[event].length; i++) {
            // this.events[event][i].apply(this, Array.prototype.slice.call(arguments, 1));
            this.events[event][i].apply(this, args);
        }
    };
    return MicroEvent;
}());
define("novumware", ["require", "exports"], function (require, exports) {
    "use strict";
    // from the Typescript docs
    function applyMixins(derivedCtor) {
        var baseCtors = [];
        for (var _i = 1; _i < arguments.length; _i++) {
            baseCtors[_i - 1] = arguments[_i];
        }
        baseCtors.forEach(function (baseCtor) {
            Object.getOwnPropertyNames(baseCtor.prototype).forEach(function (name) {
                derivedCtor.prototype[name] = baseCtor.prototype[name];
            });
        });
    }
    exports.applyMixins = applyMixins;
    // ==================================================== Store ============================================
    var AbstractStore = (function () {
        function AbstractStore() {
            // MicroEvent
            this.events = [];
        }
        return AbstractStore;
    }());
    exports.AbstractStore = AbstractStore;
    applyMixins(AbstractStore, MicroEvent);
    // ==================================================== Model ============================================
    var AbstractModel = (function () {
        function AbstractModel() {
            // MicroEvent
            this.events = [];
        }
        AbstractModel.prototype.updateData = function (data) {
            this.trigger('change');
        };
        return AbstractModel;
    }());
    exports.AbstractModel = AbstractModel;
    applyMixins(AbstractModel, MicroEvent);
});
define("app/Answer", ["require", "exports", "novumware"], function (require, exports, NovumWare) {
    "use strict";
    var Answer = (function (_super) {
        __extends(Answer, _super);
        function Answer() {
            _super.apply(this, arguments);
        }
        Answer.prototype.render = function () {
            return (React.createElement("div", null, this.props.answer.answer_text));
        };
        return Answer;
    }(React.Component));
    exports.Answer = Answer;
    // =========================================== Answer Model ==========================================
    var AnswerModel = (function (_super) {
        __extends(AnswerModel, _super);
        function AnswerModel(data) {
            _super.call(this);
            if (data)
                this.updateData(data);
        }
        AnswerModel.prototype.updateData = function (data) {
            if (data.id)
                this.id = Number(data.id);
            if (data.question_id)
                this.question_id = Number(data.question_id);
            if (data.answer_text)
                this.answer_text = data.answer_text;
            _super.prototype.updateData.call(this, data);
        };
        return AnswerModel;
    }(NovumWare.AbstractModel));
    exports.AnswerModel = AnswerModel;
});
define("app/Question", ["require", "exports", "novumware", "app/Answer"], function (require, exports, NovumWare, Answer_1) {
    "use strict";
    var Question = (function (_super) {
        __extends(Question, _super);
        function Question() {
            _super.apply(this, arguments);
        }
        Question.prototype.handleAnswerSelect = function (radio) {
            this.props.question.updateData({
                selected_answer_id: radio.value
            });
        };
        Question.prototype.render = function () {
            var answers = this.props.question.answers.map(function (answer) {
                return React.createElement(Answers, {key: answer.id, answer: answer, selectAction: this.handleAnswerSelect.bind(this)});
            }, this);
            return (React.createElement("div", null, React.createElement("h2", null, this.props.question.question_text), React.createElement("form", {className: "NWForm:json", method: "post", action: '/questions/' + this.props.question.id + '/submit'}, answers, React.createElement("div", null, React.createElement("button", {type: "submit", disabled: this.props.question.selected_answer_id ? '' : 'disabled'}, this.props.question.selected_answer_id ? 'Submit (only if you\'re super sure)' : 'Choose Wisely!')))));
        };
        return Question;
    }(React.Component));
    exports.Question = Question;
    var Answers = (function (_super) {
        __extends(Answers, _super);
        function Answers() {
            _super.apply(this, arguments);
        }
        Answers.prototype.handleChange = function (event) {
            var inputElmt = event.target;
            this.props.selectAction(inputElmt);
        };
        Answers.prototype.render = function () {
            return (React.createElement("div", null, React.createElement("input", {type: "radio", name: "answer_id", value: this.props.answer.id, onChange: this.handleChange.bind(this)}), " ", this.props.answer.answer_text));
        };
        return Answers;
    }(React.Component));
    // =========================================== Question Model ==========================================
    var QuestionModel = (function (_super) {
        __extends(QuestionModel, _super);
        function QuestionModel(data) {
            _super.call(this);
            if (data)
                this.updateData(data);
        }
        Object.defineProperty(QuestionModel.prototype, "answers", {
            get: function () {
                if (!this.id)
                    return [];
                if (!this._answers) {
                    console.log('QuestionModel fetching answers');
                    new NWRequest.JSON({
                        url: '/questions/' + this.id + '/answers',
                        onSuccess: function (response) { this.answers = response.answers; }.bind(this)
                    });
                    this._answers = [];
                }
                return this._answers;
            },
            set: function (answers) {
                for (var _i = 0, answers_1 = answers; _i < answers_1.length; _i++) {
                    var answer = answers_1[_i];
                    var newAnswer = new Answer_1.AnswerModel(answer);
                    newAnswer.bind('change', this.onAnswersChange.bind(this));
                    this._answers.push(newAnswer);
                }
                this.trigger('change');
            },
            enumerable: true,
            configurable: true
        });
        QuestionModel.prototype.onAnswersChange = function () {
            console.log('QuestionModel.onAnswersChange');
            this.trigger('change');
        };
        QuestionModel.prototype.updateData = function (data) {
            if (data.id)
                this.id = Number(data.id);
            if (data.question_text)
                this.question_text = data.question_text;
            if (data.correct_answer_id)
                this.correct_answer_id = data.correct_answer_id;
            if (data.selected_answer_id)
                this.selected_answer_id = data.selected_answer_id;
            _super.prototype.updateData.call(this, data);
        };
        return QuestionModel;
    }(NovumWare.AbstractModel));
    exports.QuestionModel = QuestionModel;
});
define("app/QuestionController", ["require", "exports", "novumware", "app/Question", "app/Question"], function (require, exports, NovumWare, Question_1, Question_2) {
    "use strict";
    var QuestionController = (function (_super) {
        __extends(QuestionController, _super);
        function QuestionController() {
            _super.apply(this, arguments);
            this.state = { question: new Question_2.QuestionModel() };
            this.questionStore = new QuestionStore();
        }
        QuestionController.prototype.componentDidMount = function () {
            this.questionStore.bind('change', this.onQuestionStoreChange.bind(this));
            new NWRequest.JSON({
                url: this.props.questionUrl,
                onSuccess: function (response) {
                    this.questionStore.question = response.question;
                }.bind(this)
            });
            $nw.initContainer(ReactDOM.findDOMNode(this));
        };
        QuestionController.prototype.componentWillUnmount = function () {
            this.questionStore.unbind('change', this.onQuestionStoreChange.bind(this));
        };
        QuestionController.prototype.onQuestionStoreChange = function () {
            console.log('QuestionController.onQuestionStoreChange');
            this.setState({ question: this.questionStore.question });
        };
        QuestionController.prototype.render = function () {
            return (React.createElement("div", null, React.createElement("h1", null, "Stop!  Answer me this question..."), React.createElement(Question_1.Question, {question: this.state.question})));
        };
        return QuestionController;
    }(React.Component));
    exports.QuestionController = QuestionController;
    // =========================================== Question Store ==========================================
    var QuestionStore = (function (_super) {
        __extends(QuestionStore, _super);
        function QuestionStore() {
            _super.apply(this, arguments);
            this._question = new Question_2.QuestionModel();
        }
        Object.defineProperty(QuestionStore.prototype, "question", {
            get: function () { return this._question; },
            set: function (question) {
                if (question instanceof Question_2.QuestionModel)
                    this._question = question;
                else
                    this._question = new Question_2.QuestionModel(question);
                this._question.bind('change', this.onQuestionChange.bind(this));
                this.trigger('change');
            },
            enumerable: true,
            configurable: true
        });
        QuestionStore.prototype.onQuestionChange = function () {
            console.log('QuestionStore.onQuestionChange');
            this.trigger('change');
        };
        return QuestionStore;
    }(NovumWare.AbstractStore));
});
define("app/SurveyController", ["require", "exports", "./Survey", "./Survey"], function (require, exports, Survey_1, Survey_2) {
    "use strict";
    var SurveyController = (function (_super) {
        __extends(SurveyController, _super);
        function SurveyController() {
            _super.apply(this, arguments);
            this.state = { survey: new Survey_2.SurveyModel() };
            this.surveyStore = new SurveyStore();
        }
        SurveyController.prototype.componentDidMount = function () {
            this.surveyStore.bind('change', this.onSurveyStoreChange.bind(this));
            new NWRequest.JSON({
                url: this.props.surveyUrl,
                onSuccess: function (response) {
                    this.surveyStore = response.survey;
                }.bind(this)
            });
        };
        SurveyController.prototype.componentWillUnmount = function () {
            this.surveyStore.unbind('change', this.onSurveyStoreChange.bind(this));
        };
        SurveyController.prototype.onSurveyStoreChange = function () {
            console.log('SurveyController.onSurveyStoreChange');
            this.setState({ survey: this.surveyStore.survey });
        };
        SurveyController.prototype.render = function () {
            return React.createElement(Survey_1.Survey, {survey: this.state.survey});
        };
        return SurveyController;
    }(React.Component));
    exports.SurveyController = SurveyController;
});
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
