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
                    foreach ($ssb->ui->btns_order as $btn_key => $btn_id) {
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
                    }

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
