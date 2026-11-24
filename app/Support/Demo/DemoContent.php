<?php

namespace App\Support\Demo;

/**
 * Sample content for the UI-first phase. Every value here is a labelled sample, not real
 * data: people are role placeholders, articles describe this CMS itself. Replace each method
 * with a repository call when the database layer is built; the page props keep their shape.
 */
class DemoContent
{
    /** @return list<array<string, mixed>> */
    public static function roles(): array
    {
        return [
            ['id' => 1, 'name' => 'Administrator', 'slug' => 'administrator', 'description' => 'Akses penuh ke semua modul, termasuk pengaturan sistem.', 'users_count' => 1],
            ['id' => 2, 'name' => 'Editor', 'slug' => 'editor', 'description' => 'Menyunting dan menerbitkan berita, slide, dan halaman Tentang Kami.', 'users_count' => 1],
            ['id' => 3, 'name' => 'Penulis', 'slug' => 'penulis', 'description' => 'Menulis draf berita. Tidak bisa menerbitkan.', 'users_count' => 2],
        ];
    }

    /** @return list<array<string, mixed>> */
    public static function users(): array
    {
        return [
            ['id' => 1, 'name' => 'Administrator', 'email' => 'admin@example.com', 'role_id' => 1, 'role' => 'Administrator', 'is_active' => true, 'last_login_at' => '2026-11-20T08:12:00+07:00', 'created_at' => '2026-10-01T09:00:00+07:00'],
            ['id' => 2, 'name' => 'Editor Konten', 'email' => 'editor@example.com', 'role_id' => 2, 'role' => 'Editor', 'is_active' => true, 'last_login_at' => '2026-11-19T15:40:00+07:00', 'created_at' => '2026-10-03T10:30:00+07:00'],
            ['id' => 3, 'name' => 'Penulis Satu', 'email' => 'penulis1@example.com', 'role_id' => 3, 'role' => 'Penulis', 'is_active' => true, 'last_login_at' => null, 'created_at' => '2026-11-02T13:15:00+07:00'],
            ['id' => 4, 'name' => 'Penulis Dua', 'email' => 'penulis2@example.com', 'role_id' => 3, 'role' => 'Penulis', 'is_active' => false, 'last_login_at' => null, 'created_at' => '2026-11-10T11:00:00+07:00'],
        ];
    }

    /** @return list<array<string, mixed>> */
    public static function menus(): array
    {
        $menus = [
            [1, 'Dashboard', 'dashboard', 'layout-dashboard', null, 1],
            [2, 'Master Data', null, 'database', null, 2],
            [3, 'Pengguna', 'admin.users.index', 'users', 2, 1],
            [4, 'Peran', 'admin.roles.index', 'shield', 2, 2],
            [5, 'Menu', 'admin.menus.index', 'list-tree', 2, 3],
            [6, 'Akses Menu', 'admin.menu-access.index', 'key-round', 2, 4],
            [7, 'CMS', null, 'newspaper', null, 3],
            [8, 'Slide Beranda', 'admin.carousels.index', 'gallery-horizontal', 7, 1],
            [9, 'Kategori Berita', 'admin.news-categories.index', 'tags', 7, 2],
            [10, 'Berita', 'admin.news.index', 'newspaper', 7, 3],
            [11, 'Tentang Kami', 'admin.about.edit', 'building-2', 7, 4],
            [12, 'Sistem', null, 'server', null, 4],
            [13, 'Pengaturan', 'admin.system-settings.index', 'sliders-horizontal', 12, 1],
            [14, 'Log', 'admin.logs.index', 'scroll-text', 12, 2],
            [15, 'Antrian Gagal', 'admin.failed-jobs.index', 'list-x', 12, 3],
        ];

        $names = array_column($menus, 1, 0);

        return array_map(fn (array $m) => [
            'id' => $m[0],
            'name' => $m[1],
            'route' => $m[2],
            'icon' => $m[3],
            'parent_id' => $m[4],
            'parent_name' => $m[4] ? $names[$m[4]] : null,
            'order' => $m[5],
            'is_active' => true,
        ], $menus);
    }

