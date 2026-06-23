( function () {
	'use strict';

	var apSectionSelector = '.elementor-control-section_ap_custom_css';
	var apControlSelector = '.elementor-control-ap_custom_css';
	var scheduled = false;

	function isControlVisible( control ) {
		return ! control.hidden && 'none' !== control.style.display;
	}

	function isSectionOpen( section ) {
		var ariaToggle = section.querySelector( '[aria-expanded]' );

		return section.classList.contains( 'e-open' ) ||
			section.classList.contains( 'elementor-open' ) ||
			section.classList.contains( 'elementor-active' ) ||
			'true' === section.getAttribute( 'aria-expanded' ) ||
			( ariaToggle && 'true' === ariaToggle.getAttribute( 'aria-expanded' ) );
	}

	function setControlVisibility( control, isVisible ) {
		var displayValue = isVisible ? '' : 'none';

		if ( control.hidden === isVisible ) {
			control.hidden = ! isVisible;
		}

		if ( control.style.display !== displayValue ) {
			control.style.display = displayValue;
		}
	}

	function syncApCustomCssControl( section, control, shouldPreserveVisibleState ) {
		var isVisible = isSectionOpen( section );

		if ( shouldPreserveVisibleState && isControlVisible( control ) ) {
			isVisible = true;
		}

		setControlVisibility( control, isVisible );
	}

	function bindApCustomCssToggle( section ) {
		if ( '1' === section.getAttribute( 'data-ap-custom-css-bound' ) ) {
			return;
		}

		section.setAttribute( 'data-ap-custom-css-bound', '1' );

		section.addEventListener( 'click', function () {
			window.setTimeout( scheduleReorder, 0 );
			window.setTimeout( scheduleReorder, 50 );
			window.setTimeout( scheduleReorder, 150 );
		} );
	}

	function reorderApCustomCss() {
		var panel = document.querySelector( '#elementor-panel' ) || document;
		var apSection = panel.querySelector( apSectionSelector );
		var apControl = panel.querySelector( apControlSelector );

		if ( ! apSection || ! apSection.parentElement ) {
			return;
		}

		var parent = apSection.parentElement;
		var shouldPreserveVisibleState = '1' !== apSection.getAttribute( 'data-ap-custom-css-reordered' );

		if ( ! apControl ) {
			if ( parent.lastElementChild !== apSection ) {
				parent.appendChild( apSection );
			}

			apSection.setAttribute( 'data-ap-custom-css-reordered', '1' );
			bindApCustomCssToggle( apSection );
			return;
		}

		if ( parent.lastElementChild !== apControl || apControl.previousElementSibling !== apSection ) {
			parent.appendChild( apSection );
			parent.appendChild( apControl );
		}

		apSection.setAttribute( 'data-ap-custom-css-reordered', '1' );
		bindApCustomCssToggle( apSection );
		syncApCustomCssControl( apSection, apControl, shouldPreserveVisibleState );
	}

	function scheduleReorder() {
		if ( scheduled ) {
			return;
		}

		scheduled = true;

		window.requestAnimationFrame( function () {
			scheduled = false;
			reorderApCustomCss();
		} );
	}

	function observePanel() {
		var panel = document.querySelector( '#elementor-panel' ) || document.body;

		if ( ! panel || ! window.MutationObserver ) {
			return;
		}

		var observer = new MutationObserver( scheduleReorder );

		observer.observe( panel, {
			attributes: true,
			attributeFilter: [ 'aria-expanded', 'class', 'hidden', 'style' ],
			childList: true,
			subtree: true,
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', function () {
			observePanel();
			scheduleReorder();
		} );
	} else {
		observePanel();
		scheduleReorder();
	}

	window.addEventListener( 'load', scheduleReorder );
}() );
