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

	function getDirectChildByClass( element, className ) {
		var children = element ? element.children : [];
		var index;

		for ( index = 0; index < children.length; index += 1 ) {
			if ( children[ index ].classList.contains( className ) ) {
				return children[ index ];
			}
		}

		return null;
	}

	function getDirectLink( element ) {
		var children = element ? element.children : [];
		var index;

		for ( index = 0; index < children.length; index += 1 ) {
			if ( "A" === children[ index ].tagName ) {
				return children[ index ];
			}
		}

		return null;
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

		function setSubmenuState( item, expanded ) {
			var link = getDirectLink( item );
			var submenu = getDirectChildByClass( item, "sub-menu" );

			if ( ! link || ! submenu ) {
				return;
			}

			item.classList.toggle( SUBMENU_OPEN_CLASS, expanded );
			link.setAttribute( "aria-expanded", expanded ? "true" : "false" );
			submenu.hidden = ! expanded;
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
					submenu.hidden = false;
				}
			} );
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
			setSubmenuState( item, ! item.classList.contains( SUBMENU_OPEN_CLASS ) );
		}

		function prepareSubmenusForCollapse() {
			getParentItems().forEach( function ( item, index ) {
				var link = getDirectLink( item );
				var submenu = getDirectChildByClass( item, "sub-menu" );
				var expanded;

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

				expanded = item.classList.contains( SUBMENU_OPEN_CLASS ) || item.classList.contains( "current-menu-ancestor" );
				setSubmenuState( item, expanded );
			} );
		}

		function setMenuOpen( isOpen ) {
			open = Boolean( isOpen );
			nav.classList.toggle( OPEN_CLASS, open );

			if ( button ) {
				button.setAttribute( "aria-expanded", open ? "true" : "false" );
			}

			menu.hidden = nav.classList.contains( COLLAPSIBLE_CLASS ) && ! open;
		}

		function syncMode() {
			var collapsible = isCollapsibleMode();
			var wasCollapsible = nav.classList.contains( COLLAPSIBLE_CLASS );

			nav.classList.add( READY_CLASS );
			nav.classList.toggle( COLLAPSIBLE_CLASS, collapsible );

			if ( ! collapsible ) {
				open = false;
				nav.classList.remove( OPEN_CLASS );
				menu.hidden = false;
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

			setMenuOpen( open );
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

				setMenuOpen( ! open );
			} );
		}

		nav.addEventListener( "keydown", function ( event ) {
			if ( "Escape" !== event.key || ! nav.classList.contains( COLLAPSIBLE_CLASS ) || ! open ) {
				return;
			}

			setMenuOpen( false );

			if ( button ) {
				button.focus();
			}
		} );

		document.addEventListener( "click", function ( event ) {
			if ( ! open || ! nav.classList.contains( COLLAPSIBLE_CLASS ) || nav.contains( event.target ) ) {
				return;
			}

			setMenuOpen( false );
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
