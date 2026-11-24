# DESIGN.md

Fondasi desain untuk **website LSP Abdi Nusantara** (lembaga sertifikasi profesi) dan **panel admin
CMS**-nya, termasuk halaman auth dan pengaturan akun. Dokumen ini adalah sumber tunggal untuk
token, tipografi, bentuk, tata letak, dan gerak. Kode di `resources/css/app.css` dan komponen UI
mengikuti dokumen ini. Bila keduanya berbeda, perbarui salah satunya sampai sama lagi.

Arah ini menggantikan arah-arah lama (Spotify untuk website, Metronic untuk admin, lalu Apple dan
laguna). Alasannya: dua identitas untuk satu produk, dan semuanya meniru produk lain. Sekarang
warna datang dari **logo referensi pemilik proyek** (biru merek, biru langit, emas), dan tata letak admin mengikuti
referensi dashboard bersidebar dari pemilik proyek, dengan motif khas sendiri (ornamen guilloche dan sertifikat).

Design Read: situs lembaga sertifikasi untuk calon peserta uji kompetensi (website) dan staf yang
mengelola konten (admin), berbahasa visual biru-emas yang resmi dan tenang dengan motif sertifikat,
dial di bagian Dial.

## Konteks produk

- Pengguna website: calon peserta, asesor, dan pihak industri yang mencari skema sertifikasi,
  alur, jadwal uji, dan pengumuman.
- Pengguna admin: editor dan admin lembaga yang mengelola berita, slide beranda, halaman
  Tentang, pengguna, dan peran.
- **Semua data di UI saat ini adalah contoh** (`app/Support/Demo/DemoContent.php`) dan diberi
  label "Data contoh" yang terlihat. Tidak ada klaim izin, akreditasi, atau angka tanpa sumber.
  Skema, jenjang, dan jumlah unit kompetensi adalah placeholder sampai modul skema sungguhan ada.
- **Logo contoh:** logo dibuat sebagai contoh (belum logo resmi) dan bisa diganti kapan saja.
  Lambangnya adalah cap bergerigi emas, sama dengan cap pada sertifikat, berisi cakram biru
  dengan tanda centang. Wordmark: "LSP Abdi Nusantara" serif (Literata) dan keterangan
  "Lembaga Sertifikasi Profesi". Aset: `public/logo.svg` (untuk latar terang), `public/logo-light.svg`
  (latar gelap), `public/logo-mark.svg` (lambang saja), `public/favicon.svg`. Di antarmuka dipakai
  komponen `LogoMark.vue` dan `AppLogo.vue`. Teks pada SVG memakai fon cadangan, jadi ubah ke
  outline di perangkat desain sebelum dicetak. Untuk mengganti dengan logo resmi, timpa berkas
  di `public/` dan isi `LogoMark.vue`.
- **Hex diperkirakan dari gambar logo referensi**, bukan dari berkas desain. Bila lembaga punya panduan
  merek dengan kode warna resmi, ganti nilai di bagian Palet dan di `app.css`.

## Identitas

- **Dokumen sebagai tema.** Lembaga sertifikasi menerbitkan satu benda: sertifikat. Bahasa visual
  diambil dari benda itu, bukan dari template SaaS: judul serif formal, ornamen guilloche, cap
  emas, dan daftar unit kompetensi yang tercentang. Tampilannya sengaja tenang dan resmi, bukan
  ramah-anak: radius kecil, tombol persegi, tanpa kemiringan dan pantulan.
- **Biru merek dan emas.** Biru `#2563a8` (dari logo referensi) untuk
  tombol dan tautan, navy `#0f2a4a` untuk teks, sidebar, dan tile gelap, biru langit `#5db4f0` hanya di
  mode gelap dan segmen bagan. **Satu aksen emas** `#f2b01e` (dari logo referensi)
  muncul sebagai penanda: halaman aktif di navigasi, tanda logo, garis ornamen, garis di atas
  jenjang KKNI yang punya skema, dan cap pada sertifikat. Aksen tidak dipakai di tombol, tautan,
  atau latar kartu.
- **Aturan guilloche (motif identitas).** Dua garis sinus berlawanan fase yang saling menjalin,
  seperti cetak pengaman di tepi sertifikat (`.rule-guilloche`). Dipakai sebagai pembatas antar
  bagian besar di website, dan hiasan di sertifikat. Di hero ada
  **rosette** cincin konsentris tipis (`.hero-rosette`) yang memudar ke tepi.
