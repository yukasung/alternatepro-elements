(function () {
	"use strict";

	var settings = window.aproHeaderFooterRules || {};
	var valueRuleTypes = Array.isArray(settings.valueRuleTypes) ? settings.valueRuleTypes : [];

	function isValueRule(type) {
		return valueRuleTypes.indexOf(type) !== -1;
	}

	function updateLocationRule(rule) {
		var select = rule.querySelector(".apro-hfb-rule-select");
		var valueWrap = rule.querySelector(".apro-hfb-rule-value-wrap");
		var valueField = rule.querySelector(".apro-hfb-rule-value");

		if (!select || !valueWrap || !valueField) {
			return;
		}

		if (isValueRule(select.value)) {
			valueWrap.classList.remove("apro-is-hidden");
		} else {
			valueWrap.classList.add("apro-is-hidden");
			valueField.value = "";
		}
	}

	function updateLocationRuleNames(builder) {
		var prefix = builder.getAttribute("data-name-prefix");
		var rules = builder.querySelectorAll(".apro-hfb-rule");

		if (!prefix) {
			return;
		}

		rules.forEach(function (rule, index) {
			var select = rule.querySelector(".apro-hfb-rule-select");
			var valueField = rule.querySelector(".apro-hfb-rule-value");

			rule.setAttribute("data-rule-index", String(index));

			if (select) {
				select.name = prefix + "[rule][" + index + "]";
			}

			if (valueField) {
				valueField.name = prefix + "[value][" + index + "]";
			}
		});
	}

	function updateLocationRemoveButtons(builder) {
		var rules = builder.querySelectorAll(".apro-hfb-rule");
		var ruleKind = builder.getAttribute("data-rule-kind");
		var showRemove = rules.length > 1 || ruleKind === "exclude";

		rules.forEach(function (rule) {
			var button = rule.querySelector(".apro-hfb-remove-rule");

			if (button) {
				button.classList.toggle("apro-is-hidden", !showRemove);
			}
		});
	}

	function resetLocationRule(rule) {
		var select = rule.querySelector(".apro-hfb-rule-select");
		var valueField = rule.querySelector(".apro-hfb-rule-value");

		if (select) {
			select.value = "";
		}

		if (valueField) {
			valueField.value = "";
		}

		updateLocationRule(rule);
	}

	function addLocationRule(builder) {
		var list = builder.querySelector(".apro-hfb-rule-list");
		var firstRule = builder.querySelector(".apro-hfb-rule");
		var rule = firstRule ? firstRule.cloneNode(true) : null;

		if (!list || !rule) {
			return;
		}

		resetLocationRule(rule);
		list.appendChild(rule);
		updateLocationRuleNames(builder);
		updateLocationRemoveButtons(builder);
	}

	function hideExclusionRowIfEmpty(builder) {
		if (builder.getAttribute("data-rule-kind") !== "exclude") {
			return;
		}

		var row = builder.closest(".apro-hfb-option-row--exclusions");
		var rules = builder.querySelectorAll(".apro-hfb-rule");
		var onlyRule = rules.length === 1 ? rules[0] : null;
		var select = onlyRule ? onlyRule.querySelector(".apro-hfb-rule-select") : null;
		var valueField = onlyRule ? onlyRule.querySelector(".apro-hfb-rule-value") : null;

		if (row && select && valueField && select.value === "" && valueField.value === "") {
			row.classList.add("apro-is-hidden");
		}
	}

	function initLocationBuilder(builder) {
		builder.querySelectorAll(".apro-hfb-rule").forEach(updateLocationRule);
		updateLocationRuleNames(builder);
		updateLocationRemoveButtons(builder);

		builder.addEventListener("change", function (event) {
			if (event.target && event.target.matches(".apro-hfb-rule-select")) {
				updateLocationRule(event.target.closest(".apro-hfb-rule"));
			}
		});

		builder.addEventListener("click", function (event) {
			var addButton = event.target.closest(".apro-hfb-add-rule");
			var removeButton = event.target.closest(".apro-hfb-remove-rule");

			if (addButton) {
				event.preventDefault();
				addLocationRule(builder);
				return;
			}

			if (removeButton) {
				event.preventDefault();

				var rule = removeButton.closest(".apro-hfb-rule");
				var rules = builder.querySelectorAll(".apro-hfb-rule");

				if (!rule) {
					return;
				}

				if (rules.length <= 1) {
					resetLocationRule(rule);
					hideExclusionRowIfEmpty(builder);
				} else {
					rule.remove();
				}

				updateLocationRuleNames(builder);
				updateLocationRemoveButtons(builder);
			}
		});
	}

	function revealExclusionRow() {
		var row = document.querySelector(".apro-hfb-option-row--exclusions");
		var select = row ? row.querySelector(".apro-hfb-rule-select") : null;

		if (!row) {
			return;
		}

		row.classList.remove("apro-is-hidden");

		if (select) {
			select.focus();
		}
	}

	function updateUserRoleRemoveButtons(builder) {
		var rules = builder.querySelectorAll(".apro-hfb-user-role-rule");
		var showRemove = rules.length > 1;

		rules.forEach(function (rule) {
			var button = rule.querySelector(".apro-hfb-remove-user-rule");

			if (button) {
				button.classList.toggle("apro-is-hidden", !showRemove);
			}
		});
	}

	function addUserRoleRule(builder) {
		var list = builder.querySelector(".apro-hfb-user-role-list");
		var firstRule = builder.querySelector(".apro-hfb-user-role-rule");
		var rule = firstRule ? firstRule.cloneNode(true) : null;
		var select = rule ? rule.querySelector(".apro-hfb-user-role-select") : null;

		if (!list || !rule) {
			return;
		}

		if (select) {
			select.value = "";
		}

		list.appendChild(rule);
		updateUserRoleRemoveButtons(builder);
	}

	function initUserRoleBuilder(builder) {
		updateUserRoleRemoveButtons(builder);

		builder.addEventListener("click", function (event) {
			var addButton = event.target.closest(".apro-hfb-add-user-rule");
			var removeButton = event.target.closest(".apro-hfb-remove-user-rule");

			if (addButton) {
				event.preventDefault();
				addUserRoleRule(builder);
				return;
			}

			if (removeButton) {
				event.preventDefault();

				if (builder.querySelectorAll(".apro-hfb-user-role-rule").length > 1) {
					removeButton.closest(".apro-hfb-user-role-rule").remove();
				}

				updateUserRoleRemoveButtons(builder);
			}
		});
	}

	function init() {
		document.querySelectorAll(".apro-hfb-rule-builder--locations").forEach(initLocationBuilder);
		document.querySelectorAll(".apro-hfb-user-role-builder").forEach(initUserRoleBuilder);

		document.addEventListener("click", function (event) {
			if (event.target.closest(".apro-hfb-show-exclusions")) {
				event.preventDefault();
				revealExclusionRow();
			}
		});
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", init);
	} else {
		init();
	}
})();
