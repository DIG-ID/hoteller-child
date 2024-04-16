<?php

function newIcons()
{



	global $ssb;
	// Show on
	if ((isset($ssb->ui->settings['show_on_pages']) && $ssb->ui->settings['show_on_pages'] && get_post_type() == 'page' && !is_front_page()) ||
		(isset($ssb->ui->settings['show_on_posts']) && $ssb->ui->settings['show_on_posts'] && (get_post_type() == 'post')) ||
		(isset($ssb->ui->settings['show_on_frontpage']) && $ssb->ui->settings['show_on_frontpage'] && is_front_page()) || (!empty($ssb->ui->showoncpt) && in_array(get_post_type(), $ssb->ui->showoncpt))
	) {

		// Buttons exists
		if (isset($ssb->ui->buttons['btns'])) {
?>
			<div id="ssb-container" class="<?php
											echo (isset($ssb->ui->settings['btn_pos']) && $ssb->ui->settings['btn_pos'] == 'left') ? 'ssb-btns-left' : 'ssb-btns-right';
											echo (isset($ssb->ui->settings['btn_disable_mobile'])) ? ' ssb-disable-on-mobile' : '';
											echo (isset($ssb->ui->settings['btn_anim']) && $ssb->ui->settings['btn_anim'] == 'slide') ? ' ssb-anim-slide' : '';
											echo (isset($ssb->ui->settings['btn_anim']) && $ssb->ui->settings['btn_anim'] == 'icons') ? ' ssb-anim-icons' : '';
											?>">
				<ul class="<?php echo (isset($ssb->ui->settings['btn_hover']) && $ssb->ui->settings['btn_hover'] == 'light') ? 'ssb-light-hover' : 'ssb-dark-hover'; ?>">
					<?php
					// Buttons loop + ordering
					/*foreach ($ssb->ui->btns_order as $btn_key => $btn_id) {
					?>
						<li id="ssb-btn-<?php echo $btn_id; ?>">
							<p>
								<a href="
								<?php
								if (get_permalink(get_the_ID()) == "https://phwin.ch/en/") {
									echo "https://www.simplebooking.it/ibe/hotelbooking/search?hid=6860&lang=EN&_gl=1";
								} else if (get_permalink(get_the_ID()) == "https://phwin.ch/fr/") {
									echo "https://www.simplebooking.it/ibe/hotelbooking/search?hid=6860&lang=FR&_gl=1";
								} else {
									echo $ssb->ui->buttons['btns'][$btn_id]['btn_link'];
								}
								?>" <?php echo (!empty($ssb->ui->buttons['btns'][$btn_id]['open_new_window'])) ? 'target="_blank"' : '';
									?>>
									<?php

									echo (isset($ssb->ui->buttons['btns'][$btn_id]['btn_icon']) && $ssb->ui->buttons['btns'][$btn_id]['btn_icon']) ? '<span class="' . $ssb->ui->buttons['btns'][$btn_id]['btn_icon'] . '"></span> ' : '';
									if (get_permalink(get_the_ID()) == "https://phwin.ch/en/") {

										switch ($ssb->ui->buttons['btns'][$btn_id]['btn_text']) {
											case "Buchen":
												echo "Book";
												break;
											case "10% Preisvorteil bei Direktbuchung":
												echo "10% price advantage with direct booking";
												break;
											case "Gratis ÖV-Tageskarte":
												echo "Free public transport day pass";
												break;
											case "Gratis Minibar":
												echo "Free minibar";
												break;
											default:
												echo (isset($ssb->ui->buttons['btns'][$btn_id]['btn_text']) && (isset($ssb->ui->settings['btn_anim']) && $ssb->ui->settings['btn_anim'] != 'icons')) ? __($ssb->ui->buttons['btns'][$btn_id]['btn_text'], 'sticky-side-buttons') : ' &nbsp; ';
										}
									} else if (get_permalink(get_the_ID()) == "https://phwin.ch/fr/") {

										switch ($ssb->ui->buttons['btns'][$btn_id]['btn_text']) {
											case "Buchen":
												echo "Réserver";
												break;
											case "10% Preisvorteil bei Direktbuchung":
												echo "10% d'avantage tarifaire avec réservation directe";
												break;
											case "Gratis ÖV-Tageskarte":
												echo "Billet journalier gratuit pour les transports en commun";
												break;
											case "Gratis Minibar":
												echo "Minibar gratuit";
												break;
											default:
												echo (isset($ssb->ui->buttons['btns'][$btn_id]['btn_text']) && (isset($ssb->ui->settings['btn_anim']) && $ssb->ui->settings['btn_anim'] != 'icons')) ? __($ssb->ui->buttons['btns'][$btn_id]['btn_text'], 'sticky-side-buttons') : ' &nbsp; ';
										}
									} else {
										echo (isset($ssb->ui->buttons['btns'][$btn_id]['btn_text']) && (isset($ssb->ui->settings['btn_anim']) && $ssb->ui->settings['btn_anim'] != 'icons')) ? __($ssb->ui->buttons['btns'][$btn_id]['btn_text'], 'sticky-side-buttons') : ' &nbsp; ';
									}

									?>
								</a>
							</p>
						</li>
					<?php
					} */

					// Social Icons
					if (isset($ssb->ui->settings['btn_share'])) {
					?>
						<li class="ssb-share-btn">
							<p>
								<a href="#"><span class="fas fa-share-alt"></span> <?php echo (isset($ssb->ui->settings['btn_anim']) && $ssb->ui->settings['btn_anim'] != 'icons') ? 'Social Share ' : ' &nbsp;&nbsp; '; ?>
								</a>
							</p>
							<div class="ssb-social-popup">
								<a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink() ?>" onclick="window.open(this.href, 'facebook', 'left=60,top=40,width=500,height=500,toolbar=1,resizable=0'); return false;"><span class="fab fa-facebook-f"></span> Facebook</a>
								<a href="https://twitter.com/home?status=<?php the_permalink(); ?>" onclick="window.open(this.href, 'twitter', 'left=60,top=40,width=500,height=500,toolbar=1,resizable=0'); return false;"><span class="fab fa-twitter"></span> Twitter</a>
							</div>
						</li>
					<?php
					}
					?>
				</ul>
			</div>
<?php
		}
	}
}

