import SelectDropdown from "./selectDropdown.js";

const researchSelect = new SelectDropdown('js-publication__list');
researchSelect.init();


const publicationSelect = new SelectDropdown('ul[data-pub-list]');
publicationSelect.init();


const projectsSelect = new SelectDropdown('ul[data-project-list]');
projectsSelect.init();