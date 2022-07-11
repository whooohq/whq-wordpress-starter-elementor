function JetGMInfoBox(opt_opts) {

	opt_opts = opt_opts || {};

	google.maps.OverlayView.apply(this, arguments);

	// Standard options (in common with google.maps.InfoWindow):
	this.content_ = opt_opts.content || "";
	this.disableAutoPan_ = opt_opts.disableAutoPan || false;
	this.maxWidth_ = opt_opts.maxWidth || 0;
	this.pixelOffset_ = opt_opts.pixelOffset || new google.maps.Size(0, 0);
	this.position_ = opt_opts.position || new google.maps.LatLng(0, 0);
	this.zIndex_ = opt_opts.zIndex || null;

	// Additional options (unique to JetGMInfoBox):
	this.boxClass_ = opt_opts.boxClass || "infoBox";
	this.boxStyle_ = opt_opts.boxStyle || {};
	this.closeBoxMargin_ = opt_opts.closeBoxMargin || "2px";
	this.closeBoxURL_ = opt_opts.closeBoxURL || "//www.google.com/intl/en_us/mapfiles/close.gif";
	if (opt_opts.closeBoxURL === "") {
		this.closeBoxURL_ = "";
	}
	this.closeBoxTitle_ = opt_opts.closeBoxTitle || " Close ";
	this.infoBoxClearance_ = opt_opts.infoBoxClearance || new google.maps.Size(1, 1);

	if (typeof opt_opts.visible === "undefined") {
		if (typeof opt_opts.isHidden === "undefined") {
			opt_opts.visible = true;
		} else {
			opt_opts.visible = !opt_opts.isHidden;
		}
	}
	this.isHidden_ = !opt_opts.visible;

	this.alignBottom_ = opt_opts.alignBottom || false;
	this.pane_ = opt_opts.pane || "floatPane";
	this.enableEventPropagation_ = opt_opts.enableEventPropagation || false;

	this.div_ = null;
	this.closeListener_ = null;
	this.moveListener_ = null;
	this.contextListener_ = null;
	this.eventListeners_ = null;
	this.fixedWidthSet_ = null;

}

/* JetGMInfoBox extends OverlayView in the Google Maps API v3.
 */
JetGMInfoBox.prototype = new google.maps.OverlayView();

/**
 * Creates the DIV representing the JetGMInfoBox.
 * @private
 */
JetGMInfoBox.prototype.createJetGMInfoBoxDiv_ = function () {

	var i;
	var events;
	var bw;
	var me = this;

	// This handler prevents an event in the JetGMInfoBox from being passed on to the map.
	//
	var cancelHandler = function (e) {
		e.cancelBubble = true;
		if (e.stopPropagation) {
			e.stopPropagation();
		}
	};

	// This handler ignores the current event in the JetGMInfoBox and conditionally prevents
	// the event from being passed on to the map. It is used for the contextmenu event.
	//
	var ignoreHandler = function (e) {

		e.returnValue = false;

		if (e.preventDefault) {

			e.preventDefault();
		}

		if (!me.enableEventPropagation_) {

			cancelHandler(e);
		}
	};

	if (!this.div_) {

		this.div_ = document.createElement("div");

		this.setBoxStyle_();

		if (typeof this.content_.nodeType === "undefined") {
			this.div_.innerHTML = this.getCloseBoxImg_() + this.content_;
		} else {
			this.div_.innerHTML = this.getCloseBoxImg_();
			this.div_.appendChild(this.content_);
		}

		// Add the JetGMInfoBox DIV to the DOM
		this.getPanes()[this.pane_].appendChild(this.div_);

		this.addClickHandler_();

		if (this.div_.style.width) {

			this.fixedWidthSet_ = true;

		} else {


			if ( this.maxWidth_ !== 0 ) {

				this.div_.style.width = this.maxWidth_ + 'px';
				this.fixedWidthSet_ = true;

			} else { // The following code is needed to overcome problems with MSIE

				bw = this.getBoxWidths_();

				this.div_.style.width = (this.div_.offsetWidth - bw.left - bw.right) + "px";
				this.fixedWidthSet_ = false;
			}
		}

		this.panBox_(this.disableAutoPan_);

		if (!this.enableEventPropagation_) {

			this.eventListeners_ = [];

			// Cancel event propagation.
			//
			// Note: mousemove not included (to resolve Issue 152)
			events = ["mousedown", "mouseover", "mouseout", "mouseup",
				"click", "dblclick", "touchstart", "touchend", "touchmove"];

			for (i = 0; i < events.length; i++) {

				this.eventListeners_.push(google.maps.event.addDomListener(this.div_, events[i], cancelHandler));
			}

			// Workaround for Google bug that causes the cursor to change to a pointer
			// when the mouse moves over a marker underneath JetGMInfoBox.
			this.eventListeners_.push(google.maps.event.addDomListener(this.div_, "mouseover", function (e) {
				this.style.cursor = "default";
			}));
		}

		this.contextListener_ = google.maps.event.addDomListener(this.div_, "contextmenu", ignoreHandler);

		/**
		 * This event is fired when the DIV containing the JetGMInfoBox's content is attached to the DOM.
		 * @name JetGMInfoBox#domready
		 * @event
		 */
		google.maps.event.trigger(this, "domready");
	}
};

