# Deploy UI fixes ke jnpbali.com

## File yang perlu di-upload

Upload ke `/wp-content/themes/moderno-child/` via FTP / File Manager:

| File | Aksi |
|------|------|
| `ui-fixes.css` | **Upload baru** |
| `header-custom.css` | **Ganti** (opsional — sudah ada perbaikan typography di ui-fixes.css) |
| `functions-snippet.php` | Salin isinya ke `functions.php` child theme |

## functions.php

Tambahkan isi `functions-snippet.php` ke akhir `moderno-child/functions.php`, lalu sesuaikan dependency handle jika perlu:

```php
wp_enqueue_style(
    'jnp-ui-fixes',
    get_stylesheet_directory_uri() . '/ui-fixes.css',
    array(), // atau array('jnp-header-custom')
    '14.0.0'
);
```

Pastikan `ui-fixes.css` dimuat **paling akhir** (priority 99).

## WordPress Customizer — hapus CSS rusak

Buka **Appearance → Customize → Additional CSS** dan **HAPUS** blok ini (penyebab teks payment dobel):

```css
/* Footer payment message unified — v2 fix. */
.jnp-payment-note .elementor-icon-list-text {
  color: transparent !important;
  ...
}
.jnp-payment-note .elementor-icon-list-text::after {
  content: "Payment: PayPal, Credit Card, or Bank Transfer (IDR/USD)";
  ...
}
```

Ganti dengan komentar saja:

```css
/* Payment footer fix moved to ui-fixes.css v14 */
```

## Elementor footer widget (opsional)

Di footer Elementor, edit teks item `.jnp-payment-note` menjadi:

`Payment: PayPal, Credit Card, or Bank Transfer (IDR/USD)`

Lalu CSS `::before` di ui-fixes.css bisa dihapus nanti — teks langsung dari Elementor lebih bersih.

## Setelah deploy

1. Clear cache (LiteSpeed / Cloudflare / plugin cache)
2. Hard refresh browser (Ctrl+Shift+R)
3. Cek footer: payment 1 baris, tidak tumpuk
4. Cek teks abu-abu gelap sudah terang di background hitam
