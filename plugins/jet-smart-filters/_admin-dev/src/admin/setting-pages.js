'use strict';

/*
 * Mixins
 */
// Settings main
const jetSmartFiltersSettinsMixin = {
	data: function () {
		return {
			data: window.jetSmartFiltersSettingsConfig.data,
			settings: window.jetSmartFiltersSettingsConfig.settings,
			preparedOptions: {},
			savingStatus: false,
			ajaxSaveHandler: null,
		};
	},

	watch: {
		settings: {
			handler(options) {
				const prepared = {};

				for (const option in options)
					prepared[option] = options[option];

				this.preparedOptions = prepared;
				this.saveOptions();
			},
			deep: true
		}
	},

	methods: {
		saveOptions: function () {
			var self = this;

			self.savingStatus = true;

			self.ajaxSaveHandler = jQuery.ajax({
				type: 'POST',
				url: window.jetSmartFiltersSettingsConfig.settingsApiUrl,
				dataType: 'json',
				data: self.preparedOptions,
				beforeSend: function (jqXHR, ajaxSettings) {
					jqXHR.setRequestHeader('X-WP-Nonce', window.jetSmartFiltersSettingsConfig.nonce);

					if (null !== self.ajaxSaveHandler) {
						self.ajaxSaveHandler.abort();
					}
				},
				success: function (responce, textStatus, jqXHR) {
					self.savingStatus = false;

					if ('success' === responce.status) {
						self.$CXNotice.add({
							message: responce.message,
							type: 'success',
							duration: 3000,
						});
					}

					if ('error' === responce.status) {
						self.$CXNotice.add({
							message: responce.message,
							type: 'error',
							duration: 3000,
						});
					}
				}
			});
		},

		repeaterAddItem: function (newItem, collection) {
			collection.push(newItem);
		},

		repeaterDeleteItem: function (index, collection) {
			collection.splice(index, 1);
		},

		updateAlias(value, index, key) {
			const alias = this.settings.url_aliases[index];

			if (!alias || alias[key] === value)
				return;

			alias[key] = value;
		},

		// Actions
		onAliasInputEvent(evt, index, key) {
			if ((evt.type !== 'keypress' || evt.keyCode !== 13) && evt.type !== 'blur')
				return;

			this.updateAlias(evt.target.value, index, key);
		}
	}
};

/*
 * Pages
 */
//______________________________ General ______________________________
Vue.component('jet-smart-filters-general-settings', {
	template: '#jet-dashboard-jet-smart-filters-general-settings',
	mixins: [jetSmartFiltersSettinsMixin],
});

//______________________________ Indexer ______________________________
Vue.component('jet-smart-filters-indexer-settings', {
	template: '#jet-dashboard-jet-smart-filters-indexer-settings',
	mixins: [jetSmartFiltersSettinsMixin],
});

//___________________________ URL structure ___________________________
Vue.component('jet-smart-filters-url-structure-settings', {
	template: '#jet-dashboard-jet-smart-filters-url-structure-settings',
	mixins: [jetSmartFiltersSettinsMixin],
});

//_____________________________ AJAX type _____________________________
Vue.component('jet-smart-filters-ajax-request-type', {
	template: '#jet-dashboard-jet-smart-filters-ajax-request-type',
	mixins: [jetSmartFiltersSettinsMixin],
});

//___________________________ Accessibility ___________________________
Vue.component('jet-smart-filters-accessibility-settings', {
	template: '#jet-dashboard-jet-smart-filters-accessibility-settings',
	mixins: [jetSmartFiltersSettinsMixin],
});

/*
 * Components
 */
// URL aliases example
Vue.component('jsf-url-aliases-example', {
	template: '#jet-smart-filters-url-aliases-example',

	props: {
		value: {
			type: String,
			default: ''
		},
		aliases: {
			type: Array,
			default: () => []
		},
		urlPrefix: {
			type: String,
			default: 'http://demo.org/'
		},
		defaultUrl: {
			type: String,
			default: ''
		},
	},

	data: function () {
		return {
			url: this.value,
			directOpened: false,
			reverseOpened: false,
			isUrlEdite: false
		};
	},

	computed: {
		directUrl() {
			return this.urlAliasesTransform(this.url);
		},
		reverseUrl() {
			return this.urlAliasesTransform(this.directUrl, true);
		},
		directTransformations() {
			return this.urlAliasesTransformations(this.url);
		},
		reverseTransformations() {
			return this.urlAliasesTransformations(this.directUrl, true);
		},
		isUrl() {
			return this.url
				? /[\/|\?|\&]jsf[\/|=]/.test(this.url) // is URL contains JSF params
				: false;
		},
		isNotDefaultUrl() {
			return this.defaultUrl && this.url !== this.defaultUrl;
		},
		isMatches() {
			return this.directTransformations.length > 0 || this.reverseTransformations.length > 0;
		}
	},

	watch: {
		value(val) {
			this.url = val;
		},
	},

	methods: {
		urlAliasesTransformations(url, reverse = false) {
			const output = [];
			let html = url,
				index = 0;

			this.aliases.forEach(alias => {
				index++;

				if (!alias.needle || !alias.replacement)
					return;

				const startPos = url.indexOf(reverse ? alias.replacement : alias.needle);

				if (startPos > -1) {
					const endPos = startPos + (reverse ? alias.needle.length : alias.replacement.length);

					url = reverse
						? url.replace(alias.replacement, alias.needle)
						: url.replace(alias.needle, alias.replacement);

					html = url.slice(0, startPos)
						+ '<span class="highlight">'
						+ url.slice(startPos, endPos)
						+ '</span>'
						+ url.slice(endPos);

					output.push({
						url,
						html,
						replacement: {
							from: reverse ? alias.replacement : alias.needle,
							to: reverse ? alias.needle : alias.replacement,
						},
						index
					});
				}
			});

			return output;
		},
		urlAliasesTransform(url, reverse = false) {
			this.aliases.forEach(alias => {
				if (!alias.needle || !alias.replacement)
					return;

				url = reverse
					? url.replace(alias.replacement, alias.needle)
					: url.replace(alias.needle, alias.replacement);
			});

			return url;
		},
		// Actions
		onUrlInput(evt) {
			// Do nothing
			return;
		},
		onUrlEditClick() {
			this.isUrlEdite = true;
			// Input set focus
			this.$nextTick(() => this.$refs.urlInput.focus());
		},
		onUrlEditRestoreClick() {
			if (this.isNotDefaultUrl)
				this.$emit('input', this.defaultUrl);
		},
		onUrlEditConfirmClick() {
			const newUrl = jQuery(this.$el).find('.url-aliases-example-string-editor-input').val();

			if (newUrl !== this.value)
				this.$emit('input', newUrl);

			// Stop editing
			this.isUrlEdite = false;
		},
		onUrlEditCancelClick() {
			this.url = this.value;
			// Stop editing
			this.isUrlEdite = false;
		},
		onDirectClick() {
			this.directOpened = !this.directOpened;
		},
		onReverseClick() {
			this.reverseOpened = !this.reverseOpened;
		}
	}
});