    /** @return array<int, list<int>> role id => menu ids */
    public static function menuAccess(): array
    {
        $all = array_column(self::menus(), 'id');

        return [
            1 => $all,
            2 => [1, 7, 8, 9, 10, 11],
            3 => [1, 7, 10],
        ];
    }

    /** @return list<array<string, mixed>> */
    public static function newsCategories(): array
    {
        return [
            ['id' => 1, 'name' => 'Panduan',      'slug' => 'panduan',      'description' => 'Cara mendaftar, mempersiapkan asesmen, dan mengikuti uji kompetensi.'],
            ['id' => 2, 'name' => 'Pengumuman',   'slug' => 'pengumuman',   'description' => 'Kabar resmi dari lembaga sertifikasi.'],
            ['id' => 3, 'name' => 'Jadwal uji',   'slug' => 'jadwal',       'description' => 'Jadwal dan lokasi pelaksanaan uji kompetensi.'],
        ];
    }

    /** @return list<array<string, mixed>> */
    public static function news(): array
    {
        $categories = array_column(self::newsCategories(), 'name', 'id');

        // Sample articles: replace from the CMS before launch.
        $items = [
            [1, 'Jadwal Uji Kompetensi Gelombang Berikutnya Dibuka', 3, 'published', '2026-11-20T08:00:00+07:00',
                'Peserta dapat mendaftar untuk gelombang uji kompetensi berikutnya. Tanggal dan lokasi diumumkan di halaman ini.',
                "Pendaftaran gelombang uji kompetensi berikutnya sudah dibuka. Pilih skema yang sesuai dengan bidang kerja Anda, lalu lengkapi dokumen persyaratan.\n\nTanggal pelaksanaan dan lokasi tempat uji kompetensi (TUK) diumumkan melalui halaman ini dan dihubungi langsung kepada peserta yang sudah mendaftar.\n\nTeks ini contoh. Ganti dengan jadwal sebenarnya dari panel CMS."],
            [2, 'Panduan Alur Sertifikasi dari Pendaftaran sampai Sertifikat', 1, 'published', '2026-11-15T10:30:00+07:00',
                'Lima tahap yang dilalui peserta: pendaftaran, asesmen mandiri, uji kompetensi, keputusan asesor, dan penerbitan sertifikat.',
                "Sertifikasi kompetensi berjalan dalam lima tahap. Peserta mendaftar dan mengunggah dokumen, mengisi asesmen mandiri, mengikuti uji kompetensi, menunggu keputusan asesor, lalu menerima sertifikat bila dinyatakan kompeten.\n\nSetiap tahap punya kebutuhan berbeda. Baca panduan skema yang Anda pilih sebelum mendaftar.\n\nTeks ini contoh. Sesuaikan dengan prosedur lembaga Anda."],
            [3, 'Dokumen yang Perlu Disiapkan untuk Asesmen Mandiri', 1, 'published', '2026-11-12T14:00:00+07:00',
                'Siapkan identitas, bukti pengalaman atau pelatihan, dan portofolio kerja agar asesmen mandiri berjalan lancar.',
                "Asesmen mandiri meminta Anda menilai diri terhadap setiap unit kompetensi pada skema. Bukti pendukung mempercepat proses.\n\nBukti yang umum diminta: kartu identitas, ijazah atau sertifikat pelatihan, surat keterangan pengalaman kerja, dan portofolio hasil kerja.\n\nTeks ini contoh. Daftar dokumen sebenarnya berbeda tiap skema."],
            [4, 'Peninjauan Unit Kompetensi pada Beberapa Skema', 2, 'published', '2026-11-18T09:00:00+07:00',
                'Beberapa skema sedang ditinjau agar sejalan dengan kebutuhan industri. Peserta terdaftar akan diberi tahu bila ada perubahan.',
                "Lembaga meninjau unit kompetensi pada beberapa skema secara berkala. Peserta yang sudah terdaftar tidak terdampak pada gelombang yang sedang berjalan.\n\nPerubahan akan diumumkan di halaman ini sebelum berlaku.\n\nTeks ini contoh."],
            [5, 'Pendaftaran Asesor Baru', 2, 'draft', null,
                'Lembaga membuka pendaftaran asesor kompetensi baru untuk beberapa bidang.',
                'Draf. Persyaratan dan tanggal pendaftaran belum ditetapkan.'],
            [6, 'Persiapan Uji Kompetensi: Apa yang Dinilai Asesor', 1, 'draft', null,
                'Gambaran singkat tentang cara asesor menilai: observasi praktik, wawancara, dan tinjauan bukti.',
                'Draf. Materi masih disusun.'],
        ];

        return array_values(array_map(fn (array $n) => [
            'id' => $n[0],
            'title' => $n[1],
            'slug' => str($n[1])->slug()->toString(),
            'category_id' => $n[2],
            'category_name' => $categories[$n[2]],
            'status' => $n[3],
            'published_at' => $n[4],
            'excerpt' => $n[5],
            'content' => $n[6],
            'updated_at' => $n[4] ?? '2026-11-19T16:00:00+07:00',
        ], $items));
    }

