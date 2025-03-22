// Add a click event to the navigation button to toggle the navigation menu
import trapFocus from "./inert.js";

// Constants and DOM elements
const MOBILE_BREAKPOINT = 960;
const nav = document.querySelector('#primary-navigation');
const list = nav.querySelector('ul');
const navItems = Array.from(list?.querySelectorAll('li.nav__item'));
const burgerClone = document.querySelector('#burger-template')?.content.cloneNode(true);
const button = burgerClone?.querySelector('button');

if (!nav || !list || !button) {
    console.error('Required navigation elements not found');
    throw new Error('Required navigation elements not found');
}

// Handle burger menu click
const handleBurgerClick = () => {
    const isOpen = button.getAttribute('aria-expanded') === 'false';
    button.setAttribute('aria-expanded', isOpen);
    nav.setAttribute('data-mobile-expanded', isOpen);
    trapFocus(nav);
};

// Insert burger menu and add click handler
nav.insertBefore(burgerClone, list);
button.addEventListener('click', handleBurgerClick);

// Handle submenu interactions
const handleSubmenuToggle = (item, target, subMenu) => {
    if (!target || !subMenu) return;

    const isOpen = target.getAttribute('data-mobile-expanded') === 'false';
    const elements = [target, subMenu, item];

    elements.forEach(el => el.setAttribute('data-mobile-expanded', isOpen));

    if (window.innerWidth < MOBILE_BREAKPOINT) {
        const height = subMenu.scrollHeight;
        const padding = 20;

        subMenu.style.height = isOpen ? `${height + padding}px` : '0';
        subMenu.style.transition = `height 0.3s ${isOpen ? 'ease-in-out' : 'ease-out'}`;
    }
};

// Add click events to navigation items for mobile submenu toggle
navItems.forEach(item => {
    item.addEventListener('click', (e) => {
        const target = e.target.closest('button.toggle__icon');
        const subMenu = item.querySelector('div.sub__menu');
        handleSubmenuToggle(item, target, subMenu);
    });
});

// Add resize listener to handle mobile/desktop transitions
const mediaQuery = window.matchMedia(`(min-width: ${MOBILE_BREAKPOINT}px)`);
mediaQuery.addEventListener('change', () => {
    navItems.forEach(item => {
        const subMenu = item.querySelector('div.sub__menu');
        if (subMenu) {
            subMenu.style.height = '';
            subMenu.style.transition = '';
        }
    });
});