/**
 * Returns the HTML <IMG> tag for the close box.
 * @private
 */
JetGMInfoBox.prototype.getCloseBoxImg_ = function () {

	var img = "";

	if (this.closeBoxURL_ !== "") {

		img  = "<img";
		img += " src='" + this.closeBoxURL_ + "'";
		img += " align=right"; // Do this because Opera chokes on style='float: right;'
		img += " title='" + this.closeBoxTitle_ + "'";
		img += " class='jet-map-close'";
		img += " style='";
		img += " margin: " + this.closeBoxMargin_ + ";";
		img += "'>";
	}

	return img;
};

/**
 * Adds the click handler to the JetGMInfoBox close box.
 * @private
 */
JetGMInfoBox.prototype.addClickHandler_ = function () {

	var closeBox;

	if (this.closeBoxURL_ !== "") {

		closeBox = this.div_.firstChild;
		this.closeListener_ = google.maps.event.addDomListener(closeBox, "click", this.getCloseClickHandler_());

	} else {

		this.closeListener_ = null;
	}
};

/**
 * Returns the function to call when the user clicks the close box of an JetGMInfoBox.
 * @private
 */
JetGMInfoBox.prototype.getCloseClickHandler_ = function () {

	var me = this;

	return function (e) {

		// 1.0.3 fix: Always prevent propagation of a close box click to the map:
		e.cancelBubble = true;

		if (e.stopPropagation) {

			e.stopPropagation();
		}

		/**
		 * This event is fired when the JetGMInfoBox's close box is clicked.
		 * @name JetGMInfoBox#closeclick
		 * @event
		 */
		google.maps.event.trigger(me, "closeclick");

		me.close();
	};
};

/**
 * Pans the map so that the JetGMInfoBox appears entirely within the map's visible area.
 * @private
 */
JetGMInfoBox.prototype.panBox_ = function (disablePan) {

	var map;
	var bounds;
	var xOffset = 0, yOffset = 0;

	if (!disablePan) {

		map = this.getMap();

		if (map instanceof google.maps.Map) { // Only pan if attached to map, not panorama

			if (!map.getBounds().contains(this.position_)) {
				// Marker not in visible area of map, so set center
				// of map to the marker position first.
				map.setCenter(this.position_);
			}

			var iwOffsetX = this.pixelOffset_.width;
			var iwOffsetY = this.pixelOffset_.height;
			var iwWidth = this.div_.offsetWidth;
			var iwHeight = this.div_.offsetHeight;
			var padX = this.infoBoxClearance_.width;
			var padY = this.infoBoxClearance_.height;

			if (map.panToBounds.length == 2) {
				// Using projection.fromLatLngToContainerPixel to compute the infowindow position
				// does not work correctly anymore for JS Maps API v3.32 and above if there is a
				// previous synchronous call that causes the map to animate (e.g. setCenter when
				// the position is not within bounds). Hence, we are using panToBounds with
				// padding instead, which works synchronously.
				var padding = {left: 0, right: 0, top: 0, bottom: 0};
				padding.left = -iwOffsetX + padX;
				padding.right = iwOffsetX + iwWidth + padX;
				if (this.alignBottom_) {
					padding.top = -iwOffsetY + padY + iwHeight;
					padding.bottom = iwOffsetY + padY;
				} else {
					padding.top = -iwOffsetY + padY;
					padding.bottom = iwOffsetY + iwHeight + padY;
				}
				map.panToBounds(new google.maps.LatLngBounds(this.position_), padding);
			} else {
				var mapDiv = map.getDiv();
				var mapWidth = mapDiv.offsetWidth;
				var mapHeight = mapDiv.offsetHeight;
				var pixPosition = this.getProjection().fromLatLngToContainerPixel(this.position_);

				if (pixPosition.x < (-iwOffsetX + padX)) {
					xOffset = pixPosition.x + iwOffsetX - padX;
				} else if ((pixPosition.x + iwWidth + iwOffsetX + padX) > mapWidth) {
					xOffset = pixPosition.x + iwWidth + iwOffsetX + padX - mapWidth;
				}
				if (this.alignBottom_) {
					if (pixPosition.y < (-iwOffsetY + padY + iwHeight)) {
						yOffset = pixPosition.y + iwOffsetY - padY - iwHeight;
					} else if ((pixPosition.y + iwOffsetY + padY) > mapHeight) {
						yOffset = pixPosition.y + iwOffsetY + padY - mapHeight;
					}
				} else {
					if (pixPosition.y < (-iwOffsetY + padY)) {
						yOffset = pixPosition.y + iwOffsetY - padY;
					} else if ((pixPosition.y + iwHeight + iwOffsetY + padY) > mapHeight) {
						yOffset = pixPosition.y + iwHeight + iwOffsetY + padY - mapHeight;
					}
				}

				if (!(xOffset === 0 && yOffset === 0)) {

					// Move the map to the shifted center.
					//
					var c = map.getCenter();
					map.panBy(xOffset, yOffset);
				}
			}
		}
	}
};

