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
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$GLOBALS['sbb_timetable_loaded'] = false;

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
			'de' => array( 'title' => 'Swiss Travel Pass', 'desc' => 'Die Schweiz mit einem einzigen Ticket entdecken.' ),
			'fr' => array( 'title' => 'Swiss Travel Pass', 'desc' => 'Découvrez la Suisse avec un seul billet.' ),
			'en' => array( 'title' => 'Swiss Travel Pass', 'desc' => 'Discover Switzerland with just a single ticket.' ),
		),
		'saver-day-pass'    => array(
			'de' => array( 'title' => 'Sparbillett',        'desc' => 'Ab CHF 29.-, solange Vorrat.' ),
			'fr' => array( 'title' => 'Billet Supersaver',  'desc' => 'Dès CHF 29.-, dans la limite des stocks disponibles.' ),
			'en' => array( 'title' => 'Saver Day Pass',     'desc' => 'From CHF 29.-, while stocks last.' ),
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
	$GLOBALS['sbb_timetable_loaded'] = true;

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

	$from_attr = $atts['from'] ? ' value="' . esc_attr( $atts['from'] ) . '"' : '';
	$to_attr   = $atts['to']   ? ' value="' . esc_attr( $atts['to'] ) . '"'   : '';
	$tracking  = $atts['tracking'] ? esc_attr( $atts['tracking'] ) : 'affiliate-tracking-type-placeholder';

	$l       = sbb_timetable_get_labels();
	$banners = sbb_timetable_get_banners( $tracking );

	ob_start();
	?>
	<form id="sbb-timetable-form-wrapper" class="sbb-timetable-form">
		<sbb-signet></sbb-signet>
		<sbb-timetable-form id="sbb-timetable-form">
			<sbb-timetable-form-field id="sbb-timetable-form-field-from">
				<label><?php echo esc_html( $l['from'] ); ?></label>
				<input id="sbb-timetable-form-field-input-from" type="text" name="from"<?php echo $from_attr; ?> />
				<sbb-autocomplete id="sbb-timetable-form-field-autocomplete-from"
					origin="sbb-timetable-form-field-to"
					trigger="sbb-timetable-form-field-input-from">
				</sbb-autocomplete>
			</sbb-timetable-form-field>
			<sbb-timetable-form-swap-button></sbb-timetable-form-swap-button>
			<sbb-timetable-form-field id="sbb-timetable-form-field-to">
				<label><?php echo esc_html( $l['to'] ); ?></label>
				<input id="sbb-timetable-form-field-input-to" type="text" name="to"<?php echo $to_attr; ?> />
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
 * Checks whether assets should be force-loaded regardless of shortcode presence.
 * True inside the Elementor editor or preview iframe.
 */
function sbb_timetable_is_elementor_context() {
	if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
		return false;
	}
	$plugin = \Elementor\Plugin::$instance;
	return $plugin->editor->is_edit_mode() || $plugin->preview->is_preview_mode();
}

// Frontend: inject after shortcode renders (avoids loading on every page).
add_action(
	'wp_footer',
	function () {
		if ( ! $GLOBALS['sbb_timetable_loaded'] && ! sbb_timetable_is_elementor_context() ) {
			return;
		}
		$base = get_stylesheet_directory_uri() . '/src/vendor/sbb-timetable';
		echo '<link rel="stylesheet" href="' . esc_url( $base . '/css/smapi-widget.css' ) . '">' . "\n";
		echo '<script type="module" src="' . esc_url( $base . '/js/smapi-widget.js' ) . '"></script>' . "\n";
	},
	5
);
