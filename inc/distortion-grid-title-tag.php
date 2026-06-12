<?php
/**
 * Distortion Hover Grid — Title HTML tag selector.
 *
 * O widget "Distortion Hover Grid" do plugin `hoteller-elementor` gera o título
 * sempre como <h2> (hardcoded em templates/distortion-grid/index.php) e não
 * oferece opção para escolher a tag. Como o plugin não tem nenhum filtro e o
 * template é incluído por caminho fixo (não há override por child theme), fazemos
 * tudo via hooks do Elementor — sem tocar no plugin, sobrevive a updates.
 *
 * O que isto faz:
 *   1. Injeta um controlo SELECT "HTML Tag" no widget (H1–H6, div, span, p).
 *   2. Torna os seletores CSS de Cor/Tipografia do título independentes da tag,
 *      para que a estilização do plugin continue a aplicar a qualquer tag.
 *   3. Filtra o HTML renderizado e troca <h2 class="distortion_grid_item-title">
 *      pela tag escolhida.
 *
 * @package hoteller-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Tags HTML permitidas para o título (allowlist de segurança).
 *
 * @return array
 */
function digid_dg_title_allowed_tags() {
	return array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' );
}

/**
 * 1) Adiciona o controlo "HTML Tag" do título à secção de conteúdo do widget.
 */
add_action(
	'elementor/element/hoteller-distortion-grid/section_content/before_section_end',
	function ( $element ) {
		$tags    = digid_dg_title_allowed_tags();
		$options = array();
		foreach ( $tags as $tag ) {
			$options[ $tag ] = strtoupper( $tag );
		}

		$element->add_control(
			'title_html_tag',
			array(
				'label'       => __( 'HTML Tag do Título', 'hoteller-child' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'h2',
				'options'     => $options,
				'description' => __( 'Tag usada para o título de cada item (aplica-se a todos os slides).', 'hoteller-child' ),
			)
		);
	},
	10,
	1
);

/**
 * 2) Torna os seletores de Cor/Tipografia do título independentes da tag.
 *
 * Os controlos originais do plugin fixam "h2.distortion_grid_item-title" nos
 * seus seletores (controlo de Cor + sub-controlos do group de Tipografia). Ao
 * remover o prefixo "h2" os estilos continuam a aplicar seja qual for a tag.
 *
 * Em vez de atualizar cada controlo individualmente (frágil entre versões do
 * Elementor, sobretudo para group controls), percorremos todos os controlos já
 * registados e substituímos a tag fixa por um seletor apenas-de-classe. Corre na
 * última secção, quando todos os controlos de estilo já estão no stack.
 */
add_action(
	'elementor/element/hoteller-distortion-grid/section_link_style/before_section_end',
	function ( $element ) {
		foreach ( $element->get_controls() as $control_id => $control ) {
			if ( empty( $control['selectors'] ) || ! is_array( $control['selectors'] ) ) {
				continue;
			}

			$changed       = false;
			$new_selectors = array();
			foreach ( $control['selectors'] as $selector => $css ) {
				$new_selector = str_replace(
					'h2.distortion_grid_item-title',
					'.distortion_grid_item-title',
					$selector
				);
				if ( $new_selector !== $selector ) {
					$changed = true;
				}
				$new_selectors[ $new_selector ] = $css;
			}

			if ( $changed ) {
				$element->update_control( $control_id, array( 'selectors' => $new_selectors ) );
			}
		}
	},
	10,
	1
);

/**
 * 3) Troca a tag <h2> do título pela escolhida, no HTML renderizado.
 *
 * @param string                  $content Conteúdo HTML do widget.
 * @param \Elementor\Widget_Base  $widget  Instância do widget.
 * @return string
 */
add_filter(
	'elementor/widget/render_content',
	function ( $content, $widget ) {
		if ( 'hoteller-distortion-grid' !== $widget->get_name() ) {
			return $content;
		}

		$settings = $widget->get_settings_for_display();
		$tag      = isset( $settings['title_html_tag'] ) ? strtolower( $settings['title_html_tag'] ) : 'h2';

		// Validação contra a allowlist; default seguro.
		if ( ! in_array( $tag, digid_dg_title_allowed_tags(), true ) ) {
			$tag = 'h2';
		}

		// Nada a fazer se já é h2.
		if ( 'h2' === $tag ) {
			return $content;
		}

		// Troca apenas os <h2> com a classe do título (mantém o atributo class intacto).
		$content = preg_replace(
			'/<h2(\s+class="distortion_grid_item-title"[^>]*)>(.*?)<\/h2>/s',
			'<' . $tag . '$1>$2</' . $tag . '>',
			$content
		);

		return $content;
	},
	10,
	2
);