/**
 * Sets the style of the JetGMInfoBox by setting the style sheet and applying
 * other specific styles requested.
 * @private
 */
JetGMInfoBox.prototype.setBoxStyle_ = function () {

	var i, boxStyle;

	if (this.div_) {

		// Apply style values from the style sheet defined in the boxClass parameter:
		this.div_.className = this.boxClass_;

		// Clear existing inline style values:
		this.div_.style.cssText = "";

		// Apply style values defined in the boxStyle parameter:
		boxStyle = this.boxStyle_;
		for (i in boxStyle) {

			if (boxStyle.hasOwnProperty(i)) {

				this.div_.style[i] = boxStyle[i];
			}
		}

		// Fix for iOS disappearing JetGMInfoBox problem.
		// See http://stackoverflow.com/questions/9229535/google-maps-markers-disappear-at-certain-zoom-level-only-on-iphone-ipad
		// Required: use "matrix" technique to specify transforms in order to avoid this bug.
		if ((typeof this.div_.style.WebkitTransform === "undefined") || (this.div_.style.WebkitTransform.indexOf("translateZ") === -1 && this.div_.style.WebkitTransform.indexOf("matrix") === -1)) {

			this.div_.style.WebkitTransform = "translateZ(0)";
		}

		// Fix up opacity style for benefit of MSIE:
		//
		if (typeof this.div_.style.opacity !== "undefined" && this.div_.style.opacity !== "") {
			// See http://www.quirksmode.org/css/opacity.html
			this.div_.style.MsFilter = "\"progid:DXImageTransform.Microsoft.Alpha(Opacity=" + (this.div_.style.opacity * 100) + ")\"";
			this.div_.style.filter = "alpha(opacity=" + (this.div_.style.opacity * 100) + ")";
		}

		// Apply required styles:
		//
		this.div_.style.position = "absolute";
		this.div_.style.visibility = 'hidden';
		if (this.zIndex_ !== null) {

			this.div_.style.zIndex = this.zIndex_;
		}
	}
};

/**
 * Get the widths of the borders of the JetGMInfoBox.
 * @private
 * @return {Object} widths object (top, bottom left, right)
 */
JetGMInfoBox.prototype.getBoxWidths_ = function () {

	var computedStyle;
	var bw = {top: 0, bottom: 0, left: 0, right: 0};
	var box = this.div_;

	if (document.defaultView && document.defaultView.getComputedStyle) {

		computedStyle = box.ownerDocument.defaultView.getComputedStyle(box, "");

		if (computedStyle) {

			// The computed styles are always in pixel units (good!)
			bw.top = parseInt(computedStyle.borderTopWidth, 10) || 0;
			bw.bottom = parseInt(computedStyle.borderBottomWidth, 10) || 0;
			bw.left = parseInt(computedStyle.borderLeftWidth, 10) || 0;
			bw.right = parseInt(computedStyle.borderRightWidth, 10) || 0;
		}

	} else if (document.documentElement.currentStyle) { // MSIE

		if (box.currentStyle) {

			// The current styles may not be in pixel units, but assume they are (bad!)
			bw.top = parseInt(box.currentStyle.borderTopWidth, 10) || 0;
			bw.bottom = parseInt(box.currentStyle.borderBottomWidth, 10) || 0;
			bw.left = parseInt(box.currentStyle.borderLeftWidth, 10) || 0;
			bw.right = parseInt(box.currentStyle.borderRightWidth, 10) || 0;
		}
	}

	return bw;
};

/**
 * Invoked when <tt>close</tt> is called. Do not call it directly.
 */
JetGMInfoBox.prototype.onRemove = function () {

	if (this.div_) {

		this.div_.parentNode.removeChild(this.div_);
		this.div_ = null;
	}
};

/**
 * Draws the JetGMInfoBox based on the current map projection and zoom level.
 */
