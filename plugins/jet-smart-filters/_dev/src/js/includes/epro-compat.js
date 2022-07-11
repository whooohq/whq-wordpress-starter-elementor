import eventBus from 'includes/event-bus';

export default {
	archivePostsClass: '.elementor-widget-archive-posts',
	defaultPostsClass: '.elementor-widget-posts',
	postsSettings: {},
	skin: 'archive_classic',

	addSubscribers() {
		eventBus.subscribe('provider/content-rendered', this.eproPostRendered.bind(this));
	},

	eproPostRendered(providerName, $provider) {
		if ('epro-archive' === providerName || 'epro-posts' === providerName) {
			let postsSelector = this.defaultPostsClass,
				$archive = null,
				widgetName = 'posts',
				hasMasonry = false;

			if ('epro-archive' === providerName) {
				postsSelector = this.archivePostsClass;
				widgetName = 'archive-posts';
			}

			$archive = $provider.parent(postsSelector);

			this.fitImages($archive);
			this.postsSettings = $archive.data('settings');

			if ('widget' === $archive.data('element_type')) {
				this.skin = $archive.data('widget_type');
			} else {
				this.skin = $archive.data('element_type');
			}

			this.skin = this.skin.split(widgetName + '.');
			this.skin = this.skin[1];

			hasMasonry = this.postsSettings[this.skin + '_masonry'];

			if ('yes' === hasMasonry) {
				setTimeout(this.initMasonry($archive), 0);
			}
		}
	},

	initMasonry($archive) {
		let $container = $archive.find('.elementor-posts-container'),
			$posts = $container.find('.elementor-post'),
			settings = this.postsSettings,
			colsCount = 1,
			hasMasonry = true;

		$posts.css({
			marginTop: '',
			transitionDuration: ''
		});

		let currentDeviceMode = window.elementorFrontend.getCurrentDeviceMode();

		switch (currentDeviceMode) {
			case 'mobile':
				colsCount = settings[this.skin + '_columns_mobile'];
				break;
			case 'tablet':
				colsCount = settings[this.skin + '_columns_tablet'];
				break;
			default:
				colsCount = settings[this.skin + '_columns'];
		}

		hasMasonry = colsCount >= 2;

		$container.toggleClass('elementor-posts-masonry', hasMasonry);

		if (!hasMasonry) {
			$container.height('');
			return;
		}

		let verticalSpaceBetween = settings[this.skin + '_row_gap']['size'];

		if (!verticalSpaceBetween) {
			verticalSpaceBetween = settings[this.skin + '_item_gap']['size'];
		}

		let masonry = new elementorModules.utils.Masonry({
			container: $container,
			items: $posts.filter(':visible'),
			columnsCount: colsCount,
			verticalSpaceBetween: verticalSpaceBetween
		});

		masonry.run();
	},

	fitImage($post) {
		let $imageParent = $post.find('.elementor-post__thumbnail'),
			$image = $imageParent.find('img'),
			image = $image[0];

		if (!image) {
			return;
		}

		let imageParentRatio = $imageParent.outerHeight() / $imageParent.outerWidth(),
			imageRatio = image.naturalHeight / image.naturalWidth;

		$imageParent.toggleClass('elementor-fit-height', imageRatio < imageParentRatio);
	},

	fitImages($element) {
		let itemRatio = getComputedStyle($element[0], ':after').content;

		$element.find('.elementor-posts-container').toggleClass('elementor-has-item-ratio', !!itemRatio.match(/\d/));
		$element.find('.elementor-post').each((index, item) => {
			let $post = $(item),
				$image = $post.find('.elementor-post__thumbnail img');

			this.fitImage($post);

			$image.on('load', () => {
				this.fitImage($post);
			});
		});
	},
};