(function ($) {

	"use strict";

	var JetSmartFiltersAdminData = window.JetSmartFiltersAdminData || false;

	var JetSmartFiltersAdmin = {

		init: function () {

			var self = JetSmartFiltersAdmin;

			self.currentFilterType = $('#_filter_type').val();
			self.$querySettingsBox = $('#query-settings');
			self.$notesBox = $('#filter-notes');
			self.$dateFormatsBox = $('#filter-date-formats');

			self.switchQueryVar();
			self.toggleMetaBoxes();

			$('#_filter_type').attr('required', 'required');
			$('#jet-smart-filters-indexer-button').on('click', self.reindexFilters);

			$(document)
				.ready(() => {
					self.updateExcludeInclude();
					self.updateColorImageOptions();
				})
				.on('change.JetSmartFiltersAdmin', '#_filter_type', self.switchFilterType)
				.on('change.JetSmartFiltersAdmin', '#_filter_type', self.switchQueryVar)
				.on('change.JetSmartFiltersAdmin', '#_data_source', self.switchQueryVar)
				.on('change.JetSmartFiltersAdmin', '#_date_source', self.switchQueryVar)
				.on('change.JetSmartFiltersAdmin', '#_s_by', self.switchQueryVar)
				.on('change.JetSmartFiltersAdmin', '#_data_source', self.updateExcludeInclude)
				.on('change.JetSmartFiltersAdmin', '#_source_post_type', self.updateExcludeInclude)
				.on('change.JetSmartFiltersAdmin', '#_source_taxonomy', self.updateExcludeInclude)
				.on('change.JetSmartFiltersAdmin', '#_data_source', self.updateColorImageOptions)
				.on('cx-control-init', self.updateColorImageOptions)
				.on('change.JetSmartFiltersAdmin', '#_source_post_type', self.initColorImageOptions)
				.on('change.JetSmartFiltersAdmin', '#_source_taxonomy', self.initColorImageOptions)
				.on('change.JetSmartFiltersAdmin', '#_color_image_type', self.switchColorImageControls);

			$(window).on('cx-switcher-change', self.switchQueryVar);

		},

		reindexFilters: function () {

			var $this = $(this),
				defaultText = $this.data('default-text'),
				loadingText = $this.data('loading-text');

			$this.addClass('loading');
			$this.html(loadingText);

			$.ajax({
				url: JetSmartFiltersAdminData.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'jet_smart_filters_admin_indexer',
				},
			}).done(function (response) {
				$this.removeClass('loading');
				$this.html(defaultText);
			});
		},

		updateColorImageOptions: function (event, item) {
			JetSmartFiltersAdmin.switchColorImageControls();
			JetSmartFiltersAdmin.initColorImageOptions(item);
		},

		updateExcludeInclude: function () {

			var taxonomy = $('#_source_taxonomy option:selected').val(),
				postType = $('#_source_post_type option:selected').val(),
				source = $('#_data_source option:selected').val();

			if ($('#_data_exclude_include').length === 0) {
				return;
			}

			$.ajax({
				url: JetSmartFiltersAdminData.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'jet_smart_filters_admin',
					taxonomy: taxonomy,
					post_type: postType,
				},
			}).done(function (response) {

				var excludeIncludeInput = $('#_data_exclude_include');

				switch (source) {
					case 'taxonomies':
						excludeIncludeInput.html(response.terms);
						break;
					case 'posts':
						excludeIncludeInput.html(response.posts);
						break;
				}

				excludeIncludeInput.val(JetSmartFiltersAdminData.dataExcludeInclude);
				excludeIncludeInput.trigger('change');

			});

		},

		switchColorImageControls: function () {

			var filter_type = $('#_filter_type option:selected').val(),
				type = $('#_color_image_type option:selected').val(),
				source = $('#_data_source option:selected').val(),
				repeater = $('.jet-smart-filters-color-image');

			if ('color-image' === filter_type) {
				repeater.attr('data-type', type);
				repeater.attr('data-source', source);
			}

		},

		initColorImageOptions: function (item) {

			var taxonomy = $('#_source_taxonomy option:selected').val(),
				filter_type = $('#_filter_type option:selected').val(),
				postType = $('#_source_post_type option:selected').val(),
				source = $('#_data_source option:selected').val(),
				showEmptyTerms = $('#_show_empty_terms-true').prop('checked');

			if ('color-image' !== filter_type) {
				return;
			}

			$.ajax({
				url: JetSmartFiltersAdminData.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'jet_smart_filters_admin',
					post_type: postType,
					taxonomy: taxonomy,
					hide_empty: !showEmptyTerms
				},
			}).done(function (response) {
				var is_last = false,
					$repeaterItems = $('.cx-ui-select[name*="selected_value"]');

				if (item) {
					$repeaterItems = $('.cx-ui-select[name*="selected_value"]', item.target);
					is_last = true;
				}

				switch (source) {
					case 'taxonomies':
						$repeaterItems.each(function () {
							$(this).html(response.terms);
						});
						break;
					case 'posts':
						$repeaterItems.each(function () {
							$(this).html(response.posts);
						});
						break;
				}

				if (!is_last) {
					JetSmartFiltersAdmin.setColorImageOptions();
				}
			});

		},

		setColorImageOptions: function () {
			var options = JetSmartFiltersAdminData.dataColorImage,
				index = 0;

			for (const key in options) {
				const option = options[key];

				$('.cx-ui-select[name="_source_color_image_input[item-' + index + '][selected_value]"]').val(option.selected_value);

				index++;
			}
		},

		switchQueryVar: function (event) {
			var type = $('#_filter_type option:selected').val(),
				source = $('#_data_source option:selected').val(),
				sourceSelect = $('#_data_source'),
				dateSource = $('#_date_source option:selected').val(),
				sBy = $('#_s_by option:selected').val(),
				isHierarchical = $('#_is_hierarchical-true').prop('checked'),
				isCustomCheckbox = $('#_is_custom_checkbox-true').prop('checked'),
				types = ['checkboxes', 'select', 'radio', 'color-image'],
				sources = ['taxonomies'],
				$queryVar = $('div[data-control-name="_query_var"]'),
				queryVarHidden = false,
				$queryCompare = $('div[data-control-name="_query_compare"]'),
				queryCompareHidden = true;

			if ('color-image' === type) {
				sourceSelect.find('option[value="custom_fields"]').addClass('cx-control-hidden');

				if ('custom_fields' === source) {
					sourceSelect.val('').change();
				}
			} else {
				sourceSelect.find('option[value="custom_fields"]').removeClass('cx-control-hidden');
			}

			if (isHierarchical || 'alphabet' === type) {
				queryVarHidden = true;
			} else if ('search' === type) {
				if ('default' === sBy) {
					queryVarHidden = true;
				} else {
					queryVarHidden = false;
				}
			} else if (['date-range', 'date-period'].includes(type)) {
				if ('date_query' === dateSource) {
					queryVarHidden = true;
				} else {
					queryVarHidden = false;
				}
			} else if (-1 !== types.indexOf(type) && -1 !== sources.indexOf(source)) {
				queryVarHidden = true;
			}

			$.each(['select', 'radio'], function (index, value) {
				if (value === type && !isCustomCheckbox) {
					queryCompareHidden = false;
				}
			});

			if (queryVarHidden && !$queryVar.hasClass('cx-control-hidden')) {
				$queryVar
					.addClass('cx-control-hidden')
					.find('input[name="_query_var"]')
					.removeAttr('required');
			}

			if (!queryVarHidden && $queryVar.hasClass('cx-control-hidden')) {
				$queryVar
					.removeClass('cx-control-hidden')
					.find('input[name="_query_var"]')
					.attr('required', 'required');
			}

			if (queryVarHidden || queryCompareHidden) {
				$queryCompare
					.addClass('cx-control-hidden');
			} else {
				$queryCompare
					.removeClass('cx-control-hidden');
			}

			if (!event)
				return;

			if (event.controlName === '_show_empty_terms') {
				JetSmartFiltersAdmin.initColorImageOptions();
			}
		},

		switchFilterType: function (event) {
			const filterType = event.target.value;

			JetSmartFiltersAdmin.currentFilterType = filterType;
			JetSmartFiltersAdmin.toggleMetaBoxes();
		},

		toggleMetaBoxes() {
			// DateFormats
			if (['date-range', 'date-period'].includes(JetSmartFiltersAdmin.currentFilterType)) {
				JetSmartFiltersAdmin.$dateFormatsBox.show();
			} else {
				JetSmartFiltersAdmin.$dateFormatsBox.hide();
			}

			// Alphabet Type
			if ('alphabet' === JetSmartFiltersAdmin.currentFilterType) {
				JetSmartFiltersAdmin.$querySettingsBox.hide();
				JetSmartFiltersAdmin.$notesBox.hide();
			} else {
				JetSmartFiltersAdmin.$querySettingsBox.show();
				JetSmartFiltersAdmin.$notesBox.show();
			}
		}

	};

	JetSmartFiltersAdmin.init();

}(jQuery));
