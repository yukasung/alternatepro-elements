/**
 * AlternatePro Image Carousel frontend behavior.
 *
 * @package AlternatePro\Elements
 */

(function ( $ ) {
	"use strict";

	var CAROUSEL_SELECTOR     = "[data-apro-image-carousel]";
	var OPTIONS_ATTRIBUTE     = "data-apro-image-carousel-options";
	var INITIALIZED_ATTRIBUTE = "data-apro-image-carousel-initialized";

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

	function updateFocusableState( $item, active ) {
		$item.find( "a, button, input, select, textarea, [tabindex]" ).each(
			function () {
				var element = this;
				var originalTabindex;

				if ( active ) {
					originalTabindex = element.getAttribute( "data-apro-carousel-tabindex" );

					if ( null === originalTabindex ) {
						return;
					}

					if ( "" === originalTabindex ) {
						element.removeAttribute( "tabindex" );
					} else {
						element.setAttribute( "tabindex", originalTabindex );
					}

					element.removeAttribute( "data-apro-carousel-tabindex" );

					return;
				}

				if ( null === element.getAttribute( "data-apro-carousel-tabindex" ) ) {
					element.setAttribute( "data-apro-carousel-tabindex", element.getAttribute( "tabindex" ) || "" );
				}

				element.setAttribute( "tabindex", "-1" );
			}
		);
	}

	function updateSlideAccessibility( $root ) {
		$root.find( ".owl-item" ).each(
			function () {
				var $item  = $( this );
				var active = $item.hasClass( "active" );

				$item.attr( "aria-hidden", active ? "false" : "true" );
				updateFocusableState( $item, active );
			}
		);
	}

	function updateControlLabels( $root, options ) {
		var dotLabel = options.dotLabel || "Go to slide";

		$root.find( ".owl-prev" ).attr(
			{
				"aria-label": options.prevLabel || "Previous slide",
				"type": "button"
			}
		);

		$root.find( ".owl-next" ).attr(
			{
				"aria-label": options.nextLabel || "Next slide",
				"type": "button"
			}
		);

		$root.find( ".owl-dot" ).each(
			function ( index ) {
				$( this ).attr(
					{
						"aria-label": dotLabel + " " + ( index + 1 ),
						"type": "button"
					}
				);
			}
		);
	}

	function getOwlOptions( options, $root ) {
		var owlOptions = $.extend( true, {}, options );

		owlOptions.navText = [
			'<span class="apro-image-carousel__arrow-icon" aria-hidden="true">&lsaquo;</span>',
			'<span class="apro-image-carousel__arrow-icon" aria-hidden="true">&rsaquo;</span>'
		];

		owlOptions.onInitialized = function () {
			updateControlLabels( $root, options );
			updateSlideAccessibility( $root );
		};

		owlOptions.onRefreshed = function () {
			updateControlLabels( $root, options );
			updateSlideAccessibility( $root );
		};

		owlOptions.onTranslated = function () {
			updateControlLabels( $root, options );
			updateSlideAccessibility( $root );
		};

		return owlOptions;
	}

	function initCarousel( carousel ) {
		var $root   = $( carousel );
		var $track  = $root.find( ".apro-image-carousel__track" ).first();
		var options = parseOptions( carousel );
		var owlOptions;

		if ( ! $track.length || "yes" === carousel.getAttribute( INITIALIZED_ATTRIBUTE ) ) {
			return;
		}

		if ( "function" !== typeof $track.owlCarousel ) {
			return;
		}

		if ( shouldReduceMotion() ) {
			options.autoplay   = false;
			options.smartSpeed = 0;
		}

		owlOptions = getOwlOptions( options, $root );

		carousel.setAttribute( INITIALIZED_ATTRIBUTE, "yes" );
		$track.owlCarousel( owlOptions );
		$track.on(
			"changed.owl.carousel refreshed.owl.carousel translated.owl.carousel",
			function () {
				updateControlLabels( $root, options );
				updateSlideAccessibility( $root );
			}
		);

		if ( options.pauseOnInteraction ) {
			$root.on(
				"click",
				".owl-prev, .owl-next, .owl-dot",
				function () {
					$track.trigger( "stop.owl.autoplay" );
				}
			);
		}
	}

	function initCarousels( scope ) {
		var $scope = $( scope );

		$scope.find( CAROUSEL_SELECTOR ).addBack( CAROUSEL_SELECTOR ).each(
			function () {
				initCarousel( this );
			}
		);
	}

	function bindElementorHook() {
		if ( ! window.elementorFrontend || ! window.elementorFrontend.hooks ) {
			return;
		}

		window.elementorFrontend.hooks.addAction(
			"frontend/element_ready/alternatepro-image-carousel.default",
			function ( $scope ) {
				initCarousels( $scope );
			}
		);
	}

	$(
		function () {
			initCarousels( document );
		}
	);

	if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
		bindElementorHook();
	} else {
		window.addEventListener( "elementor/frontend/init", bindElementorHook );
	}
}( jQuery ));
