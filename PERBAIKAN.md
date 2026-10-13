# Catatan perbaikan

Project ini merupakan salinan latihan dengan perbaikan L1–L9 pada `app/Http/Controllers/Api/PendaftaranOsisController.php`.

## Setup
1. Pastikan PHP 8.2+ dan Composer 2 tersedia (`php -v`, `composer -V`).
2. `composer install`
3. Salin `.env.example` menjadi `.env`, lalu sesuaikan koneksi MySQL.
4. `php artisan key:generate`
5. Buat database `pendaftaran_osis`.
6. `php artisan migrate --seed`
7. `php artisan serve`

Collection Postman ada di `docs/postman/lat-rest-api-2.postman_collection.json`. Sesuaikan variable ID dengan data lokal sebelum menjalankan semua skenario.

## Perbaikan yang dilakukan
- L1: daftar pendaftaran eager-load relasi siswa, kelas, dan jenis kelamin.
- L2: validasi `siswa_id` dengan `exists:siswa,id`.
- L3: validasi duplikasi `siswa_id` dengan `unique:pendaftaran_osis,siswa_id`.
- L4: membatasi `pilihan_bidang` ke empat pilihan yang ditentukan README.
- L5: validasi `motivasi` antara 20–300 karakter.
- L6: POST berhasil mengembalikan HTTP 201.
- L7: PUT mengecualikan record saat ini dari aturan unique.
- L8: PUT menyimpan ketiga field yang divalidasi.
- L9: DELETE benar-benar menghapus record lalu mengembalikan 204.

## Catatan
Folder `vendor/` dan `.git/` tidak disertakan di ZIP agar ukuran lebih kecil. Jalankan `composer install` di komputer lokal untuk memasang dependency. File `.env` tidak disertakan karena berisi konfigurasi lokal/rahasia.
