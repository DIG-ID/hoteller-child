<?php
/**
 * Team Members (Elementor) — tags HTML configuráveis para Name e Title.
 *
 * O widget `ever-team-members` (plugin "Team Members for Elementor") gera o nome
 * sempre como <h2> e o título como <h3>, sem opção no editor (ver render() em
 * plugins/team-members-for-elementor/includes/widgets/ever-team-members.php,
 * linhas 626-627). O plugin não tem filtro próprio e o markup é hardcoded.
 *
 * Resolvemos 100% no child theme, via hooks do Elementor (sem tocar no plugin,
 * sobrevive a updates):
 *   1. Injeta dois controlos SELECT ("HTML Tag") na secção de informação.
 *   2. Filtra o HTML renderizado e troca as tags pelas escolhidas.
 *
 * Nota: os estilos de cor/tipografia do plugin usam seletores por CLASSE
 * (`.ee-team-members-name` / `.ee-team-members-title`), não por tag, por isso
 * mudar a tag NÃO afeta a estilização — não é preciso ajustar seletores.
 *
 * @package hoteller-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Tags HTML permitidas (allowlist de segurança).
 *
 * @return array
 */
function digid_tm_allowed_tags() {
	return array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' );
}

/**
 * 1) Adiciona os controlos "HTML Tag" para Name e Title à secção de informação.
 */
add_action(
	'elementor/element/ever-team-members/member_info/before_section_end',
	function ( $element ) {
		$tags    = digid_tm_allowed_tags();
		$options = array();
		foreach ( $tags as $tag ) {
			$options[ $tag ] = strtoupper( $tag );
		}

		$element->add_control(
			'member_name_tag',
			array(
				'label'   => __( 'Name HTML Tag', 'hoteller-child' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => $options,
			)
		);

		$element->add_control(
			'member_title_tag',
			array(
				'label'   => __( 'Title HTML Tag', 'hoteller-child' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => $options,
			)
		);
	},
	10,
	1
);

/**
 * 2) Troca as tags de Name (<h2>) e Title (<h3>) pelas escolhidas, no HTML
 *    renderizado.
 *
 * @param string                 $content Conteúdo HTML do widget.
 * @param \Elementor\Widget_Base $widget  Instância do widget.
 * @return string
 */
add_filter(
	'elementor/widget/render_content',
	function ( $content, $widget ) {
		if ( 'ever-team-members' !== $widget->get_name() ) {
			return $content;
		}

		$settings = $widget->get_settings_for_display();
		$allowed  = digid_tm_allowed_tags();

		$swaps = array(
			// Original => [ setting, default, classe identificadora ].
			'h2' => array( 'member_name_tag', 'h2', 'ee-team-members-name' ),
			'h3' => array( 'member_title_tag', 'h3', 'ee-team-members-title' ),
		);

		foreach ( $swaps as $orig_tag => $info ) {
			list( $setting_key, $default, $class ) = $info;

			$tag = isset( $settings[ $setting_key ] ) ? strtolower( $settings[ $setting_key ] ) : $default;
			if ( ! in_array( $tag, $allowed, true ) ) {
				$tag = $default;
			}
			if ( $tag === $orig_tag ) {
				continue; // Nada a fazer.
			}

			$pattern = '/<' . $orig_tag . '\b([^>]*\b' . preg_quote( $class, '/' ) . '\b[^>]*)>(.*?)<\/' . $orig_tag . '>/s';
			$content = preg_replace( $pattern, '<' . $tag . '$1>$2</' . $tag . '>', $content );
		}

		return $content;
	},
	10,
	2
);
