// from the Typescript docs
export function applyMixins(derivedCtor: any, ...baseCtors: any[]) {
	baseCtors.forEach(baseCtor => {
		Object.getOwnPropertyNames(baseCtor.prototype).forEach(name => {
			derivedCtor.prototype[name] = baseCtor.prototype[name];
		});
	});
}


// ==================================================== Store ============================================
export abstract class AbstractStore implements MicroEvent {
	// MicroEvent
	events: any[] = [];
	bind: (event, fct) => void;
	unbind: (event, fct) => void;
	trigger: (event, ...args: any[]) => void;
}
applyMixins(AbstractStore, MicroEvent);


// ==================================================== Model ============================================
export abstract class AbstractModel implements MicroEvent {
	updateData(data) {
		this.trigger('change');
	}

	// MicroEvent
	events: any[] = [];
	bind: (event, fct) => void;
	unbind: (event, fct) => void;
	trigger: (event, ...args: any[]) => void;
}
applyMixins(AbstractModel, MicroEvent);