- Tidak ada emoji di UI. Tidak ada foto palsu: konten tanpa gambar memakai sampul tipografis.

## Palet

Inti: navy, biru merek, dan netral biru-es/putih. Aksen: emas. Netral tidak dihitung ke inti (R-29).

| Token             | Terang                  | Gelap                      | Peran                                                       |
| ----------------- | ----------------------- | -------------------------- | ----------------------------------------------------------- |
| frame / sidebar   | `#0f2a4a`               | `#081526`                  | Sidebar dan bilah navigasi, selalu gelap                    |
| background        | `#ffffff`               | `#0a1a2e`                  | Kanvas website                                              |
| ground admin      | `#eef4fb`               | `#0a1a2e`                  | Latar panel admin                                           |
| section / muted   | `#eef4fb`               | `#0f2240`                  | Tile selang-seling, footer, header tabel                    |
| card              | `#ffffff`               | `#12294a`                  | Kartu dan panel                                             |
| accent (hover)    | `#e1ebf7`               | `#183560`                  | Hover baris dan item                                        |
| tile gelap        | `#12365f`               | `#12365f`                  | Tile gelap website (hero, alur sertifikasi)                 |
| foreground (navy) | `#0f2a4a`               | `#eaf2fb`                  | Teks utama                                                  |
| muted-foreground  | `#4a5f78`               | `#9db6d1`                  | Teks sekunder                                               |
| primary (biru)    | `#2563a8` (teks `#fff`) | `#5db4f0` (teks `#0a1a2e`) | Tombol utama                                                |
| link              | `#2563a8`               | `#7cc4ff`                  | Tautan teks                                                 |
| highlight (emas)  | `#f2b01e`               | `#f2b01e`                  | Aksen tunggal, lihat Identitas                              |
| ring              | `#2563a8`               | `#7cc4ff`                  | Cincin fokus keyboard 2px (di sidebar dan tile gelap: emas) |
| border (hairline) | `#d5e1ef`               | `rgb(255 255 255 / .14)`   | Garis kartu dan tabel                                       |
| input border      | `#6b84a0`               | `#5f7a99`                  | Batas input, rasio >= 3:1                                   |
| destructive       | `#c4321c`               | `#ff8a75`                  | Hapus, error                                                |
| success           | `#2a7d2e`               | `#6fd37a`                  | Berhasil, aktif                                             |
| warning           | `#8a5a00`               | `#f2b01e`                  | Peringatan                                                  |
| info              | `#2563a8`               | `#7cc4ff`                  | Informasi (memakai biru merek)                              |

Kontras terverifikasi (WCAG): putih di `#2563a8` 6.12:1, `#2563a8` di `#eef4fb` 5.53:1,
`#4a5f78` di `#eef4fb` 5.93:1, `#9db6d1` di `#12365f` 5.85:1 dan di `#12294a` 6.97:1,
`#7cc4ff` di `#12365f` 6.52:1, `#0a1a2e` di `#5db4f0` 7.71:1, `#f2b01e` di `#0f2a4a` 7.59:1.
`#6b84a0` (3.87:1 di putih) hanya untuk batas input, bukan teks. Teks di atas emas selalu navy
`#0f2a4a` (7.59:1).

Mode terang dan gelap sama-sama wajib berfungsi (R-34). Default keduanya terang. Preferensi
disimpan terpisah untuk website dan admin.

## Tipografi

- **Dua keluarga yang jelas berbeda.** **Literata** (serif variabel) untuk judul website dan isi
  artikel: suara dokumen resmi, nyaman dibaca panjang. **Plus Jakarta Sans** (sans variabel,
  dirancang di Jakarta) untuk antarmuka, label, tabel, dan seluruh panel admin. Keduanya
  dibundel lokal lewat `@fontsource-variable`. Kelas `font-display` memilih Literata.
- **Bobot:** 400, 600, 700. Bobot 500 dipetakan ke 600 di tema. Hierarki dari kontras bobot dan
  ukuran, bukan banyak warna.
- **Website:** hero 52/600 serif (lh 1.08), judul bagian 40/600 serif, lead 20 sampai 24 sans, badan
  17/400 (lh 1.47), keterangan 14, cetak kecil 12. Panjang baris artikel maksimal 68 karakter.