    /** @return list<array<string, mixed>> */
    public static function publishedNews(): array
    {
        $published = array_filter(self::news(), fn (array $n) => $n['status'] === 'published');
        usort($published, fn (array $a, array $b) => strcmp($b['published_at'], $a['published_at']));

        return $published;
    }

    /** @return array<string, string|null> */
    public static function about(): array
    {
        return [
            'name' => config('app.name'),
            'tagline' => 'Lembaga Sertifikasi Profesi Abdi Nusantara.',
            'description' => "Lembaga sertifikasi profesi (LSP) menguji dan menyatakan kompetensi tenaga kerja berdasarkan skema sertifikasi. Peserta dinilai oleh asesor pada unit-unit kompetensi yang sesuai bidang kerjanya.\n\nTeks ini contoh. Ganti dengan profil, ruang lingkup, dan dasar penetapan lembaga Anda dari panel CMS.",
            'address' => 'Jl. Contoh No. 1, Jakarta',
            'email' => 'kontak@example.com',
            'phone' => '(021) 0000 0000',
            'map_url' => null,
        ];
    }

    /** @return list<array<string, mixed>> */
    public static function systemSettings(): array
    {
        return [
            ['id' => 1, 'key' => 'site.name', 'label' => 'Nama situs', 'value' => config('app.name'), 'group' => 'Umum'],
            ['id' => 2, 'key' => 'site.timezone', 'label' => 'Zona waktu', 'value' => 'Asia/Jakarta', 'group' => 'Umum'],
            ['id' => 3, 'key' => 'news.per_page', 'label' => 'Berita per halaman', 'value' => '9', 'group' => 'Berita'],
            ['id' => 4, 'key' => 'auth.registration', 'label' => 'Pendaftaran terbuka', 'value' => 'aktif', 'group' => 'Autentikasi'],
            ['id' => 5, 'key' => 'mail.from_address', 'label' => 'Alamat pengirim email', 'value' => 'noreply@example.com', 'group' => 'Email'],
        ];
    }

