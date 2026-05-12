<?php
/**
 * SBB Timetable Widget Shortcode
 *
 * Usage:
 *   [sbb_timetable]
 *   [sbb_timetable from="Zürich HB" to="Bern"]
 *   [sbb_timetable banner="swiss-travel-pass" tracking="YOUR_CODE"]
 *   [sbb_timetable banner="saver-day-pass" tracking="YOUR_CODE"]
 *
 * Assets (CSS + JS) are loaded only on pages that contain this shortcode.
 * Compatible with WPML (de/en/fr) and WP Rocket.
 *
 * @package Hoteller_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns UI labels for the current WPML language.
 */
function sbb_timetable_get_labels() {
	$lang = apply_filters( 'wpml_current_language', 'en' );

	$all = array(
		'de' => array(
			'from'   => 'Von',
			'to'     => 'Nach',
			'dep'    => 'Ab',
			'arr'    => 'An',
			'search' => 'Suchen',
		),
		'fr' => array(
			'from'   => 'De',
			'to'     => 'À',
			'dep'    => 'Dép',
			'arr'    => 'Arr',
			'search' => 'Chercher',
		),
		'en' => array(
			'from'   => 'From',
			'to'     => 'To',
			'dep'    => 'Dep',
			'arr'    => 'Arr',
			'search' => 'Search',
		),
	);

	return isset( $all[ $lang ] ) ? $all[ $lang ] : $all['en'];
}

/**
 * Returns the banners config for the current WPML language.
 *
 * @param string $tracking Affiliate tracking code.
 */
function sbb_timetable_get_banners( $tracking ) {
	$lang = apply_filters( 'wpml_current_language', 'en' );

	$base_url = array(
		'swiss-travel-pass' => array(
			'de' => 'https://www.sbb.ch/de/angebote/swiss-travel-pass',
			'fr' => 'https://www.sbb.ch/fr/offres/swiss-travel-pass',
			'en' => 'https://www.sbb.ch/en/offers/swiss-travel-pass',
		),
		'saver-day-pass'    => array(
			'de' => 'https://www.sbb.ch/de/angebote/sparbillett',
			'fr' => 'https://www.sbb.ch/fr/offres/billet-supersaver',
			'en' => 'https://www.sbb.ch/en/offers/saver-day-pass',
		),
	);

	$affiliate = '?at_medium=affiliate&at_campaign=b2p&at_type=' . $tracking;

	$stp_url = ( isset( $base_url['swiss-travel-pass'][ $lang ] )
		? $base_url['swiss-travel-pass'][ $lang ]
		: $base_url['swiss-travel-pass']['en'] ) . $affiliate;

	$sdp_url = ( isset( $base_url['saver-day-pass'][ $lang ] )
		? $base_url['saver-day-pass'][ $lang ]
		: $base_url['saver-day-pass']['en'] ) . $affiliate;

	$titles = array(
		'swiss-travel-pass' => array(
			'de' => array(
				'title' => 'Swiss Travel Pass',
				'desc'  => 'Die Schweiz mit einem einzigen Ticket entdecken.',
			),
			'fr' => array(
				'title' => 'Swiss Travel Pass',
				'desc'  => 'Découvrez la Suisse avec un seul billet.',
			),
			'en' => array(
				'title' => 'Swiss Travel Pass',
				'desc'  => 'Discover Switzerland with just a single ticket.',
			),
		),
		'saver-day-pass'    => array(
			'de' => array(
				'title' => 'Sparbillett',
				'desc'  => 'Ab CHF 29.-, solange Vorrat.',
			),
			'fr' => array(
				'title' => 'Billet Supersaver',
				'desc'  => 'Dès CHF 29.-, dans la limite des stocks disponibles.',
			),
			'en' => array(
				'title' => 'Saver Day Pass',
				'desc'  => 'From CHF 29.-, while stocks last.',
			),
		),
	);

	$stp_copy = isset( $titles['swiss-travel-pass'][ $lang ] )
		? $titles['swiss-travel-pass'][ $lang ]
		: $titles['swiss-travel-pass']['en'];

	$sdp_copy = isset( $titles['saver-day-pass'][ $lang ] )
		? $titles['saver-day-pass'][ $lang ]
		: $titles['saver-day-pass']['en'];

	$img_base = get_stylesheet_directory_uri() . '/src/vendor/sbb-timetable/images';

	return array(
		'swiss-travel-pass' => array(
			'href'  => $stp_url,
			'image' => $img_base . '/swiss-travel-pass.jpg',
			'alt'   => $stp_copy['title'],
			'title' => $stp_copy['title'],
			'desc'  => $stp_copy['desc'],
		),
		'saver-day-pass'    => array(
			'href'  => $sdp_url,
			'image' => $img_base . '/saver-day-pass.jpg',
			'alt'   => $sdp_copy['title'],
			'title' => $sdp_copy['title'],
			'desc'  => $sdp_copy['desc'],
		),
	);
}

/**
 * Shortcode callback.
 *
 * @param array $atts Shortcode attributes.
 */
