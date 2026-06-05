<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; }
        .event-title { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
        .qr-container { margin: 40px 0; }
        .url-text { color: #6366f1; font-size: 14px; margin-top: 20px; }
        .footer { font-size: 10px; color: #94a3b8; margin-top: 50px; }
    </style>
</head>
<body>
    <div class="event-title">{{ $event->nama_event }}</div>
    <div style="font-size: 14px; color: #64748b;">Scan QR Code di bawah untuk mengisi kuesioner</div>
    
    <div class="qr-container">
        {{-- Menampilkan QR Code dari string Base64 --}}
        <img src="data:image/svg+xml;base64,{{ $qrcode }}" width="500">
    </div>

    <div class="url-text" style="font-size: 20px;">
        <strong>URL Link:</strong><br>
        {{ $url }}
    </div>

    <div class="footer">
        Mental Health Screening by Abasa HR Consulting - {{ date('d M Y') }}
    </div>
</body>
</html>