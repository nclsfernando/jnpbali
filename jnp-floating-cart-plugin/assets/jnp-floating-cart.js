(function ($) {
	"use strict";

	if (typeof jnpFloatingCart === "undefined") {
		return;
	}

	var root = document.getElementById("jnp-float-cart");
	if (!root) {
		return;
	}

	var btn = root.querySelector(".jnp-float-cart__btn");
	var panel = root.querySelector(".jnp-float-cart__panel");
	var closeBtn = root.querySelector(".jnp-float-cart__close");
	var backdrop = root.querySelector(".jnp-float-cart__backdrop");
	var countEl = root.querySelector(".jnp-float-cart__count");
	var bodyEl = root.querySelector(".jnp-float-cart__body");
	var subtotalEl = root.querySelector(".jnp-float-cart__subtotal");
	var toastEl = document.getElementById("jnp-float-cart-toast");
	var openOnAdd = true;

	function showToast(message) {
		if (!toastEl) {
			return;
		}
		toastEl.textContent = message;
		toastEl.classList.add("is-visible");
		window.clearTimeout(showToast._t);
		showToast._t = window.setTimeout(function () {
			toastEl.classList.remove("is-visible");
		}, 2800);
	}

	function setEmptyState(count) {
		var n = parseInt(count, 10) || 0;
		root.classList.toggle("is-empty", n === 0);
	}

	function setCount(count) {
		var n = parseInt(count, 10) || 0;
		countEl.textContent = n > 0 ? String(n) : "";
		countEl.setAttribute("data-count", String(n));
		countEl.style.display = n > 0 ? "" : "none";
		setEmptyState(n);

		document.querySelectorAll(".jnp-cart-link__count").forEach(function (el) {
			el.textContent = n > 0 ? String(n) : "0";
		});

		document.querySelectorAll(".jnp-cart-link").forEach(function (link) {
			link.setAttribute(
				"aria-label",
				n === 1 ? "Cart, 1 item" : "Cart, " + n + " items"
			);
		});
	}

	function syncCountFromHeader() {
		var max = 0;
		document.querySelectorAll(".jnp-cart-link__count").forEach(function (el) {
			var n = parseInt(el.textContent, 10) || 0;
			if (n > max) {
				max = n;
			}
		});
		setCount(max);
	}

	function refreshMiniCart(openPanel) {
		return $.post(jnpFloatingCart.ajaxUrl, {
			action: "jnp_mini_cart",
			nonce: jnpFloatingCart.nonce,
		})
			.done(function (response) {
				if (!response || !response.success) {
					return;
				}
				bodyEl.innerHTML = response.data.html || "";
				if (subtotalEl) {
					subtotalEl.innerHTML = response.data.subtotal || "";
				}
				setCount(response.data.count);
				if (openPanel) {
					openPanelFn();
				}
			})
			.fail(function () {
				showToast("Could not refresh cart. Please try again.");
			});
	}

	function openPanelFn() {
		root.classList.add("is-open");
		btn.setAttribute("aria-expanded", "true");
		if (panel) {
			panel.removeAttribute("hidden");
		}
	}

	function closePanelFn() {
		root.classList.remove("is-open");
		btn.setAttribute("aria-expanded", "false");
		if (panel) {
			panel.setAttribute("hidden", "hidden");
		}
	}

	btn.addEventListener("click", function () {
		if (root.classList.contains("is-open")) {
			closePanelFn();
			return;
		}
		refreshMiniCart(true);
	});

	if (closeBtn) {
		closeBtn.addEventListener("click", closePanelFn);
	}
	if (backdrop) {
		backdrop.addEventListener("click", closePanelFn);
	}

	document.addEventListener("keydown", function (e) {
		if (e.key === "Escape") {
			closePanelFn();
		}
	});

	$(document.body).on("added_to_cart", function (e, fragments) {
		if (fragments) {
			$.each(fragments, function (key, value) {
				$(key).replaceWith(value);
			});
		}
		syncCountFromHeader();
		refreshMiniCart(openOnAdd);
		showToast(jnpFloatingCart.i18n.added);
	});

	$(document.body).on(
		"removed_from_cart wc_fragments_refreshed",
		function () {
			syncCountFromHeader();
			if (root.classList.contains("is-open")) {
				refreshMiniCart(false);
			}
		}
	);

	$(document).on("click", "a.add_to_cart_button", function () {
		openOnAdd = true;
	});

	$(document).on("submit", "form.cart", function () {
		openOnAdd = true;
	});

	$(document).on("click", ".js-wishlist-btn", function () {
		if (!$("body").hasClass("single-product")) {
			return;
		}

		var $form = $("form.variations_form, form.cart").first();
		if (!$form.length) {
			return;
		}

		window.setTimeout(function () {
			var $addBtn = $form.find(".single_add_to_cart_button");
			if (!$addBtn.length || $addBtn.hasClass("disabled")) {
				showToast(jnpFloatingCart.i18n.selectOptions);
				return;
			}

			if ($form.hasClass("variations_form")) {
				var variationId = $form.find('input[name="variation_id"]').val();
				if (!variationId || variationId === "0") {
					showToast(jnpFloatingCart.i18n.selectOptions);
					return;
				}
			}

			openOnAdd = true;
			$addBtn.trigger("click");
			showToast(jnpFloatingCart.i18n.wishlistAdded);
		}, 120);
	});

	$(document).on(
		"click",
		".c-wishlist a.add_to_cart_button, .c-wishlist .add_to_cart_button",
		function () {
			openOnAdd = true;
		}
	);

	closePanelFn();
	syncCountFromHeader();
	refreshMiniCart(false);
})(jQuery);
