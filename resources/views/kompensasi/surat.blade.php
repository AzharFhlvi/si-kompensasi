<!DOCTYPE html>
<html>
<head>
    <title>Surat Kompensasi</title>
    <style>
        /* Tambahkan gaya CSS untuk PDF di sini */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .content {
            margin-bottom: 40px;
        }
        .content p {
            margin: 0;
        }
        .footer {
            text-align: right;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .signature p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Surat Kompensasi</h1>
        <p>Politeknik Negeri Banjarmasin</p>
    </div>
    <div class="content">
        <p>Kepada Yth. {{ $mahasiswa->nama }},</p>
        <p>
            Dengan ini kami sampaikan bahwa Anda telah berhasil menyelesaikan kompensasi yang dibutuhkan
            untuk kegiatan-kegiatan berikut. Selamat!
        </p>
        <p><strong>Tanggal Penyelesaian:</strong> {{ $kompensasi->created_at->format('d-m-Y') }}</p>

        <p><strong>Daftar Kegiatan yang Diselesaikan:</strong></p>
        <ul>
            @foreach ($kegiatanList as $kegiatan)
                <li>{{ $kegiatan->deskripsi }} selama {{ $kegiatan->jam }}jam</li>
            @endforeach
        </ul>

        <p>
            Anda telah memenuhi persyaratan untuk kompensasi, dan kami mengakui dedikasi dan partisipasi Anda
            dalam kegiatan-kegiatan tersebut.
        </p>
    </div>
    <div class="signature">
        <p>...............................................</p>
        <p>Tanda Tangan Pihak Berwenang</p>
    </div>
</body>
</html>
