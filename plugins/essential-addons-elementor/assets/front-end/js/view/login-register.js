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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/login-register.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/login-register.js":
/*!***************************************!*\
  !*** ./src/js/view/login-register.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/*--- Pro Version --- */\nea.hooks.addAction(\"init\", \"ea\", function () {\n  var EALoginRegisterPro = function EALoginRegisterPro($scope, $) {\n    var $wrap = $scope.find('.eael-login-registration-wrapper'); // cache wrapper\n\n    var ajaxEnabled = $wrap.data('is-ajax');\n    var widgetId = $wrap.data('widget-id');\n    var redirectTo = $wrap.data('redirect-to');\n    var $loginForm = $wrap.find('#eael-login-form');\n    window.isLoggedInByFB = false;\n    window.isUsingGoogleLogin = false; // Google\n\n    var gLoginNodeId = 'eael-google-login-btn-' + widgetId;\n    var $gBtn = $loginForm.find('#' + gLoginNodeId); // Facebook\n\n    var fLoginNodeId = 'eael-fb-login-btn-' + widgetId;\n    var $fBtn = $loginForm.find('#' + fLoginNodeId);\n    var $registerForm = $wrap.find('#eael-register-form');\n    var ajaxAction = {\n      name: \"action\",\n      value: 'eael-login-register-form'\n    };\n    var valid_login_vendors = ['facebook', 'google', 'login'];\n    var $passField = $registerForm.find('#form-field-password');\n    var psOps = $registerForm.find('.pass-meta-info').data('strength-options');\n    var $passNotice = $registerForm.find('.eael-pass-notice');\n    var $passMeter = $registerForm.find('.eael-pass-meter');\n    var $passHint = $registerForm.find('.eael-pass-hint');\n    var showPassMeta = $passField.length > 0 && ($passNotice.length > 0 || $passMeter.length > 0 || $passHint.length > 0);\n\n    var sendData = function sendData(form_data, formType) {\n      // set the correct form type we are submitting: login or register?\n      form_data.push({\n        \"name\": \"eael-\".concat(formType, \"-submit\"),\n        \"value\": true\n      });\n      form_data.push(ajaxAction);\n      $.ajax({\n        url: localize.ajaxurl,\n        type: 'POST',\n        dataType: 'json',\n        data: form_data,\n        success: function success(data) {\n          var success = data && data.success;\n          var isLoginForm = valid_login_vendors.includes(formType);\n          var message;\n\n          if (success) {\n            message = \"<div class=\\\"eael-form-msg valid\\\">\".concat(data.data.message, \"</div>\");\n          } else {\n            message = \"<div class=\\\"eael-form-msg invalid\\\">\".concat(data.data, \"</div>\");\n          }\n\n          if (isLoginForm) {\n            if (!success) {\n              $loginForm.find(\"#eael-login-submit\").prop(\"disabled\", false);\n            }\n\n            $loginForm.find('.eael-form-validation-container').html(message);\n          } else {\n            $registerForm.find(\"#eael-register-submit\").prop(\"disabled\", false);\n            $registerForm.find('.eael-form-validation-container').html(message);\n          } //handle redirect\n\n\n          if (success) {\n            if (data.data.redirect_to) {\n              setTimeout(function () {\n                return window.location = data.data.redirect_to;\n              }, 500);\n            } else if (isLoginForm) {\n              // refresh the page on login success\n              setTimeout(function () {\n                return location.reload();\n              }, 1000);\n            }\n          }\n        },\n        error: function error(xhr, err) {\n          var errorHtml = \"\\n                    <p class=\\\"eael-form-msg invalid\\\">\\n                    Error occurred: \".concat(err.toString(), \" \\n                    </p>\\n                    \");\n\n          if ('login' === formType) {\n            $loginForm.find(\"#eael-login-submit\").prop(\"disabled\", false);\n            $loginForm.find('.eael-form-validation-container').html(errorHtml);\n          } else {\n            $registerForm.find(\"#eael-register-submit\").prop(\"disabled\", false);\n            $registerForm.find('.eael-form-validation-container').html(errorHtml);\n          }\n        }\n      });\n    };\n\n    if ('yes' === ajaxEnabled) {\n      //Handle Register form submission via ajax\n      $loginForm.on('submit', function (e) {\n        $loginForm.find(\"#eael-login-submit\").prop(\"disabled\", true);\n        var form_data = $(this).serializeArray();\n        form_data.filter(function (currentValue, index) {\n          if (form_data[index].name == 'eael-login-nonce') {\n            form_data[index].value = localize.eael_login_nonce;\n            return;\n          }\n        });\n        sendData(form_data, 'login');\n        return false;\n      }); //Handle Register form submission via ajax\n\n      $registerForm.on('submit', function (e) {\n        $registerForm.find(\"#eael-register-submit\").prop(\"disabled\", true);\n        var form_data = $(this).serializeArray();\n        form_data.filter(function (currentValue, index) {\n          if (form_data[index].name == 'eael-register-nonce') {\n            form_data[index].value = localize.eael_register_nonce;\n          }\n        });\n        sendData(form_data, 'register');\n        return false;\n      });\n    }\n\n    if ($gBtn.length && !isEditMode) {\n      var gClientId = $gBtn.data('g-client-id'); // Login with Google\n\n      if (typeof gapi !== 'undefined' && gapi !== null) {\n        gapi.load('auth2', function () {\n          auth2 = gapi.auth2.init({\n            client_id: gClientId,\n            cookiepolicy: 'single_host_origin'\n          });\n          auth2.attachClickHandler(document.getElementById(gLoginNodeId), {}, function (googleUser) {\n            var profile = googleUser.getBasicProfile();\n            var name = profile.getName();\n            var email = profile.getEmail();\n\n            if (window.isUsingGoogleLogin) {\n              var id_token = googleUser.getAuthResponse().id_token;\n              var googleData = [{\n                name: 'widget_id',\n                value: widgetId\n              }, {\n                name: 'redirect_to',\n                value: redirectTo\n              }, {\n                name: 'email',\n                value: email\n              }, {\n                name: 'full_name',\n                value: name\n              }, {\n                name: 'id_token',\n                value: id_token\n              }, {\n                name: 'nonce',\n                value: $loginForm.find('#eael-login-nonce').val()\n              }];\n              sendData(googleData, 'google');\n            }\n          }, function (error) {\n            var msg = \"<p class=\\\"eael-form-msg invalid\\\"> Something went wrong! \".concat(error.error, \"</p>\");\n            $scope.find('.eael-form-validation-container').html(msg);\n          });\n        });\n      } else {\n        console.log('gapi not defined or loaded');\n      }\n    }\n\n    if ($fBtn.length && !isEditMode) {\n      // Fetch the user profile data from facebook.\n      var logUserInOurAppUsingFB = function logUserInOurAppUsingFB() {\n        FB.api('/me', {\n          fields: 'id, name, email'\n        }, function (response) {\n          window.isLoggedInByFB = true;\n          var fbData = [{\n            name: 'widget_id',\n            value: widgetId\n          }, {\n            name: 'redirect_to',\n            value: redirectTo\n          }, {\n            name: 'email',\n            value: response.email\n          }, {\n            name: 'full_name',\n            value: response.name\n          }, {\n            name: 'user_id',\n            value: response.id\n          }, {\n            name: 'access_token',\n            value: FB.getAuthResponse()['accessToken']\n          }, {\n            name: 'nonce',\n            value: $loginForm.find('#eael-login-nonce').val()\n          }];\n          sendData(fbData, 'facebook');\n        });\n      };\n\n      var appId = $fBtn.data('fb-appid');\n\n      window.fbAsyncInit = function () {\n        FB.init({\n          appId: appId,\n          cookie: true,\n          xfbml: true,\n          version: 'v8.0'\n        });\n        FB.AppEvents.logPageView();\n      };\n\n      (function (d, s, id) {\n        var js,\n            fjs = d.getElementsByTagName(s)[0];\n\n        if (d.getElementById(id)) {\n          return;\n        }\n\n        js = d.createElement(s);\n        js.id = id;\n        js.src = \"https://connect.facebook.net/en_US/sdk.js\";\n        fjs.parentNode.insertBefore(js, fjs);\n      })(document, 'script', 'facebook-jssdk');\n\n      $fBtn.on('click', function () {\n        if (!isLoggedInByFB) {\n          FB.login(function (response) {\n            // handle the response\n            if (response.status === 'connected') {\n              // Logged into our webpage and Facebook.\n              logUserInOurAppUsingFB();\n            } else {\n              console.log('The person is not logged into our webpage or facebook is unable to tell.');\n            }\n          }, {\n            scope: 'public_profile,email'\n          });\n        }\n      });\n    }\n\n    $gBtn.on('click', function (e) {\n      window.isUsingGoogleLogin = true;\n    }); // Password Strength Related meta information\n\n    if (showPassMeta) {\n      var showStrengthMeter = function showStrengthMeter(strength, password) {\n        if ('yes' !== psOps.show_ps_meter) {\n          return;\n        }\n\n        if (!password) {\n          $passMeter.hide(300);\n          return;\n        }\n\n        $passMeter.show(400);\n        var meterValue = 0 === strength ? 1 : strength;\n        $passMeter.val(meterValue);\n      };\n\n      var showStrengthText = function showStrengthText(strength, password) {\n        if ('yes' !== psOps.show_pass_strength) {\n          return;\n        }\n\n        if (!password) {\n          $passNotice.hide(300);\n          return;\n        }\n\n        $passNotice.show(400);\n        var pText = '';\n        var useCustomText = 'custom' === psOps.ps_text_type;\n        var cssClasses = 'short bad mismatch good strong';\n\n        switch (strength) {\n          case -1:\n            // do nothing\n            break;\n\n          case 2:\n            pText = useCustomText ? psOps.ps_text_bad : pwsL10n.bad;\n            $passNotice.html(pText).removeClass(cssClasses).addClass('bad');\n            break;\n\n          case 3:\n            pText = useCustomText ? psOps.ps_text_good : pwsL10n.good;\n            $passNotice.html(pText).removeClass(cssClasses).addClass('good');\n            break;\n\n          case 4:\n            pText = useCustomText ? psOps.ps_text_strong : pwsL10n.strong;\n            $passNotice.html(pText).removeClass(cssClasses).addClass('strong');\n            break;\n\n          case 5:\n            $passNotice.html(pwsL10n.mismatch).removeClass(cssClasses).addClass('mismatch');\n            break;\n\n          default:\n            pText = useCustomText ? psOps.ps_text_short : pwsL10n[\"short\"];\n            $passNotice.html(pText).removeClass(cssClasses).addClass('short');\n        }\n      };\n\n      var togglePassHint = function togglePassHint(strength) {\n        if (strength >= 3) {\n          $passHint.hide(300); // hide hint when pass word is good.\n        } else {\n          $passHint.show(400);\n        }\n      };\n\n      var checkPassStrength = function checkPassStrength() {\n        var strength;\n        var password = $passField.val();\n\n        if (password) {\n          strength = wp.passwordStrength.meter(password, wp.passwordStrength.userInputDisallowedList(), password); // @todo; add confirm pass check later\n        }\n\n        showStrengthMeter(strength, password);\n        showStrengthText(strength, password);\n        togglePassHint(strength);\n      };\n\n      $passField.on('keyup', function (e) {\n        checkPassStrength();\n      });\n    }\n  };\n\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-login-register.default\", EALoginRegisterPro);\n});\n\n//# sourceURL=webpack:///./src/js/view/login-register.js?");

/***/ })

/******/ });