JetGMInfoBox.prototype.draw = function () {

	this.createJetGMInfoBoxDiv_();

	var pixPosition = this.getProjection().fromLatLngToDivPixel(this.position_);

	this.div_.style.left = (pixPosition.x + this.pixelOffset_.width) + "px";

	if (this.alignBottom_) {
		this.div_.style.bottom = -(pixPosition.y + this.pixelOffset_.height) + "px";
	} else {
		this.div_.style.top = (pixPosition.y + this.pixelOffset_.height) + "px";
	}

	if (this.isHidden_) {

		this.div_.style.visibility = "hidden";

	} else {

		this.div_.style.visibility = "visible";
	}
};

/**
 * Sets the options for the JetGMInfoBox. Note that changes to the <tt>maxWidth</tt>,
 *  <tt>closeBoxMargin</tt>, <tt>closeBoxTitle</tt>, <tt>closeBoxURL</tt>, and
 *  <tt>enableEventPropagation</tt> properties have no affect until the current
 *  JetGMInfoBox is <tt>close</tt>d and a new one is <tt>open</tt>ed.
 * @param {JetGMInfoBoxOptions} opt_opts
 */
JetGMInfoBox.prototype.setOptions = function (opt_opts) {
	if (typeof opt_opts.boxClass !== "undefined") { // Must be first

		this.boxClass_ = opt_opts.boxClass;
		this.setBoxStyle_();
	}
	if (typeof opt_opts.boxStyle !== "undefined") { // Must be second

		this.boxStyle_ = opt_opts.boxStyle;
		this.setBoxStyle_();
	}
	if (typeof opt_opts.content !== "undefined") {

		this.setContent(opt_opts.content);
	}
	if (typeof opt_opts.disableAutoPan !== "undefined") {

		this.disableAutoPan_ = opt_opts.disableAutoPan;
	}
	if (typeof opt_opts.maxWidth !== "undefined") {

		this.maxWidth_ = opt_opts.maxWidth;
	}
	if (typeof opt_opts.pixelOffset !== "undefined") {

		this.pixelOffset_ = opt_opts.pixelOffset;
	}
	if (typeof opt_opts.alignBottom !== "undefined") {

		this.alignBottom_ = opt_opts.alignBottom;
	}
	if (typeof opt_opts.position !== "undefined") {

		this.setPosition(opt_opts.position);
	}
	if (typeof opt_opts.zIndex !== "undefined") {

		this.setZIndex(opt_opts.zIndex);
	}
	if (typeof opt_opts.closeBoxMargin !== "undefined") {

		this.closeBoxMargin_ = opt_opts.closeBoxMargin;
	}
	if (typeof opt_opts.closeBoxURL !== "undefined") {

		this.closeBoxURL_ = opt_opts.closeBoxURL;
	}
	if (typeof opt_opts.closeBoxTitle !== "undefined") {

		this.closeBoxTitle_ = opt_opts.closeBoxTitle;
	}
	if (typeof opt_opts.infoBoxClearance !== "undefined") {

		this.infoBoxClearance_ = opt_opts.infoBoxClearance;
	}
	if (typeof opt_opts.isHidden !== "undefined") {

		this.isHidden_ = opt_opts.isHidden;
	}
	if (typeof opt_opts.visible !== "undefined") {

		this.isHidden_ = !opt_opts.visible;
	}
	if (typeof opt_opts.enableEventPropagation !== "undefined") {

		this.enableEventPropagation_ = opt_opts.enableEventPropagation;
	}

	if (this.div_) {

		this.draw();
	}
};

JetGMInfoBox.prototype.contentIsSet = function () {
	return "" !== this.content_;
};

/**
 * Sets the content of the JetGMInfoBox.
 *  The content can be plain text or an HTML DOM node.
 * @param {string|Node} content
 */
JetGMInfoBox.prototype.setContent = function (content) {
	this.content_ = content;

	if (this.div_) {

		if (this.closeListener_) {

			google.maps.event.removeListener(this.closeListener_);
			this.closeListener_ = null;
		}

		// Odd code required to make things work with MSIE.
		//
		if (!this.fixedWidthSet_) {

			this.div_.style.width = "";
		}

		if (typeof content.nodeType === "undefined") {
			this.div_.innerHTML = this.getCloseBoxImg_() + content;
		} else {
			this.div_.innerHTML = this.getCloseBoxImg_();
			this.div_.appendChild(content);
		}

		// Perverse code required to make things work with MSIE.
		// (Ensures the close box does, in fact, float to the right.)
		//
		if (!this.fixedWidthSet_) {
			this.div_.style.width = this.div_.offsetWidth + "px";
			if (typeof content.nodeType === "undefined") {
				this.div_.innerHTML = this.getCloseBoxImg_() + content;
			} else {
				this.div_.innerHTML = this.getCloseBoxImg_();
				this.div_.appendChild(content);
			}
		}

		this.addClickHandler_();
	}

	/**
	 * This event is fired when the content of the JetGMInfoBox changes.
	 * @name JetGMInfoBox#content_changed
	 * @event
	 */
	google.maps.event.trigger(this, "content_changed");
};

