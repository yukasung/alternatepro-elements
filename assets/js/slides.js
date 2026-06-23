/**
 * AlternatePro Slides frontend behavior.
 *
 * @package AlternatePro\Elements
 */

(function ( $ ) {
	"use strict";

	var SLIDES_SELECTOR       = "[data-ap-slides]";
	var TRACK_SELECTOR        = "[data-ap-slides-track]";
	var SLIDE_SELECTOR        = "[data-ap-slides-slide]";
	var DOT_SELECTOR          = "[data-ap-slides-dot]";
	var OPTIONS_ATTRIBUTE     = "data-ap-slides-options";
	var COUNT_ATTRIBUTE       = "data-ap-slides-count";
	var INITIALIZED_ATTRIBUTE = "data-ap-slides-initialized";
	var CONTENT_ANIMATION_CLASS_PREFIX = "ap-slides--content-animation-";
	var CONTENT_ANIMATING_CLASS        = "ap-slides__slide--content-animating";
	var CONTENT_ANIMATION_TIMER_KEY    = "apSlidesContentAnimationTimer";
	var CONTENT_ANIMATION_STAGGER_MS   = 90;
	var CONTENT_ANIMATION_MODES        = [ "none", "up", "down", "left", "right", "zoom" ];

	function parseOptions( element ) {
		var rawOptions = element ? element.getAttribute( OPTIONS_ATTRIBUTE ) : "";

		if ( ! rawOptions ) {
			return {};
		}

		try {
			return JSON.parse( rawOptions );
		} catch ( error ) {
			return {};
		}
	}

	function shouldReduceMotion() {
		return window.matchMedia && window.matchMedia( "(prefers-reduced-motion: reduce)" ).matches;
	}

	function getContentAnimationMode( options ) {
		var mode = options && options.contentAnimation ? String( options.contentAnimation ) : "up";

		return CONTENT_ANIMATION_MODES.indexOf( mode ) >= 0 ? mode : "up";
	}

	function applyContentAnimationMode( $root, options ) {
		CONTENT_ANIMATION_MODES.forEach(
			function ( mode ) {
				$root.removeClass( CONTENT_ANIMATION_CLASS_PREFIX + mode );
			}
		);

		$root.addClass( CONTENT_ANIMATION_CLASS_PREFIX + getContentAnimationMode( options ) );
	}

	function getSlideCount( root, options ) {
		var attributeCount = parseInt( root.getAttribute( COUNT_ATTRIBUTE ) || "0", 10 );
		var optionCount    = parseInt( options.slideCount || 0, 10 );

		if ( ! isNaN( attributeCount ) && attributeCount > 0 ) {
			return attributeCount;
		}

		if ( ! isNaN( optionCount ) && optionCount > 0 ) {
			return optionCount;
		}

		return $( root ).find( TRACK_SELECTOR ).first().children( SLIDE_SELECTOR ).length;
	}

	function storeFocusableState( element ) {
		if ( null !== element.getAttribute( "data-ap-slides-tabindex" ) ) {
			return;
		}

		element.setAttribute( "data-ap-slides-tabindex", element.getAttribute( "tabindex" ) || "" );
	}

	function restoreFocusableState( element ) {
		var originalTabindex = element.getAttribute( "data-ap-slides-tabindex" );

		if ( null === originalTabindex ) {
			return;
		}

		if ( "" === originalTabindex ) {
			element.removeAttribute( "tabindex" );
		} else {
			element.setAttribute( "tabindex", originalTabindex );
		}

		element.removeAttribute( "data-ap-slides-tabindex" );
	}

	function updateFocusableState( $item, active ) {
		$item.find( "a, button, input, select, textarea, [tabindex]" ).each(
			function () {
				if ( active ) {
					restoreFocusableState( this );
					return;
				}

				storeFocusableState( this );
				this.setAttribute( "tabindex", "-1" );
			}
		);
	}

	function getCarousel( $track ) {
		return $track.data( "owl.carousel" );
	}

	function getCurrentIndex( $track ) {
		var carousel = getCarousel( $track );

		if ( ! carousel || "function" !== typeof carousel.current || "function" !== typeof carousel.relative ) {
			return 0;
		}

		return carousel.relative( carousel.current() );
	}

	function getCurrentItem( $track ) {
		var carousel = getCarousel( $track );
		var index;

		if ( ! carousel || "function" !== typeof carousel.current || ! carousel.$stage ) {
			return $track.find( ".owl-item.active" ).first();
		}

		index = carousel.current();

		if ( "number" !== typeof index ) {
			return $track.find( ".owl-item.active" ).first();
		}

		return carousel.$stage.children( ".owl-item" ).eq( index );
	}

	function getActiveItem( $track ) {
		var $active = $track.find( ".owl-item.active" ).first();

		return $active.length ? $active : getCurrentItem( $track );
	}

	function getContentAnimationDuration( options ) {
		var duration = options && options.smartSpeed ? parseInt( options.smartSpeed, 10 ) : 500;

		if ( isNaN( duration ) || duration < 0 ) {
			return 500;
		}

		return duration;
	}

	function clearContentAnimationTimer( $root ) {
		var timer = $root.data( CONTENT_ANIMATION_TIMER_KEY );

		if ( timer ) {
			window.clearTimeout( timer );
			$root.removeData( CONTENT_ANIMATION_TIMER_KEY );
		}
	}

	function updateArrowState( $root, currentIndex, slideCount, options ) {
		var loop = true === options.loop;

		$root.find( "[data-ap-slides-prev]" ).prop( "disabled", ! loop && 0 === currentIndex );
		$root.find( "[data-ap-slides-next]" ).prop( "disabled", ! loop && currentIndex >= slideCount - 1 );
	}

	function updateDotState( $root, currentIndex ) {
		$root.find( DOT_SELECTOR ).each(
			function ( dotIndex ) {
				var active = dotIndex === currentIndex;

				$( this )
					.toggleClass( "ap-slides__dot--active", active )
					.attr( "aria-selected", active ? "true" : "false" );
			}
		);
	}

	function updateSlideAccessibility( $root, $track, options ) {
		var currentIndex = getCurrentIndex( $track );
		var slideCount   = getSlideCount( $root[0], options );

		$root.attr( "data-ap-slides-active-index", String( currentIndex ) );

		$track.find( ".owl-item" ).each(
			function () {
				var $item  = $( this );
				var active = $item.hasClass( "active" );

				$item.attr( "aria-hidden", active ? "false" : "true" );
				updateFocusableState( $item, active );
				$item.find( SLIDE_SELECTOR )
					.toggleClass( "ap-slides__slide--active", active )
					.attr( "aria-hidden", active ? "false" : "true" );
			}
		);

		updateArrowState( $root, currentIndex, slideCount, options );
		updateDotState( $root, currentIndex );
	}

	function restartContentAnimation( $root, $track, options, $targetItem ) {
		var mode     = getContentAnimationMode( options );
		var $slides  = $track.find( SLIDE_SELECTOR );
		var $active;
		var timer;

		clearContentAnimationTimer( $root );
		$slides.removeClass( CONTENT_ANIMATING_CLASS );

		if ( "none" === mode || shouldReduceMotion() ) {
			return;
		}

		$active = $targetItem && $targetItem.length ? $targetItem.find( SLIDE_SELECTOR ) : getActiveItem( $track ).find( SLIDE_SELECTOR );

		if ( ! $active.length ) {
			$active = $track.find( ".owl-item.active " + SLIDE_SELECTOR );
		}

		if ( ! $active.length ) {
			return;
		}

		$active.each(
			function () {
				void this.offsetWidth;
			}
		);

		window.requestAnimationFrame(
			function () {
				$active.addClass( CONTENT_ANIMATING_CLASS );
				$root.attr( "data-ap-slides-content-animation", mode );

				timer = window.setTimeout(
					function () {
						$active.removeClass( CONTENT_ANIMATING_CLASS );
						$root.removeData( CONTENT_ANIMATION_TIMER_KEY );
					},
					getContentAnimationDuration( options ) + ( CONTENT_ANIMATION_STAGGER_MS * 2 ) + 80
				);

				$root.data( CONTENT_ANIMATION_TIMER_KEY, timer );
			}
		);
	}

	function getOwlOptions( options ) {
		var reducedMotion = shouldReduceMotion();
		var owlOptions    = {
			items: 1,
			slideBy: 1,
			margin: 0,
			loop: true === options.loop,
			rewind: false,
			autoplay: true === options.autoplay,
			autoplayTimeout: options.autoplayTimeout || 5000,
			autoplaySpeed: options.autoplaySpeed || options.smartSpeed || 500,
			autoplayHoverPause: true === options.autoplayHoverPause,
			smartSpeed: options.smartSpeed || 500,
			nav: false,
			dots: false,
			mouseDrag: true,
			touchDrag: true,
			pullDrag: true,
			rtl: true === options.rtl
		};

		if ( "fade" === options.transition ) {
			owlOptions.animateIn  = options.animateIn || "ap-slides-owl-fade-in";
			owlOptions.animateOut = options.animateOut || "ap-slides-owl-fade-out";
		}

		if ( reducedMotion ) {
			owlOptions.autoplay    = false;
			owlOptions.smartSpeed  = 0;
			owlOptions.animateIn   = false;
			owlOptions.animateOut  = false;
			owlOptions.mouseDrag   = false;
			owlOptions.touchDrag   = false;
			owlOptions.pullDrag    = false;
		}

		return owlOptions;
	}

	function stopAutoplayOnInteraction( $track, options ) {
		if ( true === options.pauseOnInteraction ) {
			$track.trigger( "stop.owl.autoplay" );
		}
	}

	function bindCustomControls( $root, $track, options ) {
		$root.off( "click.apSlides" ).on(
			"click.apSlides",
			"[data-ap-slides-prev], [data-ap-slides-next], " + DOT_SELECTOR,
			function ( event ) {
				var $control = $( this );
				var speed    = options.smartSpeed || 500;
				var dotIndex;

				event.preventDefault();

				if ( $control.prop( "disabled" ) ) {
					return;
				}

				stopAutoplayOnInteraction( $track, options );

				if ( $control.is( "[data-ap-slides-prev]" ) ) {
					$track.trigger( "prev.owl.carousel", [ speed ] );
					return;
				}

				if ( $control.is( "[data-ap-slides-next]" ) ) {
					$track.trigger( "next.owl.carousel", [ speed ] );
					return;
				}

				dotIndex = parseInt( $control.attr( "data-ap-slides-dot" ) || "0", 10 );
				$track.trigger( "to.owl.carousel", [ isNaN( dotIndex ) ? 0 : dotIndex, speed ] );
			}
		);
	}

	function initStaticSlides( $root ) {
		$root.attr(
			{
				"data-ap-slides-active-index": "0",
				"data-ap-slides-initialized": "yes"
			}
		);

		$root.find( SLIDE_SELECTOR ).each(
			function ( index ) {
				$( this )
					.toggleClass( "ap-slides__slide--active", 0 === index )
					.attr( "aria-hidden", 0 === index ? "false" : "true" );
			}
		);
	}

	function initSlides( root ) {
		var $root      = $( root );
		var $track     = $root.find( TRACK_SELECTOR ).first();
		var options    = parseOptions( root );
		var slideCount = getSlideCount( root, options );
		var owlOptions;

		if ( ! $track.length || "yes" === root.getAttribute( INITIALIZED_ATTRIBUTE ) ) {
			return;
		}

		if ( slideCount < 2 ) {
			initStaticSlides( $root );
			return;
		}

		if ( "function" !== typeof $track.owlCarousel ) {
			initStaticSlides( $root );
			return;
		}

		owlOptions = getOwlOptions( options );

		if ( shouldReduceMotion() ) {
			$root.addClass( "ap-slides--reduced-motion" );
		}

		applyContentAnimationMode( $root, options );

		$root
			.attr(
				{
					"data-ap-slides-animation-engine": options.animationEngineName || "OwlCarousel2",
					"data-ap-slides-initialized": "yes"
				}
			)
			.css( "--ap-slides-content-animation-duration", ( owlOptions.smartSpeed || 0 ) + "ms" );

		function syncSlides( animateContent, $targetItem ) {
			window.requestAnimationFrame(
				function () {
					updateSlideAccessibility( $root, $track, options );

					if ( animateContent ) {
						restartContentAnimation( $root, $track, options, $targetItem );
					}
				}
			);
		}

		$track.on(
			"initialized.owl.carousel refreshed.owl.carousel",
			function () {
				syncSlides( true );
			}
		);

		$track.on(
			"changed.owl.carousel",
			function ( event ) {
				if ( event.property && "position" === event.property.name ) {
					syncSlides( false );
					return;
				}

				window.requestAnimationFrame(
					function () {
						updateSlideAccessibility( $root, $track, options );
					}
				);
			}
		);

		$track.on(
			"translated.owl.carousel",
			function () {
				syncSlides( true );
			}
		);

		$track.on(
			"resized.owl.carousel",
			function () {
				syncSlides( false );
			}
		);

		$track.owlCarousel( owlOptions );
		bindCustomControls( $root, $track, options );
		updateSlideAccessibility( $root, $track, options );
		restartContentAnimation( $root, $track, options, getCurrentItem( $track ) );
	}

	function initAllSlides( scope ) {
		var $scope = $( scope );

		$scope.find( SLIDES_SELECTOR ).addBack( SLIDES_SELECTOR ).each(
			function () {
				initSlides( this );
			}
		);
	}

	function bindElementorHook() {
		if ( ! window.elementorFrontend || ! window.elementorFrontend.hooks ) {
			return;
		}

		window.elementorFrontend.hooks.addAction(
			"frontend/element_ready/ap-slides.default",
			function ( $scope ) {
				initAllSlides( $scope );
			}
		);
	}

	$(
		function () {
			initAllSlides( document );
		}
	);

	if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
		bindElementorHook();
	} else {
		window.addEventListener( "elementor/frontend/init", bindElementorHook );
	}
}( jQuery ));
