<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pesanan Kendaraan (SPK) - ORD-<?= str_pad($pemesanan['id_pemesanan'], 4, '0', STR_PAD_LEFT) ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; border: 1px solid #ddd; padding: 30px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #1e3a8a; }
        .header p { margin: 5px 0 0 0; font-size: 12px; color: #666; }
        .title { text-align: center; margin-bottom: 25px; }
        .title h2 { margin: 0; font-size: 18px; text-transform: uppercase; text-decoration: underline; }
        .title p { margin: 5px 0 0 0; font-size: 14px; }
        
        table { w-full; width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 4px 8px; vertical-align: top; border: none; }
        .info-table td:first-child { width: 130px; font-weight: bold; }
        .info-table td:nth-child(2) { width: 10px; }

        .item-table th, .item-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .item-table th { background-color: #f8fafc; font-weight: bold; }
        .item-table td.right { text-align: right; font-family: monospace; font-size: 14px; }
        .item-table th.right { text-align: right; }
        
        .footer { display: flex; justify-content: space-between; margin-top: 50px; text-align: center; }
        .signature-box { width: 250px; }
        .signature-box p { margin: 0 0 70px 0; }
        .signature-name { font-weight: bold; text-decoration: underline; }
        
        .note { font-size: 11px; color: #666; border-top: 1px solid #eee; padding-top: 10px; margin-top: 30px; }
        @media print {
            body { padding: 0; }
            .container { border: none; padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container">
    <div class="header">
        <h1>SHOWROOM MOBIL</h1>
        <p>Jl. Contoh Alamat No. 123, Kota Anda, Telp: (021) 1234567</p>
    </div>

    <div class="title">
        <h2>Surat Pesanan Kendaraan (SPK)</h2>
        <p>No: ORD-<?= str_pad($pemesanan['id_pemesanan'], 4, '0', STR_PAD_LEFT) ?></p>
    </div>

    <table class="info-table">
        <tr>
            <td>Tanggal Pesan</td>
            <td>:</td>
            <td><?= date('d M Y', strtotime($pemesanan['tgl_pesan'])) ?></td>
            <td>Nama Pemesan</td>
            <td>:</td>
            <td><strong><?= $pemesanan['nama_customer'] ?></strong></td>
        </tr>
        <tr>
            <td>Status Pesanan</td>
            <td>:</td>
            <td style="text-transform: uppercase;"><strong><?= $pemesanan['status_pemesanan'] ?></strong></td>
            <td>No. Telepon</td>
            <td>:</td>
            <td><?= $pemesanan['no_telp'] ?></td>
        </tr>
        <tr>
            <td>Batas Waktu DP</td>
            <td>:</td>
            <td><?= date('d M Y', strtotime($pemesanan['tgl_jatuh_tempo'])) ?></td>
            <td>Alamat</td>
            <td>:</td>
            <td><?= $pemesanan['alamat'] ?></td>
        </tr>
    </table>

    <table class="item-table">
        <thead>
            <tr>
                <th>Deskripsi Kendaraan</th>
                <th width="200" class="right">Rincian Harga (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Merek:</strong> <?= $pemesanan['merek'] ?><br>
                    <strong>Tipe/Warna:</strong> <?= $pemesanan['nama_mobil'] ?> - <?= $pemesanan['tipe'] ?> / <?= $pemesanan['warna'] ?><br>
                    <strong>Tahun:</strong> <?= $pemesanan['tahun'] ?><br>
                    <strong>No. Polisi:</strong> <?= $pemesanan['no_polisi'] ?><br>
                    <strong>No. Rangka:</strong> <?= $pemesanan['no_rangka'] ?><br>
                    <strong>No. Mesin:</strong> <?= $pemesanan['no_mesin'] ?>
                </td>
                <td class="right">
                    <strong><?= number_format($pemesanan['harga_jual_snapshot'], 0, ',', '.') ?></strong>
                </td>
            </tr>
            <tr>
                <td class="right">Nilai Tanda Jadi (Booking Fee)</td>
                <td class="right" style="color: #16a34a;">- <?= number_format($pemesanan['nilai_tanda_jadi'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td class="right">Uang Muka (DP 30%)</td>
                <td class="right" style="color: #16a34a;">- <?= number_format($pemesanan['harga_dp'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th class="right">SISA PELUNASAN</th>
                <th class="right" style="font-size: 16px;">
                    <?php 
                        $sisa = $pemesanan['harga_jual_snapshot'] - $pemesanan['nilai_tanda_jadi'] - $pemesanan['harga_dp'];
                        echo number_format($sisa, 0, ',', '.');
                    ?>
                </th>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>Pemesan,</p>
            <div class="signature-name"><?= $pemesanan['nama_customer'] ?></div>
        </div>
        <div class="signature-box">
            <p>Hormat Kami, Sales/Admin</p>
            <div class="signature-name">Showroom Mobil</div>
        </div>
    </div>

    <div class="note">
        <strong>Syarat & Ketentuan:</strong>
        <ol>
            <li>Tanda Jadi (Booking Fee) sebesar Rp 500.000 wajib dibayarkan saat SPK ini dibuat.</li>
            <li>Uang Muka (DP) wajib dilunasi paling lambat tanggal <?= date('d M Y', strtotime($pemesanan['tgl_jatuh_tempo'])) ?> (7 hari dari tanggal pemesanan).</li>
            <li>Apabila DP tidak dilunasi sampai batas waktu tersebut, maka pesanan dianggap <strong>HANGUS</strong> dan Tanda Jadi tidak dapat dikembalikan.</li>
            <li>Pembatalan sebelum batas waktu DP dapat dilakukan dan Tanda Jadi akan dikembalikan (Refund).</li>
        </ol>
    </div>
</div>

</body>
</html>
