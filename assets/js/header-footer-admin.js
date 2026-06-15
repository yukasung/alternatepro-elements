(function () {
	"use strict";

	function updateConditionState(condition) {
		var checkbox = condition.querySelector('input[type="checkbox"]');
		var textarea = condition.querySelector("textarea");

		if (!checkbox || !textarea) {
			return;
		}

		textarea.disabled = !checkbox.checked;
	}

	function init() {
		var conditions = document.querySelectorAll(".apro-hfb-condition");

		conditions.forEach(function (condition) {
			updateConditionState(condition);

			condition.addEventListener("change", function (event) {
				if (event.target && event.target.matches('input[type="checkbox"]')) {
					updateConditionState(condition);
				}
			});
		});
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", init);
	} else {
		init();
	}
})();
