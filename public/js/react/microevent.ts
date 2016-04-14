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

class MicroEvent {
	events: any[] = [];

	public bind(event, fct) {
		this.events[event] = this.events[event] || [];
		this.events[event].push(fct);
	}

	public unbind(event, fct) {
		if (event in this.events === false) return;
		this.events[event].splice(this.events[event].indexOf(fct), 1);
	}

	trigger(event, ...args:any[]) {
		if (event in this.events === false) return;
		for (var i = 0; i < this.events[event].length; i++) {
			// this.events[event][i].apply(this, Array.prototype.slice.call(arguments, 1));
			this.events[event][i].apply(this, args);
		}
	}
}
