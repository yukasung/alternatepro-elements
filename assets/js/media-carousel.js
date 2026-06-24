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
	var LIGHTBOX_TRIGGER      = "[data-ap-media-carousel-lightbox-trigger]";
	var LIGHTBOX_OPEN_CLASS   = "ap-media-carousel-lightbox--open";
	var LIGHTBOX_ZOOM_CLASS   = "ap-media-carousel-lightbox--zoom";
	var BODY_LIGHTBOX_CLASS   = "ap-media-carousel-lightbox-open";
	var lightbox              = null;

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

	function getBooleanOption( options, key, defaultValue ) {
		if ( ! Object.prototype.hasOwnProperty.call( options, key ) ) {
			return defaultValue;
		}

		return true === options[ key ] || "true" === options[ key ] || "yes" === options[ key ] || 1 === options[ key ] || "1" === options[ key ];
	}

	function getPaginationType( options ) {
		return "none" === options.pagination ? "none" : "dots";
	}

	function getTransitionDuration( options ) {
		var duration = parseInt( options.transitionDuration, 10 );

		if ( isNaN( duration ) ) {
			return 500;
		}

		return clamp( duration, 0, 5000 );
	}

	function prefersReducedMotion() {
		return window.matchMedia && window.matchMedia( "(prefers-reduced-motion: reduce)" ).matches;
	}

	function getDotLabel( state, page ) {
		return state.dotLabel + " " + ( page.slideIndex + 1 );
	}

	function toArray( items ) {
		return Array.prototype.slice.call( items || [] );
	}

	function clamp( value, min, max ) {
		return Math.min( Math.max( value, min ), max );
	}

	function getLightboxItemFromSlide( slide ) {
		var source = slide ? slide.getAttribute( "data-ap-media-carousel-lightbox-src" ) : "";
		var type   = slide ? slide.getAttribute( "data-ap-media-carousel-lightbox-type" ) : "image";

		if ( ! source ) {
			return null;
		}

		return {
			slide: slide,
			source: source,
			type: "video" === type ? "video" : "image"
		};
	}

	function getLightboxItems( state ) {
		var items = [];

		state.slides.forEach(
			function ( slide ) {
				var item = getLightboxItemFromSlide( slide );

				if ( item ) {
					items.push( item );
				}
			}
		);

		return items;
	}

	function getLightboxIndexBySlide( state, slide ) {
		var matchedIndex = -1;

		state.lightboxItems.forEach(
			function ( item, index ) {
				if ( item.slide === slide ) {
					matchedIndex = index;
				}
			}
		);

		return matchedIndex;
	}

	function getLightboxLabels( carousel ) {
		function getLabel( key ) {
			return carousel.getAttribute( "data-ap-media-carousel-lightbox-" + key + "-label" ) || "";
		}

		return {
			close: getLabel( "close" ),
			dialog: getLabel( "dialog" ),
			exitFullscreen: getLabel( "exit-fullscreen" ),
			fallback: getLabel( "fallback" ),
			fullscreen: getLabel( "fullscreen" ),
			next: getLabel( "next" ),
			previous: getLabel( "previous" ),
			share: getLabel( "share" ),
			zoom: getLabel( "zoom" ),
			zoomOut: getLabel( "zoom-out" ),
			video: getLabel( "video" )
		};
	}

	function createLightboxButton( className, text, iconClass ) {
		var button = document.createElement( "button" );
		var icon;

		button.className = className;
		button.type      = "button";

		if ( iconClass ) {
			icon           = document.createElement( "span" );
			icon.className = iconClass;
			icon.setAttribute( "aria-hidden", "true" );
			button.appendChild( icon );
		} else {
			button.textContent = text;
		}

		return button;
	}

	function createLightboxCloseButton() {
		var button = createLightboxButton( "ap-media-carousel-lightbox__header-button ap-media-carousel-lightbox__close", "" );
		var icon   = document.createElementNS( "http://www.w3.org/2000/svg", "svg" );
		var first  = document.createElementNS( "http://www.w3.org/2000/svg", "path" );
		var second = document.createElementNS( "http://www.w3.org/2000/svg", "path" );

		icon.classList.add( "ap-media-carousel-lightbox__icon" );
		icon.setAttribute( "aria-hidden", "true" );
		icon.setAttribute( "focusable", "false" );
		icon.setAttribute( "viewBox", "0 0 24 24" );

		first.setAttribute( "d", "M6 6l12 12" );
		second.setAttribute( "d", "M18 6L6 18" );

		icon.appendChild( first );
		icon.appendChild( second );
		button.appendChild( icon );

		return button;
	}

	function createLightbox() {
		var root;
		var header;
		var actions;
		var counter;
		var content;
		var closeButton;
		var shareButton;
		var zoomButton;
		var fullscreenButton;
		var previousButton;
		var nextButton;

		if ( lightbox ) {
			return lightbox;
		}

		root             = document.createElement( "div" );
		header           = document.createElement( "header" );
		counter          = document.createElement( "span" );
		actions          = document.createElement( "div" );
		content          = document.createElement( "div" );
		closeButton      = createLightboxCloseButton();
		shareButton      = createLightboxButton( "ap-media-carousel-lightbox__header-button ap-media-carousel-lightbox__share", "", "eicon-share-arrow" );
		zoomButton       = createLightboxButton( "ap-media-carousel-lightbox__header-button ap-media-carousel-lightbox__zoom", "", "eicon-zoom-in-bold" );
		fullscreenButton = createLightboxButton( "ap-media-carousel-lightbox__header-button ap-media-carousel-lightbox__fullscreen", "", "eicon-frame-expand" );
		previousButton   = createLightboxButton( "ap-media-carousel-lightbox__arrow ap-media-carousel-lightbox__arrow--prev", "\u2039" );
		nextButton       = createLightboxButton( "ap-media-carousel-lightbox__arrow ap-media-carousel-lightbox__arrow--next", "\u203a" );

		root.className    = "ap-media-carousel-lightbox";
		header.className  = "ap-media-carousel-lightbox__header ap-media-carousel-lightbox-prevent-close";
		counter.className = "ap-media-carousel-lightbox__counter";
		actions.className = "ap-media-carousel-lightbox__header-actions";
		content.className = "ap-media-carousel-lightbox__content";
		root.hidden       = true;
		root.tabIndex     = -1;

		root.setAttribute( "role", "dialog" );
		root.setAttribute( "aria-modal", "true" );
		zoomButton.setAttribute( "aria-checked", "false" );
		zoomButton.setAttribute( "role", "switch" );
		fullscreenButton.setAttribute( "aria-checked", "false" );
		fullscreenButton.setAttribute( "role", "switch" );

		actions.appendChild( shareButton );
		actions.appendChild( zoomButton );
		actions.appendChild( fullscreenButton );
		actions.appendChild( closeButton );
		header.appendChild( counter );
		header.appendChild( actions );
		root.appendChild( header );
		root.appendChild( content );
		root.appendChild( previousButton );
		root.appendChild( nextButton );
		document.body.appendChild( root );

		lightbox = {
			activeIndex: 0,
			activeState: null,
			activeTrigger: null,
			actions: actions,
			closeButton: closeButton,
			content: content,
			counter: counter,
			fullscreenButton: fullscreenButton,
			header: header,
			labels: {},
			nextButton: nextButton,
			previousButton: previousButton,
			shareButton: shareButton,
			root: root,
			zoomButton: zoomButton
		};

		root.addEventListener(
			"click",
			function ( event ) {
				if ( event.target === root ) {
					closeLightbox();
				}
			}
		);

		closeButton.addEventListener( "click", closeLightbox );
		shareButton.addEventListener( "click", shareLightboxItem );
		zoomButton.addEventListener( "click", toggleLightboxZoom );
		fullscreenButton.addEventListener( "click", toggleLightboxFullscreen );
		document.addEventListener( "fullscreenchange", syncLightboxFullscreenButton );

		previousButton.addEventListener(
			"click",
			function () {
				moveLightbox( -1 );
			}
		);

		nextButton.addEventListener(
			"click",
			function () {
				moveLightbox( 1 );
			}
		);

		document.addEventListener( "keydown", handleLightboxKeydown );

		return lightbox;
	}

	function applyLightboxLabels( box, labels ) {
		box.labels = labels;

		box.root.setAttribute( "aria-label", labels.dialog );
		box.closeButton.setAttribute( "aria-label", labels.close );
		box.shareButton.setAttribute( "aria-label", labels.share );
		box.zoomButton.setAttribute( "aria-label", labels.zoom );
		box.fullscreenButton.setAttribute( "aria-label", labels.fullscreen );
		box.previousButton.setAttribute( "aria-label", labels.previous );
		box.nextButton.setAttribute( "aria-label", labels.next );
	}

	function setLightboxVariable( root, sourceStyle, property ) {
		var value = sourceStyle.getPropertyValue( property );

		if ( value ) {
			root.style.setProperty( property, value.trim() );
		}
	}

	function applyLightboxStyles( box, carousel ) {
		var sourceStyle = window.getComputedStyle( carousel );

		setLightboxVariable( box.root, sourceStyle, "--ap-media-carousel-lightbox-color" );
		setLightboxVariable( box.root, sourceStyle, "--ap-media-carousel-lightbox-ui-color" );
		setLightboxVariable( box.root, sourceStyle, "--ap-media-carousel-lightbox-ui-hover-color" );
		setLightboxVariable( box.root, sourceStyle, "--ap-media-carousel-lightbox-video-width" );
	}

	function getYoutubeId( url ) {
		var pathParts;

		if ( "youtu.be" === url.hostname.replace( /^www\./, "" ) ) {
			pathParts = url.pathname.split( "/" );

			return pathParts[ 1 ] || "";
		}

		if ( -1 === url.hostname.indexOf( "youtube.com" ) ) {
			return "";
		}

		if ( 0 === url.pathname.indexOf( "/embed/" ) ) {
			pathParts = url.pathname.split( "/" );

			return pathParts[ 2 ] || "";
		}

		if ( 0 === url.pathname.indexOf( "/shorts/" ) ) {
			pathParts = url.pathname.split( "/" );

			return pathParts[ 2 ] || "";
		}

		return url.searchParams ? url.searchParams.get( "v" ) || "" : "";
	}

	function getVimeoId( url ) {
		var pathParts;

		if ( -1 === url.hostname.indexOf( "vimeo.com" ) ) {
			return "";
		}

		pathParts = url.pathname.split( "/" ).filter(
			function ( item ) {
				return "" !== item;
			}
		);

		if ( "video" === pathParts[ 0 ] ) {
			return pathParts[ 1 ] || "";
		}

		return pathParts[ 0 ] || "";
	}

	function getVideoEmbedUrl( source ) {
		var url;
		var youtubeId;
		var vimeoId;

		try {
			url = new URL( source, window.location.href );
		} catch ( error ) {
			return "";
		}

		youtubeId = getYoutubeId( url );

		if ( youtubeId ) {
			return "https://www.youtube.com/embed/" + encodeURIComponent( youtubeId ) + "?autoplay=1&rel=0";
		}

		vimeoId = getVimeoId( url );

		if ( vimeoId ) {
			return "https://player.vimeo.com/video/" + encodeURIComponent( vimeoId ) + "?autoplay=1";
		}

		return "";
	}

	function renderLightboxItem( box, item ) {
		var image;
		var video;
		var frame;
		var embedSource;
		var fallbackLink;

		box.content.innerHTML = "";

		if ( "video" === item.type ) {
			embedSource = getVideoEmbedUrl( item.source );

			if ( ! embedSource ) {
				fallbackLink             = document.createElement( "a" );
				fallbackLink.className   = "ap-media-carousel-lightbox__fallback-link";
				fallbackLink.href        = item.source;
				fallbackLink.target      = "_blank";
				fallbackLink.rel         = "noopener";
				fallbackLink.textContent = box.labels.fallback;
				box.content.appendChild( fallbackLink );

				return;
			}

			video           = document.createElement( "div" );
			frame           = document.createElement( "iframe" );
			video.className = "ap-media-carousel-lightbox__video";
			frame.src       = embedSource;

			frame.setAttribute( "allow", "autoplay; fullscreen; picture-in-picture" );
			frame.setAttribute( "title", box.labels.video );

			video.appendChild( frame );
			box.content.appendChild( video );

			return;
		}

		image           = document.createElement( "img" );
		image.className = "ap-media-carousel-lightbox__image";
		image.src       = item.source;
		image.alt       = "";

		box.content.appendChild( image );
	}

	function updateLightboxControls( box ) {
		var hasMultipleItems = box.activeState && 1 < box.activeState.lightboxItems.length;

		box.previousButton.hidden = ! hasMultipleItems;
		box.nextButton.hidden     = ! hasMultipleItems;

		updateLightboxHeader( box );
	}

	function getActiveLightboxItem( box ) {
		if ( ! box || ! box.activeState ) {
			return null;
		}

		return box.activeState.lightboxItems[ box.activeIndex ] || null;
	}

	function setLightboxButtonIcon( button, iconClass ) {
		if ( button.firstElementChild ) {
			button.firstElementChild.className = iconClass;
		}
	}

	function syncLightboxZoomButton( box, isZoomed ) {
		box.zoomButton.setAttribute( "aria-checked", isZoomed ? "true" : "false" );
		box.zoomButton.setAttribute( "aria-label", isZoomed ? box.labels.zoomOut : box.labels.zoom );
		setLightboxButtonIcon( box.zoomButton, isZoomed ? "eicon-zoom-out-bold" : "eicon-zoom-in-bold" );
	}

	function resetLightboxZoom( box ) {
		box.root.classList.remove( LIGHTBOX_ZOOM_CLASS );
		syncLightboxZoomButton( box, false );
	}

	function updateLightboxHeader( box ) {
		var item       = getActiveLightboxItem( box );
		var itemCount  = box.activeState ? box.activeState.lightboxItems.length : 0;
		var itemNumber = itemCount ? box.activeIndex + 1 : 0;
		var isImage    = item && "image" === item.type;

		box.counter.textContent  = itemCount ? itemNumber + " / " + itemCount : "";
		box.shareButton.disabled = ! item;
		box.zoomButton.disabled  = ! isImage;

		if ( ! isImage ) {
			resetLightboxZoom( box );
		}

		syncLightboxFullscreenButton();
	}

	function shareLightboxItem() {
		var box  = lightbox;
		var item = getActiveLightboxItem( box );
		var opened;

		if ( ! item || ! item.source ) {
			return;
		}

		if ( window.navigator && window.navigator.share ) {
			window.navigator.share( { url: item.source } ).catch(
				function () {}
			);

			return;
		}

		opened = window.open( item.source, "_blank", "noopener" );

		if ( opened ) {
			opened.opener = null;
		}
	}

	function toggleLightboxZoom() {
		var box  = lightbox;
		var item = getActiveLightboxItem( box );
		var zoomed;

		if ( ! box || ! item || "image" !== item.type ) {
			return;
		}

		zoomed = ! box.root.classList.contains( LIGHTBOX_ZOOM_CLASS );
		box.root.classList.toggle( LIGHTBOX_ZOOM_CLASS, zoomed );
		syncLightboxZoomButton( box, zoomed );
	}

	function toggleLightboxFullscreen() {
		var box = lightbox;

		if ( ! box || ! document.fullscreenEnabled ) {
			return;
		}

		if ( document.fullscreenElement === box.root ) {
			document.exitFullscreen().catch(
				function () {}
			);

			return;
		}

		box.root.requestFullscreen().catch(
			function () {}
		);
	}

	function syncLightboxFullscreenButton() {
		var box          = lightbox;
		var isFullscreen = box && document.fullscreenElement === box.root;

		if ( ! box ) {
			return;
		}

		box.fullscreenButton.disabled = ! document.fullscreenEnabled;
		box.fullscreenButton.setAttribute( "aria-checked", isFullscreen ? "true" : "false" );
		box.fullscreenButton.setAttribute( "aria-label", isFullscreen ? box.labels.exitFullscreen : box.labels.fullscreen );
		setLightboxButtonIcon( box.fullscreenButton, isFullscreen ? "eicon-frame-minimize" : "eicon-frame-expand" );
	}

	function getLightboxFocusableElements( box ) {
		return toArray( box.root.querySelectorAll( "a[href], button:not([disabled]), [tabindex]:not([tabindex='-1'])" ) ).filter(
			function ( element ) {
				return ! element.hidden && 0 < element.getClientRects().length;
			}
		);
	}

	function trapLightboxFocus( box, event ) {
		var focusableElements = getLightboxFocusableElements( box );
		var firstElement      = focusableElements[ 0 ];
		var lastElement       = focusableElements[ focusableElements.length - 1 ];

		if ( ! focusableElements.length ) {
			event.preventDefault();
			box.root.focus();

			return;
		}

		if ( -1 === focusableElements.indexOf( document.activeElement ) ) {
			event.preventDefault();
			firstElement.focus();

			return;
		}

		if ( event.shiftKey && document.activeElement === firstElement ) {
			event.preventDefault();
			lastElement.focus();

			return;
		}

		if ( ! event.shiftKey && document.activeElement === lastElement ) {
			event.preventDefault();
			firstElement.focus();
		}
	}

	function openLightbox( state, index, trigger ) {
		var box  = createLightbox();
		var item = state.lightboxItems[ index ];

		if ( ! item ) {
			return;
		}

		box.activeIndex   = index;
		box.activeState   = state;
		box.activeTrigger = trigger;

		applyLightboxLabels( box, state.lightboxLabels );
		applyLightboxStyles( box, state.carousel );
		resetLightboxZoom( box );
		renderLightboxItem( box, item );
		updateLightboxControls( box );
		stopAutoplay( state );

		box.root.hidden = false;
		document.body.classList.add( BODY_LIGHTBOX_CLASS );

		window.requestAnimationFrame(
			function () {
				box.root.classList.add( LIGHTBOX_OPEN_CLASS );
				box.closeButton.focus();
			}
		);
	}

	function closeLightbox() {
		var box = lightbox;
		var trigger;
		var state;

		if ( ! box || box.root.hidden ) {
			return;
		}

		trigger = box.activeTrigger;
		state   = box.activeState;

		box.root.classList.remove( LIGHTBOX_OPEN_CLASS );
		resetLightboxZoom( box );
		document.body.classList.remove( BODY_LIGHTBOX_CLASS );
		box.activeState   = null;
		box.activeTrigger = null;

		if ( document.fullscreenElement === box.root && document.exitFullscreen ) {
			document.exitFullscreen().catch(
				function () {}
			);
		}

		window.setTimeout(
			function () {
				if ( ! box.root.classList.contains( LIGHTBOX_OPEN_CLASS ) ) {
					box.content.innerHTML = "";
					box.root.hidden       = true;
				}
			},
			200
		);

		if ( state ) {
			startAutoplay( state );
		}

		if ( trigger && trigger.focus ) {
			trigger.focus();
		}
	}

	function moveLightbox( direction ) {
		var box = lightbox;
		var items;
		var maxIndex;

		if ( ! box || ! box.activeState ) {
			return;
		}

		items    = box.activeState.lightboxItems;
		maxIndex = items.length - 1;

		if ( 0 >= maxIndex ) {
			return;
		}

		box.activeIndex += direction;

		if ( 0 > box.activeIndex ) {
			box.activeIndex = maxIndex;
		} else if ( box.activeIndex > maxIndex ) {
			box.activeIndex = 0;
		}

		resetLightboxZoom( box );
		renderLightboxItem( box, items[ box.activeIndex ] );
		updateLightboxControls( box );
	}

	function handleLightboxKeydown( event ) {
		if ( ! lightbox || lightbox.root.hidden ) {
			return;
		}

		if ( "Escape" === event.key ) {
			event.preventDefault();
			closeLightbox();
		}

		if ( "Tab" === event.key ) {
			trapLightboxFocus( lightbox, event );
		}

		if ( "ArrowLeft" === event.key ) {
			event.preventDefault();
			moveLightbox( -1 );
		}

		if ( "ArrowRight" === event.key ) {
			event.preventDefault();
			moveLightbox( 1 );
		}
	}

	function bindLightboxTriggers( state ) {
		state.carousel.addEventListener(
			"click",
			function ( event ) {
				var trigger = event.target.closest ? event.target.closest( LIGHTBOX_TRIGGER ) : null;
				var slide;
				var lightboxIndex;

				if ( ! trigger || ! state.carousel.contains( trigger ) ) {
					return;
				}

				slide         = trigger.closest ? trigger.closest( ".ap-media-carousel__slide" ) : null;
				lightboxIndex = getLightboxIndexBySlide( state, slide );

				if ( 0 > lightboxIndex ) {
					return;
				}

				event.preventDefault();
				event.stopPropagation();
				openLightbox( state, lightboxIndex, trigger );
			}
		);
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

	function getMaxStartIndex( state ) {
		var maxOffset     = getMaxOffset( state );
		var maxStartIndex = 0;

		state.slides.forEach(
			function ( slide, index ) {
				if ( slide.offsetLeft <= maxOffset + 1 ) {
					maxStartIndex = index;
				}
			}
		);

		return maxStartIndex;
	}

	function getPages( state ) {
		var maxStartIndex = getMaxStartIndex( state );
		var pages         = [];
		var index         = 0;
		var lastPage;

		if ( 0 >= maxStartIndex ) {
			pages.push(
				{
					offset: 0,
					slideIndex: 0
				}
			);

			return pages;
		}

		while ( index < maxStartIndex ) {
			pages.push(
				{
					offset: getSlideOffset( state, index ),
					slideIndex: index
				}
			);

			index += state.slidesToScroll;
		}

		lastPage = pages[ pages.length - 1 ];

		if ( ! lastPage || lastPage.slideIndex !== maxStartIndex ) {
			pages.push(
				{
					offset: getSlideOffset( state, maxStartIndex ),
					slideIndex: maxStartIndex
				}
			);
		}

		return pages;
	}

	function createDot( state, page, pageIndex ) {
		var dot = document.createElement( "button" );

		dot.className = "ap-media-carousel__dot";
		dot.type      = "button";

		dot.setAttribute( "role", "tab" );
		dot.setAttribute( "aria-label", getDotLabel( state, page ) );
		dot.setAttribute( "aria-current", "false" );
		dot.setAttribute( "aria-selected", "false" );
		dot.setAttribute( "data-ap-media-carousel-page", String( pageIndex ) );

		dot.addEventListener(
			"click",
			function () {
				goToPageAfterInteraction( state, pageIndex );
			}
		);

		return dot;
	}

	function renderDots( state ) {
		if ( state.pagination ) {
			state.pagination.innerHTML = "";
		}

		if ( ! state.pagination || "dots" !== state.paginationType ) {
			state.dots = [];

			return;
		}

		state.dots = [];

		state.pages.forEach(
			function ( page, pageIndex ) {
				var dot = createDot( state, page, pageIndex );

				state.pagination.appendChild( dot );
				state.dots.push( dot );
			}
		);
	}

	function syncControlVisibility( state ) {
		var hasMultiplePages = 1 < state.pages.length;

		if ( state.pagination ) {
			state.pagination.hidden = ! hasMultiplePages || "dots" !== state.paginationType;
		}

		if ( state.previousButton ) {
			state.previousButton.hidden = ! hasMultiplePages || ! state.showArrows;
		}

		if ( state.nextButton ) {
			state.nextButton.hidden = ! hasMultiplePages || ! state.showArrows;
		}
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
				var isActive = index === state.currentPageIndex;

				dot.classList.toggle( "ap-media-carousel__dot--active", isActive );
				dot.setAttribute( "aria-current", isActive ? "true" : "false" );
				dot.setAttribute( "aria-selected", isActive ? "true" : "false" );
			}
		);

		if ( state.previousButton ) {
			state.previousButton.disabled = ! state.loop && 0 >= state.currentPageIndex;
		}

		if ( state.nextButton ) {
			state.nextButton.disabled = ! state.loop && state.currentPageIndex >= state.pages.length - 1;
		}

		updateSlideVisibility( state, offset );
	}

	function goToPage( state, pageIndex ) {
		var maxPageIndex = Math.max( state.pages.length - 1, 0 );
		var targetPageIndex;
		var page;

		if ( state.loop && 0 < state.pages.length ) {
			if ( 0 > pageIndex ) {
				targetPageIndex = maxPageIndex;
			} else if ( pageIndex > maxPageIndex ) {
				targetPageIndex = 0;
			} else {
				targetPageIndex = pageIndex;
			}
		} else {
			targetPageIndex = clamp( pageIndex, 0, maxPageIndex );
		}

		state.currentPageIndex  = targetPageIndex;
		page                    = state.pages[ state.currentPageIndex ] || state.pages[ 0 ];
		state.currentSlideIndex = page ? page.slideIndex : 0;

		state.track.style.transform = "translate3d(" + ( -1 * ( page ? page.offset : 0 ) ) + "px, 0, 0)";

		updateControls( state, page ? page.offset : 0 );
	}

	function stopAutoplay( state ) {
		if ( ! state.autoplayTimer ) {
			return;
		}

		window.clearInterval( state.autoplayTimer );
		state.autoplayTimer = null;
	}

	function startAutoplay( state ) {
		stopAutoplay( state );

		if ( ! state.autoplay || 1 >= state.pages.length ) {
			return;
		}

		state.autoplayTimer = window.setInterval(
			function () {
				if ( ! state.loop && state.currentPageIndex >= state.pages.length - 1 ) {
					stopAutoplay( state );

					return;
				}

				goToPage( state, state.currentPageIndex + 1 );
			},
			state.autoplayDelay
		);
	}

	function restartAutoplay( state ) {
		stopAutoplay( state );
		startAutoplay( state );
	}

	function goToPageAfterInteraction( state, pageIndex ) {
		goToPage( state, pageIndex );
		restartAutoplay( state );
	}

	function getPageIndexForSlideIndex( state, slideIndex ) {
		var matchedIndex = 0;

		state.pages.forEach(
			function ( page, pageIndex ) {
				if ( page.slideIndex <= slideIndex ) {
					matchedIndex = pageIndex;
				}
			}
		);

		return matchedIndex;
	}

	function refreshPages( state ) {
		var currentSlideIndex = state.currentSlideIndex || 0;

		state.pages            = getPages( state );
		state.currentPageIndex = getPageIndexForSlideIndex( state, currentSlideIndex );

		renderDots( state );
		syncControlVisibility( state );
		goToPage( state, state.currentPageIndex );
		restartAutoplay( state );
	}

	function scheduleRefresh( state ) {
		if ( state.refreshFrame ) {
			window.cancelAnimationFrame( state.refreshFrame );
		}

		state.refreshFrame = window.requestAnimationFrame(
			function () {
				refreshPages( state );
			}
		);
	}

	function initCarousel( carousel ) {
		var options      = parseOptions( carousel );
		var reduceMotion = prefersReducedMotion();
		var state;

		if ( "yes" === carousel.getAttribute( INITIALIZED_ATTRIBUTE ) ) {
			return;
		}

		state = {
			autoplay: ! reduceMotion && getBooleanOption( options, "autoplay", false ),
			autoplayDelay: 5000,
			autoplayTimer: null,
			carousel: carousel,
			currentPageIndex: 0,
			currentSlideIndex: 0,
			dotLabel: "",
			dots: [],
			lightboxItems: [],
			lightboxLabels: getLightboxLabels( carousel ),
			loop: getBooleanOption( options, "loop", false ),
			nextButton: carousel.querySelector( ".ap-media-carousel__arrow--next" ),
			pages: [],
			pagination: carousel.querySelector( ".ap-media-carousel__pagination" ),
			paginationType: getPaginationType( options ),
			previousButton: carousel.querySelector( ".ap-media-carousel__arrow--prev" ),
			refreshFrame: null,
			showArrows: getBooleanOption( options, "arrows", true ),
			slides: toArray( carousel.querySelectorAll( ".ap-media-carousel__slide" ) ),
			slidesToScroll: getSlidesToScroll( options ),
			track: carousel.querySelector( ".ap-media-carousel__slides" ),
			transitionDuration: reduceMotion ? 0 : getTransitionDuration( options ),
			viewport: carousel.querySelector( ".ap-media-carousel__viewport" )
		};

		if ( ! state.track || ! state.viewport || ! state.slides.length ) {
			return;
		}

		state.dotLabel                       = state.pagination ? state.pagination.getAttribute( "data-ap-media-carousel-dot-label" ) || "Go to slide" : "Go to slide";
		state.lightboxItems                  = getLightboxItems( state );
		state.track.style.transitionDuration = state.transitionDuration + "ms";

		carousel.setAttribute( INITIALIZED_ATTRIBUTE, "yes" );
		carousel.apMediaCarousel = state;

		if ( state.previousButton ) {
			state.previousButton.addEventListener(
				"click",
				function () {
					goToPageAfterInteraction( state, state.currentPageIndex - 1 );
				}
			);
		}

		if ( state.nextButton ) {
			state.nextButton.addEventListener(
				"click",
				function () {
					goToPageAfterInteraction( state, state.currentPageIndex + 1 );
				}
			);
		}

		bindLightboxTriggers( state );

		window.addEventListener(
			"resize",
			function () {
				scheduleRefresh( state );
			}
		);

		refreshPages( state );
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
