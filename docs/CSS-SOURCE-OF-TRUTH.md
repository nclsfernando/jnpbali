# CSS / PHP source of truth (jnpbali)

Keep the repo small and deployable. Prefer theme files over WP Admin paste dumps.

## Canonical layout

| Layer | Path | Role |
| --- | --- | --- |
| Child theme CSS (primary) | `moderno-child/ui-fixes.css` | All lasting UI/layout overrides. Bump enqueue version in `functions-snippet.php` on every meaningful change. |
| Header chrome | `moderno-child/header-custom.css` + `header-custom.js` | Sticky header / mobile nav / lightbox guards only. |
| Feature PHP | `moderno-child/jnp-*.php` | Shop, sizes, floating cart helpers required from `functions-snippet.php`. |
| Plugin (shipping) | `jnp-floating-cart-plugin/` | Preferred packaged floating-cart for install as a normal WP plugin. |

## Do not treat as source of truth

- WP **Additional CSS** (e.g. post 2845) — live paste target only; sync *from* `ui-fixes.css`, never the other way around for Git history.
- Files matching `_wp-2845-*`, `_mcp-*`, `_batch-*`, `_pwtmp/`, `*-v238*` — local automation artifacts.
- Root `PASTE-INTO-WORDPRESS-ADDITIONAL-CSS.css` — one-shot paste helper; migrate useful rules into `ui-fixes.css` then delete when unused.
- Versioned layout dumps like `jnp-layout-mcp-23.x.css` — experiments; fold winners into `ui-fixes.css` and remove stale copies.

## Floating cart copies

Three historical copies exist (`jnp-floating-cart/`, `jnpbali-float-cart/`, `UPLOAD-ke-mu-plugins-*.php`, plus theme embed). **Canonical package:** `jnp-floating-cart-plugin/`. Quarantine/delete the others only after confirming production loads the plugin version.

## PR hygiene

- One CSS change → one file (`ui-fixes.css`) + version bump; avoid mirroring the same rules into Additional-CSS dump files and layout-mcp copies in the same PR.
- Draft audit PRs that duplicate each other: keep the newer superseding PR, close the older as duplicate.
- Never commit secrets, JWT tokens, or MCP payload JSON.
