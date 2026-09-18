# Ujian Online (Aplikasi Ujian Esai Berbasis Timer)

Aplikasi Ujian Online Esai berbasis Laravel 12 dengan fitur ujian per soal, timer otomatis, auto-submit, panel manajemen admin, upload soal bergambar, dan rekap penilaian jawaban.

---

## 🚀 Fitur Utama

- **Ujian Berbasis Timer**: Timer otomatis per soal dengan hitung mundur dan auto-submit saat waktu habis.
- **Dukungan Soal Gambar**: Fitur upload gambar untuk ilustrasi soal.
- **Panel Admin**:
  - Manajemen Ujian (Buat, Edit, Hapus, Detail Ujian & Soal).
  - Manajemen Peserta / Jawaban Siswa.
  - Penilaian & Koreksi Esai Manual / Otomatis.
  - Export & Rekapitulasi Nilai.
- **Halaman Peserta**: Tampilan responsif, bersih, dan interaktif untuk siswa mengerjakan ujian.

---

## 🛠️ Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL / MariaDB / SQLite
- Ekstensi PHP: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo

---

## ⚙️ Panduan Instalasi Lokal

1. **Clone repositori**:
   ```bash
   git clone https://github.com/kckdevelop/ujian-esai.git
   cd ujian-esai
   ```

2. **Install Dependensi Composer & NPM**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Sesuaikan pengaturan koneksi database di file `.env`.*

4. **Migrasi Database & Seeder**:
   ```bash
   php artisan migrate --seed
   ```

5. **Buat Storage Link**:
   ```bash
   php artisan storage:link
   ```

6. **Build Asset Frontend**:
   ```bash
   npm run build
   ```

7. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Akses aplikasi di `http://127.0.0.1:8000`.

---

## 🌐 Panduan Deploy ke Production (Server / VPS / Shared Hosting)

1. **Set Environment Production**:
   Pada file `.env` di server production:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domain-anda.com
   ```

2. **Optimasi Cache Laravel**:
   Jalankan perintah berikut untuk performa maksimal:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```

3. **Symlink Storage**:
   ```bash
   php artisan storage:link
   ```

4. **Build Asset**:
   ```bash
   npm ci
   npm run build
   ```

5. **Izin Direktori (Linux / VPS)**:
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

---

## 📄 Lisensi
Open-source di bawah lisensi [MIT](LICENSE).
