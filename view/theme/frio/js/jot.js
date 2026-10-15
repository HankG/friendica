// SPDX-FileCopyrightText: 2010-2026 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

// @license magnet:?xt=urn:btih:0b31508aeb0634b347b8270c7bee4d411b5d4109&dn=agpl-3.0.txt AGPLv3-or-later

/**
 * @file view/theme/frio/js/jot.js
 * JavaScript for link attachment in the jot (post composer).
 *
 * Handles inserting links into posts with automatic preview generation.
 *
 * @requires jQuery
 * @requires linkPreview.js
 * @requires autosize
 *
 * Note: The linkPreview variable is intentionally global for cross-module access.
 */

// Global linkPreview instance for cross-module access
var linkPreview;

(function ($, window) {
	"use strict";

	/**
	 * Prompt for a link URL and insert it into the jot with preview.
	 *
	 * @returns {void}
	 */
	window.jotGetLink = function () {
		// Check for required global variables and functions
		if (typeof window.aStr === "undefined" || !window.aStr.linkurl) {
			return;
		}

		const reply = prompt(window.aStr.linkurl);

		if (!reply || !reply.length) {
			return;
		}

		const $textarea = $("#profile-jot-text");

		if (!$textarea.length) {
			return;
		}

		const currentText = $textarea.val();

		// Clear previous attachment preview
		$("#jot-attachment-preview").empty();
		$("#profile-rotator").show();

		// Check if post already has an attachment
		const hasAttachment = currentText.includes("[attachment") &&
		                      currentText.includes("[/attachment]");
		const noAttachment = hasAttachment ? "&noAttachment=1" : "";

		// Use linkPreview library if available
		if (typeof linkPreview === "object" && linkPreview !== null) {
			linkPreview.crawlText(reply + noAttachment);
		} else if (typeof window.bin2hex === "function") {
			// Fallback: directly fetch and insert BBCode
			const hexReply = window.bin2hex(reply);
			$.get("parseurl?binurl=" + hexReply + noAttachment, function (data) {
				if (typeof window.addeditortext === "function") {
					window.addeditortext(data);
				}
				$("#profile-rotator").hide();
			}).fail(function () {
				$("#profile-rotator").hide();
			});
		} else {
			$("#profile-rotator").hide();
		}

		// Update textarea autosize if available
		if (typeof window.autosize === "function") {
			window.autosize.update($textarea);
		}
	};

	/**
	 * Show the jot modal with cached content.
	 * This function is called when the jot button is clicked.
	 * 
	 * @requires jQuery
	 * @requires jotcache (global variable from theme.js)
	 * @requires linkPreview (global variable)
	 * @requires toggleJotNav (global function from modal.js)
	 * 
	 * @returns {void}
	 */
	window.jotShow = function () {
		var modal = $('#jot-modal').modal();
		var jotcache = window.jotcache || $("#jot-sections");

		// Auto focus on the first enabled field in the modal
		modal.on('shown.bs.modal', function (e) {
			$('#jot-modal-content').find('select:not([disabled]), input:not([type=hidden]):not([disabled]), textarea:not([disabled])').first().focus();
		});

		modal
			.find('#jot-modal-content')
			.append(jotcache)
			.modal('show');

		// Jot attachment live preview.
		if (typeof linkPreview !== "undefined") {
			linkPreview = $('#profile-jot-text').linkPreview();
		}
	};

	/**
	 * Activate the jot text section in the jot modal.
	 * 
	 * @requires jQuery
	 * @requires toggleJotNav (global function from modal.js)
	 * 
	 * @returns {void}
	 */
	window.jotActive = function () {
		// Make sure jot text does have really the active class (we do this because there are some
		// other events which trigger jot text (we need to do this for the desktop and mobile
		// jot nav
		var elem = $("#jot-modal .jot-nav #jot-text-lnk");
		var elemMobile = $("#jot-modal .jot-nav #jot-text-lnk-mobile");
		if (typeof window.toggleJotNav === "function") {
			window.toggleJotNav(elem[0]);
			window.toggleJotNav(elemMobile[0]);
		}
	};

})(jQuery, window);
// @license-end