function sbb_timetable_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'from'     => '',
			'to'       => '',
			'banner'   => '',
			'tracking' => '',
		),
		$atts,
		'sbb_timetable'
	);

	$tracking = $atts['tracking'] ? esc_attr( $atts['tracking'] ) : 'affiliate-tracking-type-placeholder';
	$l        = sbb_timetable_get_labels();
	$banners  = sbb_timetable_get_banners( $tracking );

	ob_start();
	?>
	<form id="sbb-timetable-form-wrapper" class="sbb-timetable-form">
		<sbb-signet></sbb-signet>
		<sbb-timetable-form id="sbb-timetable-form">
			<sbb-timetable-form-field id="sbb-timetable-form-field-from">
				<label><?php echo esc_html( $l['from'] ); ?></label>
				<input id="sbb-timetable-form-field-input-from" type="text" name="from" value="<?php echo esc_attr( $atts['from'] ); ?>" />
				<sbb-autocomplete id="sbb-timetable-form-field-autocomplete-from"
					origin="sbb-timetable-form-field-to"
					trigger="sbb-timetable-form-field-input-from">
				</sbb-autocomplete>
			</sbb-timetable-form-field>
			<sbb-timetable-form-swap-button></sbb-timetable-form-swap-button>
			<sbb-timetable-form-field id="sbb-timetable-form-field-to">
				<label><?php echo esc_html( $l['to'] ); ?></label>
				<input id="sbb-timetable-form-field-input-to" type="text" name="to" value="<?php echo esc_attr( $atts['to'] ); ?>" />
				<sbb-autocomplete id="sbb-timetable-form-field-autocomplete-to"
					origin="sbb-timetable-form-field-to"
					trigger="sbb-timetable-form-field-input-to">
				</sbb-autocomplete>
			</sbb-timetable-form-field>
			<sbb-timetable-form-details>
				<sbb-form-field width="collapse" size="l" borderless class="sbb-timetable-form-mobile-block">
					<sbb-date-input id="sbb-timetable-form-details-date-input"></sbb-date-input>
					<sbb-datepicker-previous-day class="sbb-timetable-form-mobile-hidden"></sbb-datepicker-previous-day>
					<sbb-datepicker-toggle></sbb-datepicker-toggle>
					<sbb-datepicker-next-day class="sbb-timetable-form-mobile-hidden"></sbb-datepicker-next-day>
					<sbb-datepicker></sbb-datepicker>
				</sbb-form-field>
				<sbb-divider orientation="vertical" class="sbb-timetable-form-mobile-hidden"></sbb-divider>
				<sbb-form-field width="collapse" size="l" borderless>
					<sbb-time-input id="sbb-timetable-form-details-time-input" value="13:30"></sbb-time-input>
				</sbb-form-field>
				<sbb-toggle size="s" name="departure-arrival">
					<sbb-toggle-option id="sbb-timetable-form-details-toggle-option-departure" value="departure"><?php echo esc_html( $l['dep'] ); ?></sbb-toggle-option>
					<sbb-toggle-option id="sbb-timetable-form-details-toggle-option-arrival" value="arrival"><?php echo esc_html( $l['arr'] ); ?></sbb-toggle-option>
				</sbb-toggle>
				<div style="flex-grow: 1;"></div>
				<sbb-button id="sbb-timetable-form-button-submit" type="submit" size="m"><?php echo esc_html( $l['search'] ); ?></sbb-button>
			</sbb-timetable-form-details>
		</sbb-timetable-form>
		<?php
		if ( ! empty( $atts['banner'] ) && isset( $banners[ $atts['banner'] ] ) ) :
			$b = $banners[ $atts['banner'] ];
			?>
		<div class="div-smapi-product-banner">
			<sbb-teaser class="block teaser-product-banner"
				href="<?php echo esc_url( $b['href'] ); ?>"
				alignment="after-centered">
				<img src="<?php echo esc_url( $b['image'] ); ?>"
					slot="image"
					alt="<?php echo esc_attr( $b['alt'] ); ?>"
					class="img-product-banner" />
				<sbb-title level="2" slot="title" visual-level="5"><?php echo esc_html( $b['title'] ); ?></sbb-title>
				<?php echo esc_html( $b['desc'] ); ?>
			</sbb-teaser>
		</div>
		<?php endif; ?>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'sbb_timetable', 'sbb_timetable_shortcode' );

/**
 * Detects whether the current request is inside the Elementor editor or preview.
 */
function sbb_timetable_is_elementor_context() {
	if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
		return false;
	}
	$plugin = \Elementor\Plugin::$instance;
	return $plugin->editor->is_edit_mode() || $plugin->preview->is_preview_mode();
}

/**
 * Detects whether the current page contains the [sbb_timetable] shortcode,
 * checking both classic post_content and Elementor's _elementor_data meta.
 */
function sbb_timetable_page_has_shortcode() {
	global $post;

	if ( ! is_a( $post, 'WP_Post' ) ) {
		return false;
	}

	if ( has_shortcode( $post->post_content, 'sbb_timetable' ) ) {
		return true;
	}

	$elementor_data = get_post_meta( $post->ID, '_elementor_data', true );

	return $elementor_data && false !== strpos( $elementor_data, 'sbb_timetable' );
}

/**
 * Enqueues CSS and JS only on pages containing the shortcode.
 * CSS and JS load in <head> so Web Components have styles before rendering.
 */
function sbb_timetable_enqueue_assets() {
	if ( ! sbb_timetable_page_has_shortcode() && ! sbb_timetable_is_elementor_context() ) {
		return;
	}

	$base    = get_stylesheet_directory_uri() . '/src/vendor/sbb-timetable';
	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'sbb-timetable-widget',
		$base . '/css/smapi-widget.css',
		array(),
		$version
	);

	wp_enqueue_script(
		'sbb-timetable-widget',
		$base . '/js/smapi-widget.js',
		array(),
		$version,
		false
	);
}
add_action( 'wp_enqueue_scripts', 'sbb_timetable_enqueue_assets' );

/**
 * Adds type="module" to the SBB widget script tag.
 *
 * @param string $tag    The script HTML tag.
 * @param string $handle The script handle.
 */
function sbb_timetable_script_type_module( $tag, $handle ) {
	if ( 'sbb-timetable-widget' === $handle ) {
		return str_replace( '<script ', '<script type="module" ', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'sbb_timetable_script_type_module', 10, 2 );
