var EleCustomSkinSlider = elementorModules.frontend.handlers.Base.extend({
  
  getSkinPrefix: function getSkinPrefix() {
    return 'custom_';
  },  
  getDefaultSettings: function getDefaultSettings() {
    return {
      selectors: {
        carousel: '.swiper-container',
        slideContent: '.swiper-slide'
      }
    }; 
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings('selectors');
    var elements = {
      $carousel: this.$element.find(selectors.carousel)
    };
    elements.$swiperSlides = elements.$carousel.find(selectors.slideContent);
    return elements;
  },
 
  getSlidesCount: function getSlidesCount() {
    return this.elements.$swiperSlides.length;
  },
/***** start new getSwipperSettings function *******************/
   getSwiperSettings: function getSwiperSettings() {
    const elementSettings = this.getElementSettings(),
          slidesToShow = +elementSettings[this.getSkinPrefix() + 'slides_to_show'] || 3,
          isSingleSlide = 1 === slidesToShow,
          elementorBreakpoints = elementorFrontend.config.responsive.activeBreakpoints,
          defaultSlidesToShowMap = {
      mobile: 1,
      tablet: isSingleSlide ? 1 : 2
    };
    const swiperOptions = {
      slidesPerView: slidesToShow,
      loop: 'yes' === elementSettings[this.getSkinPrefix() + 'infinite'],
      speed: elementSettings[this.getSkinPrefix() + 'speed'],
      handleElementorBreakpoints: true
    };
    swiperOptions.breakpoints = {};
    let lastBreakpointSlidesToShowValue = slidesToShow;
    Object.keys(elementorBreakpoints).reverse().forEach(breakpointName => {
      // Tablet has a specific default `slides_to_show`.
      const defaultSlidesToShow = defaultSlidesToShowMap[breakpointName] ? defaultSlidesToShowMap[breakpointName] : lastBreakpointSlidesToShowValue;
      swiperOptions.breakpoints[elementorBreakpoints[breakpointName].value] = {
        slidesPerView: +elementSettings[this.getSkinPrefix() + 'slides_to_show_' + breakpointName] || defaultSlidesToShow,
        slidesPerGroup: +elementSettings[this.getSkinPrefix() + 'slides_to_scroll_' + breakpointName] || 1
      };
      lastBreakpointSlidesToShowValue = +elementSettings[this.getSkinPrefix() + 'slides_to_show_' + breakpointName] || defaultSlidesToShow;
    });

    if ('yes' === elementSettings[this.getSkinPrefix() + 'autoplay']) {
      swiperOptions.autoplay = {
        delay: elementSettings[this.getSkinPrefix() + 'autoplay_speed'],
        disableOnInteraction: 'yes' === elementSettings[this.getSkinPrefix() + 'pause_on_interaction']
      };
    }

    if (isSingleSlide) {
      swiperOptions.effect = elementSettings[this.getSkinPrefix() + 'effect'];

      if ('fade' === elementSettings[this.getSkinPrefix() + 'effect']) {
        swiperOptions.fadeEffect = {
          crossFade: true
        };
      }
    } else {
      swiperOptions.slidesPerGroup = +elementSettings[this.getSkinPrefix() + 'slides_to_scroll'] || 1;
    }

     
    if (elementSettings[this.getSkinPrefix() + 'slide_gap']['size']) {
      swiperOptions.spaceBetween = elementSettings[this.getSkinPrefix() + 'slide_gap']['size'];
    }

    const showArrows = 'arrows' === elementSettings[this.getSkinPrefix() + 'navigation'] || 'both' === elementSettings[this.getSkinPrefix() + 'navigation'],
          showDots = 'dots' === elementSettings[this.getSkinPrefix() + 'navigation'] || 'both' === elementSettings[this.getSkinPrefix() + 'navigation'];

    if (showArrows) {
      swiperOptions.navigation = {
        prevEl: '.elementor-swiper-button-prev',
        nextEl: '.elementor-swiper-button-next'
      };
    }

    if (showDots) {
      swiperOptions.pagination = {
        el: '.swiper-pagination',
        type: 'bullets',
        clickable: true
      };
    }

    return swiperOptions;
  },

  
  /***** end new getSwipperSettings function *******************/

  getSwiperSettingss: function getSwiperSettingss() {
    
    var elementSettings = this.getElementSettings(),
      slidesToShow = +elementSettings[this.getSkinPrefix() + 'slides_to_show'] || 3, // this.getElementSettings(this.getSkinPrefix() + 'slides_to_show');
      isSingleSlide = 1 === slidesToShow,
      defaultLGDevicesSlidesCount = isSingleSlide ? 1 : 2,
    //elementorBreakpoints = elementorFrontend.config.breakpoints;
      elementorBreakpoints = elementorFrontend.config.responsive.activeBreakpoints;

    var swiperOptions = {
      slidesPerView: slidesToShow,
      loop: 'yes' === elementSettings[this.getSkinPrefix() + 'infinite'],
      speed: elementSettings[this.getSkinPrefix() + 'speed'],
      handleElementorBreakpoints: true
    };
    swiperOptions.breakpoints = {};
    swiperOptions.breakpoints[elementorBreakpoints.mobile.value] = {
      slidesPerView: +elementSettings[this.getSkinPrefix() + 'slides_to_show_mobile'] || 1,
      slidesPerGroup: +elementSettings[this.getSkinPrefix() + 'slides_to_scroll_mobile'] || 1
    };
    swiperOptions.breakpoints[elementorBreakpoints.tablet.value] = {
      slidesPerView: +elementSettings[this.getSkinPrefix() + 'slides_to_show_tablet'] || defaultLGDevicesSlidesCount,
      slidesPerGroup: +elementSettings[this.getSkinPrefix() + 'slides_to_scroll_tablet'] || defaultLGDevicesSlidesCount
    };
    if (!this.isEdit && 'yes' === elementSettings[this.getSkinPrefix() + 'autoplay']) {
      swiperOptions.autoplay = {
        delay: elementSettings[this.getSkinPrefix() + 'autoplay_speed'],
        pause_on_hover:'yes',
        disableOnInteraction: true, //!!elementSettings[this.getSkinPrefix() + 'pause_on_hover'],
        pauseOnMouseEnter: true//!!elementSettings[this.getSkinPrefix() + 'pause_on_hover']
      };
    }

    if (true === swiperOptions.loop) {
      swiperOptions.loopedSlides = this.getSlidesCount();
    }

    if (isSingleSlide) {
      swiperOptions.effect = elementSettings[this.getSkinPrefix() + 'effect'];
    } else {
      swiperOptions.slidesPerGroup = +elementSettings[this.getSkinPrefix() + 'slides_to_scroll'] || defaultLGDevicesSlidesCount;
    }

    if (elementSettings[this.getSkinPrefix() + 'slide_gap'].size) {
      swiperOptions.spaceBetween = elementSettings[this.getSkinPrefix() + 'slide_gap'].size;
    }

    var showArrows = 'arrows' === elementSettings[this.getSkinPrefix() + 'navigation'] || 'both' === elementSettings[this.getSkinPrefix() + 'navigation'],
      showDots = 'dots' === elementSettings[this.getSkinPrefix() + 'navigation'] || 'both' === elementSettings[this.getSkinPrefix() + 'navigation'];

    if (showArrows) {
      swiperOptions.navigation = {
        prevEl: '.elementor-swiper-button-prev',
        nextEl: '.elementor-swiper-button-next'
      };
    }

    if (showDots) {
      swiperOptions.pagination = {
        el: '.swiper-pagination',
        type: 'bullets',
        clickable: true
      };
    }

    return swiperOptions;
  },

  updateSpaceBetween: function updateSpaceBetween() {
    this.swiper.params.spaceBetween = this.getElementSettings(this.getSkinPrefix() + 'slide_gap') || 0;
    this.swiper.update();
  },
  isSliderEnabled: function isSliderEnabled() {
    return !!this.getElementSettings(this.getSkinPrefix() + 'post_slider');
  },
   togglePauseOnHover: function togglePauseOnHover(toggleOn) {
    if (toggleOn) {
      this.elements.$carousel.on({
        mouseenter: () => {
          //this.swiper.autoplay.stop();
        },
        mouseleave: () => {
          //this.swiper.autoplay.start();
        }
      });
    } else {
      this.elements.$carousel.off('mouseenter mouseleave');
    }
                     //console.log(this.swiper.classNames);// ------------------------comment this

  },
  run: function run(){
    if(!this.isSliderEnabled()) return;
    
    if (!this.elements.$carousel.length) {
      return;
    }
/* new way to call the swiper*/    
    const Swiper = elementorFrontend.utils.swiper;
    this.swiper = new Swiper(this.elements.$carousel, this.getSwiperSettings()); // Expose the swiper instance in the frontend

    this.elements.$carousel.data('swiper', this.swiper);

    if ('yes' === this.getElementSettings(this.getSkinPrefix() + 'pause_on_hover') ) {
      this.togglePauseOnHover(true);//search for the function to add it here
    }  

    
    //add reinitilze for swiper
    ECScarousel = this.elements.$carousel;
    ECSswiperSettings = this.getSwiperSettings();
    
    ECS_add_action("swiper", function(){
      ECSswiper = new Swiper(ECScarousel, ECSswiperSettings);
    });
    
                //console.log(this.getSwiperSettings());// ------------------------comment this
  },
  onInit: function onInit(...arguments) {
    elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
    this.run();
  },

  onElementChange: function onElementChange(propertyName) {
 //    console.log(this.swiper);
    if (0 === propertyName.indexOf(this.getSkinPrefix() + 'slide_gap')) {
      this.updateSpaceBetween();
    }
  },

  onEditSettingsChange: function onEditSettingsChange(propertyName) { // here you need to refresh stuff when it
    if(!this.isSliderEnabled()) return;
    if ('activeItemIndex' === propertyName) {
     // this.swiper.slideToLoop(this.getEditSettings('activeItemIndex') - 1);//no need, can you delete this?
    }
  }
}
                                                                        
                                                                        
);