    /** @return list<array<string, mixed>> */
    public static function logs(): array
    {
        return [
            ['id' => 1, 'level' => 'error', 'message' => 'Gagal mengirim email verifikasi: koneksi SMTP ditolak', 'context' => '{"user":"penulis2@example.com","mailer":"smtp"}', 'logged_at' => '2026-11-20T07:58:12+07:00'],
            ['id' => 2, 'level' => 'warning', 'message' => 'Percobaan masuk gagal 5 kali, akun dibatasi sementara', 'context' => '{"email":"penulis1@example.com","ip":"127.0.0.1"}', 'logged_at' => '2026-11-19T21:14:03+07:00'],
            ['id' => 3, 'level' => 'info', 'message' => 'Berita diterbitkan', 'context' => '{"news_id":4,"by":"editor@example.com"}', 'logged_at' => '2026-11-20T08:00:41+07:00'],
            ['id' => 4, 'level' => 'info', 'message' => 'Pengguna masuk', 'context' => '{"email":"admin@example.com"}', 'logged_at' => '2026-11-20T08:12:00+07:00'],
            ['id' => 5, 'level' => 'info', 'message' => 'Slide beranda diubah', 'context' => '{"carousel_id":2,"by":"editor@example.com"}', 'logged_at' => '2026-11-19T16:20:00+07:00'],
            ['id' => 6, 'level' => 'info', 'message' => 'Pengguna masuk', 'context' => '{"email":"editor@example.com"}', 'logged_at' => '2026-11-19T15:40:00+07:00'],
            ['id' => 7, 'level' => 'warning', 'message' => 'Antrian email tertunda lebih dari 5 menit', 'context' => '{"queue":"default","pending":3}', 'logged_at' => '2026-11-19T14:02:10+07:00'],
            ['id' => 8, 'level' => 'info', 'message' => 'Kategori berita ditambahkan', 'context' => '{"category":"Rilis","by":"admin@example.com"}', 'logged_at' => '2026-11-18T10:05:00+07:00'],
            ['id' => 9, 'level' => 'info', 'message' => 'Berita diterbitkan', 'context' => '{"news_id":1,"by":"editor@example.com"}', 'logged_at' => '2026-11-18T09:00:12+07:00'],
            ['id' => 10, 'level' => 'error', 'message' => 'Gagal membuat thumbnail: ekstensi GD tidak tersedia', 'context' => '{"file":"slide-2.png"}', 'logged_at' => '2026-11-17T13:44:51+07:00'],
            ['id' => 11, 'level' => 'info', 'message' => 'Akses menu peran Editor diperbarui', 'context' => '{"role":"editor","by":"admin@example.com"}', 'logged_at' => '2026-11-16T11:30:00+07:00'],
            ['id' => 12, 'level' => 'info', 'message' => 'Pengguna baru didaftarkan', 'context' => '{"email":"penulis2@example.com","role":"penulis"}', 'logged_at' => '2026-11-10T11:00:00+07:00'],
            ['id' => 13, 'level' => 'info', 'message' => 'Berita diterbitkan', 'context' => '{"news_id":2,"by":"editor@example.com"}', 'logged_at' => '2026-11-15T10:30:20+07:00'],
            ['id' => 14, 'level' => 'warning', 'message' => 'Ukuran berkas log melewati 10 MB', 'context' => '{"file":"storage/logs/laravel.log"}', 'logged_at' => '2026-11-14T00:00:05+07:00'],
        ];
    }

    /** @return list<array<string, mixed>> */
    public static function failedJobs(): array
    {
        return [
            ['id' => 1, 'uuid' => '9c1f4e2a-5b7d-4c11-9a3e-0f2b8d6e7a10', 'job' => 'App\\Notifications\\VerifyEmailNotification', 'queue' => 'default', 'exception' => 'Symfony\\Component\\Mailer\\Exception\\TransportException: Connection refused', 'failed_at' => '2026-11-20T07:58:12+07:00'],
        ];
    }

    // ── Public website data (sample, replace from the CMS) ──────────────

    /** @return list<array<string, mixed>> */
    public static function schemeFields(): array
    {
        return [
            ['id' => 1, 'name' => 'Teknologi informasi', 'slug' => 'teknologi-informasi'],
            ['id' => 2, 'name' => 'Administrasi',        'slug' => 'administrasi'],
            ['id' => 3, 'name' => 'Keuangan',            'slug' => 'keuangan'],
            ['id' => 4, 'name' => 'Desain kreatif',      'slug' => 'desain-kreatif'],
            ['id' => 5, 'name' => 'Pemasaran',           'slug' => 'pemasaran'],
        ];
    }