/**
 * Sets the geographic location of the JetGMInfoBox.
 * @param {LatLng} latlng
 */
JetGMInfoBox.prototype.setPosition = function (latlng) {

	this.position_ = latlng;

	if (this.div_) {

		this.draw();
	}

	/**
	 * This event is fired when the position of the JetGMInfoBox changes.
	 * @name JetGMInfoBox#position_changed
	 * @event
	 */
	google.maps.event.trigger(this, "position_changed");
};

/**
 * Sets the zIndex style for the JetGMInfoBox.
 * @param {number} index
 */
JetGMInfoBox.prototype.setZIndex = function (index) {

	this.zIndex_ = index;

	if (this.div_) {

		this.div_.style.zIndex = index;
	}

	/**
	 * This event is fired when the zIndex of the JetGMInfoBox changes.
	 * @name JetGMInfoBox#zindex_changed
	 * @event
	 */
	google.maps.event.trigger(this, "zindex_changed");
};

/**
 * Sets the visibility of the JetGMInfoBox.
 * @param {boolean} isVisible
 */
JetGMInfoBox.prototype.setVisible = function (isVisible) {

	this.isHidden_ = !isVisible;
	if (this.div_) {
		this.div_.style.visibility = (this.isHidden_ ? "hidden" : "visible");
	}
};

/**
 * Returns the content of the JetGMInfoBox.
 * @returns {string}
 */
JetGMInfoBox.prototype.getContent = function () {

	return this.content_;
};

/**
 * Returns the geographic location of the JetGMInfoBox.
 * @returns {LatLng}
 */
JetGMInfoBox.prototype.getPosition = function () {

	return this.position_;
};

/**
 * Returns the zIndex for the JetGMInfoBox.
 * @returns {number}
 */
JetGMInfoBox.prototype.getZIndex = function () {

	return this.zIndex_;
};

/**
 * Returns a flag indicating whether the JetGMInfoBox is visible.
 * @returns {boolean}
 */
JetGMInfoBox.prototype.getVisible = function () {

	var isVisible;

	if ((typeof this.getMap() === "undefined") || (this.getMap() === null)) {
		isVisible = false;
	} else {
		isVisible = !this.isHidden_;
	}
	return isVisible;
};

/**
 * Returns the width of the JetGMInfoBox in pixels.
 * @returns {number}
 */
JetGMInfoBox.prototype.getWidth = function () {
	var width = null;

	if (this.div_) {
		width = this.div_.offsetWidth;
	}

	return width;
};

/**
 * Returns the height of the JetGMInfoBox in pixels.
 * @returns {number}
 */
JetGMInfoBox.prototype.getHeight = function () {
	var height = null;

	if (this.div_) {
		height = this.div_.offsetHeight;
	}

	return height;
};

/**
 * Shows the JetGMInfoBox. [Deprecated; use <tt>setVisible</tt> instead.]
 */
JetGMInfoBox.prototype.show = function () {

	this.isHidden_ = false;
	if (this.div_) {
		this.div_.style.visibility = "visible";
	}
};

/**
 * Hides the JetGMInfoBox. [Deprecated; use <tt>setVisible</tt> instead.]
 */
JetGMInfoBox.prototype.hide = function () {

	this.isHidden_ = true;
	if (this.div_) {
		this.div_.style.visibility = "hidden";
	}
};

/**
 * Adds the JetGMInfoBox to the specified map or Street View panorama. If <tt>anchor</tt>
 *  (usually a <tt>google.maps.Marker</tt>) is specified, the position
 *  of the JetGMInfoBox is set to the position of the <tt>anchor</tt>. If the
 *  anchor is dragged to a new location, the JetGMInfoBox moves as well.
 * @param {Map|StreetViewPanorama} map
 * @param {MVCObject} [anchor]
 */
JetGMInfoBox.prototype.open = function (map, anchor) {

	var me = this;

	if (anchor) {

		this.setPosition(anchor.getPosition()); // BUG FIX 2/17/2018: needed for v3.32
		this.moveListener_ = google.maps.event.addListener(anchor, "position_changed", function () {
			me.setPosition(this.getPosition());
		});
	}

	this.setMap(map);

	if ( this.div_ ) {

		jQuery( this.div_ ).find( 'div[data-element_type]' ).each( function() {

			var $this       = jQuery( this ),
				elementType = $this.data( 'element_type' );

			if( 'widget' === elementType ){

				elementType = $this.data( 'widget_type' );

				window.elementorFrontend.hooks.doAction(
					'frontend/element_ready/widget',
					$this,
					jQuery
				);

			}

			window.elementorFrontend.hooks.doAction(
				'frontend/element_ready/' + elementType,
				$this,
				jQuery
			);

		});

		this.panBox_(this.disableAutoPan_); // BUG FIX 2/17/2018: add missing parameter

	}
};

