/**
 * Header/Footer frontend readiness marker.
 *
 * @package AlternatePro\Elements
 */

(function () {
	"use strict";

	function init() {
		document.documentElement.classList.add( "apro-hfb-ready" );
		document.dispatchEvent( new CustomEvent( "apro:header-footer:ready" ) );
	}

	if (document.readyState === "loading") {
		document.addEventListener( "DOMContentLoaded", init );
	} else {
		init();
	}
})();
