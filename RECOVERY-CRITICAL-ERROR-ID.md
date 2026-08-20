# Site Down (Critical Error) — Recovery Steps

## Step 1 — Restore site immediately

In cPanel File Manager:

1. Open `wp-content/mu-plugins/`
2. **Rename** `jnpbali-float-cart.php` → `jnpbali-float-cart.php.bak`
3. **Delete** any duplicate files if present:
   - `UPLOAD-ke-mu-plugins-jnpbali-float-cart.php`
   - Any second copy of the cart plugin
4. Refresh https://jnpbali.com — site should load again

## Step 2 — Upload fixed v1.0.3 (only ONE file)

1. Upload: `UPLOAD-ke-mu-plugins-jnpbali-float-cart.php` from this folder
2. **Rename on server** to exactly: `jnpbali-float-cart.php`
3. Confirm **only one** cart file exists in `mu-plugins/`
4. Clear SpeedyCache + hard refresh browser

## Step 3 — Verify

- https://jnpbali.com/shop/ — gold cart button bottom-right
- https://jnpbali.com/product/custom-trip-tee/ — same, click opens panel

## Common causes of critical error

| Cause | Fix |
|-------|-----|
| Two cart PHP files in mu-plugins | Keep only `jnpbali-float-cart.php` |
| Upload truncated / corrupt file | Re-upload v1.0.3 |
| Old plugin still active in Plugins | Deactivate/delete `jnp-floating-cart*` folders |
