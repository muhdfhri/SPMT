<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Sertifikat Penghargaan</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');
        
        @page {
            size: A4 landscape;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            width: 297mm;
            height: 210mm;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ee 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .certificate-container {
            width: 96%;
            height: 94%;
            background: white;
            border-radius: 0;  /* Changed to make it square */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            border: 10px solid #1a365d;  /* Reduced from 15px to 10px */
            display: flex;
            flex-direction: column;
        }
        
        .watermark {
            position: absolute;
            opacity: 0.05;
            font-size: 40rem;
            transform: rotate(-30deg);
            pointer-events: none;
            z-index: 1;
            color: #1a365d;
            font-weight: bold;
            white-space: nowrap;
            top: -100px;
            left: -200px;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo {
            max-height: 100px;
            max-width: 300px;
            object-fit: contain;
        }
        
        .header {
            text-align: center;
            padding: 30px 20px 10px;
            position: relative;
            z-index: 2;
            margin-top: 40px;
        }
        
        .header h1 {
            font-size: 36px;
            color: #1a365d;
            margin: 0;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }
        
        /* Removed yellow underline from header */
        
        .subtitle {
            font-size: 18px;
            color: #4a5568;
            margin: 5px 0 30px;
            font-weight: 500;
            letter-spacing: 1px;
        }
        
        .recipient {
            text-align: center;
            margin: 20px 0 40px;
            position: relative;
            z-index: 2;
        }
        
        .recipient h2 {
            font-size: 38px;
            color: #2d3748;
            margin: 0;
            font-weight: 700;
            padding: 15px 50px;
            display: inline-block;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Removed yellow corner borders from recipient name */
        
        .content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 30px;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }
        
        .content p {
            font-size: 16px;
            color: #4a5568;
            line-height: 1.8;
            margin-bottom: 15px;
        }
        
        .highlight {
            color: #2b6cb0;
            font-weight: 600;
        }
        
        .signature {
            margin: 40px auto 0;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        
        .signature-line {
            width: 300px;
            height: 2px;
            background: #cbd5e0;
            margin: 0 auto 10px;
            position: relative;
        }
        
        /* Removed yellow circle from signature line */
        
        .signature-name {
            font-size: 18px;
            font-weight: 700;
            color: #2d3748;
            text-decoration: underline;
            margin: 15px 0 5px;
        }
        
        .signature-title {
            font-size: 14px;
            color: #718096;
            font-weight: 500;
        }
        
        .certificate-number {
            position: absolute;
            bottom: 20px;
            right: 40px;
            font-size: 12px;
            color: #718096;
            font-weight: 500;
            z-index: 2;
        }
        
        .date {
            margin-top: 5px;
            font-size: 14px;
            color: #4a5568;
        }
        

    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="header">
            <h1>SERTIFIKAT PENGHARGAAN</h1>
            <div class="subtitle">MAGANG REGULER SPMT PT PELINDO MULTI TERMINAL</div>
        </div>
        
        <div class="recipient">
            <h2>{{ strtoupper($user->name) }}</h2>
        </div>
        
        <div class="content">
            <p>Telah menyelesaikan program magang dengan baik dan penuh dedikasi di:</p>
            <p>Posisi: <span class="highlight">{{ $application->internship->title ?? 'Program Magang' }}</span></p>
            <p>Divisi: <span class="highlight">{{ $application->internship->division ?? 'Divisi Magang' }}</span></p>
            <p>Periode <span class="highlight">{{ \Carbon\Carbon::parse($application->internship->start_date)->locale('id')->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::parse($application->internship->end_date)->locale('id')->isoFormat('D MMMM YYYY') }}</span></p>
            <p>Atas dedikasi dan kontribusi yang telah diberikan selama masa magang, maka dengan ini diberikan sertifikat sebagai bentuk apresiasi.</p>
        </div>
        
        <div class="signature">
        <div class="date">
                {{ \Carbon\Carbon::now()->format('d F Y') }}
            </div>
            <div class="signature-name">NAMA PENANDATANGAN</div>
            <div class="signature-title">Jabatan</div>
        </div>
        
        <div class="certificate-number">
            No. Sertifikat: {{ $certificateNumber ?? ($certificate->certificate_number ?? 'CERT/XXXX/XXXX') }}
        </div>
    </div>
</body>
</html>