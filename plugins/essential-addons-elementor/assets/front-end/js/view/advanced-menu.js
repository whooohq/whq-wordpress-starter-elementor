/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/advanced-menu.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/advanced-menu.js":
/*!**************************************!*\
  !*** ./src/js/view/advanced-menu.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var AdvancedMenu = function AdvancedMenu($scope, $) {\n  var $indicator_class = $('.eael-advanced-menu-container', $scope).data('indicator-class');\n  var $dropdown_indicator_class = $('.eael-advanced-menu-container', $scope).data('dropdown-indicator-class');\n  var $horizontal = $('.eael-advanced-menu', $scope).hasClass('eael-advanced-menu-horizontal');\n  var $fullWidth = $('.eael-advanced-menu--stretch');\n\n  if ($horizontal) {\n    // insert indicator\n    $('.eael-advanced-menu > li.menu-item-has-children', $scope).each(function () {\n      $('> a', $(this)).append('<span class=\"' + $indicator_class + '\"></span>');\n    });\n    $('.eael-advanced-menu > li ul li.menu-item-has-children', $scope).each(function () {\n      $('> a', $(this)).append('<span class=\"' + $dropdown_indicator_class + '\"></span>');\n    }); // insert responsive menu toggle, text\n\n    $('.eael-advanced-menu-horizontal', $scope).before('<span class=\"eael-advanced-menu-toggle-text\"></span>').after('<button class=\"eael-advanced-menu-toggle\"><span class=\"eicon-menu-bar\"></span></button>'); // responsive menu slide\n\n    $('.eael-advanced-menu-container', $scope).on('click', '.eael-advanced-menu-toggle', function (e) {\n      e.preventDefault();\n      var $siblings = $(this).siblings('nav').children('.eael-advanced-menu-horizontal');\n      $siblings.css('display') == 'none' ? $siblings.slideDown(300) : $siblings.slideUp(300);\n    }); // clear responsive props\n\n    $(window).on('resize load', function () {\n      if (window.matchMedia('(max-width: 991px)').matches) {\n        $('.eael-advanced-menu-horizontal', $scope).addClass('eael-advanced-menu-responsive');\n        $('.eael-advanced-menu-toggle-text', $scope).text($('.eael-advanced-menu-horizontal .current-menu-item a', $scope).eq(0).text());\n\n        if ($fullWidth) {\n          var css = {};\n\n          if (!$('.eael-advanced-menu-horizontal', $scope).parent().hasClass('eael-nav-menu-wrapper')) {\n            $('.eael-advanced-menu-horizontal', $scope).wrap('<nav class=\"eael-nav-menu-wrapper\"></nav>');\n          }\n\n          var $navMenu = $(\".eael-advanced-menu-container nav\", $scope);\n          menu_size_reset($navMenu);\n\n          if ($fullWidth.length > 0) {\n            css.width = parseFloat($('.elementor').width()) + 'px';\n            css.left = -parseFloat($navMenu.offset().left) + 'px';\n            css.position = 'absolute';\n          }\n\n          $navMenu.css(css);\n        }\n      } else {\n        $('.eael-advanced-menu-horizontal', $scope).removeClass('eael-advanced-menu-responsive');\n        $('.eael-advanced-menu-horizontal, .eael-advanced-menu-horizontal ul', $scope).css('display', '');\n        $(\".eael-advanced-menu-container nav\", $scope).removeAttr('style');\n        ;\n      }\n    });\n  }\n\n  function menu_size_reset(selector) {\n    var css = {};\n    css.width = '';\n    css.left = '';\n    css.position = 'inherit';\n    selector.css(css);\n  }\n\n  $('.eael-advanced-menu > li.menu-item-has-children', $scope).each(function () {\n    // indicator position\n    var $height = parseInt($('a', this).css('line-height')) / 2;\n    $(this).append('<span class=\"eael-advanced-menu-indicator ' + $indicator_class + '\" style=\"top:' + $height + 'px\"></span>'); // if current, keep indicator open\n    // $(this).hasClass('current-menu-ancestor') ? $(this).addClass('eael-advanced-menu-indicator-open') : ''\n  });\n  $('.eael-advanced-menu > li ul li.menu-item-has-children', $scope).each(function (e) {\n    // indicator position\n    var $height = parseInt($('a', this).css('line-height')) / 2;\n    $(this).append('<span class=\"eael-advanced-menu-indicator ' + $dropdown_indicator_class + '\" style=\"top:' + $height + 'px\"></span>'); // if current, keep indicator open\n    // $(this).hasClass('current-menu-ancestor') ? $(this).addClass('eael-advanced-menu-indicator-open') : ''\n  }); // menu indent\n\n  $('.eael-advanced-menu-dropdown-align-left .eael-advanced-menu-vertical li.menu-item-has-children').each(function () {\n    var $padding_left = parseInt($('a', $(this)).css('padding-left'));\n    $('ul li a', this).css({\n      'padding-left': $padding_left + 20 + 'px'\n    });\n  });\n  $('.eael-advanced-menu-dropdown-align-right .eael-advanced-menu-vertical li.menu-item-has-children').each(function () {\n    var $padding_right = parseInt($('a', $(this)).css('padding-right'));\n    $('ul li a', this).css({\n      'padding-right': $padding_right + 20 + 'px'\n    });\n  });\n  $('.eael-advanced-menu-vertical li.menu-item-has-children.current-menu-ancestor .eael-advanced-menu-indicator').each(function () {\n    // ToDo Alternate way: check eael_advanced_menu_submenu_expand settings and expand if enabled\n    var isMenuOpen = $(this).siblings('ul.sub-menu').css('display');\n\n    if (isMenuOpen !== 'none') {\n      $(this).toggleClass('eael-advanced-menu-indicator-open');\n    }\n  }); // menu dropdown toggle\n\n  $('.eael-advanced-menu', $scope).on('click', '.eael-advanced-menu-indicator', function (e) {\n    e.preventDefault();\n    $(this).toggleClass('eael-advanced-menu-indicator-open');\n    $(this).hasClass('eael-advanced-menu-indicator-open') ? $(this).siblings('ul').slideDown(300) : $(this).siblings('ul').slideUp(300);\n  }); // main menu toggle\n\n  $('.eael-advanced-menu-container', $scope).on('click', '.eael-advanced-menu-responsive li a', function (e) {\n    $(this).parents('.eael-advanced-menu-horizontal').slideUp(300);\n  });\n};\n\njQuery(window).on('elementor/frontend/init', function () {\n  elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-menu.default', AdvancedMenu);\n  elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-menu.skin-one', AdvancedMenu);\n  elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-menu.skin-two', AdvancedMenu);\n  elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-menu.skin-three', AdvancedMenu);\n  elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-menu.skin-four', AdvancedMenu);\n  elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-menu.skin-five', AdvancedMenu);\n  elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-menu.skin-six', AdvancedMenu);\n  elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-menu.skin-seven', AdvancedMenu);\n});\n\n//# sourceURL=webpack:///./src/js/view/advanced-menu.js?");

/***/ })

/******/ });