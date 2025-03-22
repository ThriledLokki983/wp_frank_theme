import { PUBLICATIONS } from "./data/publications.js";
import { ListItem } from "./utils.js";

const publicationsContainer = document.querySelector('.js-publication__list');
if (!publicationsContainer) {
	console.warn('................No container found............');
} else {
	const publications = new ListItem(PUBLICATIONS, publicationsContainer);
	publications?.renderPublication();
}
