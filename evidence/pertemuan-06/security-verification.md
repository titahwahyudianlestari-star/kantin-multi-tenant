# Security Verification

## QR Token
- Token QR dibuat secara acak.
- Server menyimpan hash token, bukan token mentah.
- Token mentah hanya diberikan saat penerbitan.
- Rotate QR mencabut token aktif sebelumnya.
- Token revoked menghasilkan 404 generik.
- Token expired menghasilkan 404 generik.
- ID numerik tidak dapat digunakan untuk enumeration.

## Customer Session
- Session pelanggan dibuat setelah QR valid.
- Session terikat pada dining table dan QR token.
- Cookie customer_session menggunakan HttpOnly.
- Cookie menggunakan SameSite=Lax.
- Session berlaku selama 4 jam.

## Rate Limiting
- Route /q/{token} menggunakan throttle:qr-scan.
