<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masa Trial Akan Berakhir</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 12px 12px 0 0; text-align: center; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 12px 12px; }
        .warning-box { background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .btn { display: inline-block; background: #667eea; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; }
        .btn:hover { background: #5a6fd8; }
        .footer { text-align: center; color: #666; font-size: 14px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Peringatan Masa Trial</h1>
            <p>SIS Platform EduSaaS</p>
        </div>
        
        <div class="content">
            <p>Halo {{ $foundation->name }},</p>
            
            <p>Masa trial gratis Anda untuk SIS Platform EduSaaS akan berakhir dalam <strong>{{ $daysLeft }} hari</strong> pada tanggal <strong>{{ $trialEndsAt }}</strong>.</p>
            
            <div class="warning-box">
                <h3>🎯 Apa yang perlu Anda lakukan?</h3>
                <ul>
                    <li>Hubungi admin platform untuk informasi paket langganan</li>
                    <li>Pilih paket yang sesuai dengan kebutuhan yayasan Anda</li>
                    <li>Lakukan pembayaran untuk melanjutkan akses platform</li>
                </ul>
            </div>
            
            <p>Setelah masa trial berakhir, akses ke platform akan ditangguhkan hingga Anda melakukan upgrade ke paket berbayar.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="mailto:admin@edusaas.com?subject=Upgrade Paket - {{ $foundation->name }}" class="btn">
                    Hubungi Admin untuk Upgrade
                </a>
            </div>
            
            <p>Terima kasih telah menggunakan SIS Platform EduSaaS. Kami siap membantu yayasan Anda dalam mengelola data pendidikan dengan lebih baik.</p>
            
            <p>Salam,<br>Tim EduSaaS</p>
        </div>
        
        <div class="footer">
            <p>© 2026 SIS Platform EduSaaS. Email ini dikirim otomatis, jangan balas email ini.</p>
        </div>
    </div>
</body>
</html>
