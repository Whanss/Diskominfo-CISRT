<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newsData = [
            [
                'title' => 'CRITICAL ALERT: Ransomware Campaign Targeting Government Infrastructure',
                'slug' => 'critical-alert-ransomware-campaign-targeting-government-infrastructure',
                'excerpt' => 'CSIRT Lombok Tengah has identified a sophisticated ransomware campaign specifically targeting regional government systems. Immediate action required.',
                'content' => "THREAT ASSESSMENT: CRITICAL\n\nCSIRT Lombok Tengah has detected a significant increase in ransomware attacks targeting regional government infrastructure over the past 14 days. The threat actors are employing advanced social engineering techniques combined with exploitation of unpatched system vulnerabilities.\n\nTACTICS, TECHNIQUES, AND PROCEDURES (TTPs):\n\n1. INITIAL ACCESS\n   - Spear-phishing emails masquerading as official government communications\n   - Exploitation of CVE-2023-XXXX in unpatched systems\n   - USB-based malware distribution targeting air-gapped networks\n\n2. PERSISTENCE & LATERAL MOVEMENT\n   - Deployment of custom backdoors\n   - Credential harvesting using Mimikatz variants\n   - Network reconnaissance via PowerShell scripts\n\n3. IMPACT\n   - File encryption using AES-256 + RSA-2048\n   - Exfiltration of sensitive government data\n   - Ransom demands ranging from 5-50 BTC\n\nIMMEDIATE MITIGATION ACTIONS:\n- Deploy emergency patches for all critical systems\n- Implement network segmentation\n- Activate incident response procedures\n- Conduct threat hunting activities\n- Verify backup integrity and offline storage\n\nFor immediate assistance or to report suspicious activity, contact CSIRT Lombok Tengah via our secure incident reporting system.",
                'category' => 'alert',
                'is_published' => true,
            ],
            [
                'title' => 'SECURITY GUIDELINE: Enterprise Password Security Framework',
                'slug' => 'security-guideline-enterprise-password-security-framework',
                'excerpt' => 'Comprehensive security guidelines for implementing robust password policies and authentication mechanisms in government environments.',
                'content' => "Password yang kuat adalah garis pertahanan pertama dalam keamanan siber. Berikut adalah panduan lengkap untuk membuat password yang aman:\n\n**Karakteristik Password yang Kuat:**\n1. Minimal 12 karakter\n2. Kombinasi huruf besar, huruf kecil, angka, dan simbol\n3. Tidak menggunakan informasi personal\n4. Unik untuk setiap akun\n\n**Teknik Membuat Password yang Mudah Diingat:**\n- Gunakan passphrase: gabungan beberapa kata acak\n- Metode substitusi karakter\n- Gunakan kalimat yang bermakna bagi Anda\n\n**Yang Harus Dihindari:**\n- Tanggal lahir, nama, atau informasi personal\n- Password yang sama untuk multiple akun\n- Kata-kata yang ada di kamus\n- Pola keyboard seperti '123456' atau 'qwerty'\n\n**Rekomendasi Tambahan:**\n- Gunakan password manager\n- Aktifkan two-factor authentication (2FA)\n- Ganti password secara berkala\n- Jangan share password dengan orang lain",
                'category' => 'tips',
                'is_published' => true,
            ],
            [
                'title' => 'CSIRT Lombok Tengah Menjalin Kerjasama dengan BSSN',
                'slug' => 'csirt-lombok-tengah-kerjasama-bssn',
                'excerpt' => 'Penandatanganan MoU antara CSIRT Lombok Tengah dengan Badan Siber dan Sandi Negara untuk meningkatkan keamanan siber daerah.',
                'content' => "CSIRT Lombok Tengah dengan bangga mengumumkan penandatanganan Memorandum of Understanding (MoU) dengan Badan Siber dan Sandi Negara (BSSN) dalam rangka meningkatkan kapasitas keamanan siber di wilayah Lombok Tengah.\n\n**Ruang Lingkup Kerjasama:**\n1. Pertukaran informasi ancaman siber\n2. Pelatihan dan capacity building\n3. Koordinasi respons insiden\n4. Pengembangan standar keamanan siber\n\n**Manfaat Kerjasama:**\n- Akses ke threat intelligence nasional\n- Peningkatan kemampuan deteksi dini\n- Standardisasi prosedur keamanan\n- Dukungan teknis dari level nasional\n\n**Program yang Akan Dilaksanakan:**\n- Workshop keamanan siber untuk ASN\n- Simulasi cyber attack dan response\n- Audit keamanan sistem pemerintahan\n- Pengembangan SOC (Security Operations Center) daerah\n\nKerjasama ini diharapkan dapat meningkatkan resiliensi keamanan siber di Lombok Tengah dan memberikan perlindungan yang lebih baik bagi sistem informasi pemerintahan dan masyarakat.",
                'category' => 'news',
                'is_published' => true,
            ],
            [
                'title' => 'Update Keamanan Sistem: Patch Critical Windows Vulnerability',
                'slug' => 'update-keamanan-patch-critical-windows-vulnerability',
                'excerpt' => 'Microsoft merilis patch keamanan untuk menutup kerentanan kritis yang dapat dieksploitasi untuk remote code execution.',
                'content' => "Microsoft telah merilis update keamanan kritis untuk mengatasi kerentanan CVE-2024-XXXX yang memungkinkan penyerang melakukan remote code execution pada sistem Windows.\n\n**Detail Kerentanan:**\n- Severity: Critical (CVSS Score: 9.8)\n- Affected Systems: Windows 10, Windows 11, Windows Server 2019/2022\n- Attack Vector: Network\n- Exploitation: Dapat dieksploitasi tanpa autentikasi\n\n**Dampak Potensial:**\n- Remote code execution dengan privilege tinggi\n- Pengambilalihan sistem secara penuh\n- Penyebaran lateral dalam jaringan\n- Pencurian data sensitif\n\n**Langkah Mitigasi:**\n1. **Segera install update keamanan** melalui Windows Update\n2. **Restart sistem** setelah instalasi patch\n3. **Verifikasi instalasi** melalui Windows Update History\n4. **Monitor sistem** untuk aktivitas mencurigakan\n\n**Timeline Update:**\n- Patch tersedia: Segera\n- Deadline instalasi: 7 hari dari pengumuman\n- Verifikasi compliance: 14 hari\n\n**Untuk Administrator Sistem:**\n- Gunakan WSUS atau SCCM untuk deployment massal\n- Test patch di environment non-production terlebih dahulu\n- Dokumentasikan proses patching\n\nJika mengalami kesulitan dalam proses update, silakan hubungi CSIRT Lombok Tengah untuk bantuan teknis.",
                'category' => 'update',
                'is_published' => true,
            ],
            [
                'title' => 'Waspada Email Phishing Mengatasnamakan Bank Indonesia',
                'slug' => 'waspada-email-phishing-bank-indonesia',
                'excerpt' => 'Ditemukan kampanye email phishing yang mengatasnamakan Bank Indonesia untuk mencuri kredensial perbankan.',
                'content' => "CSIRT Lombok Tengah mendeteksi kampanye email phishing yang mengatasnamakan Bank Indonesia (BI) untuk mencuri informasi kredensial perbankan masyarakat.\n\n**Karakteristik Email Phishing:**\n- Sender: Menggunakan domain mirip resmi BI\n- Subject: 'Verifikasi Akun Segera' atau 'Pemblokiran Rekening'\n- Content: Meminta klik link untuk 'verifikasi'\n- Urgency: Menciptakan rasa panik dan urgensi\n\n**Red Flags yang Harus Diwaspadai:**\n1. Alamat email sender tidak resmi\n2. Terdapat typo dan grammar error\n3. Link mengarah ke domain mencurigakan\n4. Meminta informasi sensitif via email\n5. Ancaman pemblokiran akun\n\n**Cara Melindungi Diri:**\n- Jangan klik link dalam email mencurigakan\n- Verifikasi langsung ke bank terkait\n- Periksa URL dengan teliti\n- Gunakan bookmark untuk akses internet banking\n- Aktifkan notifikasi SMS untuk transaksi\n\n**Jika Sudah Terlanjur:**\n1. Segera ganti password internet banking\n2. Hubungi call center bank\n3. Monitor rekening secara berkala\n4. Laporkan ke bank dan CSIRT\n\n**Cara Melaporkan:**\nJika menemukan email phishing serupa, forward ke: report@csirt-lomboktengah.go.id dengan subject 'PHISHING REPORT'.",
                'category' => 'alert',
                'is_published' => true,
            ],
            [
                'title' => 'Workshop Keamanan Siber untuk ASN Lombok Tengah',
                'slug' => 'workshop-keamanan-siber-asn-lombok-tengah',
                'excerpt' => 'CSIRT Lombok Tengah mengadakan workshop keamanan siber untuk meningkatkan awareness ASN terhadap ancaman siber.',
                'content' => "CSIRT Lombok Tengah akan mengadakan Workshop Keamanan Siber untuk seluruh Aparatur Sipil Negara (ASN) di lingkungan Pemerintah Kabupaten Lombok Tengah.\n\n**Detail Acara:**\n- Tanggal: 15-16 Februari 2024\n- Waktu: 08.00 - 16.00 WITA\n- Tempat: Aula Kantor Bupati Lombok Tengah\n- Peserta: 200 ASN dari berbagai OPD\n\n**Materi Workshop:**\n\n**Hari Pertama:**\n- Pengenalan Cyber Security Awareness\n- Jenis-jenis Ancaman Siber Terkini\n- Social Engineering dan Phishing\n- Keamanan Email dan Internet Browsing\n\n**Hari Kedua:**\n- Password Security dan Multi-Factor Authentication\n- Keamanan Data dan Backup\n- Incident Response dan Pelaporan\n- Hands-on: Simulasi Phishing Attack\n\n**Narasumber:**\n- Tim CSIRT Lombok Tengah\n- Praktisi Keamanan Siber BSSN\n- Akademisi Cyber Security\n\n**Fasilitas:**\n- Sertifikat kehadiran\n- Modul pelatihan\n- Lunch dan coffee break\n- Doorprize menarik\n\n**Pendaftaran:**\n- Batas pendaftaran: 10 Februari 2024\n- Daftar melalui: bit.ly/workshop-cybersec-loteng\n- Kontak: 0370-123456\n\n**Tujuan Workshop:**\n1. Meningkatkan awareness ASN terhadap ancaman siber\n2. Membangun budaya keamanan siber di lingkungan pemerintahan\n3. Mengurangi risiko insiden keamanan siber\n4. Menciptakan first line of defense yang kuat\n\nWorkshop ini gratis dan wajib diikuti oleh minimal 2 perwakilan dari setiap OPD.",
                'category' => 'news',
                'is_published' => true,
            ]
        ];

        foreach ($newsData as $data) {
            News::create($data);
        }
    }
}
