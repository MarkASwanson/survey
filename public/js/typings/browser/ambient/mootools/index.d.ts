// Type definitions for mootools v1.6.0
// Definitions by: Mark Swanson

declare interface Element {
	addEvent(eventName: string, callback: () => any): void;
	getElement(selecter:string): HTMLElement;
}