- **Admin:** judul halaman 24/600, judul kartu 17 sampai 18/600, badan tabel dan form 14/400,
  keterangan 12, semuanya sans. Angka memakai `tabular-nums`.
- Label tombol memakai sentence case. Tidak ada label kecil berhuruf kapital berjarak lebar di
  atas judul.

## Bentuk dan permukaan

- **Radius (formal, kecil):** 6px tombol, input, chip filter, dan blok tanggal, 8px kartu dan
  dialog di kedua permukaan, 50% untuk avatar dan titik penanda. **Badge dan penghitung berbentuk
  pil** (permintaan pemilik proyek), tombol, kolom cari, dan chip filter tidak. Tile bleed penuh
  tanpa radius.
- **Elevasi:** datar. Pemisahan lewat warna permukaan dan hairline 1px. Bayangan hanya untuk
  tiga hal yang memang terangkat: lapisan melayang (dropdown, dialog, toast), **bilah navigasi
  saat halaman digulir** (supaya terpisah dari konten yang lewat di bawahnya), dan **sertifikat
  di hero** (benda kertas di atas latar gelap). Tidak ada blur di sistem.
- **Tombol:** utama biru teks putih, sekunder garis biru 1px, outline netral, utilitas navy,
  ghost tanpa latar. Tinggi 44px di website, 36px di admin desktop, 44px di mobile. Tekan:
  `scale(.97)`. Satu tombol utama per layar.
- **Badge** (`components/ui/badge`): pil dengan varian `default` (biru), `secondary` (netral
  ber-cincin), `destructive`, `success`, `warning`, dan `outline`. Ukuran `count` membuat
  penghitung bulat (8, 99, 20+) dengan angka tabular. Badge ber-ikon (misalnya centang "Terverifikasi")
  menaruh ikon 14px sebagai anak pertama. Badge hanya untuk status, label, atau jumlah yang nyata,
  dan labelnya selalu tertulis, warna hanya memperkuat. `StatusBadge` membungkus `Badge` untuk
  status di tabel. Lencana jenjang skema memakai `outline`.
- **shadcn-vue:** komponen primitif (`components/ui`) berasal dari shadcn-vue di atas reka-ui,
  termasuk `Sidebar`, `Collapsible`, `Dialog`, `Sheet`, `Select`, `Tabs`. Token shadcn
  (`--sidebar-*`, `--card`, `--primary`, dan seterusnya) dipetakan ke palet ini di `app.css`.
  Tambah komponen baru dengan CLI shadcn-vue (`components.json` sudah ada), lalu sesuaikan
  warnanya lewat token, bukan hex.

### Navigasi

- **Website:** bilah navy 48px yang menempel di atas, **bergaya 3D**: cahaya tipis dari atas,
  bevel terang di tepi atas, bevel gelap di tepi bawah, dan bayangan jatuh yang mendalam saat halaman
  digulir (`.nav-3d`, `data-scrolled`). Tautan terangkat 1px saat hover dengan garis bawah emas yang
  meluncur dari kiri (`.nav-link`), dan halaman aktif tampak **seperti tombol tertekan** (inset
  gelap). Garis emas tipis di tepi bawah bilah menunjukkan progres scroll (`ScrollProgress`).
- **Admin:** topbar navy yang sama dengan website (`.nav-3d`, bevel, bayangan jatuh yang menguat
  saat digulir, garis progres scroll). Satu-satunya kontrol navigasi di bilah itu adalah tombol
  ciut/perluas sidebar (juga `Ctrl/Cmd+B`); tema dan menu akun ada di kanan. Ikon sidebar bergeser
  2px saat hover, tombol tema memutar ikonnya 12 derajat.
- Gradasi cahaya tipis pada navbar adalah satu-satunya gradasi di sistem dan ada untuk memodelkan
  kedalaman bilah, bukan hiasan.

## Tata letak website (RHYTHM 3)

Struktur mengikuti konten lembaga: slide, skema, alur, berita, profil. Setiap bagian berbeda
komposisi, dan pembatas antar bagian adalah pergantian warna tile atau aturan guilloche.

