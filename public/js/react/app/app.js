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
            if (data.order)
                this.order = Number(data.order);
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
        Question.prototype.componentDidMount = function () {
            $nw.initContainer(ReactDOM.findDOMNode(this));
            var domElmt = ReactDOM.findDOMNode(this);
            var formElmt = domElmt.getElement('form');
            formElmt.addEvent('submitSuccess', this.onSubmitSuccess.bind(this));
        };
        Question.prototype.handleAnswerSelect = function (radio) {
            this.props.question.updateData({
                selected_answer_id: radio.value
            });
        };
        Question.prototype.onSubmitSuccess = function () {
            console.log('Question.onSubmitSuccess');
            if (this.props.submitSuccessAction)
                this.props.submitSuccessAction();
        };
        Question.prototype.render = function () {
            var answers = this.props.question.answers.map(function (answer) {
                return React.createElement(Answers, {key: answer.id, answer: answer, selectAction: this.handleAnswerSelect.bind(this)});
            }, this);
            return (React.createElement("section", {className: "panel"}, React.createElement("h1", null, this.props.question.question_text), React.createElement("form", {className: "NWForm:json", method: "post", action: '/questions/' + this.props.question.id + '/submit', "data-nwform-successcb": "onQuestionAnswered"}, React.createElement(React.addons.CSSTransitionGroup, {component: "ul", className: "list-style-none", transitionName: "fade", transitionEnterTimeout: 500, transitionLeaveTimeout: 500}, answers), React.createElement("button", {type: "submit", disabled: this.props.question.selected_answer_id ? '' : 'disabled'}, this.props.question.selected_answer_id ? 'Submit (only if you\'re super sure)' : 'Choose Wisely!'))));
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
            return (React.createElement("li", null, React.createElement("input", {id: 'answerRadio-' + this.props.answer.id, type: "radio", name: "answer_id", value: this.props.answer.id, onChange: this.handleChange.bind(this)}), " ", React.createElement("label", {htmlFor: 'answerRadio-' + this.props.answer.id}, this.props.answer.answer_text)));
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
                var _this = this;
                if (!this.id)
                    return [];
                if (!this._answers) {
                    console.log('QuestionModel fetching answers');
                    new NWRequest.JSON({
                        url: '/questions/' + this.id + '/answers',
                        onSuccess: function (response) { _this.answers = response.answers; }
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
                // sort answers
                this._answers.sort(function (a, b) { return a.order - b.order; });
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
            var _this = this;
            this.questionStore.bind('change', this.onQuestionStoreChange.bind(this));
            new NWRequest.JSON({
                url: '/questions/' + this.props.question_id,
                onSuccess: function (response) { _this.questionStore.question = response.question; }
            });
        };
        QuestionController.prototype.componentWillUnmount = function () {
            this.questionStore.unbind('change', this.onQuestionStoreChange.bind(this));
        };
        QuestionController.prototype.onQuestionStoreChange = function () {
            console.log('QuestionController.onQuestionStoreChange');
            this.setState({ question: this.questionStore.question });
        };
        QuestionController.prototype.handleSubmitSuccess = function () {
            console.log('QuestionController.handleSubmitSuccess');
            if (this.props.submitSuccessAction)
                this.props.submitSuccessAction(this.state.question);
        };
        QuestionController.prototype.handleSeeSubmissionStats = function () {
            console.log('QuestionController.handleSeeSubmissionStats');
            this.props.seeSubmissionStatsAction();
        };
        QuestionController.prototype.render = function () {
            return (React.createElement("div", null, React.createElement("h1", null, "Stop!  Answer me this question..."), React.createElement(Question_1.Question, {question: this.state.question, submitSuccessAction: this.handleSubmitSuccess.bind(this)}), React.createElement("br", null), React.createElement("a", {onClick: this.handleSeeSubmissionStats.bind(this)}, "Let me see the answer (cheater...)")));
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
define("app/SubmissionStats", ["require", "exports", "novumware"], function (require, exports, NovumWare) {
    "use strict";
    var SubmissionStats = (function (_super) {
        __extends(SubmissionStats, _super);
        function SubmissionStats() {
            _super.apply(this, arguments);
        }
        SubmissionStats.prototype.render = function () {
            var _this = this;
            var selectedAnswerText = '';
            var totalSubmissionCount = 0;
            for (var _i = 0, _a = this.props.submissionStats; _i < _a.length; _i++) {
                var submissionStat = _a[_i];
                totalSubmissionCount += submissionStat.count;
            }
            var submissionStatRows = this.props.submissionStats.map(function (submissionStat) {
                var isCorrectRow = (submissionStat.answer_id == _this.props.question.correct_answer_id);
                var isSelectedAnswer = (_this.props.question.selected_answer_id == submissionStat.answer_id);
                var percent = Math.round(submissionStat.count / totalSubmissionCount * 100) || 0;
                var selectedAnswerElmt = React.createElement("span", {className: ((isCorrectRow) ? 'positive' : 'negative') + ' selectedAnswerText tablet-andLarger'}, React.createElement("span", {className: "text"}, (isCorrectRow) ? 'Well done!' : 'Bummer...', " You chose"), " ", React.createElement("i", {className: "icon-chevron-right no-float"}));
                if (isSelectedAnswer)
                    selectedAnswerText = submissionStat.answer_text;
                return (React.createElement("li", {key: submissionStat.answer_id, className: ((isCorrectRow) ? 'positive' : 'negative') + ' borderless'}, (isSelectedAnswer) ? selectedAnswerElmt : '', React.createElement("span", {className: "count"}, "(", submissionStat.count, ") "), React.createElement("div", {className: "fullBar"}, React.createElement("div", {className: "percentBar", style: { width: percent + '%' }}), React.createElement("span", {className: "percentText"}, percent, "%")), React.createElement("span", {className: "answerText"}, React.createElement("i", {className: ((isCorrectRow) ? 'icon-checkmark' : 'icon-cancel') + ' no-float'}), " ", submissionStat.answer_text)));
            });
            var isCorrectAnswer = (this.props.question.selected_answer_id == this.props.question.correct_answer_id);
            var selectedAnswerElmt = React.createElement("div", {className: ((isCorrectAnswer) ? 'positive' : 'negative') + ' selectedAnswerText alert phone-andSmaller'}, React.createElement("span", {className: "text"}, (isCorrectAnswer) ? 'Well done!' : 'Bummer...', " You chose"), " ", React.createElement("span", {className: "answer"}, "\"", selectedAnswerText, "\""));
            return (React.createElement("div", null, (this.props.question.selected_answer_id) ? selectedAnswerElmt : '', React.createElement(React.addons.CSSTransitionGroup, {component: "ul", className: "submissionStats list-style-none", transitionName: "fade", transitionEnterTimeout: 500, transitionLeaveTimeout: 500}, submissionStatRows)));
        };
        return SubmissionStats;
    }(React.Component));
    exports.SubmissionStats = SubmissionStats;
    // =========================================== Submission Stat Model ==========================================
    var SubmissionStatModel = (function (_super) {
        __extends(SubmissionStatModel, _super);
        function SubmissionStatModel(data) {
            _super.call(this);
            if (data)
                this.updateData(data);
        }
        SubmissionStatModel.prototype.updateData = function (data) {
            if (data.answer_id)
                this.answer_id = Number(data.answer_id);
            if (data.answer_text)
                this.answer_text = data.answer_text;
            if (data.count)
                this.count = Number(data.count);
            if (data.answer_order)
                this.answer_order = Number(data.answer_order);
            _super.prototype.updateData.call(this, data);
        };
        return SubmissionStatModel;
    }(NovumWare.AbstractModel));
    exports.SubmissionStatModel = SubmissionStatModel;
});
define("app/SubmissionStatsController", ["require", "exports", "novumware", "app/Question", "app/SubmissionStats", "app/SubmissionStats"], function (require, exports, NovumWare, Question_3, SubmissionStats_1, SubmissionStats_2) {
    "use strict";
    var SubmissionStatsController = (function (_super) {
        __extends(SubmissionStatsController, _super);
        function SubmissionStatsController() {
            _super.apply(this, arguments);
            this.state = {
                question: new Question_3.QuestionModel(),
                submissionStats: []
            };
            this.submissionStatsStore = new SubmissionStatsStore();
        }
        SubmissionStatsController.prototype.componentDidMount = function () {
            var _this = this;
            this.submissionStatsStore.bind('change', this.onSubmissionStatsStoreChange.bind(this));
            // possibly load the question
            if (this.props.question.id)
                this.submissionStatsStore.question = this.props.question;
            else {
                new NWRequest.JSON({
                    url: '/questions/' + this.props.question_id,
                    onSuccess: function (response) { _this.submissionStatsStore.question = response.question; }
                });
            }
            // load the submission stats
            new NWRequest.JSON({
                url: '/questions/' + this.props.question_id + '/submission-stats',
                onSuccess: function (response) { _this.submissionStatsStore.submissionStats = response.submissionStats; }
            });
        };
        SubmissionStatsController.prototype.componentWillUnmount = function () {
            this.submissionStatsStore.unbind('change', this.onSubmissionStatsStoreChange.bind(this));
        };
        SubmissionStatsController.prototype.onSubmissionStatsStoreChange = function () {
            console.log('SubmissionStatsController.onSubmissionStatsStoreChange');
            this.setState({
                question: this.submissionStatsStore.question,
                submissionStats: this.submissionStatsStore.submissionStats
            });
        };
        SubmissionStatsController.prototype.handleTryAgain = function () {
            console.log('SubmissionStatsController.handleTrayAgain');
            this.props.tryAgainAction();
        };
        SubmissionStatsController.prototype.render = function () {
            return (React.createElement("div", null, React.createElement("h1", null, "All The Previous Answers!"), React.createElement("section", {className: "panel"}, React.createElement("h1", null, this.state.question.question_text), React.createElement(SubmissionStats_2.SubmissionStats, {question: this.state.question, submissionStats: this.state.submissionStats})), React.createElement("br", null), React.createElement("a", {onClick: this.handleTryAgain.bind(this)}, "Try again (don't give up!)")));
        };
        return SubmissionStatsController;
    }(React.Component));
    exports.SubmissionStatsController = SubmissionStatsController;
    // =========================================== SubmissionStats Store ==========================================
    var SubmissionStatsStore = (function (_super) {
        __extends(SubmissionStatsStore, _super);
        function SubmissionStatsStore() {
            _super.apply(this, arguments);
            // === QuestionModel ===
            this._question = new Question_3.QuestionModel();
            // === SubmissionStatModels ===
            this._submissions = [];
        }
        Object.defineProperty(SubmissionStatsStore.prototype, "question", {
            get: function () { return this._question; },
            set: function (questionData) {
                if (questionData instanceof Question_3.QuestionModel)
                    this._question = questionData;
                else
                    this._question = new Question_3.QuestionModel(questionData);
                this._question.bind('change', this.onQuestionChange.bind(this));
                this.trigger('change');
            },
            enumerable: true,
            configurable: true
        });
        SubmissionStatsStore.prototype.onQuestionChange = function () {
            console.log('SubmissionStatsStore.onQuestionChange');
            this.trigger('change');
        };
        Object.defineProperty(SubmissionStatsStore.prototype, "submissionStats", {
            get: function () { return this._submissions; },
            set: function (submissionStatsData) {
                this._submissions = [];
                for (var _i = 0, submissionStatsData_1 = submissionStatsData; _i < submissionStatsData_1.length; _i++) {
                    var submissionStatData = submissionStatsData_1[_i];
                    var submissionStat = new SubmissionStats_1.SubmissionStatModel(submissionStatData);
                    submissionStat.bind('change', this.onSubmissionStatsChange.bind(this));
                    this._submissions.push(submissionStat);
                }
                // sort submission stats based on answer order
                this._submissions.sort(function (a, b) { return a.answer_order - b.answer_order; });
                this.trigger('change');
            },
            enumerable: true,
            configurable: true
        });
        SubmissionStatsStore.prototype.onSubmissionStatsChange = function () {
            console.log('SubmissionStatsStore.onSubmissionStatsChange');
            this.trigger('change');
        };
        return SubmissionStatsStore;
    }(NovumWare.AbstractStore));
});
define("app/SurveyController", ["require", "exports", "app/SubmissionStatsController", "app/QuestionController", "app/Question"], function (require, exports, SubmissionStatsController_1, QuestionController_1, Question_4) {
    "use strict";
    var SurveyController = (function (_super) {
        __extends(SurveyController, _super);
        function SurveyController() {
            _super.apply(this, arguments);
            this.state = {};
        }
        SurveyController.prototype.handleQuestionSubmitSuccess = function (question) {
            console.log('SurveyController.handleQuestionSubmitSuccess');
            this.setState({
                answeredQuestion: question
            });
        };
        SurveyController.prototype.handleSeeSubmissionStats = function () {
            console.log('SurveyController.handleSeeSubmissionStats');
            this.setState({
                answeredQuestion: new Question_4.QuestionModel()
            });
        };
        SurveyController.prototype.handleTryAgain = function () {
            console.log('SurveyController.handleTryAgain');
            this.setState({
                answeredQuestion: null
            });
        };
        SurveyController.prototype.render = function () {
            var displayedPage = (this.state.answeredQuestion) ?
                React.createElement(SubmissionStatsController_1.SubmissionStatsController, {question: this.state.answeredQuestion, question_id: this.state.answeredQuestion.id || this.props.question_id, tryAgainAction: this.handleTryAgain.bind(this)}) :
                React.createElement(QuestionController_1.QuestionController, {question_id: this.props.question_id, submitSuccessAction: this.handleQuestionSubmitSuccess.bind(this), seeSubmissionStatsAction: this.handleSeeSubmissionStats.bind(this)});
            return (React.createElement("div", null, displayedPage));
        };
        return SurveyController;
    }(React.Component));
    exports.SurveyController = SurveyController;
});