/**
 * Removes the JetGMInfoBox from the map.
 */
JetGMInfoBox.prototype.close = function () {

	var i;

	if (this.closeListener_) {

		google.maps.event.removeListener(this.closeListener_);
		this.closeListener_ = null;
	}

	if (this.eventListeners_) {

		for (i = 0; i < this.eventListeners_.length; i++) {

			google.maps.event.removeListener(this.eventListeners_[i]);
		}
		this.eventListeners_ = null;
	}

	if (this.moveListener_) {

		google.maps.event.removeListener(this.moveListener_);
		this.moveListener_ = null;
	}

	if (this.contextListener_) {

		google.maps.event.removeListener(this.contextListener_);
		this.contextListener_ = null;
	}

	this.setMap(null);
};

(function(){var b=true,f=false;function g(a){var c=a||{};this.d=this.c=f;if(a.visible==undefined)a.visible=b;if(a.shadow==undefined)a.shadow="7px -3px 5px rgba(88,88,88,0.7)";if(a.anchor==undefined)a.anchor=i.BOTTOM;this.setValues(c)}g.prototype=new google.maps.OverlayView;window.RichMarker=g;g.prototype.getVisible=function(){return this.get("visible")};g.prototype.getVisible=g.prototype.getVisible;g.prototype.setVisible=function(a){this.set("visible",a)};g.prototype.setVisible=g.prototype.setVisible;
	g.prototype.s=function(){if(this.c){this.a.style.display=this.getVisible()?"":"none";this.draw()}};g.prototype.visible_changed=g.prototype.s;g.prototype.setFlat=function(a){this.set("flat",!!a)};g.prototype.setFlat=g.prototype.setFlat;g.prototype.getFlat=function(){return this.get("flat")};g.prototype.getFlat=g.prototype.getFlat;g.prototype.p=function(){return this.get("width")};g.prototype.getWidth=g.prototype.p;g.prototype.o=function(){return this.get("height")};g.prototype.getHeight=g.prototype.o;
	g.prototype.setShadow=function(a){this.set("shadow",a);this.g()};g.prototype.setShadow=g.prototype.setShadow;g.prototype.getShadow=function(){return this.get("shadow")};g.prototype.getShadow=g.prototype.getShadow;g.prototype.g=function(){if(this.c)this.a.style.boxShadow=this.a.style.webkitBoxShadow=this.a.style.MozBoxShadow=this.getFlat()?"":this.getShadow()};g.prototype.flat_changed=g.prototype.g;g.prototype.setZIndex=function(a){this.set("zIndex",a)};g.prototype.setZIndex=g.prototype.setZIndex;
	g.prototype.getZIndex=function(){return this.get("zIndex")};g.prototype.getZIndex=g.prototype.getZIndex;g.prototype.t=function(){if(this.getZIndex()&&this.c)this.a.style.zIndex=this.getZIndex()};g.prototype.zIndex_changed=g.prototype.t;g.prototype.getDraggable=function(){return this.get("draggable")};g.prototype.getDraggable=g.prototype.getDraggable;g.prototype.setDraggable=function(a){this.set("draggable",!!a)};g.prototype.setDraggable=g.prototype.setDraggable;
	g.prototype.k=function(){if(this.c)this.getDraggable()?j(this,this.a):k(this)};g.prototype.draggable_changed=g.prototype.k;g.prototype.getPosition=function(){return this.get("position")};g.prototype.getPosition=g.prototype.getPosition;g.prototype.setPosition=function(a){this.set("position",a)};g.prototype.setPosition=g.prototype.setPosition;g.prototype.q=function(){this.draw()};g.prototype.position_changed=g.prototype.q;g.prototype.l=function(){return this.get("anchor")};g.prototype.getAnchor=g.prototype.l;
	g.prototype.r=function(a){this.set("anchor",a)};g.prototype.setAnchor=g.prototype.r;g.prototype.n=function(){this.draw()};g.prototype.anchor_changed=g.prototype.n;function l(a,c){var d=document.createElement("DIV");d.innerHTML=c;if(d.childNodes.length==1)return d.removeChild(d.firstChild);else{for(var e=document.createDocumentFragment();d.firstChild;)e.appendChild(d.firstChild);return e}}function m(a,c){if(c)for(var d;d=c.firstChild;)c.removeChild(d)}
	g.prototype.setContent=function(a){this.set("content",a)};g.prototype.setContent=g.prototype.setContent;g.prototype.getContent=function(){return this.get("content")};g.prototype.getContent=g.prototype.getContent;
	g.prototype.j=function(){if(this.b){m(this,this.b);var a=this.getContent();if(a){if(typeof a=="string"){a=a.replace(/^\s*([\S\s]*)\b\s*$/,"$1");a=l(this,a)}this.b.appendChild(a);var c=this;a=this.b.getElementsByTagName("IMG");for(var d=0,e;e=a[d];d++){google.maps.event.addDomListener(e,"mousedown",function(h){if(c.getDraggable()){h.preventDefault&&h.preventDefault();h.returnValue=f}});google.maps.event.addDomListener(e,"load",function(){c.draw()})}google.maps.event.trigger(this,"domready")}this.c&&
	this.draw()}};g.prototype.content_changed=g.prototype.j;function n(a,c){if(a.c){var d="";if(navigator.userAgent.indexOf("Gecko/")!==-1){if(c=="dragging")d="-moz-grabbing";if(c=="dragready")d="-moz-grab"}else if(c=="dragging"||c=="dragready")d="move";if(c=="draggable")d="pointer";if(a.a.style.cursor!=d)a.a.style.cursor=d}}
	function o(a,c){if(a.getDraggable())if(!a.d){a.d=b;var d=a.getMap();a.m=d.get("draggable");d.set("draggable",f);a.h=c.clientX;a.i=c.clientY;n(a,"dragready");a.a.style.MozUserSelect="none";a.a.style.KhtmlUserSelect="none";a.a.style.WebkitUserSelect="none";a.a.unselectable="on";a.a.onselectstart=function(){return f};p(a);google.maps.event.trigger(a,"dragstart")}}
	function q(a){if(a.getDraggable())if(a.d){a.d=f;a.getMap().set("draggable",a.m);a.h=a.i=a.m=null;a.a.style.MozUserSelect="";a.a.style.KhtmlUserSelect="";a.a.style.WebkitUserSelect="";a.a.unselectable="off";a.a.onselectstart=function(){};r(a);n(a,"draggable");google.maps.event.trigger(a,"dragend");a.draw()}}
	function s(a,c){if(!a.getDraggable()||!a.d)q(a);else{var d=a.h-c.clientX,e=a.i-c.clientY;a.h=c.clientX;a.i=c.clientY;d=parseInt(a.a.style.left,10)-d;e=parseInt(a.a.style.top,10)-e;a.a.style.left=d+"px";a.a.style.top=e+"px";var h=t(a);a.setPosition(a.getProjection().fromDivPixelToLatLng(new google.maps.Point(d-h.width,e-h.height)));n(a,"dragging");google.maps.event.trigger(a,"drag")}}function k(a){if(a.f){google.maps.event.removeListener(a.f);delete a.f}n(a,"")}
	function j(a,c){if(c){a.f=google.maps.event.addDomListener(c,"mousedown",function(d){o(a,d)});n(a,"draggable")}}function p(a){if(a.a.setCapture){a.a.setCapture(b);a.e=[google.maps.event.addDomListener(a.a,"mousemove",function(c){s(a,c)},b),google.maps.event.addDomListener(a.a,"mouseup",function(){q(a);a.a.releaseCapture()},b)]}else a.e=[google.maps.event.addDomListener(window,"mousemove",function(c){s(a,c)},b),google.maps.event.addDomListener(window,"mouseup",function(){q(a)},b)]}
	function r(a){if(a.e){for(var c=0,d;d=a.e[c];c++)google.maps.event.removeListener(d);a.e.length=0}}
	function t(a){var c=a.l();if(typeof c=="object")return c;var d=new google.maps.Size(0,0);if(!a.b)return d;var e=a.b.offsetWidth;a=a.b.offsetHeight;switch(c){case i.TOP:d.width=-e/2;break;case i.TOP_RIGHT:d.width=-e;break;case i.LEFT:d.height=-a/2;break;case i.MIDDLE:d.width=-e/2;d.height=-a/2;break;case i.RIGHT:d.width=-e;d.height=-a/2;break;case i.BOTTOM_LEFT:d.height=-a;break;case i.BOTTOM:d.width=-e/2;d.height=-a;break;case i.BOTTOM_RIGHT:d.width=-e;d.height=-a}return d}
	g.prototype.onAdd=function(){if(!this.a){this.a=document.createElement("DIV");this.a.style.position="absolute"}if(this.getZIndex())this.a.style.zIndex=this.getZIndex();this.a.style.display=this.getVisible()?"":"none";if(!this.b){this.b=document.createElement("DIV");this.a.appendChild(this.b);var a=this;google.maps.event.addDomListener(this.b,"click",function(){google.maps.event.trigger(a,"click")});google.maps.event.addDomListener(this.b,"mouseover",function(){google.maps.event.trigger(a,"mouseover")});
		google.maps.event.addDomListener(this.b,"mouseout",function(){google.maps.event.trigger(a,"mouseout")})}this.c=b;this.j();this.g();this.k();var c=this.getPanes();c&&c.overlayImage.appendChild(this.a);google.maps.event.trigger(this,"ready")};g.prototype.onAdd=g.prototype.onAdd;
	g.prototype.draw=function(){if(!(!this.c||this.d)){var a=this.getProjection();if(a){var c=this.get("position");a=a.fromLatLngToDivPixel(c);c=t(this);this.a.style.top=a.y+c.height+"px";this.a.style.left=a.x+c.width+"px";a=this.b.offsetHeight;c=this.b.offsetWidth;c!=this.get("width")&&this.set("width",c);a!=this.get("height")&&this.set("height",a)}}};g.prototype.draw=g.prototype.draw;g.prototype.onRemove=function(){this.a&&this.a.parentNode&&this.a.parentNode.removeChild(this.a);k(this)};
	g.prototype.onRemove=g.prototype.onRemove;var i={TOP_LEFT:1,TOP:2,TOP_RIGHT:3,LEFT:4,MIDDLE:5,RIGHT:6,BOTTOM_LEFT:7,BOTTOM:8,BOTTOM_RIGHT:9};window.RichMarkerPosition=i;
})();