1. **Nav global** (lihat Navigasi). Menu mobile lewat sheet.
2. **Hero** tile navy dengan rosette berputar. Dua kolom: kiri judul serif rata kiri, subjudul, satu
   tombol (hanya bila slide punya tautan); kanan **sertifikat ilustrasi** (kertas putih, bingkai
   ganda biru-emas, "Nama peserta" dan "Nama skema" sebagai placeholder, tiga unit kompetensi, cap emas
   "Kompeten"). Keterangan di bawahnya menyebut ini ilustrasi, bukan dokumen resmi. Di bawah kedua
   kolom ada **tab indeks**: judul tiap slide sebagai daftar isi bernomor, tab aktif bergaris emas
   di atasnya, dan di atas tab ada **penggaris** (tick panjang tiap 60px, pendek tiap 12px)
   sebagai alat ukur penilaian. Sudut atas hero diberi **tanda registrasi cetak**. Slide dari CMS
   menggerakkan teks di kiri. Tanpa panah dan titik carousel, tanpa autoplay.
3. **Pencarian** kartu putih yang menjuntai di tepi bawah hero: kolom cari dan chip bidang.
4. **Skema sertifikasi** tile putih. Di kanan judul: **tangga jenjang KKNI** (sembilan anak tangga
   yang makin tinggi, Romawi I sampai IX). Anak tangga yang punya skema berisi jumlahnya dan
   menjadi tautan penyaring, yang kosong berupa garis putus-putus. Di bawahnya kartu skema,
   skema pertama lebih besar. **Kartu skema menggambar datanya sendiri:** glyph tangga sembilan
   anak tangga yang menyala sampai jenjang KKNI skema itu (dengan angka Romawi), dan satu kotak
   kecil untuk tiap unit kompetensi. Saat hover, glyph dan kotak menyala lebih terang.
5. **Aturan guilloche**, lalu **Lima tahap menuju sertifikat** tile navy: jalur horizontal dengan
   lima simpul persegi bernomor yang tersambung garis emas, simpul terakhir bertanda centang
   emas. Penomoran dipakai karena kontennya memang urutan.
6. **Berita dan pengumuman** tile biru-es: berita utama berupa blok navy dengan blok tanggal emas,
   sisanya daftar kartu dengan blok tanggal (tanggal memimpin tiap entri seperti papan pengumuman).
7. **Tentang** tile putih: pernyataan dan dua tombol di kiri, kartu "Hubungi lembaga" (alamat,
   email, telepon dari data Tentang) di kanan. Lalu aturan guilloche dan **footer** navy satu baris (logo, hak cipta, tautan) setinggi kira-kira 70px.

- Daftar skema dan berita: judul serif, aturan guilloche, chip filter persegi, satu kartu per
  entri, state kosong dengan tombol reset. Daftar skema juga memuat tangga KKNI.
- Artikel: judul dan isi serif di kolom baca sempit. Detail skema: data ringkas dalam daftar definisi.
- Sampul tanpa gambar: blok warna datar keluarga biru merek dengan judulnya sendiri.

## Tata letak admin (RHYTHM 1)

Mengikuti pola dashboard bersidebar: sidebar kiri bergrup, topbar tipis, kepala halaman dengan
judul dan aksi, lalu kartu.

- **Sidebar** (shadcn `Sidebar`, navy, lebar 256px): logo, Dashboard, lalu grup CMS, Master Data,
  Sistem. Tiap grup bisa diciutkan (`Collapsible`), semua halaman satu klik. Halaman aktif punya
  latar sedikit lebih terang dan penanda emas di tepi kiri yang tumbuh saat aktif. Bisa diciutkan
  ke mode ikon dengan tooltip (`Ctrl/Cmd+B`), keadaan tersimpan di cookie. Di mobile berubah
  menjadi sheet dengan target sentuh 44px. Footer sidebar: Lihat website.
- **Topbar** 56px, navy bergaya 3D seperti website: tombol ciut/perluas sidebar di kiri, tema dan
  menu akun di kanan.
- **Kartu daftar CRUD** (`ResourceIndex` + `DataTable`): judul kartu dengan garis bawah, toolbar
  (kiri: cari dan penyaring; kanan: info dan hapus massal saat ada baris terpilih), tabel
  berbingkai dengan header abu-abu, kolom bisa diurutkan (ikon naik-turun), kotak centang tiap baris,
  menu aksi tiga titik (Ubah, Hapus), dan footer paging dengan **Ke halaman**. Tombol **Tambah** ada
  di pojok kanan judul kartu, bukan di kepala halaman. Sidebar yang diciutkan membuka semua grup agar semua menu terlihat.
