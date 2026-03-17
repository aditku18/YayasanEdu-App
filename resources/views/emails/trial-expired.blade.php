<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masa Trial Telah Berakhir</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; padding: 30px; border-radius: 12px 12px 0 0; text-align: center; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 12px 12px; }
        .alert-box { background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .btn { display: inline-block; background: #e74c3c; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; }
        .btn:hover { background: #c0392b; }
        .footer { text-align: center; color: #666; font-size: 14px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔒 Masa Trial Berakhir</h1>
            <p>SIS Platform EduSaaS</p>
        </div>
        
        <div class="content">
            <p>Halo {{ $foundation->name }},</p>
            
            <p>Masa trial gratis Anda untuk SIS Platform EduSaaS telah <strong>berakhir</strong>. Akses ke platform {{ $subdomain }} telah ditangguhkan.</p>
            
            <div class="alert-box">
                <h3>📢 Status Akun Anda</h3>
                <p><strong>Status:</strong> Trial Expired</p>
                <p><strong>Akses Platform:</strong> Ditangguhkan</p>
                <p><strong>Data:</strong> Aman tersimpan</p>
            </div>
            
            <div style="background: #e8f5e8; border: 1px solid #d4edda; border-radius: 8px; padding: 20px; margin: 20px 0;">
                <h3>🚀 Cara Mengaktifkan Kembali</h3>
                <ol>
                    <li>Hubungi admin platform melalui email atau WhatsApp</li>
                    <li>Pilih paket langganan yang sesuai (Basic/Premium)</li>
                    <li>Lakukan pembayaran sesuai paket yang dipilih</li>
                    <li>Akses akan aktif kembali setelah pembayaran diverifikasi</li>
                </ol>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="mailto:admin@edusaas.com?subject=Reaktivasi Akun - {{ $foundation->name }}" class="btn">
                    Hubungi Admin untuk Aktivasi
                </a>
            </div>
            
            <p><strong>Penting:</strong> Data yayasan Anda tetap aman tersimpan dalam sistem kami dan akan dapat diakses kembali setelah Anda melakukan upgrade ke paket berbayar.</p>
            
            <p>Terima kasih telah mencoba SIS Platform EduSaaS. Kami berharap dapat terus melayani kebutuhan pengelolaan data pendidikan yayasan Anda.</p>
            
            <p>Salam,<br>Tim EduSaaS</p>
        </div>
        
        <div class="footer">
            <p>© 2026 SIS Platform EduSaaS. Email ini dikirim otomatis, jangan balas email ini.</p>
        </div>
    </div>
</body>
</html>