window.JetEngineMapsProvider = function() {

	this.initMap = function( container, settings ) {

		settings = settings || {};
		
		if ( ! settings.mapTypeId ) {
			settings.mapTypeId = google.maps.MapTypeId.ROADMAP;
		}
		
		let map = new google.maps.Map( container, settings );
		
		return map;
	}

	this.initBounds = function() {
		return new google.maps.LatLngBounds();
	}

	this.fitMapBounds = function( data ) {

		data.map.fitBounds( bounds );

		var listener = google.maps.event.addListener( data.map, 'idle', function() {
			if ( ! data.marker.getMap() ) {
				JetEngineMaps.fitMapToMarker( data.marker, data.markersClusterer );
			}
			google.maps.event.removeListener( listener );
		} );
	}

	this.addMarker = function( data ) {
		data.position = new google.maps.LatLng( data.position.lat, data.position.lng );
		return new RichMarker( data );
	}

	this.removeMarker = function( marker ) {
		marker.setMap( null );
	}

	this.addPopup = function( data ) {
		return new JetGMInfoBox( {
			position: new google.maps.LatLng( data.position.lat, data.position.lng ),
			maxWidth: data.width,
			boxClass: "jet-map-box",
			zIndex: null,
			pixelOffset: new google.maps.Size( 0 - data.width / 2, 0 - data.offset ),
			alignBottom: true,
			infoBoxClearance: new google.maps.Size( 10, 10 ),
			pane: "floatPane",
			enableEventPropagation: true,
		} );
	}

	this.markerOnClick = function( map, data, callback ) {

		data = data || {};

		data.map    = map;
		data.shadow = false;

		google.maps.event.addListener( map, "click", ( event ) => {

			data.position = {
				lat: event.latLng.lat(),
				lng: event.latLng.lng(),
			};

			if ( callback ) {
				callback( this.addMarker( data ) );
			}

		} );
	}

	this.closePopup = function( infoBox, callback ) {
		google.maps.event.addListener( infoBox, 'closeclick', callback );
	}

	this.openPopup = function( trigger, callback ) {
		google.maps.event.addListener( trigger, 'click', callback );
	}

	this.getMarkerPosition = function( marker, toJSON ) {
		toJSON = toJSON || false;

		if ( toJSON ) {
			return marker.position.toJSON();
		} else {
			return marker.position;
		}
		
	}

	this.getMarkerCluster = function( data ) {
		return new MarkerClusterer(
			data.map,
			data.markers,
			{ imagePath: data.clustererImg }
		);
	}

	this.addMarkers = function( markerCluster, markers ) {
		markerCluster.addMarkers( markers );
	}

	this.removeMarkers = function( markerCluster, markers ) {
		markerCluster.removeMarkers( markers );
	}

	this.setAutoCenter = function( data ) {

		data.map.fitBounds( data.bounds );

		if ( data.settings.maxZoom ) {

			var listener = google.maps.event.addListener( data.map, 'idle', function() {

				if ( data.map.getZoom() > data.settings.maxZoom ) {
					data.map.setZoom( data.settings.maxZoom );
				}

				google.maps.event.removeListener( listener );

			} );
		}
	}

}
