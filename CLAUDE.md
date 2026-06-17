# CLAUDE.md — Hoteller Child

Instruções para trabalhar neste child theme. **Ler antes de qualquer alteração.**

## Contexto

- Child theme do tema-pai **`hoteller`** (em `../hoteller/`).
- Plugin acompanhante relevante: **`hoteller-elementor`** (`../../plugins/hoteller-elementor/`),
  que regista widgets Elementor (ex.: `hoteller-distortion-grid`).
- Build de assets: **Laravel Mix + Tailwind** (`webpack.mix.js`, `tailwind.config.js`).
  - Fontes em `src/`, output em `dist/css/main.css` e `dist/js/main.js`.
  - **Nunca editar `dist/` à mão** — é gerado. Editar `src/` e fazer build, ou
    usar `wp_add_inline_style()` / `wp_add_inline_script()` para overrides pequenos.
- PHP do child theme está organizado em `functions.php` + ficheiros em `inc/`
  (incluídos via `require_once get_stylesheet_directory() . '/inc/...'`).

## Princípio central: patches sempre no child theme

Quando algo precisa de mudar no comportamento do tema-pai ou de um plugin:

1. **Preferir sempre resolver a partir do child theme** (filtros, actions, hooks,
   CSS/JS inline). Sobrevive a updates do pai/plugin.
2. **Não copiar templates do pai** só para mudar uma linha — verifica primeiro
   se há um filtro/hook que permita o override (muitos títulos/markups vêm de
   filtros como `dynamic_sidebar_params`, não dos templates).
3. **Editar ficheiros do tema-pai ou de plugins é último recurso.** Se for mesmo
   inevitável, documentar em [`PARENT-PATCHES.md`](PARENT-PATCHES.md) para
   reaplicar após cada update (esses patches são apagados nos updates).

Padrões já em uso (ver `inc/`):
- `inc/distortion-grid-title-tag.php` — injeta controlo Elementor + filtra
  `elementor/widget/render_content` (override de plugin, 100% no child theme).
- `inc/footer-widget-title-tag.php` — sobrepõe filtro do pai
  (`dynamic_sidebar_params`) + CSS inline (override de tema-pai, no child theme).

## Versionamento e changelog (OBRIGATÓRIO)

- **Toda a alteração funcional tem de ser registada em [`CHANGELOG.md`](CHANGELOG.md)**,
  formato [Keep a Changelog](https://keepachangelog.com/) +
  [SemVer](https://semver.org/lang/pt-BR/).
- Enquanto uma versão está **em curso** (vão entrar mais alterações), manter a
  secção marcada como **"Não lançado (em curso)"** e ir acrescentando entradas.
  Datar e considerar lançada só quando o conjunto de intervenções fecha.
- Ao fechar a versão: atualizar a data no `CHANGELOG.md` **e** o `Version:` no
  [`style.css`](style.css) (também faz cache-busting dos assets via `wp_enqueue_*`).
- Agrupar entradas em `Added` / `Changed` / `Fixed` / `Removed`.
- Para cada entrada: o quê, porquê, ficheiros tocados e notas relevantes.

## Checklist antes de terminar uma intervenção

1. Lógica no child theme (não no pai/plugin), via hooks sempre que possível.
2. Código novo em `inc/` + `require_once` no `functions.php`, seguindo o padrão.
3. `php -l` sem erros de sintaxe nos ficheiros tocados.
4. Entrada no `CHANGELOG.md` (na secção em curso).
5. Se houve patch ao tema-pai/plugin: registado em `PARENT-PATCHES.md`.
6. Strings de output escapadas (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`).

## Notas

- Site multilingue: usa **WPML** (e há suporte a Polylang no tema-pai). Ter em
  conta em strings/conteúdos traduzíveis.
- `git`: branch principal é `main`. Commits só quando pedido. Mensagens de commit
  no estilo já usado (ver histórico: `release: vX.Y.Z`, `feat(...)`, `docs:`).
