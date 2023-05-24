/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

;// CONCATENATED MODULE: external "__UNSTABLE__elementorPackages.ui"
var external_UNSTABLE_elementorPackages_ui_namespaceObject = __UNSTABLE__elementorPackages.ui;
;// CONCATENATED MODULE: external "__UNSTABLE__elementorPackages.icons"
var external_UNSTABLE_elementorPackages_icons_namespaceObject = __UNSTABLE__elementorPackages.icons;
;// CONCATENATED MODULE: external "__UNSTABLE__elementorPackages.documents"
var external_UNSTABLE_elementorPackages_documents_namespaceObject = __UNSTABLE__elementorPackages.documents;
;// CONCATENATED MODULE: external "wp.i18n"
var external_wp_i18n_namespaceObject = wp.i18n;
;// CONCATENATED MODULE: external "__UNSTABLE__elementorPackages.v1Adapters"
var external_UNSTABLE_elementorPackages_v1Adapters_namespaceObject = __UNSTABLE__elementorPackages.v1Adapters;
;// CONCATENATED MODULE: ./packages/recently-edited/src/components/top-bar/settings-button.tsx




function SettingsButton({
  type
}) {
  const {
    isActive,
    isBlocked
  } = (0,external_UNSTABLE_elementorPackages_v1Adapters_namespaceObject.useRouteStatus)('panel/page-settings');

  /* translators: %s: Post type label. */
  const title = (0,external_wp_i18n_namespaceObject.__)('%s Settings', 'elementor').replace('%s', type.label);
  return /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.Tooltip, {
    title: title
  }, /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.Box, {
    component: "span",
    "aria-label": undefined
  }, /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.ToggleButton, {
    value: "document-settings",
    selected: isActive,
    disabled: isBlocked,
    onChange: () => (0,external_UNSTABLE_elementorPackages_v1Adapters_namespaceObject.openRoute)('panel/page-settings/settings'),
    "aria-label": title,
    size: "small"
  }, /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_icons_namespaceObject.SettingsIcon, null))));
}
;// CONCATENATED MODULE: ./packages/recently-edited/src/components/top-bar/indicator.tsx
function _extends() { _extends = Object.assign ? Object.assign.bind() : function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

function Indicator({
  title,
  status
}) {
  return /*#__PURE__*/React.createElement(Tooltip, {
    title: title
  }, /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.Stack, {
    direction: "row",
    alignItems: "center",
    spacing: 2
  }, /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.Typography, {
    variant: "body2",
    sx: {
      maxWidth: '120px'
    },
    noWrap: true
  }, title), status.value !== 'publish' && /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.Typography, {
    variant: "body2",
    sx: {
      fontStyle: 'italic'
    }
  }, "(", status.label, ")")));
}
function Tooltip(props) {
  return /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.Tooltip, _extends({
    PopperProps: {
      sx: {
        '&.MuiTooltip-popper .MuiTooltip-tooltip.MuiTooltip-tooltipPlacementBottom': {
          mt: 7
        }
      }
    }
  }, props));
}
;// CONCATENATED MODULE: external "React"
var external_React_namespaceObject = React;
;// CONCATENATED MODULE: ./packages/recently-edited/src/components/top-bar/chip-doc-type.tsx



const iconsDocType = {
  header: external_UNSTABLE_elementorPackages_icons_namespaceObject.HeaderTemplateIcon,
  footer: external_UNSTABLE_elementorPackages_icons_namespaceObject.FooterTemplateIcon,
  'single-post': external_UNSTABLE_elementorPackages_icons_namespaceObject.PostTypeIcon,
  'single-page': external_UNSTABLE_elementorPackages_icons_namespaceObject.PageTypeIcon,
  popup: external_UNSTABLE_elementorPackages_icons_namespaceObject.PopupTemplateIcon,
  archive: external_UNSTABLE_elementorPackages_icons_namespaceObject.ArchiveTemplateIcon,
  'search-results': external_UNSTABLE_elementorPackages_icons_namespaceObject.SearchResultsTemplateIcon,
  'loop-item': external_UNSTABLE_elementorPackages_icons_namespaceObject.LoopItemTemplateIcon,
  'error-404': external_UNSTABLE_elementorPackages_icons_namespaceObject.Error404TemplateIcon,
  'landing-page': external_UNSTABLE_elementorPackages_icons_namespaceObject.LandingPageTemplateIcon,
  page: external_UNSTABLE_elementorPackages_icons_namespaceObject.PageTemplateIcon,
  section: external_UNSTABLE_elementorPackages_icons_namespaceObject.SectionTemplateIcon,
  container: external_UNSTABLE_elementorPackages_icons_namespaceObject.ContainerTemplateIcon,
  'wp-page': external_UNSTABLE_elementorPackages_icons_namespaceObject.PageTypeIcon,
  'wp-post': external_UNSTABLE_elementorPackages_icons_namespaceObject.PostTypeIcon
};
function DocTypeChip({
  postType,
  docType,
  label
}) {
  const color = 'elementor_library' === postType ? 'global' : 'primary';
  const Icon = iconsDocType?.[docType] || external_UNSTABLE_elementorPackages_icons_namespaceObject.PostTypeIcon;
  return /*#__PURE__*/external_React_namespaceObject.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.Chip, {
    size: "medium",
    variant: "standard",
    label: label,
    color: color,
    icon: /*#__PURE__*/external_React_namespaceObject.createElement(Icon, null)
  });
}
;// CONCATENATED MODULE: ./packages/recently-edited/src/components/top-bar/posts-list.tsx



