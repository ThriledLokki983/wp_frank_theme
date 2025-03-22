const ICONS = ['icon-download', 'icon-link'];

export class ListItem {
	constructor(listItems, container) {
		this.listItems = listItems;
		this.container = container;
	}

	renderPublication() {
		const content = this.listItems?.sort((a, b) =>
			parseInt(b.year) - parseInt(a.year)
		).map(({ title, authors, journal, year, download, link }) => `
			<li class="publication__item">
				<div>
					<h3>${title}</h3>
					<span>Journal of Cultural Analytics <strong>(${year})</strong></span>
				</div>
				<footer>
					<ul class="publishers">
						${authors.map(author => `<li><span>${author}&nbsp;</span></li>`).join('')}
					</ul>
					<ul class="links">
						${ICONS.map((icon, index) => `
							<li>
								<a disabled href="${index === 0 ? download : link}" aria-labelledby="${index === 0 ? 'Download' : 'View'} article">
									<svg class="icon">
										<use xlink:href="assets/icons/sprites.svg#${icon}"></use>
									</svg>
								</a>
							</li>
						`).join('')}
					</ul>
				</footer>
			</li>
		`).join('');

		if (!this.container) {
			console.warn('No container found............');
		}
		this.container?.insertAdjacentHTML('beforeend', content);
	}
}