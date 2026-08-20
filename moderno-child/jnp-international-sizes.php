<?php
/**
 * International apparel sizes — XS, S, M, L, XL, XXL
 * Clothing only (tees, hoodie). Mug and add-ons are skipped.
 * v1.1.0 — Aug 2026 — re-run sync (ver 2) so existing clothing gets XS–XXL
 *
 * Paste once via: require_once get_stylesheet_directory() . '/jnp-international-sizes.php';
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'JNP_INTL_SIZES_LOADED' ) ) {
	return;
}
define( 'JNP_INTL_SIZES_LOADED', true );

/**
 * Canonical international letter sizes, in display order.
 */
function jnp_intl_size_list() {
	return array( 'XS', 'S', 'M', 'L', 'XL', 'XXL' );
}

function jnp_is_mug_or_addon( $product ) {
	if ( ! $product ) {
		return true;
	}
	$slug = strtolower( (string) $product->get_slug() );
	$name = strtolower( (string) $product->get_name() );
	if ( false !== strpos( $slug, 'mug' ) || false !== strpos( $name, 'mug' ) ) {
		return true;
	}
	if ( false !== strpos( $slug, 'extra-ai' ) || false !== strpos( $name, 'extra ai' ) ) {
		return true;
	}
	return false;
}

function jnp_product_has_size_attribute( $product ) {
	if ( ! $product || ! method_exists( $product, 'get_attributes' ) ) {
		return false;
	}
	foreach ( $product->get_attributes() as $key => $attr ) {
		$name = is_object( $attr ) ? $attr->get_name() : (string) $key;
		if ( 'size' === strtolower( (string) $name ) || 'pa_size' === $key || 'size' === $key ) {
			return true;
		}
	}
	return false;
}

function jnp_is_clothing_product( $product ) {
	if ( ! $product || jnp_is_mug_or_addon( $product ) ) {
		return false;
	}
	if ( jnp_product_has_size_attribute( $product ) ) {
		return true;
	}
	if ( has_term( 'trip-wear', 'product_cat', $product->get_id() ) ) {
		return true;
	}
	$name = strtolower( (string) $product->get_name() . ' ' . $product->get_slug() );
	return (bool) preg_match( '/tee|tshirt|t-shirt|hoodie|apparel|shirt/', $name );
}

function jnp_find_size_attribute_key( $attributes ) {
	foreach ( $attributes as $key => $attr ) {
		$name = is_object( $attr ) ? $attr->get_name() : (string) $key;
		if ( 'size' === strtolower( (string) $name ) || 'pa_size' === $key || 'size' === $key ) {
			return $key;
		}
	}
	return '';
}

/**
 * Relabel the Size dropdown on clothing.
 */
add_filter(
	'woocommerce_attribute_label',
	function ( $label, $name ) {
		$norm = strtolower( (string) $name );
		if ( 'size' === $norm || 'pa_size' === $norm ) {
			return __( 'Size (International)', 'moderno-child' );
		}
		return $label;
	},
	20,
	2
);

/**
 * Keep letter sizes in XS → XXL order in the dropdown.
 */
add_filter(
	'woocommerce_dropdown_variation_attribute_options_args',
	function ( $args ) {
		$attr = isset( $args['attribute'] ) ? strtolower( (string) $args['attribute'] ) : '';
		$attr = preg_replace( '/^attribute_/', '', $attr );
		if ( 'size' !== $attr && 'pa_size' !== $attr ) {
			return $args;
		}
		$order = array_flip( jnp_intl_size_list() );
		if ( ! empty( $args['options'] ) && is_array( $args['options'] ) ) {
			usort(
				$args['options'],
				function ( $a, $b ) use ( $order ) {
					$ia = isset( $order[ strtoupper( (string) $a ) ] ) ? $order[ strtoupper( (string) $a ) ] : 50;
					$ib = isset( $order[ strtoupper( (string) $b ) ] ) ? $order[ strtoupper( (string) $b ) ] : 50;
					return $ia <=> $ib;
				}
			);
		}
		return $args;
	},
	20
);

/**
 * Size guide under the variation table (clothing only).
 */
