class SelectDropdown {
	_listContainer = '';
	_selectElement = '';
	_emptyState = '';

	constructor(containerName) {
		this._listContainer = document.querySelector(`.${containerName}`);

		if (!this._listContainer) {
			console.warn(`................No publications filter found............`);
			return;
		}

		this._selectElement = document.querySelector('select[data-select-element]');
		this._emptyState = document.querySelector('[data-empty-state]');
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
		const staggerDelay = 50;

		// First, hide items that don't match
		publicationsList.forEach((pub) => {
			const publicationType = pub.dataset.publicationType?.toLowerCase() || '';
			const shouldShow = selectedValue === 'all' || publicationType === selectedValue;

			if (!shouldShow) {
				pub.setAttribute('data-hidden', 'true');
			}
		});

		// Then show matching items with staggered animation
		let visibleIndex = 0;
		publicationsList.forEach((pub) => {
			const publicationType = pub.dataset.publicationType?.toLowerCase() || '';
			const shouldShow = selectedValue === 'all' || publicationType === selectedValue;

			if (shouldShow) {
				setTimeout(() => {
					pub.setAttribute('data-hidden', 'false');
				}, visibleIndex * staggerDelay);
				visibleIndex++;
			}
		});

		// Show/hide empty state based on whether any items are visible
		if (this._emptyState) {
			if (visibleIndex === 0) {
				this._emptyState.setAttribute('data-visible', 'true');
			} else {
				this._emptyState.setAttribute('data-visible', 'false');
			}
		}
	}
}

export default SelectDropdown;