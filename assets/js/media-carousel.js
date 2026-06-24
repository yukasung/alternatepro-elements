/**
 * AlternatePro Media Carousel frontend behavior.
 *
 * @package AlternatePro\Elements
 */

(function () {
	"use strict";

	var CAROUSEL_SELECTOR     = "[data-ap-media-carousel]";
	var OPTIONS_ATTRIBUTE     = "data-ap-media-carousel-options";
	var INITIALIZED_ATTRIBUTE = "data-ap-media-carousel-initialized";

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

	function getSlidesToScroll( options ) {
		var value = parseInt( options.slidesToScroll, 10 );

		if ( isNaN( value ) || 1 > value ) {
			return 1;
		}

		return value;
	}

	function toArray( items ) {
		return Array.prototype.slice.call( items || [] );
	}

	function clamp( value, min, max ) {
		return Math.min( Math.max( value, min ), max );
	}

	function getMaxOffset( state ) {
		return Math.max( state.track.scrollWidth - state.viewport.clientWidth, 0 );
	}

	function getSlideOffset( state, index ) {
		var slide = state.slides[ index ];

		if ( ! slide ) {
			return 0;
		}

		return Math.min( Math.max( slide.offsetLeft, 0 ), getMaxOffset( state ) );
	}

	function updateSlideVisibility( state, offset ) {
		var viewportEnd = offset + state.viewport.clientWidth + 1;

		state.slides.forEach(
			function ( slide ) {
				var slideStart = slide.offsetLeft;
				var slideEnd   = slideStart + slide.offsetWidth;
				var isVisible  = slideEnd > offset && slideStart < viewportEnd;

				slide.setAttribute( "aria-hidden", isVisible ? "false" : "true" );
			}
		);
	}

	function updateControls( state, offset ) {
		state.dots.forEach(
			function ( dot, index ) {
				var isActive = index === state.currentIndex;

				dot.classList.toggle( "ap-media-carousel__dot--active", isActive );
				dot.setAttribute( "aria-current", isActive ? "true" : "false" );
				dot.setAttribute( "aria-selected", isActive ? "true" : "false" );
			}
		);

		if ( state.previousButton ) {
			state.previousButton.disabled = 0 >= state.currentIndex;
		}

		if ( state.nextButton ) {
			state.nextButton.disabled = state.currentIndex >= state.slides.length - 1;
		}

		updateSlideVisibility( state, offset );
	}

	function goToSlide( state, index ) {
		var maxIndex = Math.max( state.slides.length - 1, 0 );
		var offset;

		state.currentIndex = clamp( index, 0, maxIndex );
		offset             = getSlideOffset( state, state.currentIndex );

		state.track.style.transform = "translate3d(" + ( -1 * offset ) + "px, 0, 0)";

		updateControls( state, offset );
	}

	function scheduleRefresh( state ) {
		if ( state.refreshFrame ) {
			window.cancelAnimationFrame( state.refreshFrame );
		}

		state.refreshFrame = window.requestAnimationFrame(
			function () {
				goToSlide( state, state.currentIndex );
			}
		);
	}

	function initCarousel( carousel ) {
		var options = parseOptions( carousel );
		var state;

		if ( "yes" === carousel.getAttribute( INITIALIZED_ATTRIBUTE ) ) {
			return;
		}

		state = {
			currentIndex: 0,
			dots: toArray( carousel.querySelectorAll( ".ap-media-carousel__dot" ) ),
			nextButton: carousel.querySelector( ".ap-media-carousel__arrow--next" ),
			previousButton: carousel.querySelector( ".ap-media-carousel__arrow--prev" ),
			refreshFrame: null,
			slides: toArray( carousel.querySelectorAll( ".ap-media-carousel__slide" ) ),
			slidesToScroll: getSlidesToScroll( options ),
			track: carousel.querySelector( ".ap-media-carousel__slides" ),
			viewport: carousel.querySelector( ".ap-media-carousel__viewport" )
		};

		if ( ! state.track || ! state.viewport || ! state.slides.length ) {
			return;
		}

		carousel.setAttribute( INITIALIZED_ATTRIBUTE, "yes" );
		carousel.apMediaCarousel = state;

		if ( state.previousButton ) {
			state.previousButton.addEventListener(
				"click",
				function () {
					goToSlide( state, state.currentIndex - state.slidesToScroll );
				}
			);
		}

		if ( state.nextButton ) {
			state.nextButton.addEventListener(
				"click",
				function () {
					goToSlide( state, state.currentIndex + state.slidesToScroll );
				}
			);
		}

		state.dots.forEach(
			function ( dot, index ) {
				dot.addEventListener(
					"click",
					function () {
						goToSlide( state, index );
					}
				);
			}
		);

		window.addEventListener(
			"resize",
			function () {
				scheduleRefresh( state );
			}
		);

		goToSlide( state, 0 );
	}

	function initCarousels( scope ) {
		var root = scope && scope.nodeType ? scope : document;

		if ( root.matches && root.matches( CAROUSEL_SELECTOR ) ) {
			initCarousel( root );
		}

		toArray( root.querySelectorAll( CAROUSEL_SELECTOR ) ).forEach(
			function ( carousel ) {
				initCarousel( carousel );
			}
		);
	}

	function bindElementorHook() {
		if ( ! window.elementorFrontend || ! window.elementorFrontend.hooks ) {
			return;
		}

		window.elementorFrontend.hooks.addAction(
			"frontend/element_ready/ap-media-carousel.default",
			function ( $scope ) {
				var scope = $scope && $scope[ 0 ] ? $scope[ 0 ] : $scope;

				initCarousels( scope );
			}
		);
	}

	if ( "loading" === document.readyState ) {
		document.addEventListener(
			"DOMContentLoaded",
			function () {
				initCarousels( document );
			}
		);
	} else {
		initCarousels( document );
	}

	if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
		bindElementorHook();
	} else {
		window.addEventListener( "elementor/frontend/init", bindElementorHook );
	}
}());
