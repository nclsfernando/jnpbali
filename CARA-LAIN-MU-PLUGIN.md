# CARA LAIN — Upload 1 File (Tanpa Plugin WordPress)

Plugin WordPress gagal karena duplikat folder. Pakai **Must-Use Plugin** — cukup upload 1 file, **tidak perlu Activate**.

---

## File yang di-upload

Salin file ini ke server:

**`UPLOAD-ke-mu-plugins-jnpbali-float-cart.php`**

(lokasi di komputer: folder `jnpbali-theme-fix`)

---

## Langkah cPanel (5 menit)

### 1. Buka File Manager → masuk ke:
```
public_html/wp-content/
```

### 2. Buat folder `mu-plugins` jika belum ada
- Klik **+ Folder** → nama: `mu-plugins`

### 3. Masuk folder `mu-plugins` → Upload file
- Upload `UPLOAD-ke-mu-plugins-jnpbali-float-cart.php`
- **Rename** menjadi: `jnpbali-float-cart.php`

Path final harus persis:
```
wp-content/mu-plugins/jnpbali-float-cart.php
```

### 4. Hapus plugin lama (penting!)
Masuk ke `wp-content/plugins/` → **Delete** folder:
- `jnp-floating-cart`
- `jnp-floating-cart-plugin`
- `jnpbali-float-cart` (jika ada)

### 5. Clear cache + refresh
- SpeedyCache → clear cache
- Browser: **Ctrl + Shift + R**

---

## Cara alternatif — lewat Child Theme

Jika mu-plugins sulit, paste 1 baris ke **`moderno-child/functions.php`** (via cPanel Edit):

```php
require_once get_stylesheet_directory() . '/jnpbali-float-cart.php';
```

Lalu upload `jnpbali-float-cart.php` ke folder:
```
wp-content/themes/moderno-child/
```

---

## Hasil yang benar

- Tombol cart **emas bulat** kanan bawah (di atas WhatsApp)
- Panel cart **tidak** tampil saat page load
- Klik tombol → panel cart rapi dengan tombol X putih

---

## Catatan

Must-Use plugin **tidak muncul** di halaman Plugins dan **tidak perlu di-Activate** — langsung jalan setelah file ada di folder `mu-plugins`.
