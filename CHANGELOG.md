# Changelog — Hoteller Child

Todas as alterações relevantes a este child theme são documentadas aqui.
Formato baseado em [Keep a Changelog](https://keepachangelog.com/), versões em
[Semantic Versioning](https://semver.org/lang/pt-BR/).

> **Nota:** alguns ajustes vivem no **tema-pai** (`hoteller`) e são apagados
> quando o tema-pai é atualizado. Esses estão documentados em
> [`PARENT-PATCHES.md`](PARENT-PATCHES.md) e devem ser reaplicados após updates.

---

## [1.1.0] — 2026-06-12

### Added
- **Distortion Hover Grid — seletor de tag HTML do título.**
  O widget Elementor `hoteller-distortion-grid` (plugin `hoteller-elementor`)
  gerava o título sempre como `<h2>`, sem opção no editor. Adicionado um
  controlo **"HTML Tag do Título"** (H1–H6, div, span, p) que altera a tag de
  output de todos os slides do widget.
  - Implementado **inteiramente no child theme** via hooks do Elementor — não
    toca no plugin e sobrevive a updates do plugin.
  - Os seletores de **Cor** e **Tipografia** do título do plugin (que fixavam
    `h2.distortion_grid_item-title`) foram tornados independentes da tag, para
    que a estilização continue a aplicar a qualquer tag escolhida.
  - Ficheiro: [`inc/distortion-grid-title-tag.php`](inc/distortion-grid-title-tag.php)
    (incluído via `require_once` em [`functions.php`](functions.php)).
  - **Detalhes técnicos:**
    - `elementor/element/hoteller-distortion-grid/section_content/before_section_end`
      → injeta o controlo `title_html_tag`.
    - `elementor/element/hoteller-distortion-grid/section_link_style/before_section_end`
      → `update_control` / `update_group_control` para seletores tag-agnósticos.
    - `elementor/widget/render_content` → troca `<h2 class="distortion_grid_item-title">`
      pela tag escolhida (com allowlist de segurança).
  - **Notas:** a tag aplica-se ao widget inteiro (todos os slides). O preview do
    editor pode continuar a mostrar `<h2>`; o frontend reflete a tag escolhida.
    Após instalar, fazer **Elementor → Tools → Regenerate CSS & Data** se a opção
    não aparecer de imediato.

### Fixed
- **Kirki — notices de "Creation of dynamic property" deprecated (PHP 8.2+).**
  O módulo Kirki incluído no **tema-pai** emitia dezenas de notices
  `Deprecated: Creation of dynamic property Kirki_Field_*::$label/$description`.
  Corrigido com `#[\AllowDynamicProperties]` na classe base `Kirki_Field`.
  - ⚠️ Este patch vive no **tema-pai** (`hoteller/modules/kirki/core/class-kirki-field.php`)
    e **é apagado a cada update do tema-pai**. Reaplicar conforme
    [`PARENT-PATCHES.md`](PARENT-PATCHES.md) (Patch #1).

### Changed
- Versão do tema: `1.0` → `1.1.0` (também faz cache-busting dos assets via
  `wp_enqueue_*`).

---

## [1.0] — anterior

- Versão base do child theme (pré-changelog): shortcode `[advantages]`,
  ícones sociais (`newIcons`), enqueue de assets `dist/`, integração Aleno e
  shortcode da timetable SBB.