function remove_plugin_actions()
{
	global $ssb;
	remove_action('wp_footer', array($ssb->ui, 'icons'));
	add_action('wp_footer', 'newIcons');
}
add_action('after_setup_theme', 'remove_plugin_actions');

/**
 * Enqueue scripts and styles.
 */
function digid_theme_name_scripts() {
	//Get the theme data
	$the_theme     = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );
	wp_enqueue_style( 'digid-styles', get_stylesheet_directory_uri() . '/dist/css/main.css', array(), $theme_version );
	wp_enqueue_style( 'aleno-style', 'https://mytools.aleno.me/reservations/v2.0/reservations.css', array(), $theme_version );
	wp_enqueue_script( 'digid-script', get_stylesheet_directory_uri() . '/dist/js/main.js', array(), $theme_version, true );
	wp_enqueue_script( 'aleno-script', 'https://mytools.aleno.me/reservations/v2.0/reservations.js', array(), $theme_version, true );

}
add_action( 'wp_enqueue_scripts', 'digid_theme_name_scripts', 99 );

/**
 * Advantages shortcode
 *
 * @param [type] $atts
 * @param string $content
 * @return void
 */
function digid_advantages_func( $atts, $content = '' ) {
	$sc_adv_icon_check = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>';
	$sc_adv_icon_booking = '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13 10"><path d="M8.802 0c-.938 0-1.698.77-1.698 1.719v.57L.242 7.63a.632.632 0 0 0-.114.877.615.615 0 0 0 .867.115L2.435 7.5h2.569l.9 2.209c.096.24.368.354.603.256a.472.472 0 0 0 .253-.611L6.005 7.5H6.857l.899 2.21c.097.241.369.354.604.257a.472.472 0 0 0 .253-.612l-.81-1.992A3.75 3.75 0 0 0 10.5 3.75V2.344l1.554-.393a.31.31 0 0 0 .223-.379A1.238 1.238 0 0 0 11.08.625h-.967A1.693 1.693 0 0 0 8.803 0Zm0 1.25a.458.458 0 0 1 .428.29.474.474 0 0 1-.25.612.458.458 0 0 1-.606-.254.474.474 0 0 1 .25-.612.458.458 0 0 1 .178-.036Z" fill="#2795A2"/></svg>';
	$sc_adv_code = '<ul class="digid-advantages-sc">';
	$sc_adv_code .= '<li>' . $sc_adv_icon_check . esc_html__( '10% Preisvorteil bei Direktbuchung', 'hoteller' ) . '</li>';
	$sc_adv_code .= '<li>' . $sc_adv_icon_check . esc_html__( 'Gratis ÖV-Tageskarte', 'hoteller' ) . '</li>';
	$sc_adv_code .= '<li>' . $sc_adv_icon_check . esc_html__( 'Gratis Minibar', 'hoteller' ) . '</li>';
	$sc_adv_code .= '<li class="advantages-booking-button-wrapper">';
	$digid_site_lang = apply_filters( 'wpml_current_language', null );
	switch ($digid_site_lang) :
		case 'de' :
			$sc_adv_code .= '<a class="advantages-booking-button" href="https://www.simplebooking.it/ibe2/hotel/6860?lang=DE&cur=CHF" target="_blank">' . $sc_adv_icon_booking . esc_html( 'Jetzt Buchen' ) . '</a>';
			break;
		case 'en' :
			$sc_adv_code .= '<a class="advantages-booking-button" href="https://www.simplebooking.it/ibe2/hotel/6860?lang=EN&cur=CHF" target="_blank">' . $sc_adv_icon_booking . esc_html( 'Book now' ) . '</a>';
			break;
		case 'fr' :
			$sc_adv_code .= '<a class="advantages-booking-button" href="https://www.simplebooking.it/ibe2/hotel/6860?lang=FR&cur=CHF" target="_blank">' . $sc_adv_icon_booking . esc_html( 'Réserver' ) . '</a>';
			break;
	endswitch;
	$sc_adv_code .= '</li>';
	$sc_adv_code .= '</ul>';

	$content = $sc_adv_code;

	return $content;
}
add_shortcode( 'advantages', 'digid_advantages_func' );
