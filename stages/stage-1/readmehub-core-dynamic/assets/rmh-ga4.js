/**
 * ReadMeHub GA4 affiliate click tracking (deferred, lightweight).
 * Fires a gtag event for every [rmh_affiliate_cta] outbound click.
 * No-ops gracefully when gtag is not loaded (e.g., ad blockers).
 */
(function () {
	"use strict";

	function trackAffiliateClick(event) {
		var link = event.target && event.target.closest ? event.target.closest("a[data-rmh-cta]") : null;
		if (!link) {
			return;
		}
		if (typeof window.gtag !== "function") {
			return;
		}
		window.gtag("event", "affiliate_click", {
			event_category: "affiliate",
			event_label: link.textContent.trim(),
			tool: link.dataset.rmhTool || "",
			placement: link.dataset.rmhCta || "",
			transport_type: "beacon"
		});
	}

	document.addEventListener("click", trackAffiliateClick, { passive: true, capture: true });
})();