function PostsList({
  recentPosts
}) {
  return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.ListSubheader, {
    sx: {
      fontSize: 12,
      fontStyle: 'italic',
      pl: 4
    },
    component: "div",
    id: "nested-list-subheader"
  }, (0,external_wp_i18n_namespaceObject.__)('Recent', 'elementor')), recentPosts.length ? recentPosts.map(({
    title,
    edit_url: editUrl,
    type,
    id
  }) => /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.MenuItem, {
    key: id,
    component: "a",
    href: editUrl
  }, title, /*#__PURE__*/React.createElement(DocTypeChip, {
    postType: type.post_type,
    docType: type.doc_type,
    label: type.label
  }))) : /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.Typography, {
    variant: "caption",
    sx: {
      color: 'grey.500',
      fontStyle: 'italic',
      p: 4
    },
    component: "div",
    "aria-label": undefined
  }, (0,external_wp_i18n_namespaceObject.__)('There are no other pages or templates on this site yet.', 'elementor')));
}
;// CONCATENATED MODULE: external "wp.apiFetch"
var external_wp_apiFetch_namespaceObject = wp.apiFetch;
var external_wp_apiFetch_default = /*#__PURE__*/__webpack_require__.n(external_wp_apiFetch_namespaceObject);
;// CONCATENATED MODULE: external "wp.url"
var external_wp_url_namespaceObject = wp.url;
;// CONCATENATED MODULE: ./packages/recently-edited/src/hooks/use-recent-posts.ts



function useRecentPosts(documentId) {
  const [recentPosts, setRecentPosts] = (0,external_React_namespaceObject.useState)([]);
  const [isLoading, setIsLoading] = (0,external_React_namespaceObject.useState)(false);
  (0,external_React_namespaceObject.useEffect)(() => {
    if (documentId) {
      setIsLoading(true);
      fetchRecentlyEditedPosts(documentId).then(posts => {
        setRecentPosts(posts);
        setIsLoading(false);
      });
    }
  }, [documentId]);
  return {
    isLoading,
    recentPosts
  };
}
async function fetchRecentlyEditedPosts(documentId) {
  const queryParams = {
    posts_per_page: 5,
    post__not_in: documentId
  };
  return await external_wp_apiFetch_default()({
    path: (0,external_wp_url_namespaceObject.addQueryArgs)('/elementor/v1/site-navigation/recent-posts', queryParams)
  }).then(response => response).catch(() => []);
}
;// CONCATENATED MODULE: ./packages/recently-edited/src/components/top-bar/recently-edited.tsx
function recently_edited_extends() { recently_edited_extends = Object.assign ? Object.assign.bind() : function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return recently_edited_extends.apply(this, arguments); }







function RecentlyEdited() {
  const activeDocument = (0,external_UNSTABLE_elementorPackages_documents_namespaceObject.useActiveDocument)();
  const hostDocument = (0,external_UNSTABLE_elementorPackages_documents_namespaceObject.useHostDocument)();
  const document = activeDocument && activeDocument.type.value !== 'kit' ? activeDocument : hostDocument;
  const {
    recentPosts
  } = useRecentPosts(document?.id);
  const popupState = (0,external_UNSTABLE_elementorPackages_ui_namespaceObject.usePopupState)({
    variant: 'popover',
    popupId: 'elementor-v2-top-bar-recently-edited'
  });
  if (!document) {
    return null;
  }
  return /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.Stack, {
    direction: "row",
    spacing: 1,
    alignItems: "center",
    sx: {
      paddingInlineStart: 1,
      cursor: 'default'
    }
  }, /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.Button, recently_edited_extends({
    color: "inherit",
    size: "small",
    endIcon: /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_icons_namespaceObject.ChevronDownIcon, {
      fontSize: "small"
    })
  }, (0,external_UNSTABLE_elementorPackages_ui_namespaceObject.bindTrigger)(popupState)), /*#__PURE__*/React.createElement(Indicator, {
    title: document.title,
    status: document.status
  })), /*#__PURE__*/React.createElement(external_UNSTABLE_elementorPackages_ui_namespaceObject.Menu, recently_edited_extends({
    PaperProps: {
      sx: {
        mt: 4,
        minWidth: 314
      }
    }
  }, (0,external_UNSTABLE_elementorPackages_ui_namespaceObject.bindMenu)(popupState)), /*#__PURE__*/React.createElement(PostsList, {
    recentPosts: recentPosts
  })), /*#__PURE__*/React.createElement(SettingsButton, {
    type: document.type
  }));
}
;// CONCATENATED MODULE: external "__UNSTABLE__elementorPackages.topBar"
var external_UNSTABLE_elementorPackages_topBar_namespaceObject = __UNSTABLE__elementorPackages.topBar;
;// CONCATENATED MODULE: ./packages/recently-edited/src/init.ts


function init() {
  registerTopBarMenuItems();
}
function registerTopBarMenuItems() {
  (0,external_UNSTABLE_elementorPackages_topBar_namespaceObject.injectIntoCanvasDisplay)({
    name: 'document-recently-edited',
    filler: RecentlyEdited
  });
}
;// CONCATENATED MODULE: ./packages/recently-edited/src/index.ts

init();
(window.__UNSTABLE__elementorPackages = window.__UNSTABLE__elementorPackages || {}).recentlyEdited = __webpack_exports__;
/******/ })()
;