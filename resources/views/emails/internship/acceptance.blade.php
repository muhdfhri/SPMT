<!DOCTYPE html>
<html>
<head>
    <title>Selamat! Anda Diterima Magang</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3b82f6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Selamat! Anda Diterima</h1>
    </div>
    
    <div class="content">
        <p>Halo {{ $application->user->name }},</p>
        
        <p>Kami dengan senang hati memberitahukan bahwa Anda telah diterima untuk mengikuti program magang di posisi:</p>
        
        <h2>{{ $application->internship->title }}</h2>
        
        <p><strong>Divisi:</strong> {{ $application->internship->division ?? 'Tidak disebutkan' }}</p>
        <p><strong>Lokasi:</strong> {{ $application->internship->location }}</p>
        <p><strong>Periode:</strong> 
            {{ \Carbon\Carbon::parse($application->internship->start_date)->translatedFormat('d F Y') }} - 
            {{ \Carbon\Carbon::parse($application->internship->end_date)->translatedFormat('d F Y') }}
        </p>
        
        <p>Berikut adalah beberapa langkah selanjutnya yang perlu Anda lakukan:</p>
        <ol>
            <li>Konfirmasi keikutsertaan Anda</li>
            <li>Siapkan dokumen yang diperlukan</li>
            <li>Hadir pada hari pertama sesuai jadwal</li>
        </ol>
        
        <p>Jika Anda memiliki pertanyaan, jangan ragu untuk membalas email ini.</p>
        
        <p>Salam,<br>
        Tim Rekrutmen Magang<br>
        {{ config('app.name') }}</p>
    </div>
    
    <div class="footer">
        <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
