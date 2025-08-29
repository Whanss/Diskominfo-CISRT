<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Status Tiket Diperbarui</title>
</head>
<body>
    <h2>Status Tiket Anda Telah Diperbarui</h2>
    
    <p>Halo {{ $ticket->nama_pelapor }},</p>
    
    <p>Status tiket Anda dengan kode tracking <strong>{{ $ticket->code_tracking }}</strong> telah diperbarui.</p>
    
    <h3>Detail Tiket:</h3>
    <ul>
        <li><strong>Judul:</strong> {{ $ticket->judul }}</li>
        <li><strong>Status Sebelumnya:</strong> {{ $oldStatus }}</li>
        <li><strong>Status Baru:</strong> {{ $newStatus }}</li>
        <li><strong>Tanggal Update:</strong> {{ now()->format('d/m/Y H:i') }}</li>
    </ul>
    
    <p>Untuk melihat detail lengkap tiket Anda, silakan klik link berikut:</p>
    <p><a href="{{ route('guest.liat_tiket', $ticket->code_tracking) }}">Lihat Detail Tiket</a></p>
    
    <p>Terima kasih atas laporan Anda. Kami akan terus memproses tiket ini sesuai prosedur CSIRT.</p>
    
    <p>Salam,<br>
    Tim CSIRT STMIK</p>
</body>
</html>
