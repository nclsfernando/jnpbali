# Deploy UI Fix via cPanel — jnpbali.com

Panduan langkah demi langkah untuk memperbaiki kontras teks, layout, dan footer payment.

---

## File yang perlu di-upload

Semua file ada di folder:

`C:\Users\NICHOLAS FERNANDO\Documents\jnpbali-theme-fix\moderno-child\`

| File | Aksi di server |
|------|----------------|
| `ui-fixes.css` | Upload **baru** |
| `jnp-floating-cart.css` | Upload **baru** |
| `jnp-floating-cart.js` | Upload **baru** |
| `jnp-floating-cart.php` | Upload **baru** |
| `header-custom.css` | Ganti file lama (opsional, disarankan) |
| `functions-snippet.php` | Salin isinya ke `functions.php` |

Atau gunakan zip siap pakai: `jnpbali-cpanel-upload.zip`

---

## Langkah 1 — Login cPanel

1. Buka cPanel hosting jnpbali.com (biasanya `https://namadomain.com:2083` atau dari email hosting)
2. Login dengan kredensial hosting (bukan WordPress)

---

## Langkah 2 — Buka File Manager

1. Klik **File Manager**
2. Masuk ke folder website:
   - Biasanya: `public_html/`
   - Atau: `public_html/jnpbali.com/` (jika pakai addon domain)

---

## Langkah 3 — Navigasi ke child theme

Buka folder berurutan:

```
public_html
  └── wp-content
        └── themes
              └── moderno-child
```

Di folder `moderno-child` Anda akan melihat file seperti:
- `style.css`
- `header-custom.css`
- `functions.php`

---

## Langkah 4 — Upload `ui-fixes.css`

1. Klik **Upload** (toolbar atas)
2. Pilih file `ui-fixes.css` dari komputer Anda
3. Tunggu upload selesai → **Go Back**
4. Pastikan `ui-fixes.css` muncul di folder `moderno-child`

---

## Langkah 5 — (Opsional) Ganti `header-custom.css`

1. Klik kanan `header-custom.css` → **Delete** atau rename jadi `header-custom.css.bak`
2. Upload `header-custom.css` yang baru dari folder fix

---

## Langkah 6 — Edit `functions.php`

1. Klik kanan `functions.php` → **Edit**
2. Scroll ke **baris paling bawah**
3. **Sebelum** tag penutup `?>` (jika ada), atau di akhir file, paste isi `functions-snippet.php`:

```php
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'jnp-ui-fixes',
			get_stylesheet_directory_uri() . '/ui-fixes.css',
			array(),
			'14.0.0'
		);
	},
	99
);
```

4. Klik **Save Changes**

> Jangan hapus kode PHP yang sudah ada di `functions.php` — hanya **tambahkan** di akhir.

---

## Langkah 7 — Hapus CSS rusak di WordPress (penting)

Login **WordPress Admin** → **Appearance → Customize → Additional CSS**

Cari dan **HAPUS** blok ini (penyebab teks payment dobel):

```css
.jnp-payment-note .elementor-icon-list-text {
  color: transparent !important;
  ...
}
.jnp-payment-note .elementor-icon-list-text::after {
  content: "Payment: PayPal, Credit Card, or Bank Transfer (IDR/USD)";
  ...
}
```

Ganti dengan komentar:

```css
/* Payment footer fix → ui-fixes.css v14 */
```

Klik **Publish**.

---

## Langkah 8 — Clear cache

1. Di cPanel: jika ada **LiteSpeed Cache** / **Cache Manager** → Purge All
2. Di WordPress: plugin cache (jika ada) → Clear cache
3. Di browser: **Ctrl + Shift + R** (hard refresh)

---

## Langkah 9 — Verifikasi

Buka https://jnpbali.com dan cek:

- [ ] Footer: baris payment **satu baris**, tidak tumpuk
- [ ] Teks di area hitam **putih/terang**, bukan abu-abu gelap
- [ ] Paragraf tidak berantakan (rata kiri, bukan justify aneh)
- [ ] Checkbox form terlihat jelas

Cek file CSS ter-load:
- View Page Source → cari `ui-fixes.css?ver=14.0.0`

---

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Situs error putih setelah edit functions.php | Restore backup `functions.php` via cPanel, perbaiki syntax PHP |
| Perubahan tidak muncul | Clear cache + hard refresh |
| ui-fixes.css tidak ter-load | Pastikan snippet ada di functions.php dan file ada di folder theme |
| Payment masih dobel | Hapus blok `.jnp-payment-note` di Additional CSS (Langkah 7) |

---

## Rollback

Jika perlu kembalikan seperti semula:

1. Hapus kode `jnp-ui-fixes` dari `functions.php`
2. Hapus `ui-fixes.css`
3. Restore `header-custom.css.bak` jika ada backup
