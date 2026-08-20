<?php
/**
 * Shop/checkout/contact fixes — v1.0.0 Aug 2026
 * Does not change product prices. Does not hide COD.
 *
 * require_once get_stylesheet_directory() . '/jnp-shop-fixes.php';
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'JNP_SHOP_FIXES_LOADED' ) ) {
	return;
}
define( 'JNP_SHOP_FIXES_LOADED', true );

/**
 * Free hotel delivery — live checkout still shows Flat rate $5 because this
 * filter is not in functions.php yet. Priority 999 beats other shipping plugins.
 */
add_filter(
	'woocommerce_package_rates',
	function ( $rates ) {
		foreach ( $rates as $rate_id => $rate ) {
			$rates[ $rate_id ]->cost  = 0;
			$rates[ $rate_id ]->label = __( 'Free hotel delivery (Bali)', 'moderno-child' );
			if ( isset( $rates[ $rate_id ]->taxes ) && is_array( $rates[ $rate_id ]->taxes ) ) {
				foreach ( $rates[ $rate_id ]->taxes as $tax_id => $tax ) {
					$rates[ $rate_id ]->taxes[ $tax_id ] = 0;
				}
			}
		}
		return $rates;
	},
	999
);

add_filter(
	'woocommerce_cart_shipping_method_full_label',
	function ( $label, $method ) {
		if ( $method && (float) $method->cost <= 0 ) {
			return __( 'Free hotel delivery (Bali)', 'moderno-child' );
		}
		return $label;
	},
	20,
	2
);

/**
 * Stripe shows as "Payment options" on this site. Relabel only Stripe.
 * Leave PayPal, bank transfer, and COD untouched.
 */
add_filter(
	'woocommerce_gateway_title',
	function ( $title, $id ) {
		$id = strtolower( (string) $id );
		if ( 'stripe' === $id || 'stripe_cc' === $id || 'payment_options' === strtolower( (string) $title ) ) {
			return __( 'Credit Card', 'moderno-child' );
		}
		return $title;
	},
	20,
	2
);

add_filter(
	'woocommerce_available_payment_gateways',
	function ( $gateways ) {
		foreach ( $gateways as $id => $gateway ) {
			if ( 'cod' === $id ) {
				continue;
			}
			if ( 'stripe' === $id || ( isset( $gateway->title ) && 'payment options' === strtolower( (string) $gateway->title ) ) ) {
				$gateways[ $id ]->title = __( 'Credit Card', 'moderno-child' );
			}
		}
		return $gateways;
	},
	100
);