- **Kepala halaman** (`PageHeader`): pita `page-tide` selebar area konten tanpa garis hias (garis emas hanya di website), berisi judul 24px,
  breadcrumb, deskripsi, dan aksi di kanan.
- Konten di kontainer maksimal 1320px di atas ground biru-es.
- Tabel di dalam kartu putih hairline: header kartu (judul, jumlah, cari, filter), header kolom
  biru-es, baris lega, hover biru-es, footer paging. Badge status berlatar tipis dan selalu bertulis.
- **Dashboard** dibangun di sekitar keputusan editor: empat kartu pintasan (berita terbit, draf,
  slide aktif, pengguna) yang masing-masing menuju halamannya, kartu "Perlu tindakan" (draf dan job
  gagal) dengan tombol spesifik, daftar draf, dan distribusi berita terbit per kategori
  (bar bersegmen yang legendanya menyebut tiap segmen). Semua angka dihitung dari data contoh
  dan diberi label. Tidak ada bagan tren karena tidak ada data deret waktu nyata.
- **Auth (login, daftar, lupa dan reset sandi):** tanpa navbar. Latar navy penuh dengan rosette
  berputar pelan. Di kiri (desktop) logo, judul, ilustrasi SVG orang mengerjakan uji
  (`AssessmentIllustration`), dan catatan kaki; di kanan satu kotak putih berisi form. Di mobile
  hanya kotak form dengan logo di atasnya. Tautan "Lihat website" dan saklar tema mengambang di
  pojok kanan atas. Tidak ada tombol masuk dengan passkey (passkey tetap bisa didaftarkan di
  pengaturan akun).
- **Validasi form:** zod di `resources/js/lib/schemas.ts`, dijalankan saat submit
  (`novalidate`, tanpa gelembung bawaan browser). Kolom yang salah mendapat border dan latar
  merah lembut (`aria-invalid`), pesan di bawahnya memakai `InputError` (ikon, `role="alert"`,
  muncul halus), kartu bergetar sekali (`.shake`), fokus pindah ke kolom pertama yang salah.
  Galat dari server tampil dengan gaya yang sama.
- **Form CRUD, Profil, Keamanan, Tentang:** memakai pola validasi yang sama. Dialog CRUD
  membangun skema zod dari definisi `Field` (`lib/formValidation.ts`), form akun memakai
  `lib/settingsSchemas.ts`, form auth memakai `composables/useFormValidation.ts`. Kolom teks
  memakai `IconInput` (ikon lucide di kiri, merah saat salah); ikon dipilih dari tipe atau nama
  field dan tidak ada ikon bila tidak ada yang cocok.
- **Saklar tema** (`ThemeToggle`): `role="switch"` dengan knob yang bergeser dan ikon matahari
  atau bulan, dipakai di navbar website, topbar admin, dan halaman auth.

## Gerak

Website sengaja lebih hidup daripada admin, tetapi geraknya lambat dan berkelas. Hanya lapisan
dekoratif (`aria-hidden`) yang berulang, konten tidak pernah berulang.

- **Website (MOTION 3).**
    1. **Latar bergerak:** rosette guilloche (tiga lapis kurva hipotrokoid) di hero, tile alur, dan
       tile Tentang berputar sangat pelan (90, 140, dan 200 detik per putaran, arah berlawanan).
       Pita guilloche pembatas bergeser 28 detik per siklus.
    2. **Parallax pointer:** di hero, sertifikat condong dan bergeser mengikuti kursor sedangkan
       rosette bergeser berlawanan (hanya pointer halus, `(hover: hover) and (pointer: fine)`).
       Sertifikat juga melayang 6px dalam 7 detik.
    3. Hero: teks naik berurutan (`.rise`, 0 sampai 260ms), diputar ulang saat slide berganti.
    4. **Urutan sertifikat:** tiga unit tercentang satu per satu, lalu cap emas turun (skala 1.22
       ke 1, 450ms). Versi mini dari proses sertifikasi.
    5. **Tangga KKNI** tumbuh dari bawah berurutan 55ms, dan **jalur lima tahap** digambar 1.2 detik
       dengan simpul muncul berurutan, keduanya saat pertama kali terlihat.
    6. Konten di bawah lipatan mengendap saat pertama tergulir ke layar (`v-reveal`, naik 18px,
       jeda 60ms antar-item). Yang sudah terlihat saat dimuat tidak disembunyikan.
    7. **Transisi halaman:** halaman keluar memudar 120ms, halaman masuk 220ms. Kuncinya komponen
       halaman, jadi filter di halaman yang sama tidak memuat ulang halaman.
    8. Progres scroll di navbar, hover navbar (lihat Navigasi), hover kartu berganti warna tanpa
       mengangkat kartu.
