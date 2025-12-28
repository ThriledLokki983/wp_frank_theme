class SelectDropdown {
	_listContainer = '';
	_selectElement = '';

	constructor(containerName) {
		this._listContainer = document.querySelector(`.${containerName}`);

		if (!this._listContainer) {
			console.warn(`................No publications filter found............`);
			return;
		}

		this._selectElement = document.querySelector('select[data-select-element]');
	}

	init() {
		if (!this._listContainer || !this._selectElement) {
			console.warn('................No publications filter found............');
			return;
		}
		this._selectElement.addEventListener('change', this.applyFilter.bind(this));
	}

	applyFilter(event) {
		if (!this._listContainer || !event.target) {
			console.warn('................No publications filter found............');
			return;
		}

		const selectedValue = event.target.value?.toLowerCase() || 'all';
		const publicationsList = Array.from(this._listContainer.querySelectorAll('li'));
		const staggerDelay = 50; // milliseconds between each card animation

		// First, hide all items that don't match the filter
		publicationsList.forEach((pub, index) => {
			const publicationType = pub.dataset.publicationType?.toLowerCase() || '';
			const shouldShow = selectedValue === 'all' || publicationType === selectedValue;

			if (!shouldShow) {
				// Add animation-out class for fade out effect
				pub.classList.add('animate-filter-out');
				pub.setAttribute('data-hidden', 'true');

				// After animation completes, hide the element
				pub.addEventListener('animationend', () => {
					pub.style.display = 'none';
					pub.classList.remove('animate-filter-out');
				}, { once: true });
			}
		});

		// Then, show all items that match with staggered timing
		setTimeout(() => {
			// Get only the items that match the current filter
			const visibleItems = publicationsList.filter(pub => {
				const publicationType = pub.dataset.publicationType?.toLowerCase() || '';
				return selectedValue === 'all' || publicationType === selectedValue;
			});

			// Apply staggered animation to matching items
			visibleItems.forEach((pub, index) => {
				setTimeout(() => {
					pub.style.display = 'block';
					pub.classList.add('animate-filter-in');
					pub.setAttribute('data-hidden', 'false');

					pub.addEventListener('animationend', () => {
						pub.classList.remove('animate-filter-in');
					}, { once: true });
				}, index * staggerDelay);
			});
		}, 300); // Wait for hide animations to complete
	}
}

export default SelectDropdown;