var EleCustomSkinSliderArchive = EleCustomSkinSlider.extend({
  getSkinPrefix: function getSkinPrefix() {
    return 'archive_custom_';
  }
});

//now let's see if we can call it
jQuery(window).on('elementor/frontend/init', () => {

  const addHandler = ($element) => {
    elementorFrontend.elementsHandler.addHandler(EleCustomSkinSlider, {
      $element,
    });
  };


  elementorFrontend.hooks.addAction('frontend/element_ready/posts.custom', addHandler);
  
    const addHandlerArchive = ($element) => {
    elementorFrontend.elementsHandler.addHandler(EleCustomSkinSliderArchive, {
      $element,
    });
  };


  elementorFrontend.hooks.addAction('frontend/element_ready/archive-posts.archive_custom', addHandlerArchive);

});


function ECS_reset_masonry(element){
 element.children(".ecs-post-loop").css('margin-top', '');
}

var EleCustomSkinMasonry = elementorModules.frontend.handlers.Base.extend({
  getSkinPrefix: function getSkinPrefix() {
    return 'custom_';
  },

  bindEvents: function bindEvents() {
    var cid = this.getModelCID();

    elementorFrontend.addListenerOnce(cid, 'resize', this.onWindowResize);
  },

  getClosureMethodsNames: function getClosureMethodsNames() {
    return elementorModules.frontend.handlers.Base.prototype.getClosureMethodsNames.apply(this, arguments).concat(['fitImages', 'onWindowResize', 'runMasonry']);
  },

  getDefaultSettings: function getDefaultSettings() {
    return {
      classes: {
        fitHeight: 'elementor-fit-height',
        hasItemRatio: 'elementor-has-item-ratio'
      },
      selectors: {
        postsContainer: '.elementor-posts-container',
        post: '.elementor-post',
        postThumbnail: '.elementor-post__thumbnail',
        postThumbnailImage: '.elementor-post__thumbnail img'
      }
    };
  },

  getDefaultElements: function getDefaultElements() {
    var selectors = this.getSettings('selectors');

    return {
      $postsContainer: this.$element.find(selectors.postsContainer),
      $posts: this.$element.find(selectors.post)
    };
  },

  fitImage: function fitImage($post) {
    var settings = this.getSettings(),
      $imageParent = $post.find(settings.selectors.postThumbnail),
      $image = $imageParent.find('img'),
      image = $image[0];

    if (!image) {
      return;
    }

    var imageParentRatio = $imageParent.outerHeight() / $imageParent.outerWidth(),
      imageRatio = image.naturalHeight / image.naturalWidth;

    $imageParent.toggleClass(settings.classes.fitHeight, imageRatio < imageParentRatio);
  },

  fitImages: function fitImages() {
    var $ = jQuery,
      self = this,
      itemRatio = getComputedStyle(this.$element[0], ':after').content,
      settings = this.getSettings();

    this.elements.$postsContainer.toggleClass(settings.classes.hasItemRatio, !!itemRatio.match(/\d/));

    if (self.isMasonryEnabled()) {
      return;
    }

    this.elements.$posts.each(function() {
      var $post = $(this),
        $image = $post.find(settings.selectors.postThumbnailImage);

      self.fitImage($post);

      $image.on('load', function() {
        self.fitImage($post);
      });
    });
  },

  setColsCountSettings: function setColsCountSettings() {
    var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
      settings = this.getElementSettings(),
      skinPrefix = this.getSkinPrefix(),
      colsCount;

    switch (currentDeviceMode) {
      case 'mobile':
        colsCount = settings[skinPrefix + 'columns_mobile'];
        break;
      case 'tablet':
        colsCount = settings[skinPrefix + 'columns_tablet'];
        break;
      default:
        colsCount = settings[skinPrefix + 'columns'];
    }

    this.setSettings('colsCount', colsCount);
  },

  isMasonryEnabled: function isMasonryEnabled() {
    return !!this.getElementSettings(this.getSkinPrefix() + 'masonrys');
  },

  initMasonry: function initMasonry() {
    imagesLoaded(this.elements.$posts, this.runMasonry);
  },

  runMasonry: function runMasonry() {
    var elements = this.elements;

    elements.$posts.css({
      marginTop: '',
      transitionDuration: ''
    });

    this.setColsCountSettings();

    var colsCount = this.getSettings('colsCount'),
      hasMasonry = this.isMasonryEnabled();

    elements.$postsContainer.toggleClass('elementor-posts-masonry', hasMasonry);

    if (!hasMasonry) {
      elements.$postsContainer.height('');

      return;
    }

    /* The `verticalSpaceBetween` variable is setup in a way that supports older versions of the portfolio widget */

    var verticalSpaceBetween = this.getElementSettings(this.getSkinPrefix() + 'row_gap.size');

    if ('' === this.getSkinPrefix() && '' === verticalSpaceBetween) {
      verticalSpaceBetween = this.getElementSettings(this.getSkinPrefix() + 'item_gap.size');
    }

    var masonry = new elementorModules.utils.Masonry({
      container: elements.$postsContainer,
      items: elements.$postsContainer.children( ".ecs-post-loop" ),//elements.$posts.filter(':visible'),
      columnsCount: this.getSettings('colsCount'),
      verticalSpaceBetween: verticalSpaceBetween
    });
    masonry.run();
    
    ECS_Columns_Count = this.getSettings('colsCount');
    ECS_add_action("ajax", function(){//add reinitilze to ajax
      ECS_reset_masonry(elements.$postsContainer);
      mymasonry = new elementorModules.utils.Masonry({
              container: elements.$postsContainer,
              items: elements.$postsContainer.children( ".ecs-post-loop" ),
              columnsCount: ECS_Columns_Count,
              verticalSpaceBetween: verticalSpaceBetween
            });
            mymasonry.run();

     });

  },

  run: function run() {
    // For slow browsers
    setTimeout(this.fitImages, 0);

    this.initMasonry();
  },

  onInit: function onInit() {

    elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

    this.bindEvents();

    this.run();
  },

  onWindowResize: function onWindowResize() {
    this.fitImages();
	  ECS_reset_masonry(this.elements.$postsContainer);
    this.runMasonry();
  },

  onElementChange: function onElementChange() {
    this.fitImages();

    setTimeout(this.runMasonry);
  }
});

