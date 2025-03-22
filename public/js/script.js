'use strict';

import './navigation.js';


const HOMEPAGE_KEY = '/';
const isHomePage = new URL(window.location.href).pathname === HOMEPAGE_KEY;

document.addEventListener('DOMContentLoaded', () => {

	if (!isHomePage) {
		document.querySelector('body').style.overflow = 'auto';
	}
});
