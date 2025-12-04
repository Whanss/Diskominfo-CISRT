<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $availableImages = ['logo-kominfo.png', 'lomboktengah.png'];
        $events = [
            [
                'title' => 'Festival Budaya Lombok',
                'summary' => 'Perayaan budaya tradisional Lombok dengan berbagai pertunjukan dan kuliner khas.',
                'description' => 'Festival Budaya Lombok adalah acara tahunan yang menampilkan kekayaan budaya Sasak. Acara ini mencakup pertunjukan tari tradisional, musik gamelan, dan berbagai kuliner khas Lombok.',
                'start_at' => Carbon::now()->addDays(7),
                'end_at' => Carbon::now()->addDays(9),
                'location' => 'Lapangan Nusa Tenggara Barat, Mataram',
                'is_published' => true,
                'image' => 'events/' . $availableImages[0]
            ],
            [
                'title' => 'Workshop Digital Marketing',
                'summary' => 'Pelatihan praktis tentang strategi pemasaran digital untuk UMKM.',
                'description' => 'Workshop ini dirancang untuk membantu pelaku UMKM memahami dan menerapkan strategi pemasaran digital yang efektif. Materi mencakup SEO, social media marketing, dan analitik digital.',
                'start_at' => Carbon::now()->addDays(14),
                'end_at' => Carbon::now()->addDays(14),
                'location' => 'Hotel Jayakarta, Praya',
                'is_published' => true,
                'image' => 'events/' . $availableImages[1]
            ],
            [
                'title' => 'Konser Musik Lombok',
                'summary' => 'Konser musik dengan artis lokal dan nasional.',
                'description' => 'Konser musik yang menghadirkan berbagai genre musik dari artis-artis lokal Lombok hingga artis nasional. Acara ini akan menjadi ajang silaturahmi pecinta musik di Lombok.',
                'start_at' => Carbon::now()->addDays(21),
                'end_at' => Carbon::now()->addDays(21),
                'location' => 'Stadion 17 Desember, Mataram',
                'is_published' => true,
                'image' => 'events/' . $availableImages[0]
            ],
            [
                'title' => 'Pameran Teknologi Informasi',
                'summary' => 'Pameran inovasi teknologi informasi dan komunikasi.',
                'description' => 'Pameran yang menampilkan perkembangan terkini dalam bidang teknologi informasi. Pengunjung dapat melihat berbagai inovasi dan berinteraksi langsung dengan para ahli TI.',
                'start_at' => Carbon::now()->addDays(30),
                'end_at' => Carbon::now()->addDays(32),
                'location' => 'Gedung Serbaguna NTB, Mataram',
                'is_published' => true,
                'image' => 'events/' . $availableImages[1]
            ],
            [
                'title' => 'Seminar Kewirausahaan',
                'summary' => 'Seminar tentang tips dan trik menjadi wirausahawan sukses.',
                'description' => 'Seminar ini menghadirkan pembicara-pembicara sukses di bidang wirausaha yang akan berbagi pengalaman dan tips untuk memulai dan mengembangkan bisnis.',
                'start_at' => Carbon::now()->addDays(35),
                'end_at' => Carbon::now()->addDays(35),
                'location' => 'Universitas Mataram',
                'is_published' => true,
                'image' => 'events/' . $availableImages[0]
            ],
            [
                'title' => 'Festival Kuliner Lombok',
                'summary' => 'Festival yang menampilkan berbagai kuliner khas Lombok.',
                'description' => 'Festival kuliner yang menghadirkan berbagai makanan dan minuman khas Lombok. Pengunjung dapat mencicipi ayam taliwang, plecing kangkung, dan berbagai hidangan lainnya.',
                'start_at' => Carbon::now()->addDays(45),
                'end_at' => Carbon::now()->addDays(47),
                'location' => 'Pantai Senggigi',
                'is_published' => true,
                'image' => 'events/' . $availableImages[1]
            ],
            [
                'title' => 'Workshop Fotografi',
                'summary' => 'Pelatihan fotografi untuk pemula hingga menengah.',
                'description' => 'Workshop fotografi yang mencakup teknik dasar hingga advanced. Peserta akan belajar komposisi, pencahayaan, dan editing foto menggunakan software profesional.',
                'start_at' => Carbon::now()->addDays(50),
                'end_at' => Carbon::now()->addDays(51),
                'location' => 'Studio Foto Lombok, Mataram',
                'is_published' => true,
                'image' => 'events/' . $availableImages[0]
            ],
            [
                'title' => 'Konferensi Pendidikan',
                'summary' => 'Konferensi tentang inovasi dalam dunia pendidikan.',
                'description' => 'Konferensi yang membahas perkembangan terkini dalam sistem pendidikan. Para ahli pendidikan akan berbagi penelitian dan praktik terbaik dalam meningkatkan kualitas pendidikan.',
                'start_at' => Carbon::now()->addDays(60),
                'end_at' => Carbon::now()->addDays(62),
                'location' => 'Hotel Lombok Raya, Mataram',
                'is_published' => true,
                'image' => 'events/' . $availableImages[1]
            ],
            [
                'title' => 'Pekan Olahraga Pelajar',
                'summary' => 'Kompetisi olahraga antar sekolah di Lombok.',
                'description' => 'Pekan olahraga yang diikuti oleh siswa-siswi dari berbagai sekolah di Lombok. Cabang olahraga yang diperlombakan meliputi atletik, sepak bola, basket, dan voli.',
                'start_at' => Carbon::now()->addDays(70),
                'end_at' => Carbon::now()->addDays(75),
                'location' => 'Kompleks Olahraga Mandalika',
                'is_published' => true,
                'image' => 'events/' . $availableImages[0]
            ],
            [
                'title' => 'Festival Film Dokumenter',
                'summary' => 'Festival film dokumenter tentang kehidupan di Lombok.',
                'description' => 'Festival yang menampilkan film-film dokumenter yang mengangkat tema kehidupan sehari-hari, budaya, dan isu-isu sosial di Lombok. Para sineas lokal akan mempresentasikan karya mereka.',
                'start_at' => Carbon::now()->addDays(80),
                'end_at' => Carbon::now()->addDays(82),
                'location' => 'Teater 1 Mei, Mataram',
                'is_published' => true,
                'image' => 'events/' . $availableImages[1]
            ],
            [
                'title' => 'Bazaar UMKM Lombok',
                'summary' => 'Bazaar produk-produk UMKM lokal Lombok.',
                'description' => 'Bazaar yang memberikan kesempatan kepada pelaku UMKM untuk memasarkan produk mereka. Pengunjung dapat menemukan berbagai produk kerajinan, makanan, dan jasa dari UMKM Lombok.',
                'start_at' => Carbon::now()->addDays(90),
                'end_at' => Carbon::now()->addDays(92),
                'location' => 'Mall Trans Metro, Mataram',
                'is_published' => true,
                'image' => 'events/' . $availableImages[0]
            ],
            [
                'title' => 'Seminar Lingkungan Hidup',
                'summary' => 'Seminar tentang pelestarian lingkungan di Lombok.',
                'description' => 'Seminar yang membahas pentingnya pelestarian lingkungan hidup di Lombok. Para ahli lingkungan akan berbagi pengetahuan tentang pengelolaan sampah, konservasi laut, dan energi terbarukan.',
                'start_at' => Carbon::now()->addDays(100),
                'end_at' => Carbon::now()->addDays(100),
                'location' => 'Kantor Bappeda NTB',
                'is_published' => true,
                'image' => 'events/' . $availableImages[1]
            ]
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }

        $this->command->info('Event seeder completed successfully!');
    }
}