add_filter(
	'woocommerce_checkout_fields',
	function ( $fields ) {
		foreach ( array( 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_postcode', 'billing_state' ) as $key ) {
			if ( isset( $fields['billing'][ $key ] ) ) {
				$fields['billing'][ $key ]['required'] = false;
			}
		}
		return $fields;
	},
	20
);

/**
 * Mug: hide Design Style and lock the first valid option so Add to cart works.
 * Price is not changed.
 */
function jnp_is_mug_product( $product = null ) {
	if ( ! $product && function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( get_the_ID() );
	}
	if ( ! $product ) {
		return false;
	}
	$hay = strtolower( $product->get_slug() . ' ' . $product->get_name() );
	return false !== strpos( $hay, 'mug' );
}

add_filter(
	'woocommerce_dropdown_variation_attribute_options_html',
	function ( $html, $args ) {
		$product = isset( $args['product'] ) ? $args['product'] : null;
		if ( ! jnp_is_mug_product( $product ) ) {
			return $html;
		}
		$attr = isset( $args['attribute'] ) ? strtolower( (string) $args['attribute'] ) : '';
		$attr = preg_replace( '/^attribute_/', '', $attr );
		if ( false === strpos( $attr, 'design' ) ) {
			return $html;
		}
		$value = '';
		if ( ! empty( $args['options'] ) && is_array( $args['options'] ) ) {
			foreach ( $args['options'] as $opt ) {
				if ( '' !== $opt ) {
					$value = $opt;
					break;
				}
			}
		}
		if ( '' === $value ) {
			$value = 'Original Design';
		}
		$name = 'attribute_' . sanitize_title( isset( $args['attribute'] ) ? $args['attribute'] : 'design-style' );
		return '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" data-attribute_name="' . esc_attr( $name ) . '" />';
	},
	30,
	2
);

add_filter(
	'woocommerce_ajax_variation_threshold',
	function () {
		return 300;
	}
);

/**
 * Contact / quote form — Elementor page has heading but no form widget.
 */
function jnp_is_contact_page() {
	if ( is_page( array( 'contacts', 'contact', 'request-a-quote', 'quote' ) ) ) {
		return true;
	}
	$path = isset( $_SERVER['REQUEST_URI'] ) ? strtolower( (string) wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	return (bool) preg_match( '#/(contacts|contact|request-a-quote)(/|$)#', $path );
}

function jnp_contact_form_html() {
	$sent = isset( $_GET['jnp_sent'] ) ? sanitize_text_field( wp_unslash( $_GET['jnp_sent'] ) ) : '';
	ob_start();
	?>
	<div class="jnp-contact-form" id="jnp-contact-form">
		<?php if ( '1' === $sent ) : ?>
			<p class="jnp-contact-form__ok"><?php esc_html_e( 'Thanks — we got your message and will reply shortly.', 'moderno-child' ); ?></p>
		<?php elseif ( '0' === $sent ) : ?>
			<p class="jnp-contact-form__err"><?php esc_html_e( 'Please fill name, email, and a short message.', 'moderno-child' ); ?></p>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="jnp_contact" />
			<?php wp_nonce_field( 'jnp_contact', 'jnp_contact_nonce' ); ?>
			<p class="jnp-contact-form__hp" aria-hidden="true">
				<label>Company<input type="text" name="jnp_company" tabindex="-1" autocomplete="off" /></label>
			</p>
			<p>
				<label for="jnp_c_name"><?php esc_html_e( 'Name', 'moderno-child' ); ?></label>
				<input id="jnp_c_name" name="jnp_c_name" type="text" required />
			</p>
			<p>
				<label for="jnp_c_email"><?php esc_html_e( 'Email', 'moderno-child' ); ?></label>
				<input id="jnp_c_email" name="jnp_c_email" type="email" required />
			</p>
			<p>
				<label for="jnp_c_msg"><?php esc_html_e( 'Message', 'moderno-child' ); ?></label>
				<textarea id="jnp_c_msg" name="jnp_c_msg" rows="5" required></textarea>
			</p>
			<p>
				<button type="submit" class="jnp-contact-form__btn"><?php esc_html_e( 'Send message', 'moderno-child' ); ?></button>
			</p>
		</form>
	</div>
	<?php
	return ob_get_clean();
}

function jnp_append_contact_form( $content ) {
	if ( is_admin() || ! jnp_is_contact_page() ) {
		return $content;
	}
	if ( false !== strpos( (string) $content, 'jnp-contact-form' ) ) {
		return $content;
	}
	if ( false !== strpos( (string) $content, 'wpcf7-form' ) || false !== strpos( (string) $content, 'elementor-form' ) ) {
		return $content;
	}
	return $content . jnp_contact_form_html();
}

add_filter( 'the_content', 'jnp_append_contact_form', 30 );
add_filter( 'elementor/frontend/the_content', 'jnp_append_contact_form', 30 );

add_action(
	'wp_footer',
	function () {
		if ( ! jnp_is_contact_page() ) {
			return;
		}
		if ( is_admin() ) {
			return;
		}
		?>
		<script>
		(function () {
			if (document.getElementById('jnp-contact-form')) return;
			var host = document.querySelector('.elementor-location-single .elementor, main, .l-section--content, .elementor-2098') || document.querySelector('.elementor');
			if (!host) return;
			var wrap = document.createElement('div');
			wrap.innerHTML = <?php echo wp_json_encode( jnp_contact_form_html() ); ?>;
			var footer = document.querySelector('footer.c-footer, .elementor-location-footer');
			if (footer && footer.parentNode) {
				footer.parentNode.insertBefore(wrap, footer);
			} else {
				document.body.appendChild(wrap);
			}
		})();
		</script>
		<?php
	},
	20
);

function jnp_handle_contact() {
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/contacts/' );
	$back = remove_query_arg( 'jnp_sent', $back );

	if ( ! empty( $_POST['jnp_company'] ) ) {
		wp_safe_redirect( add_query_arg( 'jnp_sent', '1', $back ) );
		exit;
	}

	if ( ! isset( $_POST['jnp_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['jnp_contact_nonce'] ) ), 'jnp_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'jnp_sent', '0', $back ) );
		exit;
	}

	$name  = isset( $_POST['jnp_c_name'] ) ? sanitize_text_field( wp_unslash( $_POST['jnp_c_name'] ) ) : '';
	$email = isset( $_POST['jnp_c_email'] ) ? sanitize_email( wp_unslash( $_POST['jnp_c_email'] ) ) : '';
	$msg   = isset( $_POST['jnp_c_msg'] ) ? sanitize_textarea_field( wp_unslash( $_POST['jnp_c_msg'] ) ) : '';

	if ( '' === $name || ! is_email( $email ) || '' === $msg ) {
		wp_safe_redirect( add_query_arg( 'jnp_sent', '0', $back ) );
		exit;
	}

	$subject = sprintf( '[J&P Bali] Message from %s', $name );
	$body    = "Name: {$name}\nEmail: {$email}\n\n{$msg}\n";
	wp_mail( 'info@jnpbali.com', $subject, $body, array( 'Reply-To: ' . $email ) );

	wp_safe_redirect( add_query_arg( 'jnp_sent', '1', $back ) );
	exit;
}

add_action( 'admin_post_nopriv_jnp_contact', 'jnp_handle_contact' );
add_action( 'admin_post_jnp_contact', 'jnp_handle_contact' );
