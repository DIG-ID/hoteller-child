# Parent Theme Patches

Este ficheiro documenta alterações feitas **manualmente no tema-pai** (`hoteller`).
Estas alterações **NÃO** ficam no child-theme e são **apagadas sempre que o tema-pai
é atualizado**. Após cada atualização do tema `hoteller`, voltar a aplicar os patches
listados abaixo.

> Histórico de versões do child theme em [`CHANGELOG.md`](CHANGELOG.md).
> O Patch #1 abaixo corresponde à entrada "Fixed" da versão 1.1.0.

---

## Patch #1 — Kirki: silenciar deprecations de "dynamic property" (PHP 8.2+)

**Data:** 2026-06-12

### Problema
No PHP 8.2+ o site emite dezenas de notices como:

```
Deprecated: Creation of dynamic property Kirki_Field_Image::$label is deprecated
  in .../themes/hoteller/modules/kirki/core/class-kirki-field.php on line 291
Deprecated: Creation of dynamic property Kirki_Field_Toggle::$description is deprecated ...
... (e variações para $label / $description em vários Kirki_Field_*)
```

**Causa:** o construtor de `Kirki_Field` percorre todos os argumentos e faz
`$this->$key = $value;` (linha ~291), incluindo args como `label` e `description`
que não estão declarados como propriedades da classe. O PHP 8.2+ marca isso como
criação de propriedade dinâmica (deprecated).

### Ficheiro a alterar
```
wp-content/themes/hoteller/modules/kirki/core/class-kirki-field.php
```
(tema-pai — **não** existe no child-theme)

### Onde / o quê
Adicionar o atributo `#[\AllowDynamicProperties]` **imediatamente antes** da
declaração da classe base `Kirki_Field`. O atributo é herdado por todas as
subclasses (`Kirki_Field_Image`, `Kirki_Field_Toggle`, `Kirki_Field_Color`,
`Kirki_Field_Slider`, etc.), por isso uma única linha resolve todos os notices.
Em PHP < 8.0 a linha `#[...]` é interpretada como comentário, logo é
retrocompatível.

**Antes:**
```php
/**
 * Please do not use this class directly.
 * You should instead extend it per-field-type.
 */
class Kirki_Field {
```

**Depois:**
```php
/**
 * Please do not use this class directly.
 * You should instead extend it per-field-type.
 */
#[\AllowDynamicProperties]
class Kirki_Field {
```

### Verificar
```bash
php -l wp-content/themes/hoteller/modules/kirki/core/class-kirki-field.php
# -> No syntax errors detected
```

### Alternativa durável (não depende do tema-pai)
Manter os notices escondidos a nível de configuração, em `wp-config.php`:
desligar `WP_DEBUG_DISPLAY` (e/ou enviar deprecations para log em vez de os
mostrar). Isto não corrige a causa, mas sobrevive às atualizações do tema-pai.