- **Admin (MOTION 2, lebih hemat).** Dashboard: kartu masuk berurutan sekali, angka menghitung naik
  700ms (`CountUp`). Sidebar: penanda emas tumbuh 200ms, chevron grup berputar, grup membuka dan
  menutup dengan transisi tinggi, sidebar menciut 200ms. Bayangan topbar menguat saat digulir.
  Tidak ada animasi masuk pada halaman tabel dan form.
- `prefers-reduced-motion` mematikan semuanya: rosette diam, tidak ada melayang atau parallax,
  centang dan cap langsung terlihat, angka langsung menampilkan nilai akhir.
- Animasi memakai `opacity` dan `transform` (garis centang memakai `stroke-dashoffset`, pita
  guilloche memakai `mask-position` pada strip setinggi 10px) agar tidak memicu layout shift.

## Aturan konten (antislop)

- Tidak ada angka, statistik, atau klaim tanpa sumber (R-17, R-36). Data contoh diberi label
  "Data contoh" yang terlihat (R-38). Tidak ada testimoni, logo mitra, atau klaim izin.
- Navigasi hanya ke halaman yang ada (R-24). Ikon satu set (lucide, `@lucide/vue`), selalu
  `aria-hidden` bila ada teks di sebelahnya. Di website dipakai untuk bidang skema
  (`lib/icons.ts`, `fieldIcon`, bidang tak dikenal memakai `Layers`), tahap sertifikasi, kontak,
  data ringkas skema, dan menu mobile; bukan hiasan acak.
- Setiap tampilan data punya state kosong, memuat, dan galat yang menyebut penyebab dan langkah
  berikutnya (R-27).
- Target sentuh minimal 44px di mobile. Fokus keyboard terlihat di semua permukaan (R-32).
- Kontak, alamat, dan isi profil di `DemoContent` adalah placeholder (`example.com`, nomor nol).
  Ganti lewat panel CMS sebelum situs diluncurkan.

## Alasan keputusan (R-31)

| Keputusan                                 | Alasan satu baris                                                         |
| ----------------------------------------- | ------------------------------------------------------------------------- |
| Biru, biru langit, dan emas dari logo     | Warna datang dari identitas lembaga, bukan dari template                  |
| Satu aksen emas di empat tempat           | Titik fokus kecil tanpa menjadi warna kedua di tombol                     |
| Tepi guilloche hanya di website           | Motif sertifikat jadi pembatas bagian; admin dibuat tenang tanpa hiasan   |
| Plus Jakarta Sans                         | Dirancang di Jakarta, cocok untuk konten Indonesia, bukan font bawaan AI  |
| Tahap sertifikasi bernomor                | Kontennya memang urutan, penomoran membawa informasi                      |
| Sidebar bergrup di admin                  | 11 halaman terlihat sekaligus, grup bisa diciutkan, sesuai referensi      |
| shadcn-vue untuk primitif                 | Komponen sudah ada, aksesibel (reka-ui), dan bisa ditema lewat token      |
| Kartu pintasan di dashboard               | Angka nyata dari data contoh, tiap kartu menuju halaman yang mengelolanya |
| Tanpa bagan tren                          | Tidak ada data deret waktu nyata, dan angka karangan dilarang             |
| Sampul tipografis datar                   | Tidak ada foto di data, dan tidak memalsukan gambar                       |
| Tanpa autoplay carousel                   | Gerak hanya atas aksi pengguna, sesuai WCAG 2.2.2                         |
| Pil hanya CTA, cari, chip; 8px di admin   | Pil di semua elemen menghapus hierarki bentuk (R-11)                      |
| Reveal dan hitung naik sekali, tanpa loop | Gerak memandu perhatian lalu berhenti, bukan wallpaper                    |

## Dial

- Website: ENERGY 2 / RHYTHM 3 / MOTION 3.
- Admin dan auth: ENERGY 1 / RHYTHM 1 / MOTION 2 (alat kerja yang tenang, gerak hanya untuk
  menjelaskan perubahan).
