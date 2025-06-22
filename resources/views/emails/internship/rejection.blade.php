<!DOCTYPE html>
<html>
<head>
    <title>Status Lamaran Magang Ditolak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #ef4444;
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
        <h1>Status Lamaran Magang</h1>
    </div>
    
    <div class="content">
        <p>Halo {{ $application->user->name }},</p>
        
        <p>Kami mengucapkan terima kasih atas ketertarikan Anda untuk mengikuti program magang di posisi:</p>
        
        <h2>{{ $application->internship->title }}</h2>
        
        <p>Setelah melalui proses seleksi, dengan berat hati kami sampaikan bahwa saat ini kami belum dapat menerima lamaran Anda.</p>
        
        @if($application->rejection_reason)
            <div style="background-color: #fef2f2; padding: 15px; border-radius: 6px; margin: 15px 0;">
                <p><strong>Catatan dari Tim Rekrutmen:</strong></p>
                <p>{{ $application->rejection_reason }}</p>
            </div>
        @endif
        
        <p>Kami mengapresiasi waktu dan usaha yang telah Anda berikan. Jangan berkecil hati, kesempatan lain masih terbuka lebar.</p>
        
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