    /** @return list<array<string, mixed>> */
    public static function schemes(): array
    {
        return [
            [
                'id' => 1, 'slug' => 'teknisi-jaringan-komputer', 'name' => 'Teknisi Jaringan Komputer',
                'field' => 'Teknologi informasi', 'level' => 'KKNI level III', 'level_no' => 3, 'units' => 8,
                'summary' => 'Merancang, memasang, dan memelihara jaringan komputer skala kecil sampai menengah.',
                'method' => 'Praktik di laboratorium dan wawancara.',
            ],
            [
                'id' => 2, 'slug' => 'pemrogram-web-junior', 'name' => 'Pemrogram Web Junior',
                'field' => 'Teknologi informasi', 'level' => 'KKNI level III', 'level_no' => 3, 'units' => 9,
                'summary' => 'Membangun dan memelihara situs web dengan basis data dan antarmuka yang responsif.',
                'method' => 'Praktik membuat fitur dan wawancara.',
            ],
            [
                'id' => 3, 'slug' => 'administrasi-perkantoran', 'name' => 'Administrasi Perkantoran',
                'field' => 'Administrasi', 'level' => 'KKNI level II', 'level_no' => 2, 'units' => 6,
                'summary' => 'Mengelola surat, arsip, jadwal, dan komunikasi kantor sehari-hari.',
                'method' => 'Observasi simulasi kerja dan portofolio.',
            ],
            [
                'id' => 4, 'slug' => 'pembukuan-akuntansi-junior', 'name' => 'Pembukuan Akuntansi Junior',
                'field' => 'Keuangan', 'level' => 'KKNI level III', 'level_no' => 3, 'units' => 7,
                'summary' => 'Mencatat transaksi, menyusun jurnal, dan menyiapkan laporan keuangan sederhana.',
                'method' => 'Studi kasus tertulis dan wawancara.',
            ],
            [
                'id' => 5, 'slug' => 'desainer-grafis-muda', 'name' => 'Desainer Grafis Muda',
                'field' => 'Desain kreatif', 'level' => 'KKNI level III', 'level_no' => 3, 'units' => 8,
                'summary' => 'Membuat materi visual cetak dan digital sesuai brief dan identitas merek.',
                'method' => 'Praktik membuat desain dan presentasi portofolio.',
            ],
            [
                'id' => 6, 'slug' => 'pemasaran-digital', 'name' => 'Pemasaran Digital',
                'field' => 'Pemasaran', 'level' => 'KKNI level IV', 'level_no' => 4, 'units' => 10,
                'summary' => 'Merencanakan dan mengukur kampanye pemasaran di kanal digital.',
                'method' => 'Studi kasus, praktik, dan wawancara.',
            ],
        ];
    }

    /**
     * KKNI has nine levels. Each entry carries how many sample schemes sit on that level.
     *
     * @return list<array{no: int, count: int}>
     */
    public static function schemeLevels(): array
    {
        $counts = array_count_values(array_column(self::schemes(), 'level_no'));

        return array_map(fn (int $no) => ['no' => $no, 'count' => $counts[$no] ?? 0], range(1, 9));
    }

    /** @return list<array<string, mixed>> */
    public static function carousels(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Buktikan kompetensi Anda dengan sertifikat profesi',
                'subtitle' => 'Lihat skema sertifikasi yang tersedia dan pilih yang sesuai bidang kerja Anda.',
                'link' => '/skema',
                'link_label' => 'Lihat skema sertifikasi',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'title' => 'Alur sertifikasi yang jelas, dari daftar sampai sertifikat',
                'subtitle' => 'Pahami lima tahap yang akan Anda lalui sebelum mendaftar.',
                'link' => '/news?category=panduan',
                'link_label' => 'Baca panduan sertifikasi',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'title' => 'Jadwal uji kompetensi diumumkan di sini',
                'subtitle' => 'Tanggal dan lokasi tempat uji kompetensi untuk gelombang berikutnya.',
                'link' => '/news?category=jadwal',
                'link_label' => 'Lihat jadwal uji',
                'order' => 3,
                'is_active' => true,
            ],
        ];
    }
}
