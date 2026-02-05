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

		// Check URL for pre-selected filter value
		this._applyUrlFilter();
	}

	_applyUrlFilter() {
		// First check for data-initial-filter attribute set by WordPress
		const mainElement = document.querySelector('[data-initial-filter]');
		let filterValue = mainElement?.dataset.initialFilter?.toLowerCase();

		// If no data attribute, check URL path (e.g., /research/publications/papers/)
		if (!filterValue) {
			const pathSegments = window.location.pathname.split('/').filter(Boolean);
			const lastSegment = pathSegments[pathSegments.length - 1]?.toLowerCase();

			// Also check query parameter (e.g., ?filter=papers)
			const urlParams = new URLSearchParams(window.location.search);
			const queryFilter = urlParams.get('filter')?.toLowerCase();

			// Use query param first, then path segment
			filterValue = queryFilter || lastSegment;
		}

		// Map plural to singular (papers -> paper)
		const filterMappings = {
			'papers': 'paper',
			'abstracts': 'abstract'
		};
		filterValue = filterMappings[filterValue] || filterValue;

		if (filterValue && filterValue !== 'publications' && filterValue !== 'research') {
			// Find matching option in dropdown
			const options = Array.from(this._selectElement.options);
			const matchingOption = options.find(opt => 
				opt.value.toLowerCase() === filterValue
			);

			if (matchingOption) {
				// Set the dropdown value
				this._selectElement.value = matchingOption.value;
				// Trigger the filter
				this.applyFilter({ target: this._selectElement });
			}
		}
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