function jnp_render_intl_size_guide() {
	global $product;
	if ( ! $product || ! jnp_is_clothing_product( $product ) ) {
		return;
	}

	$oversized = (bool) preg_match( '/oversized|hoodie/i', $product->get_name() . ' ' . $product->get_slug() );
	$note      = $oversized
		? __( 'Oversized cut. Size down if you want a regular fit. Chest is garment circumference.', 'moderno-child' )
		: __( 'Unisex international sizes. Chest is body circumference. When between sizes, size up.', 'moderno-child' );

	$rows = array(
		array( 'XS', '86–91 cm', '66 cm' ),
		array( 'S', '92–97 cm', '69 cm' ),
		array( 'M', '98–103 cm', '72 cm' ),
		array( 'L', '104–109 cm', '74 cm' ),
		array( 'XL', '110–115 cm', '76 cm' ),
		array( 'XXL', '116–121 cm', '78 cm' ),
	);
	?>
	<details class="jnp-size-guide">
		<summary><?php esc_html_e( 'International size guide (XS–XXL)', 'moderno-child' ); ?></summary>
		<p class="jnp-size-guide__note"><?php echo esc_html( $note ); ?></p>
		<table class="jnp-size-guide__table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Size', 'moderno-child' ); ?></th>
					<th><?php esc_html_e( 'Chest', 'moderno-child' ); ?></th>
					<th><?php esc_html_e( 'Length', 'moderno-child' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $rows as $row ) : ?>
					<tr>
						<th><?php echo esc_html( $row[0] ); ?></th>
						<td><?php echo esc_html( $row[1] ); ?></td>
						<td><?php echo esc_html( $row[2] ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</details>
	<?php
}

add_action( 'woocommerce_after_variations_table', 'jnp_render_intl_size_guide', 12 );
add_action(
	'woocommerce_before_add_to_cart_button',
	function () {
		global $product;
		if ( $product && $product->is_type( 'simple' ) && jnp_is_clothing_product( $product ) ) {
			jnp_render_intl_size_guide();
		}
	},
	8
);

/**
 * One-time: write XS–XXL onto clothing products and create missing variations.
 * Does not change prices. Skips mug / add-ons.
 */
function jnp_intl_sizes_sync() {
	if ( get_option( 'jnp_intl_sizes_ver' ) === '2' ) {
		return;
	}
	if ( ! function_exists( 'wc_get_products' ) ) {
		return;
	}
	if ( get_transient( 'jnp_intl_sizes_lock' ) ) {
		return;
	}
	set_transient( 'jnp_intl_sizes_lock', 1, 180 );
	if ( function_exists( 'set_time_limit' ) ) {
		@set_time_limit( 180 );
	}

	$sizes = jnp_intl_size_list();
	$ids   = wc_get_products(
		array(
			'limit'  => -1,
			'status' => array( 'publish', 'private' ),
			'return' => 'ids',
			'type'   => array( 'variable', 'simple' ),
		)
	);

	foreach ( $ids as $product_id ) {
		$product = wc_get_product( $product_id );
		if ( ! jnp_is_clothing_product( $product ) ) {
			continue;
		}

		if ( $product->is_type( 'simple' ) ) {
			if ( empty( $product->get_children() ) ) {
				jnp_intl_sizes_convert_simple( $product, $sizes );
			}
			continue;
		}

		if ( $product->is_type( 'variable' ) ) {
			jnp_intl_sizes_expand_variable( $product, $sizes );
		}
	}

	update_option( 'jnp_intl_sizes_ver', '2', true );
	delete_transient( 'jnp_intl_sizes_lock' );
}

function jnp_intl_sizes_make_size_attribute( $sizes ) {
	$attribute = new WC_Product_Attribute();
	$attribute->set_id( 0 );
	$attribute->set_name( 'Size' );
	$attribute->set_options( $sizes );
	$attribute->set_position( 1 );
	$attribute->set_visible( true );
	$attribute->set_variation( true );
	return $attribute;
}

function jnp_intl_sizes_convert_simple( $product, $sizes ) {
	$product_id = $product->get_id();
	$price      = $product->get_regular_price();
	$sale       = $product->get_sale_price();

	$attributes           = $product->get_attributes();
	$attributes['size']   = jnp_intl_sizes_make_size_attribute( $sizes );
	$product->set_attributes( $attributes );
	$product->save();

	wp_set_object_terms( $product_id, 'variable', 'product_type', false );

	foreach ( $sizes as $size ) {
		$variation = new WC_Product_Variation();
		$variation->set_parent_id( $product_id );
		$variation->set_attributes( array( 'size' => $size ) );
		$variation->set_regular_price( $price );
		if ( '' !== $sale ) {
			$variation->set_sale_price( $sale );
		}
		$variation->set_manage_stock( false );
		$variation->set_stock_status( 'instock' );
		$variation->set_status( 'publish' );
		$variation->save();
	}

	WC_Product_Variable::sync( $product_id );
	wc_delete_product_transients( $product_id );
}

function jnp_intl_sizes_expand_variable( $product, $sizes ) {
	$product_id = $product->get_id();
	$raw        = get_post_meta( $product_id, '_product_attributes', true );
	if ( is_array( $raw ) ) {
		foreach ( $raw as $key => $data ) {
			$n = isset( $data['name'] ) ? strtolower( (string) $data['name'] ) : strtolower( (string) $key );
			if ( 'size' === $n || 'size' === strtolower( (string) $key ) ) {
				if ( empty( $data['is_taxonomy'] ) ) {
					$raw[ $key ]['value']        = implode( ' | ', $sizes );
					$raw[ $key ]['is_variation'] = 1;
					$raw[ $key ]['is_visible']   = 1;
					update_post_meta( $product_id, '_product_attributes', $raw );
				}
				break;
			}
		}
	}

	$attributes = $product->get_attributes();
	$size_key   = jnp_find_size_attribute_key( $attributes );

	if ( '' === $size_key ) {
		$attributes['size'] = jnp_intl_sizes_make_size_attribute( $sizes );
		$size_key           = 'size';
	} else {
		$attr = $attributes[ $size_key ];
		if ( is_object( $attr ) ) {
			$attr->set_options( $sizes );
			$attr->set_variation( true );
			$attr->set_visible( true );
			$attributes[ $size_key ] = $attr;
		}
	}

	$product->set_attributes( $attributes );
	$product->save();

	$variation_attr_key = ( 0 === strpos( (string) $size_key, 'pa_' ) ) ? $size_key : 'size';
	$children           = $product->get_children();
	$templates          = array();
	$existing           = array();

	foreach ( $children as $variation_id ) {
		$variation = wc_get_product( $variation_id );
		if ( ! $variation || ! is_a( $variation, 'WC_Product_Variation' ) ) {
			continue;
		}
		$vattrs   = $variation->get_attributes();
		$size_val = '';
		if ( isset( $vattrs[ $variation_attr_key ] ) ) {
			$size_val = (string) $vattrs[ $variation_attr_key ];
		} elseif ( isset( $vattrs['size'] ) ) {
			$size_val = (string) $vattrs['size'];
		}
		$without_size = $vattrs;
		unset( $without_size[ $variation_attr_key ], $without_size['size'], $without_size['pa_size'] );
		$sig              = wp_json_encode( $without_size );
		$templates[ $sig ] = array(
			'attrs' => $vattrs,
			'price' => $variation->get_regular_price(),
			'sale'  => $variation->get_sale_price(),
		);
		$existing[ $sig . '|' . strtoupper( $size_val ) ] = true;
	}

	if ( empty( $templates ) ) {
		$templates['{}'] = array(
			'attrs' => array( $variation_attr_key => $sizes[0] ),
			'price' => $product->get_regular_price(),
			'sale'  => $product->get_sale_price(),
		);
	}

	foreach ( $templates as $sig => $template ) {
		foreach ( $sizes as $size ) {
			if ( ! empty( $existing[ $sig . '|' . strtoupper( $size ) ] ) ) {
				continue;
			}
			$new_attrs                          = $template['attrs'];
			$new_attrs[ $variation_attr_key ]   = $size;
			$variation                          = new WC_Product_Variation();
			$variation->set_parent_id( $product->get_id() );
			$variation->set_attributes( $new_attrs );
			$variation->set_regular_price( $template['price'] );
			if ( '' !== $template['sale'] ) {
				$variation->set_sale_price( $template['sale'] );
			}
			$variation->set_manage_stock( false );
			$variation->set_stock_status( 'instock' );
			$variation->set_status( 'publish' );
			$variation->save();
		}
	}

	WC_Product_Variable::sync( $product->get_id() );
	wc_delete_product_transients( $product->get_id() );
}

add_action( 'init', 'jnp_intl_sizes_sync', 30 );
