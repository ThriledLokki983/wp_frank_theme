
/**
 * This handles click events on the navigation links and sets an attribute [data-expanded="true"]
 * then add a rotate class to the arrow icon as well
 */
const toggleDataExpanded = () => {
	const navList = Array.from(document.querySelectorAll('.nav__item'));
	
	console.log(navList)

	navList?.forEach((item) => {
		// wrapLink(item);

		item.addEventListener('click', (event) => {
			const target = event.target?.parentElement
			target?.toggleAttribute('aria-selected');
			// target?.querySelector('.sub__menu')?.toggleAttribute('data-expanded');
			// target?.querySelector('.mobile-icon')?.classList.toggle('rotate');

			navList.forEach(
				(otherItem) => {
					if (otherItem !== item) {
						otherItem.removeAttribute('aria-selected');
						otherItem.querySelector('.mobile-icon')?.classList.remove('rotate');
						otherItem.querySelector('.sub__menu').removeAttribute('data-expanded');

					}
				}
			);
		});
	});
};
