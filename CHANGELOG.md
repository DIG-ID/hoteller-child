# Changelog — Hoteller Child

Todas as alterações relevantes a este child theme são documentadas aqui.
Formato baseado em [Keep a Changelog](https://keepachangelog.com/), versões em
[Semantic Versioning](https://semver.org/lang/pt-BR/).

> **Nota:** alguns ajustes vivem no **tema-pai** (`hoteller`) e são apagados
> quando o tema-pai é atualizado. Esses estão documentados em
> [`PARENT-PATCHES.md`](PARENT-PATCHES.md) e devem ser reaplicados após updates.

---

## [1.1.1] — 2026-06-17

### Added
- **Team Members (Elementor) — tags HTML configuráveis para Name e Title.**
  O widget `ever-team-members` (plugin "Team Members for Elementor") gerava o
  nome sempre como `<h2>` e o título como `<h3>`, sem opção no editor.
  - Implementado **100% no child theme** via hooks do Elementor — não toca no
    plugin e sobrevive a updates.
  - Dois controlos SELECT ("HTML Tag do Nome" / "HTML Tag do Título", H1–H6,
    div, span, p) injetados via
    `elementor/element/ever-team-members/member_info/before_section_end`;
    a troca de tag é feita em `elementor/widget/render_content`.
  - Os estilos de cor/tipografia do plugin usam seletores por **classe**
    (`.ee-team-members-name` / `.ee-team-members-title`), por isso mudar a tag
    **não afeta** a estilização (ao contrário do Distortion Grid).
  - Ficheiro: [`inc/team-members-tags.php`](inc/team-members-tags.php)
    (incluído via `require_once` em [`functions.php`](functions.php)).

- **Footer — tag dos títulos dos widgets alterada de `<h2>` para `<p>`.**
  Os títulos das colunas do footer eram forçados a `<h2 class="widgettitle">`
  pelo filtro `hoteller_wrap_widget_titles` do **tema-pai**
  (`dynamic_sidebar_params`, prioridade 1). Não vêm do `footer.php`, por isso
  copiar o template não resolveria.
  - Implementado **no child theme**, sem copiar ficheiros: sobrepomos o filtro
    do pai com prioridade 20, apenas na sidebar `footer-sidebar`.
  - O aspeto dos títulos é definido no pai por CSS base **e** por controlos de
    tipografia do Customizer (Kirki), ambos amarrados a `h2.widgettitle`. Para o
    `<p>` ficar idêntico, replicamos esses estilos com um **split**:
    - **Estático** (display, color, letter-spacing, text-transform, margens) →
      `src/sass/digid-style.sass` (compilado em `dist/css/main.css` — requer build).
    - **Dinâmico** (font-family/size/weight) → `wp_add_inline_style` no handle
      `digid-styles`, **lendo os theme_mods** (`tg_sidebar_title_font`,
      `tg_sidebar_title_font_size`, `tg_sidebar_title_font_weight`) para a
      tipografia ficar sempre em sincronia com o Customizer. Fica em PHP (e não no
      sass) porque depende de valores do WordPress lidos em runtime.
  - Ficheiro: [`inc/footer-widget-title-tag.php`](inc/footer-widget-title-tag.php)
    (incluído via `require_once` em [`functions.php`](functions.php)).
  - **Não afetado:** o `<h2>` do photostream (Instagram/Flickr) em `footer.php`,
    que é hardcoded e raramente visível.

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