var EleCustomSkinMasonryArchive = EleCustomSkinMasonry.extend({
  getSkinPrefix: function getSkinPrefix() {
    return 'archive_custom_';
  }
});
//now let's see if we can call it
jQuery(window).on('elementor/frontend/init', () => {

  const addHandler = ($element) => {
    elementorFrontend.elementsHandler.addHandler(EleCustomSkinMasonry, {
      $element,
    });
  };

  elementorFrontend.hooks.addAction('frontend/element_ready/posts.custom', addHandler);
  
  const addHandlerArchive = ($element) => {
    elementorFrontend.elementsHandler.addHandler(EleCustomSkinMasonryArchive, {
      $element,
    });
  };


  elementorFrontend.hooks.addAction('frontend/element_ready/archive-posts.archive_custom', addHandlerArchive);

});

//make item clickable


function EleCustomSkinItemLink() {
  // jQuery(".ecs-link-wrapper").attr("tabindex","0");
  jQuery(".ecs-link-wrapper")
    .on("click", function (event) {
      loc = jQuery(this).attr("data-href");
      aTarget = jQuery(this).attr("data-target");
      if (
        !jQuery(event.target).closest(
          ".elementor-swiper-button, .swiper-pagination, .ecs-ignore-link"
        ).length
      ) {
        if (aTarget == "_blank" || event.ctrlKey || event.metaKey) {
          window.open(loc);
        } else {
          window.location = loc;
        }
      }
    
    })
    .on("click", ".ecs-ignore-link", function (e) {
      if(!jQuery(e.target).find('.elementor-button-wrapper')){
        e.stopPropagation();
      }
    });
}

