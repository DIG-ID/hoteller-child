<?php
/**
 * Footer widget titles — muda a tag de <h2> para <p>.
 *
 * Os títulos dos widgets do footer NÃO são gerados no footer.php (que apenas faz
 * dynamic_sidebar('Footer Sidebar')). Vêm do tema-pai, via o filtro
 * `hoteller_wrap_widget_titles` em lib/theme.filter.lib.php, que corre na
 * prioridade 1 e força:
 *
 *     before_title = '<h2 class="widgettitle"><span>'
 *     after_title  = '</span></h2>'
 *
 * Por isso copiar o footer.php para o child theme NÃO mudaria estes títulos.
 * Em vez disso sobrepomo-nos ao filtro do pai (prioridade > 1) apenas na
 * footer-sidebar — fica no child theme e sobrevive a updates do tema-pai.
 *
 * Como o CSS do pai estiliza `h2.widgettitle` (específico da tag), re-aplicamos
 * as mesmas regras a `p.widgettitle` para o aspeto não mudar.
 *
 * @package hoteller-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * 1) Troca a tag dos títulos dos widgets do footer (<h2> -> <p>).
 *
 * Prioridade 20 para correr depois do filtro do tema-pai (prioridade 1) e
 * sobrepor-se. Aplica-se apenas à sidebar 'footer-sidebar'.
 *
 * @param array $params Parâmetros do dynamic_sidebar.
 * @return array
 */
function digid_footer_widget_title_tag( $params ) {
	if ( ! empty( $params[0]['id'] ) && 'footer-sidebar' === $params[0]['id'] ) {
		$params[0]['before_title'] = '<p class="widgettitle"><span>';
		$params[0]['after_title']  = '</span></p>';
	}

	return $params;
}
add_filter( 'dynamic_sidebar_params', 'digid_footer_widget_title_tag', 20 );

/**
 * 2) Tipografia DINÂMICA do título (font-family / font-size / font-weight).
 *
 * O aspeto dos títulos vem de duas fontes no tema-pai, ambas amarradas à tag
 * `h2.widgettitle`:
 *   - Propriedades estáticas (display, color, letter-spacing, text-transform,
 *     margens, etc.) → replicadas no SASS: src/sass/digid-style.sass
 *     (compiladas em dist/css/main.css).
 *   - Tipografia configurável no Customizer (Kirki) → font-family, font-size e
 *     font-weight, guardadas nos theme_mods `tg_sidebar_title_*`.
 *
 * Esta parte fica em PHP (e NÃO no sass) porque depende de valores do WordPress
 * lidos em runtime — o sass compila no build, sem acesso aos theme_mods. Assim a
 * tipografia do footer fica sempre em sincronia com o painel, sem valores fixos.
 *
 * Inline no handle 'digid-styles' (= dist/css/main.css, enfileirado em
 * functions.php @ prio 99), por isso carrega depois e sobrepõe-se ao build.
 */
function digid_footer_widget_title_css() {
	$font   = (string) get_theme_mod( 'tg_sidebar_title_font', 'Renner' );
	$size   = (int) get_theme_mod( 'tg_sidebar_title_font_size', 20 );
	$weight = (int) get_theme_mod( 'tg_sidebar_title_font_weight', 600 );

	// Sanitização defensiva.
	$font = trim( preg_replace( '/[\'";{}]/', '', $font ) );
	if ( '' === $font ) {
		$font = 'Renner';
	}
	if ( $size < 1 ) {
		$size = 20;
	}
	if ( $weight < 100 || $weight > 900 ) {
		$weight = 600;
	}

	$css  = '#footer .sidebar_widget li p.widgettitle{';
	$css .= "font-family:'" . $font . "','Helvetica Neue',Arial,Verdana,sans-serif;";
	$css .= 'font-size:' . $size . 'px;';
	$css .= 'font-weight:' . $weight . ';';
	$css .= '}';

	wp_add_inline_style( 'digid-styles', $css );
}
add_action( 'wp_enqueue_scripts', 'digid_footer_widget_title_css', 100 );
