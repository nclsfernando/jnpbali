(function () {
	var header = document.getElementById( 'jnp-header' );
	var hamburger = document.querySelector( '.jnp-hamburger' );
	var mobileMenu = document.getElementById( 'jnp-mobile-menu' );

	if ( ! header ) {
		return;
	}

	var toggleScrolled = function () {
		if ( window.scrollY > 10 ) {
			header.classList.add( 'is-scrolled' );
		} else {
			header.classList.remove( 'is-scrolled' );
		}
	};

	toggleScrolled();
	window.addEventListener( 'scroll', toggleScrolled, { passive: true } );

	function setMenuOpen( open ) {
		if ( ! hamburger || ! mobileMenu ) {
			return;
		}

		hamburger.setAttribute( 'aria-expanded', String( open ) );
		hamburger.setAttribute( 'aria-label', open ? 'Close menu' : 'Open menu' );
		mobileMenu.classList.toggle( 'is-open', open );
		document.body.classList.toggle( 'jnp-menu-open', open );
		document.body.style.overflow = open ? 'hidden' : '';
	}

	if ( hamburger && mobileMenu ) {
		hamburger.addEventListener( 'click', function () {
			var isOpen = hamburger.getAttribute( 'aria-expanded' ) === 'true';
			setMenuOpen( ! isOpen );
		} );

		mobileMenu.querySelectorAll( 'a' ).forEach( function ( link ) {
			link.addEventListener( 'click', function () {
				setMenuOpen( false );
			} );
		} );

		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' ) {
				setMenuOpen( false );
			}
		} );

		document.addEventListener( 'click', function ( e ) {
			if ( ! mobileMenu.classList.contains( 'is-open' ) ) {
				return;
			}

			if ( mobileMenu.contains( e.target ) || hamburger.contains( e.target ) ) {
				return;
			}

			setMenuOpen( false );
		} );
	}
})();

