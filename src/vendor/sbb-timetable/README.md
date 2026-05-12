# SBB Timetable Widget

Widget oficial da SBB (CFF/FFS) para pesquisa de horários e ligações ferroviárias.
Integrado como shortcode WordPress com suporte a WPML (DE/EN/FR) e WP Rocket.

## Ficheiros

```
src/vendor/sbb-timetable/
├── css/
│   └── smapi-widget.css     # Estilos do widget (pré-compilados pela SBB)
├── js/
│   └── smapi-widget.js      # Lógica do widget (ES module, pré-compilado pela SBB)
├── images/
│   ├── swiss-travel-pass.jpg  # Imagem do banner Swiss Travel Pass
│   └── saver-day-pass.jpg     # Imagem do banner Saver Day Pass
└── README.md
```

A lógica de integração (shortcode, multilingue, enqueue) está em:
`inc/sbb-timetable-shortcode.php`

## Shortcode

Registado como `[sbb_timetable]`. Usar via widget **Shortcode** do Elementor.

### Parâmetros

| Parâmetro  | Obrigatório | Valores possíveis                        | Descrição                              |
|------------|-------------|------------------------------------------|----------------------------------------|
| `from`     | Não         | Texto livre (ex: `Zürich HB`)            | Pré-preenche o campo de origem         |
| `to`       | Não         | Texto livre (ex: `Bern`)                 | Pré-preenche o campo de destino        |
| `banner`   | Não         | `swiss-travel-pass` / `saver-day-pass`   | Mostra o product banner abaixo do form |
| `tracking` | Não         | Código fornecido pela SBB                | Código de affiliate tracking           |

### Exemplos

```
[sbb_timetable]

[sbb_timetable from="Zürich HB"]

[sbb_timetable banner="swiss-travel-pass" tracking="PHWIN_CODE"]

[sbb_timetable from="Zürich HB" to="Bern" banner="saver-day-pass" tracking="PHWIN_CODE"]
```

## Multilingue (WPML)

Os labels do formulário e os textos dos banners são traduzidos automaticamente
com base na língua activa do WPML. Não é necessária configuração adicional.

| Elemento         | DE            | FR            | EN       |
|------------------|---------------|---------------|----------|
| Campo origem     | Von           | De            | From     |
| Campo destino    | Nach          | À             | To       |
| Toggle partida   | Ab            | Dép           | Dep      |
| Toggle chegada   | An            | Arr           | Arr      |
| Botão pesquisa   | Suchen        | Chercher      | Search   |
| URL do banner    | `/de/angebote/` | `/fr/offres/` | `/en/offers/` |

Para adicionar uma nova língua, editar as funções `sbb_timetable_get_labels()`
e `sbb_timetable_get_banners()` em `inc/sbb-timetable-shortcode.php`.

## Carregamento de assets

O CSS e JS **só carregam na página que contém o shortcode** — não há impacto
de performance noutras páginas. No editor/preview do Elementor os assets
carregam sempre para garantir visualização correcta.

O script usa `type="module"` (ES module). Por este motivo é injectado
directamente no `wp_footer` e **não passa pelo sistema de enqueue do WordPress**,
o que evita que o WP Rocket o atrase, minifique ou combine com outros scripts.

## Atualizar os ficheiros do widget

Quando a SBB disponibilizar uma nova versão do widget:

1. Substituir `css/smapi-widget.css` e `js/smapi-widget.js` pelos novos ficheiros do `dist/` fornecido pela SBB.
2. Verificar se o HTML do form no shortcode (`inc/sbb-timetable-shortcode.php`) precisa de ser atualizado comparando com o novo `index.html` da SBB.
3. Testar nas três línguas (DE/EN/FR) e em mobile.

## Atualizar as imagens dos banners

As imagens estão em `images/` e são servidas localmente para evitar dependência
de CDNs externas. Se a SBB atualizar as imagens de campanha, substituir os
ficheiros mantendo os mesmos nomes:

- `swiss-travel-pass.jpg`
- `saver-day-pass.jpg`

## Affiliate Tracking

O código de tracking é fornecido pela SBB ao parceiro. Deve ser passado via
parâmetro `tracking` do shortcode. Se omitido, os URLs de banner ficam com o
placeholder `affiliate-tracking-type-placeholder` (funcionais mas sem tracking).
