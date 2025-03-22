'use strict';

import './navigation.js';
import './publications.js';


const HOMEPAGE_KEY = '/';
const isHomePage = new URL(window.location.href).pathname.includes(HOMEPAGE_KEY); ;

document.addEventListener('DOMContentLoaded', () => {
	const primaryNav = document.querySelector('.navigation');

	const NavList = Array.from(
		document.querySelector('.navigation ul').querySelectorAll('li > a')
	);

	// toggleNavigation(NavList);

	if (!isHomePage) {
		document.querySelector('body').style.overflow = 'auto';
	} else {
		document.querySelector('body').style.overflow = 'clip';
	}
});
