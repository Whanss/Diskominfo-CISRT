<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Tiket PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h2>Laporan Tiket CSIRT</h2>
    <table>
        <thead>
            <tr>
                <th>Code Tracking</th>
                <th>Judul</th>
                <th>Nama Pelapor</th>
                <th>Email</th>
                <th>Description</th>
                <th>Status</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->code_tracking }}</td>
                    <td>{{ $ticket->judul }}</td>
                    <td>{{ $ticket->nama_pelapor }}</td>
                    <td>{{ $ticket->email }}</td>
                    <td>{{ $ticket->description }}</td>
                    <td>{{ $ticket->status }}</td>
                    <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Generated on {{ now()->format('d/m/Y H:i') }}</p>
</body>

</html>
