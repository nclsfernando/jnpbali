# Install Floating Cart — TANPA Plugin (Must-Use)

Cara ini **tidak perlu Activate** di WordPress. Cukup upload 1 file lewat cPanel.

---

## Langkah 1 — Login cPanel

Buka cPanel hosting jnpbali.com.

---

## Langkah 2 — File Manager

1. Buka **File Manager**
2. Masuk ke: `public_html/wp-content/`

---

## Langkah 3 — Buat folder `mu-plugins` (jika belum ada)

1. Di dalam `wp-content`, klik **+ Folder**
2. Nama folder: **`mu-plugins`**
3. Jika folder sudah ada, skip langkah ini

---

## Langkah 4 — Upload file

1. Buka folder **`mu-plugins`**
2. Klik **Upload**
3. Upload file dari komputer:

   `C:\Users\NICHOLAS FERNANDO\Documents\jnpbali-theme-fix\jnpbali-float-cart.php`

   Atau unzip `UPLOAD-jnpbali-float-cart.zip` dan upload `jnpbali-float-cart.php`

4. Pastikan path akhirnya:

   ```
   public_html/wp-content/mu-plugins/jnpbali-float-cart.php
   ```

   **Penting:** file langsung di dalam `mu-plugins`, BUKAN di subfolder.

---

## Langkah 5 — (Opsional) Hapus plugin lama yang error

Di `public_html/wp-content/plugins/` hapus folder:

- `jnp-floating-cart`
- `jnp-floating-cart-plugin`
- `jnpbali-float-cart` (jika ada)

---

## Langkah 6 — Cek situs

1. Buka https://jnpbali.com
2. Hard refresh: **Ctrl + Shift + R**
3. Tombol cart emas muncul kanan bawah (di atas WhatsApp)
4. Klik tombol → panel cart terbuka rapi

---

## Kenapa cara ini lebih aman?

| Plugin biasa | Must-Use (mu-plugins) |
|--------------|------------------------|
| Perlu Install + Activate | **Langsung jalan** |
| Bisa duplikat versi | **1 file saja** |
| Sering error path | **Tidak ada tombol activate** |

---

## Troubleshooting

**Tidak muncul tombol cart?**
- Pastikan WooCommerce aktif
- Clear cache (SpeedyCache / browser)
- Cek file ada di path yang benar

**Masih tampil layout rusak?**
- Hapus plugin cart lama di folder `plugins/`
- Hard refresh lagi
