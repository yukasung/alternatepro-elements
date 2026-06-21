/**
 * AlternatePro Nav Menu frontend behavior.
 *
 * @package AlternatePro\Elements
 */

(function () {
	"use strict";

	var NAV_SELECTOR = "[data-ap-nav-menu]";
	var READY_CLASS = "ap-nav-toggle-ready";
	var COLLAPSIBLE_CLASS = "ap-nav-is-collapsible";
	var OPEN_CLASS = "ap-nav-is-open";
	var SUBMENU_OPEN_CLASS = "ap-submenu-is-open";

	function toArray( collection ) {
		return Array.prototype.slice.call( collection );
	}

	function getDirectChild( element, matches ) {
		var children = element ? element.children : [];
		var index;

		for ( index = 0; index < children.length; index += 1 ) {
			if ( matches( children[ index ] ) ) {
				return children[ index ];
			}
		}

		return null;
	}

	function getDirectChildByClass( element, className ) {
		return getDirectChild( element, function ( child ) {
			return child.classList.contains( className );
		} );
	}

	function getDirectLink( element ) {
		return getDirectChild( element, function ( child ) {
			return "A" === child.tagName;
		} );
	}

	function shouldIgnoreMenuClick( event ) {
		return event.defaultPrevented || event.button || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey;
	}

	function initNavMenu( nav ) {
		var button;
		var menu;
		var menuId;
		var open = false;
		var ticking = false;
		var disclosureAnimations = new WeakMap();
		var transitionEndHandlers = new WeakMap();
		var transitionFrames = new WeakMap();
		var transitionTimers = new WeakMap();

		if ( ! nav || "yes" === nav.getAttribute( "data-ap-nav-initialized" ) ) {
			return;
		}

		nav.setAttribute( "data-ap-nav-initialized", "yes" );

		button = nav.querySelector( ".ap-navbar-toggle" );
		menuId = button ? button.getAttribute( "aria-controls" ) : "";
		menu = menuId ? document.getElementById( menuId ) : nav.querySelector( ".ap-nav" );

		if ( ! menu ) {
			return;
		}

		function getLayout() {
			return nav.getAttribute( "data-ap-nav-layout" ) || "horizontal";
		}

		function getToggleButton() {
			return nav.getAttribute( "data-ap-nav-toggle-button" ) || "hamburger";
		}

		function getBreakpoint() {
			var value = parseInt( nav.getAttribute( "data-ap-nav-breakpoint" ), 10 );

			return isNaN( value ) ? 0 : value;
		}

		function isCollapsibleMode() {
			var breakpoint;

			if ( ! button || "hamburger" !== getToggleButton() ) {
				return false;
			}

			if ( "dropdown" === getLayout() ) {
				return true;
			}

			breakpoint = getBreakpoint();

			return 0 < breakpoint && window.innerWidth <= breakpoint;
		}

		function getParentItems() {
			return toArray( nav.querySelectorAll( ".menu-item-has-children" ) );
		}

		function parseTransitionTime( value ) {
			var numericValue = parseFloat( value );

			if ( isNaN( numericValue ) ) {
				return 0;
			}

			return -1 !== value.indexOf( "ms" ) ? numericValue : numericValue * 1000;
		}

		function getTransitionDuration( element ) {
			var computedStyle = window.getComputedStyle( element );
			var delays = computedStyle.transitionDelay.split( "," );
			var durations = computedStyle.transitionDuration.split( "," );
			var index;
			var maxDuration = 0;
			var total;

			for ( index = 0; index < durations.length; index += 1 ) {
				total = parseTransitionTime( durations[ index ] ) + parseTransitionTime( delays[ index ] || delays[ 0 ] || "0s" );
				maxDuration = Math.max( maxDuration, total );
			}

			return maxDuration;
		}

		function getTransitionEasing( element ) {
			var computedStyle = window.getComputedStyle( element );
			var easing = computedStyle.transitionTimingFunction.match( /cubic-bezier\([^)]+\)|steps\([^)]+\)|ease-in-out|ease-in|ease-out|linear|ease/ );

			return easing ? easing[ 0 ] : "ease";
		}

		function shouldReduceMotion() {
			return window.matchMedia && window.matchMedia( "(prefers-reduced-motion: reduce)" ).matches;
		}

		function clearDisclosureTransition( element ) {
			var animation = disclosureAnimations.get( element );
			var transitionEnd = transitionEndHandlers.get( element );
			var transitionFrame = transitionFrames.get( element );
			var transitionTimer = transitionTimers.get( element );

			if ( animation ) {
				animation.cancel();
				disclosureAnimations.delete( element );
			}

			if ( transitionFrame ) {
				window.cancelAnimationFrame( transitionFrame );
				transitionFrames.delete( element );
			}

			if ( transitionTimer ) {
				window.clearTimeout( transitionTimer );
				transitionTimers.delete( element );
			}

			if ( transitionEnd ) {
				element.removeEventListener( "transitionend", transitionEnd );
				transitionEndHandlers.delete( element );
			}
		}

		function finishDisclosureState( element, isOpen, afterFinish ) {
			clearDisclosureTransition( element );

			if ( isOpen ) {
				element.hidden = false;
				element.style.maxHeight = "";
				element.style.opacity = "";
				element.style.overflow = "";
				element.style.scale = "";
				element.style.transformOrigin = "";

				if ( "function" === typeof afterFinish ) {
					afterFinish();
				}

				return;
			}

			element.hidden = true;
			element.style.maxHeight = "";
			element.style.opacity = "";
			element.style.overflow = "";
			element.style.scale = "";
			element.style.transformOrigin = "";

			if ( "function" === typeof afterFinish ) {
				afterFinish();
			}
		}

		function waitForDisclosureTransition( element, isOpen, afterFinish ) {
			var transitionDuration = getTransitionDuration( element );
			var transitionEnd;
			var transitionTimer;

			transitionEnd = function ( event ) {
				if ( event && event.target !== element ) {
					return;
				}

				if ( event && "scale" !== event.propertyName ) {
					return;
				}

				finishDisclosureState( element, isOpen, afterFinish );
			};

			element.addEventListener( "transitionend", transitionEnd );
			transitionEndHandlers.set( element, transitionEnd );

			transitionTimer = window.setTimeout( function () {
				finishDisclosureState( element, isOpen, afterFinish );
			}, transitionDuration + 50 );
			transitionTimers.set( element, transitionTimer );
		}

		function getFullDisclosureHeight( element ) {
			var previousMaxHeight = element.style.maxHeight;
			var previousScale = element.style.scale;
			var height;

			element.hidden = false;
			element.style.maxHeight = "none";
			element.style.scale = "1";
			height = element.scrollHeight;
			element.style.maxHeight = previousMaxHeight;
			element.style.scale = previousScale;

			return height;
		}

		function runAfterDisclosurePaint( element, callback ) {
			var frame;

			frame = window.requestAnimationFrame( function () {
				transitionFrames.delete( element );

				frame = window.requestAnimationFrame( function () {
					transitionFrames.delete( element );
					callback();
				} );
				transitionFrames.set( element, frame );
			} );
			transitionFrames.set( element, frame );
		}

		function animateDisclosureWithKeyframes( element, isOpen, afterFinish ) {
			var animation;
			var duration;

			if ( ! element.animate ) {
				return false;
			}

			duration = getTransitionDuration( element );

			if ( 0 >= duration ) {
				return false;
			}

			animation = element.animate(
				isOpen ?
					[
						{ opacity: 0, scale: "1 0" },
						{ opacity: 1, scale: "1 1" },
					] :
					[
						{ opacity: 1, scale: "1 1" },
						{ opacity: 0, scale: "1 0" },
					],
				{
					duration: duration,
					easing: getTransitionEasing( element ),
					fill: "both",
				}
			);

			disclosureAnimations.set( element, animation );
			animation.onfinish = function () {
				disclosureAnimations.delete( element );
				finishDisclosureState( element, isOpen, afterFinish );
			};

			return true;
		}

		function animateDisclosure( element, isOpen, animate, afterFinish ) {
			var endHeight;
			var startHeight;

			clearDisclosureTransition( element );

			if ( ! animate || shouldReduceMotion() ) {
				finishDisclosureState( element, isOpen, afterFinish );
				return;
			}

			if ( isOpen ) {
				endHeight = getFullDisclosureHeight( element );
				element.hidden = false;
				element.style.overflow = "hidden";
				element.style.maxHeight = endHeight + "px";
				element.style.opacity = "0";
				element.style.scale = "1 0";
				element.style.transformOrigin = "top";

				if ( animateDisclosureWithKeyframes( element, true, afterFinish ) ) {
					return;
				}

				runAfterDisclosurePaint( element, function () {
					element.style.opacity = "1";
					element.style.scale = "1 1";

					waitForDisclosureTransition( element, true, afterFinish );
				} );
				return;
			}

			if ( element.hidden ) {
				finishDisclosureState( element, false, afterFinish );
				return;
			}

			startHeight = getFullDisclosureHeight( element ) || element.getBoundingClientRect().height || element.scrollHeight;
			element.hidden = false;
			element.style.overflow = "hidden";
			element.style.maxHeight = startHeight + "px";
			element.style.opacity = "1";
			element.style.scale = "1 1";
			element.style.transformOrigin = "top";

			if ( animateDisclosureWithKeyframes( element, false, afterFinish ) ) {
				return;
			}

			runAfterDisclosurePaint( element, function () {
				element.style.opacity = "0";
				element.style.scale = "1 0";

				waitForDisclosureTransition( element, false, afterFinish );
			} );
		}

		function resetChildSubmenusForCollapse( item ) {
			toArray( item.querySelectorAll( ".menu-item-has-children" ) ).forEach( function ( child ) {
				if ( child === item ) {
					return;
				}

				setSubmenuState( child, false, false );
			} );
		}

		function resetSubmenusForDesktop() {
			getParentItems().forEach( function ( item ) {
				var link = getDirectLink( item );
				var submenu = getDirectChildByClass( item, "sub-menu" );

				item.classList.remove( SUBMENU_OPEN_CLASS );

				if ( link ) {
					link.removeAttribute( "aria-expanded" );
				}

				if ( submenu ) {
					finishDisclosureState( submenu, true );
				}
			} );
		}

		function resetSubmenusForCollapse() {
			getParentItems().forEach( function ( item ) {
				setSubmenuState( item, false, false );
			} );
		}

		function setSubmenuState( item, expanded, animate ) {
			var link = getDirectLink( item );
			var submenu = getDirectChildByClass( item, "sub-menu" );
			var afterFinish = null;

			if ( ! link || ! submenu ) {
				return;
			}

			item.classList.toggle( SUBMENU_OPEN_CLASS, expanded );
			link.setAttribute( "aria-expanded", expanded ? "true" : "false" );

			if ( ! expanded ) {
				afterFinish = function () {
					resetChildSubmenusForCollapse( item );
				};
			}

			animateDisclosure( submenu, expanded, Boolean( animate ), afterFinish );
		}

		function onSubmenuLinkClick( event ) {
			var item;

			if ( ! nav.classList.contains( COLLAPSIBLE_CLASS ) || shouldIgnoreMenuClick( event ) ) {
				return;
			}

			item = event.currentTarget.parentNode;

			if ( ! item || ! getDirectChildByClass( item, "sub-menu" ) ) {
				return;
			}

			event.preventDefault();
			setSubmenuState( item, ! item.classList.contains( SUBMENU_OPEN_CLASS ), true );
		}

		function prepareSubmenusForCollapse() {
			getParentItems().forEach( function ( item, index ) {
				var link = getDirectLink( item );
				var submenu = getDirectChildByClass( item, "sub-menu" );

				if ( ! link || ! submenu ) {
					return;
				}

				if ( ! submenu.id ) {
					submenu.id = nav.id + "-submenu-" + index;
				}

				link.setAttribute( "aria-haspopup", "true" );
				link.setAttribute( "aria-controls", submenu.id );

				if ( "yes" !== link.getAttribute( "data-ap-submenu-bound" ) ) {
					link.setAttribute( "data-ap-submenu-bound", "yes" );
					link.addEventListener( "click", onSubmenuLinkClick );
				}

				setSubmenuState( item, item.classList.contains( SUBMENU_OPEN_CLASS ), false );
			} );
		}

		function setMenuOpen( isOpen, animate ) {
			open = Boolean( isOpen );
			nav.classList.toggle( OPEN_CLASS, open );

			if ( button ) {
				button.setAttribute( "aria-expanded", open ? "true" : "false" );
			}

			if ( ! nav.classList.contains( COLLAPSIBLE_CLASS ) ) {
				finishDisclosureState( menu, true );
				return;
			}

			animateDisclosure( menu, open, Boolean( animate ), open ? null : resetSubmenusForCollapse );
		}

		function syncMode() {
			var collapsible = isCollapsibleMode();
			var wasCollapsible = nav.classList.contains( COLLAPSIBLE_CLASS );

			nav.classList.add( READY_CLASS );
			nav.classList.toggle( COLLAPSIBLE_CLASS, collapsible );

			if ( ! collapsible ) {
				open = false;
				nav.classList.remove( OPEN_CLASS );
				finishDisclosureState( menu, true );
				resetSubmenusForDesktop();

				if ( button ) {
					button.setAttribute( "aria-expanded", "true" );
				}

				return;
			}

			prepareSubmenusForCollapse();

			if ( ! wasCollapsible ) {
				open = false;
			}

			setMenuOpen( open, false );
		}

		function requestSync() {
			if ( ticking ) {
				return;
			}

			ticking = true;

			window.requestAnimationFrame( function () {
				ticking = false;
				syncMode();
			} );
		}

		if ( button ) {
			button.addEventListener( "click", function () {
				if ( ! nav.classList.contains( COLLAPSIBLE_CLASS ) ) {
					return;
				}

				setMenuOpen( ! open, true );
			} );
		}

		nav.addEventListener( "keydown", function ( event ) {
			if ( "Escape" !== event.key || ! nav.classList.contains( COLLAPSIBLE_CLASS ) || ! open ) {
				return;
			}

			setMenuOpen( false, true );

			if ( button ) {
				button.focus();
			}
		} );

		document.addEventListener( "click", function ( event ) {
			if ( ! open || ! nav.classList.contains( COLLAPSIBLE_CLASS ) || nav.contains( event.target ) ) {
				return;
			}

			setMenuOpen( false, true );
		} );

		window.addEventListener( "resize", requestSync, { passive: true } );
		window.addEventListener( "orientationchange", requestSync, { passive: true } );

		syncMode();
	}

	function initWithin( root ) {
		var scope = root || document;

		if ( scope.matches && scope.matches( NAV_SELECTOR ) ) {
			initNavMenu( scope );
		}

		toArray( scope.querySelectorAll( NAV_SELECTOR ) ).forEach( initNavMenu );
	}

	function bindElementorHook() {
		if ( ! window.elementorFrontend || ! window.elementorFrontend.hooks ) {
			return;
		}

		window.elementorFrontend.hooks.addAction( "frontend/element_ready/alternatepro-nav-menu.default", function ( $scope ) {
			initWithin( $scope && $scope[ 0 ] ? $scope[ 0 ] : document );
		} );
	}

	if ( "loading" === document.readyState ) {
		document.addEventListener( "DOMContentLoaded", function () {
			initWithin( document );
		} );
	} else {
		initWithin( document );
	}

	if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
		bindElementorHook();
	} else {
		window.addEventListener( "elementor/frontend/init", bindElementorHook );
	}
})();
