(function () {
	"use strict";

	var settings = window.aproHeaderFooterRules || {};
	var valueRuleTypes = Array.isArray(settings.valueRuleTypes) ? settings.valueRuleTypes : [];
	var searchableRuleTypes = Array.isArray(settings.searchableRuleTypes) ? settings.searchableRuleTypes : [];
	var searchTimers = new WeakMap();
	var strings = settings.strings || {};

	function isValueRule(type) {
		return valueRuleTypes.indexOf(type) !== -1;
	}

	function isSearchableRule(type) {
		return searchableRuleTypes.indexOf(type) !== -1;
	}

	function updateLocationRule(rule) {
		var select = rule.querySelector(".apro-hfb-rule-select");
		var valueWrap = rule.querySelector(".apro-hfb-rule-value-wrap");
		var valueField = rule.querySelector(".apro-hfb-rule-value");
		var valueDescription = rule.querySelector(".apro-hfb-rule-value-description");
		var picker = rule.querySelector(".apro-hfb-target-picker");
		var searchField = rule.querySelector(".apro-hfb-target-search");
		var searchable;

		if (!select || !valueWrap || !valueField) {
			return;
		}

		searchable = isSearchableRule(select.value);

		if (isValueRule(select.value)) {
			valueWrap.classList.remove("apro-is-hidden");
			valueField.classList.toggle("apro-hfb-rule-value--hidden", searchable);

			if (valueDescription) {
				valueDescription.classList.toggle("apro-is-hidden", searchable);
			}

			if (picker) {
				picker.classList.toggle("apro-is-hidden", !searchable);
			}
		} else {
			valueWrap.classList.add("apro-is-hidden");
			valueField.value = "";
			clearTargetChips(rule);

			if (valueDescription) {
				valueDescription.classList.add("apro-is-hidden");
			}

			if (picker) {
				picker.classList.add("apro-is-hidden");
				picker.classList.remove("apro-hfb-target-picker--active");
			}
		}

		if (searchField && !searchable) {
			searchField.value = "";
			renderTargetResults(rule, []);
		}

		updateTargetPickerState(rule);
	}

	function resultMessage(message) {
		return [{ label: message, token: "", disabled: true }];
	}

	function renderTargetResults(rule, results) {
		var resultsWrap = rule.querySelector(".apro-hfb-target-results");
		var currentGroup = "";
		var markup = [];

		if (!resultsWrap) {
			return;
		}

		if (!results.length) {
			resultsWrap.innerHTML = "";
			resultsWrap.hidden = true;
			return;
		}

		results.forEach(function (result) {
			if (result.disabled) {
				markup.push('<div class="apro-hfb-target-result apro-hfb-target-result--disabled">' + escapeHtml(result.label) + "</div>");
				return;
			}

			if (result.group && result.group !== currentGroup) {
				currentGroup = result.group;
				markup.push('<div class="apro-hfb-target-result-group">' + escapeHtml(currentGroup) + "</div>");
			}

			markup.push('<button type="button" class="apro-hfb-target-result" data-token="' + escapeAttribute(result.token) + '" data-label="' + escapeAttribute(result.label) + '"><span class="apro-hfb-target-result-label">' + escapeHtml(result.label) + "</span></button>");
		});

		resultsWrap.innerHTML = markup.join("");
		resultsWrap.hidden = false;
	}

	function searchTargets(field) {
		var rule = field.closest(".apro-hfb-rule");
		var select = rule ? rule.querySelector(".apro-hfb-rule-select") : null;
		var term = field.value.trim();
		var activeRule = select ? select.value : "";

		if (!rule || !select || !isSearchableRule(activeRule)) {
			return;
		}

		if (term.length < 2) {
			renderTargetResults(rule, resultMessage(strings.minChars || "Please enter 2 or more characters"));
			return;
		}

		renderTargetResults(rule, resultMessage(strings.searching || "Searching..."));

		fetch(settings.ajaxUrl || window.ajaxurl, {
			method: "POST",
			credentials: "same-origin",
			headers: {
				"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			body: new URLSearchParams({
				action: "apro_hfb_search_targets",
				nonce: settings.searchNonce || "",
				rule: activeRule,
				term: term
			})
		})
			.then(function (response) {
				return response.json();
			})
			.then(function (response) {
				var results = response && response.success && response.data && Array.isArray(response.data.results) ? response.data.results : [];

				if (field.value.trim() !== term || select.value !== activeRule) {
					return;
				}

				if (!results.length) {
					renderTargetResults(rule, resultMessage(strings.noResults || "No matching targets found."));
					return;
				}

				renderTargetResults(rule, results);
			})
			.catch(function () {
				renderTargetResults(rule, resultMessage(strings.error || "Search failed. Please try again."));
			});
	}

	function scheduleTargetSearch(field) {
		var existingTimer = searchTimers.get(field);

		if (existingTimer) {
			window.clearTimeout(existingTimer);
		}

		searchTimers.set(field, window.setTimeout(function () {
			searchTargets(field);
		}, 250));
	}

	function addTargetChip(rule, token, label) {
		var chips = rule.querySelector(".apro-hfb-target-chips");
		var searchField = rule.querySelector(".apro-hfb-target-search");

		if (!chips || !token) {
			return;
		}

		if (!hasTargetChip(rule, token)) {
			chips.insertAdjacentHTML("beforeend", targetChipHtml(token, label || token));
		}

		syncTargetValue(rule);

		if (searchField) {
			searchField.value = "";
			searchField.blur();
		}

		renderTargetResults(rule, []);
		setTargetPickerActive(rule, false);
		updateTargetPickerState(rule);
	}

	function hasTargetChip(rule, token) {
		return Boolean(rule.querySelector('.apro-hfb-target-chip[data-token="' + cssEscape(token) + '"]'));
	}

	function targetChipHtml(token, label) {
		return '<span class="apro-hfb-target-chip" data-token="' + escapeAttribute(token) + '"><button type="button" class="apro-hfb-target-chip-remove" aria-label="' + escapeAttribute(strings.removeTarget || "Remove selected target") + '" data-token="' + escapeAttribute(token) + '">&times;</button><span class="apro-hfb-target-chip-label">' + escapeHtml(label) + "</span></span>";
	}

	function syncTargetValue(rule) {
		var valueField = rule.querySelector(".apro-hfb-rule-value");
		var tokens;

		if (!valueField) {
			return;
		}

		tokens = Array.prototype.map.call(rule.querySelectorAll(".apro-hfb-target-chip"), function (chip) {
			return chip.dataset.token || "";
		}).filter(Boolean);

		valueField.value = tokens.join(", ");
		valueField.dispatchEvent(new Event("change", { bubbles: true }));
	}

	function removeTargetChip(chip) {
		var rule = chip ? chip.closest(".apro-hfb-rule") : null;

		if (!rule) {
			return;
		}

		chip.remove();
		syncTargetValue(rule);
		updateTargetPickerState(rule);
	}

	function removeLastTargetChip(rule) {
		var chips = rule.querySelectorAll(".apro-hfb-target-chip");
		var lastChip = chips.length ? chips[chips.length - 1] : null;

		if (lastChip) {
			removeTargetChip(lastChip);
		}
	}

	function clearTargetChips(rule) {
		var chips = rule.querySelector(".apro-hfb-target-chips");

		if (chips) {
			chips.innerHTML = "";
		}

		updateTargetPickerState(rule);
	}

	function hideTargetResults(rule) {
		renderTargetResults(rule, []);
		setTargetPickerActive(rule, false);
		updateTargetPickerState(rule);
	}

	function updateTargetPickerState(rule) {
		var picker = rule ? rule.querySelector(".apro-hfb-target-picker") : null;
		var chips = rule ? rule.querySelectorAll(".apro-hfb-target-chip") : [];

		if (!picker) {
			return;
		}

		picker.classList.toggle("apro-hfb-target-picker--has-chips", chips.length > 0);
	}

	function setTargetPickerActive(rule, active) {
		var picker = rule ? rule.querySelector(".apro-hfb-target-picker") : null;

		if (!picker) {
			return;
		}

		picker.classList.toggle("apro-hfb-target-picker--active", Boolean(active));
	}

	function activateTargetSearch(field) {
		var rule = field.closest(".apro-hfb-rule");

		if (!rule) {
			return;
		}

		setTargetPickerActive(rule, true);
		updateTargetPickerState(rule);

		if (field.value.trim().length < 2) {
			renderTargetResults(rule, resultMessage(strings.minChars || "Please enter 2 or more characters"));
		} else {
			scheduleTargetSearch(field);
		}
	}

	function cssEscape(value) {
		if (window.CSS && typeof window.CSS.escape === "function") {
			return window.CSS.escape(value);
		}

		return String(value).replace(/["\\]/g, "\\$&");
	}

	function escapeHtml(value) {
		return String(value).replace(/[&<>"']/g, function (match) {
			return {
				"&": "&amp;",
				"<": "&lt;",
				">": "&gt;",
				'"': "&quot;",
				"'": "&#039;"
			}[match];
		});
	}

	function escapeAttribute(value) {
		return escapeHtml(value).replace(/`/g, "&#096;");
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
		var searchField = rule.querySelector(".apro-hfb-target-search");

		if (select) {
			select.value = "";
		}

		if (valueField) {
			valueField.value = "";
		}

		if (searchField) {
			searchField.value = "";
		}

		clearTargetChips(rule);
		renderTargetResults(rule, []);
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
				var rule = event.target.closest(".apro-hfb-rule");
				var valueField = rule ? rule.querySelector(".apro-hfb-rule-value") : null;

				if (valueField) {
					valueField.value = "";
				}

				if (rule) {
					clearTargetChips(rule);
					renderTargetResults(rule, []);
					updateLocationRule(rule);
				}
			}
		});

		builder.addEventListener("input", function (event) {
			if (event.target && event.target.matches(".apro-hfb-target-search")) {
				scheduleTargetSearch(event.target);
			}
		});

		builder.addEventListener("focusin", function (event) {
			if (event.target && event.target.matches(".apro-hfb-target-search")) {
				activateTargetSearch(event.target);
			}
		});

		builder.addEventListener("keydown", function (event) {
			var rule;

			if (!event.target || !event.target.matches(".apro-hfb-target-search") || event.key !== "Backspace" || event.target.value !== "") {
				return;
			}

			rule = event.target.closest(".apro-hfb-rule");

			if (rule) {
				removeLastTargetChip(rule);
			}
		});

		builder.addEventListener("click", function (event) {
			var addButton = event.target.closest(".apro-hfb-add-rule");
			var removeButton = event.target.closest(".apro-hfb-remove-rule");
			var targetResult = event.target.closest(".apro-hfb-target-result");
			var chipRemove = event.target.closest(".apro-hfb-target-chip-remove");
			var targetSelection = event.target.closest(".apro-hfb-target-selection");

			if (targetResult && targetResult.dataset.token) {
				event.preventDefault();
				addTargetChip(targetResult.closest(".apro-hfb-rule"), targetResult.dataset.token, targetResult.dataset.label);
				return;
			}

			if (chipRemove) {
				event.preventDefault();
				removeTargetChip(chipRemove.closest(".apro-hfb-target-chip"));
				return;
			}

			if (targetSelection && !event.target.closest(".apro-hfb-target-chip-remove")) {
				var targetSearch = targetSelection.querySelector(".apro-hfb-target-search");

				if (targetSearch) {
					activateTargetSearch(targetSearch);
					targetSearch.focus();
				}
				return;
			}

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

	function init() {
		document.querySelectorAll(".apro-hfb-rule-builder--locations").forEach(initLocationBuilder);

		document.addEventListener("click", function (event) {
			if (event.target.closest(".apro-hfb-show-exclusions")) {
				event.preventDefault();
				revealExclusionRow();
			}

			if (!event.target.closest(".apro-hfb-target-picker")) {
				document.querySelectorAll(".apro-hfb-rule").forEach(hideTargetResults);
			}
		});
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", init);
	} else {
		init();
	}
})();