(function () {
	var WA_HREF = 'https://wa.me/6287740098436?text=' + encodeURIComponent( 'Hi J&P Bali! I would like to ask about custom trip apparel.' );
	var PAYMENT_LINE = 'Payment: PayPal, Credit Card, or Bank Transfer (IDR/USD)';

	function dbg( hypothesisId, location, message, data ) {
		// #region agent log
		fetch( 'http://127.0.0.1:7480/ingest/b65918d8-7ed4-40c3-9e0b-9622d1330445', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json', 'X-Debug-Session-Id': 'e60dbf' },
			body: JSON.stringify( {
				sessionId: 'e60dbf',
				runId: 'post-fix',
				hypothesisId: hypothesisId,
				location: location,
				message: message,
				data: data || {},
				timestamp: Date.now()
			} )
		} ).catch( function () {} );
		// #endregion
	}

	function rewriteWhatsApp() {
		var n = 0;
		document.querySelectorAll( 'a[href*="wa.me"], a[href*="api.whatsapp.com"], a[href*="whatsapp.com"], a.jnp-wa' ).forEach( function ( a ) {
			var href = a.getAttribute( 'href' ) || '';
			if ( href.indexOf( '6287740098436' ) !== -1 ) {
				return;
			}
			var textMatch = href.match( /[?&]text=([^&]*)/ );
			a.setAttribute( 'href', textMatch ? ( 'https://wa.me/6287740098436?text=' + textMatch[ 1 ] ) : WA_HREF );
			n += 1;
		} );
		document.querySelectorAll( 'a[href^="tel:"]' ).forEach( function ( a ) {
			var href = ( a.getAttribute( 'href' ) || '' ).replace( /\s/g, '' );
			if ( /6281234567890|1234567890/.test( href ) ) {
				a.setAttribute( 'href', 'tel:+6287740098436' );
				n += 1;
			}
		} );
		dbg( 'E', 'header-custom.js:rewriteWhatsApp', 'WA href rewritten', { count: n, href: WA_HREF } );
	}

	function rewritePaymentLine() {
		document.querySelectorAll( '.elementor-icon-list-text, .jnp-payment-note' ).forEach( function ( el ) {
			var t = ( el.textContent || '' ).replace( /\s+/g, ' ' ).trim();
			if ( /^Payment:/i.test( t ) && t !== PAYMENT_LINE ) {
				el.textContent = PAYMENT_LINE;
			}
		} );
	}

	function blockSyntheticGalleryClicks() {
		document.addEventListener( 'click', function ( e ) {
			var raw = e.target;
			var el = raw && raw.nodeType === 1 ? raw : ( raw && raw.parentElement );
			var modal = el && el.closest && el.closest( '.js-product-modal, .woocommerce-product-gallery__image a' );
			if ( ! modal ) {
				return;
			}
			if ( e.isTrusted ) {
				return;
			}
			e.preventDefault();
			e.stopImmediatePropagation();
			dbg( 'A', 'header-custom.js:blockSyntheticGalleryClicks', 'blocked untrusted gallery click', {
				tag: el && el.tagName,
				cls: el && ( el.className || '' ).toString().slice( 0, 80 )
			} );
		}, true );
	}

	function goToColorSlide( slug ) {
		var gal = document.querySelector( '.woocommerce-product-gallery' );
		if ( ! gal || ! slug ) {
			return;
		}
		var imgs = gal.querySelectorAll( '.woocommerce-product-gallery__image' );
		var i;
		for ( i = 0; i < imgs.length; i++ ) {
			var im = imgs[ i ].querySelector( 'img' );
			var src = im ? ( im.getAttribute( 'src' ) || '' ) : '';
			var hay = src.toLowerCase();
			if ( hay.indexOf( '-' + slug + '.' ) > -1 || hay.indexOf( '-' + slug + '-' ) > -1 ) {
				var dots = document.querySelectorAll( '.c-product__slider [aria-label^="Slide"], .js-single-product-carousel .dot' );
				if ( dots[ i ] ) {
					dots[ i ].click();
				}
				dbg( 'A', 'header-custom.js:goToColorSlide', 'carousel slide without lightbox', { slug: slug, index: i } );
				break;
			}
		}
	}

	function bindColorSwatches() {
		var wrap = document.querySelector( '[data-jnp-color-swatch]' );
		if ( ! wrap ) {
			return;
		}
		wrap.querySelectorAll( '.jnp-color-swatch__dot' ).forEach( function ( dot ) {
			dot.addEventListener( 'click', function () {
				goToColorSlide( dot.getAttribute( 'data-color-slug' ) );
			} );
		} );
	}

	function syncHotelFields() {
		var search = document.getElementById( 'jnp_hotel_search' );
		var name = document.getElementById( 'jnp_hotel_name' );
		var form = document.querySelector( 'form.checkout' );
		if ( ! search || ! name ) {
			return;
		}
		function copy() {
			if ( search.value && ! name.value ) {
				name.value = search.value;
			}
		}
		search.addEventListener( 'change', copy );
		search.addEventListener( 'blur', copy );
		if ( form ) {
			form.addEventListener( 'submit', copy );
		}
		dbg( 'G', 'header-custom.js:syncHotelFields', 'hotel field sync bound', {
			searchId: search.id,
			nameId: name.id
		} );
	}

	function closeCartOnCheckout() {
		if ( ! document.body.classList.contains( 'woocommerce-checkout' ) ) {
			return;
		}
		var root = document.getElementById( 'jnp-float-cart' );
		if ( ! root ) {
			return;
		}
		root.classList.remove( 'is-open' );
		var panel = root.querySelector( '.jnp-float-cart__panel' );
		var btn = root.querySelector( '.jnp-float-cart__btn' );
		if ( panel ) {
			panel.setAttribute( 'hidden', 'hidden' );
		}
		if ( btn ) {
			btn.setAttribute( 'aria-expanded', 'false' );
		}
	}

	function pageAudit() {
		var size = document.querySelector( 'select#size, select[name="attribute_size"], select[name="attribute_pa_size"]' );
		var sizeOpts = size ? Array.prototype.map.call( size.options, function ( o ) { return o.value; } ).filter( Boolean ) : [];
		var shipping = Array.prototype.map.call( document.querySelectorAll( '.woocommerce-shipping-methods li, #shipping_method li' ), function ( el ) {
			return el.textContent.replace( /\s+/g, ' ' ).trim().slice( 0, 80 );
		} );
		var payments = Array.prototype.map.call( document.querySelectorAll( '.wc_payment_methods label, .payment_method label' ), function ( el ) {
			return el.textContent.replace( /\s+/g, ' ' ).trim().slice( 0, 60 );
		} );
		var hotelFields = document.querySelectorAll( '#jnp_hotel_search, #jnp_hotel_name, #jnp_hotel_name_field, [id*="hotel"]' ).length;
		var forms = document.querySelectorAll( 'form.wpcf7-form, .elementor-form, form.checkout, form.cart' ).length;
		dbg( 'A', 'header-custom.js:pageAudit', 'page health', {
			path: location.pathname,
			title: document.title.slice( 0, 80 ),
			is404: /404|not found|oops/i.test( document.title + ' ' + ( document.body.innerText || '' ).slice( 0, 200 ) ),
			productType: ( document.body.className.match( /product-type-\S+/ ) || [] )[ 0 ] || '',
			sizeLabel: ( document.querySelector( 'label[for="size"], .variations label' ) || {} ).textContent || '',
			sizeOpts: sizeOpts,
			hasSizeGuide: !! document.querySelector( '.jnp-size-guide' ),
			shipping: shipping,
			payments: payments.slice( 0, 8 ),
			hotelFields: hotelFields,
			forms: forms,
			waBad: !! document.querySelector( 'a[href*="6281234567890"]' )
		} );
		if ( sizeOpts.length && sizeOpts.indexOf( 'XS' ) === -1 ) {
			dbg( 'A', 'header-custom.js:pageAudit', 'clothing missing XS', { path: location.pathname, sizeOpts: sizeOpts } );
		}
		if ( shipping.join( ' ' ).indexOf( '5.00' ) !== -1 || shipping.join( ' ' ).indexOf( '$ 5' ) !== -1 ) {
			dbg( 'B', 'header-custom.js:pageAudit', 'checkout still charging $5', { path: location.pathname, shipping: shipping } );
		}
		if ( payments.join( ' ' ).toLowerCase().indexOf( 'payment options' ) !== -1 ) {
			dbg( 'B', 'header-custom.js:pageAudit', 'stripe still labeled Payment options', { payments: payments } );
		}
		if ( /contacts|request-a-quote/.test( location.pathname ) && ! document.querySelector( 'form.wpcf7-form, .elementor-form' ) ) {
			dbg( 'C', 'header-custom.js:pageAudit', 'contact/quote form missing', { path: location.pathname, forms: forms } );
		}
		if ( hotelFields > 3 ) {
			dbg( 'D', 'header-custom.js:pageAudit', 'duplicate hotel fields', { hotelFields: hotelFields } );
		}
		if ( /mug/i.test( location.pathname ) && document.querySelector( '[name="attribute_design-style"]' ) ) {
			dbg( 'E', 'header-custom.js:pageAudit', 'mug has Design Style attribute', { path: location.pathname } );
		}
	}

	function hideHotelDupes() {
		if ( ! document.body.classList.contains( 'woocommerce-checkout' ) ) {
			return;
		}
		var nameField = document.getElementById( 'jnp_hotel_name_field' );
		if ( nameField ) {
			nameField.style.display = 'none';
		}
		Array.prototype.forEach.call( document.querySelectorAll( '.form-row, p.form-row' ), function ( row ) {
			var lab = row.querySelector( 'label' );
			if ( ! lab ) {
				return;
			}
			var t = ( lab.textContent || '' ).replace( /\s+/g, ' ' ).trim();
			if ( ! /Hotel\s*\/\s*Villa/i.test( t ) ) {
				return;
			}
			if ( row.querySelector( '#jnp_hotel_search' ) ) {
				return;
			}
			row.style.display = 'none';
		} );
	}

	function hideMugDesignStyle() {
		if ( ! /mug/i.test( location.pathname + ' ' + document.body.className ) ) {
			return;
		}
		var sel = document.querySelector( '[name="attribute_design-style"], [name="attribute_pa_design-style"]' );
		if ( ! sel ) {
			return;
		}
		if ( sel.tagName === 'SELECT' && ! sel.value ) {
			var i;
			for ( i = 0; i < sel.options.length; i++ ) {
				if ( sel.options[ i ].value ) {
					sel.value = sel.options[ i ].value;
					sel.dispatchEvent( new Event( 'change', { bubbles: true } ) );
					break;
				}
			}
		}
		var row = sel.closest( 'tr' );
		if ( row ) {
			row.style.display = 'none';
		}
	}

	rewriteWhatsApp();
	rewritePaymentLine();
	blockSyntheticGalleryClicks();
	bindColorSwatches();
	syncHotelFields();
	closeCartOnCheckout();
	hideHotelDupes();
	hideMugDesignStyle();
	pageAudit();

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', function () {
			rewriteWhatsApp();
			rewritePaymentLine();
			bindColorSwatches();
			syncHotelFields();
			closeCartOnCheckout();
			hideHotelDupes();
			hideMugDesignStyle();
		} );
	}

	window.setTimeout( function () {
		rewriteWhatsApp();
		rewritePaymentLine();
		var wa = document.querySelector( 'a.jnp-wa' );
		dbg( 'E', 'header-custom.js:verifyWA', 'WA href after boot', { href: wa ? wa.href : null } );
	}, 800 );

	if ( window.MutationObserver ) {
		var waTimer = null;
		new MutationObserver( function () {
			window.clearTimeout( waTimer );
			waTimer = window.setTimeout( rewriteWhatsApp, 200 );
		} ).observe( document.documentElement, { childList: true, subtree: true } );
	}
})();