/*function EleCustomSkinItemLink(){
   // jQuery(".ecs-link-wrapper").attr("tabindex","0");
    jQuery(".ecs-link-wrapper").click(function(event) {
     loc = jQuery(this).attr("data-href");
     aTarget = jQuery(this).attr("data-target");
     if (!jQuery(event.target).closest('.elementor-swiper-button, .swiper-pagination, .ecs-ignore-link').length) {
       if(aTarget == "_blank" || event.ctrlKey || event.metaKey){
            window.open(loc);
        } else {
            window.location = loc;
        }
      }
      return false;
  }); 
}*/

jQuery( document ).ready(function() {
  EleCustomSkinItemLink();

  ECS_add_action("ajax", function(){EleCustomSkinItemLink()});

});

jQuery.fn.inView = function(){
    if(!this.length) 
        return false;
    var rect = this.get(0).getBoundingClientRect();
    var loadOffset=0;
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom - loadOffset <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );

};

function ECScheckInView(){
    jQuery('.ecs-lazyload').each(function(){
        if (jQuery(this).inView()){
          id = jQuery(this).attr("data-targetid");
          //console.log(id);
          jQuery(this).removeClass("animation-hidden");
          ECS_load_next_page(id);
        }
    });
}

jQuery(function($){
	if(!$( "body" ).hasClass( "elementor-editor-active" ) && !$( "body" ).hasClass( "elementor-editor-preview" ) ) {
    ECScheckInView();
    $(window).scroll(function(){
      if(canBeLoaded == true ){
          ECScheckInView();
        }
  	});
  }
});
