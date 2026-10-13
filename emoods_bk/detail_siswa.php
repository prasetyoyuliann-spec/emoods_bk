
<?php
session_start();
include "koneksi.php";

// Hanya Guru BK/Admin yang sudah login boleh membuka halaman ini.
if (
    !isset($_SESSION['login']) ||
    $_SESSION['login'] !== true ||
    !in_array($_SESSION['role'] ?? '', ['guru_bk', 'admin'], true)
) {
    header("Location: login.php");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("ID hasil tidak valid. Silakan kembali ke dashboard.");
}

// Ambil hasil refleksi yang dipilih.
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM hasil_emosi WHERE id = ? LIMIT 1"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$siswa) {
    exit("Data hasil refleksi tidak ditemukan.");
}

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function tampil($value) {
    return ($value === null || $value === '') ? '-' : e($value);
}

$pertanyaan = [
    "Kesadaran Diri" => [
        1 => "Saya dapat mengenali emosi yang sedang saya rasakan.",
        2 => "Saya dapat mengenali penyebab munculnya emosi saya.",
        3 => "Saya dapat menyadari perubahan pada tubuh ketika emosi muncul.",
        4 => "Saya dapat memahami bagaimana emosi memengaruhi pikiran dan tindakan saya."
    ],
    "Manajemen Diri" => [
        5 => "Saya dapat menenangkan diri ketika emosi mulai meningkat.",
        6 => "Saya dapat berhenti sejenak sebelum bertindak ketika marah atau kesal.",
        7 => "Saya mengetahui cara yang membantu mengelola stres.",
        8 => "Saya dapat kembali fokus setelah mengalami emosi yang kuat."
    ],
    "Kesadaran Sosial" => [
        9 => "Saya memahami bahwa orang lain dapat memiliki perasaan berbeda.",
        10 => "Saya berusaha memahami perasaan teman ketika menghadapi masalah.",
        11 => "Saya menghargai pendapat orang lain meskipun berbeda.",
        12 => "Saya menunjukkan kepedulian ketika melihat orang lain mengalami kesulitan."
    ],
    "Keterampilan Berelasi" => [
        13 => "Saya dapat menyampaikan perasaan kepada orang lain dengan baik.",
        14 => "Saya dapat menyampaikan ketidaksetujuan tanpa menyakiti orang lain.",
        15 => "Saya dapat meminta bantuan ketika menghadapi masalah sulit.",
        16 => "Saya berusaha menyelesaikan masalah dengan teman melalui komunikasi yang baik."
    ],
    "Keputusan Bertanggung Jawab" => [
        17 => "Saya mempertimbangkan akibat sebelum mengambil keputusan.",
        18 => "Saya memilih tindakan yang tidak merugikan diri sendiri maupun orang lain.",
        19 => "Saya berusaha mencari beberapa pilihan solusi ketika menghadapi masalah.",
        20 => "Saya dapat belajar dari keputusan yang pernah saya buat."
    ]
];

$aspek = [
    "Kesadaran Diri" => $siswa['kesadaran_diri'],
    "Manajemen Diri" => $siswa['manajemen_diri'],
    "Kesadaran Sosial" => $siswa['kesadaran_sosial'],
    "Keterampilan Berelasi" => $siswa['keterampilan_berelasi'],
    "Keputusan Bertanggung Jawab" => $siswa['keputusan_bertanggung_jawab']
];

// Ambil riwayat pengisian siswa yang sama berdasarkan NIS.
$riwayat = [];
if (!empty($siswa['nis'])) {
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT id, emosi, intensitas, total_skor, status_hasil, created_at
         FROM hasil_emosi
         WHERE nis = ?
         ORDER BY created_at DESC, id DESC
         LIMIT 30"
    );
    mysqli_stmt_bind_param($stmt, "s", $siswa['nis']);
    mysqli_stmt_execute($stmt);
    $resRiwayat = mysqli_stmt_get_result($stmt);

    while ($r = mysqli_fetch_assoc($resRiwayat)) {
        $riwayat[] = $r;
    }

    mysqli_stmt_close($stmt);
}

$status = $siswa['status_hasil'] ?? '';
$warnaStatus = '#64748b';

if ($status === 'Kondisi Positif') {
    $warnaStatus = '#15803d';
} elseif ($status === 'Perlu Penguatan') {
    $warnaStatus = '#1d4ed8';
} elseif ($status === 'Perlu Pengembangan') {
    $warnaStatus = '#a16207';
} elseif ($status === 'Perlu Pendampingan') {
    $warnaStatus = '#b91c1c';
}

$pilihanSkor = [
    5 => 'Sangat Sesuai',
    4 => 'Sesuai',
    3 => 'Cukup',
    2 => 'Kurang',
    1 => 'Tidak Sesuai'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Detail Siswa - E-MOODS BK</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:Arial,sans-serif;background:#f1f5f9;color:#1e293b}
header{background:linear-gradient(135deg,#1d4ed8,#38bdf8);color:white;padding:25px}
.wrap{max-width:1050px;margin:auto;padding:20px 15px}
.card{background:white;border-radius:16px;padding:22px;margin-bottom:18px;box-shadow:0 4px 15px #0000000b}
h1{margin:0 0 8px}h2{color:#1e3a8a;margin-top:0}
.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
.item{background:#f8fafc;border-radius:10px;padding:12px;overflow-wrap:anywhere}
.label{font-size:13px;color:#64748b;margin-bottom:5px}
.value{font-weight:bold}
.status{border-left:6px solid <?php echo $warnaStatus; ?>}
.status h2{color:<?php echo $warnaStatus; ?>}
.total{font-size:36px;font-weight:bold;color:#2563eb}
.aspect{margin:16px 0}
.aspect-head{display:flex;justify-content:space-between;gap:10px;margin-bottom:7px}
.bar{height:12px;background:#e2e8f0;border-radius:8px;overflow:hidden}
.fill{height:100%;background:#2563eb}
.question{padding:14px 0;border-bottom:1px solid #e2e8f0}
.question:last-child{border-bottom:0}
.answer{display:inline-block;background:#eff6ff;color:#1d4ed8;padding:6px 10px;border-radius:8px;margin-top:7px;font-weight:bold}
table{width:100%;border-collapse:collapse;min-width:650px}
th,td{text-align:left;padding:11px;border-bottom:1px solid #e2e8f0}
th{background:#eff6ff;color:#1e3a8a}
.table-wrap{overflow-x:auto}
.btn{display:inline-block;padding:11px 15px;background:#2563eb;color:white;text-decoration:none;border-radius:9px;margin:4px 5px 4px 0}
.btn.secondary{background:#475569}
.note{color:#64748b;font-size:13px;line-height:1.6}
@media(max-width:600px){.grid{grid-template-columns:1fr}.card{padding:16px}.total{font-size:30px}}
@media print{header{background:white;color:black}.btn{display:none}body{background:white}.card{box-shadow:none;border:1px solid #ddd;break-inside:avoid}}
</style>
</head>
<body>

<header>
    <div class="wrap" style="padding-top:0;padding-bottom:0">
        <div style="font-size:38px">🧠</div>
        <h1>Detail Refleksi Siswa</h1>
        <div>E-MOODS BK — SMAN 1 LAMONGAN</div>
    </div>
</header>

<main class="wrap">

    <div class="card">
        <a class="btn" href="dashboard.php">← Kembali ke Dashboard</a>
        <button class="btn secondary" onclick="window.print()">🖨️ Cetak Detail</button>
    </div>

    <section class="card">
        <h2>👤 Identitas Siswa</h2>
        <div class="grid">
            <div class="item"><div class="label">Nama</div><div class="value"><?php echo tampil($siswa['nama']); ?></div></div>
            <div class="item"><div class="label">NIS</div><div class="value"><?php echo tampil($siswa['nis']); ?></div></div>
            <div class="item"><div class="label">Kelas</div><div class="value"><?php echo tampil($siswa['kelas']); ?></div></div>
            <div class="item"><div class="label">Nomor Absen</div><div class="value"><?php echo tampil($siswa['no_absen']); ?></div></div>
            <div class="item"><div class="label">Jenis Kelamin</div><div class="value"><?php echo tampil($siswa['jenis_kelamin']); ?></div></div>
            <div class="item"><div class="label">Tanggal dan Jam Pengisian</div><div class="value"><?php echo !empty($siswa['created_at']) ? e(date('d-m-Y H:i', strtotime($siswa['created_at']))) : '-'; ?></div></div>
        </div>
    </section>

    <section class="card">
        <h2>😊 Refleksi Emosi</h2>
        <div class="grid">
            <div class="item"><div class="label">Emosi yang Dipilih</div><div class="value"><?php echo tampil($siswa['emosi']); ?></div></div>
            <div class="item"><div class="label">Intensitas Emosi</div><div class="value"><?php echo tampil($siswa['intensitas']); ?> / 5</div></div>
            <div class="item"><div class="label">Tempat Emosi Muncul</div><div class="value"><?php echo tampil($siswa['tempat_emosi']); ?></div></div>
            <div class="item"><div class="label">Penyebab yang Dipilih</div><div class="value"><?php echo tampil($siswa['penyebab_emosi']); ?></div></div>
            <div class="item"><div class="label">Respons Siswa</div><div class="value"><?php echo tampil($siswa['respons_emosi']); ?></div></div>
        </div>
        <div style="margin-top:15px">
            <div class="label">Catatan Tambahan Siswa</div>
            <p><?php echo nl2br(e($siswa['catatan_emosi'] ?: '-')); ?></p>
        </div>
    </section>

    <section class="card status">
        <h2>Status Hasil Refleksi</h2>
        <div style="font-size:18px;font-weight:bold;color:<?php echo $warnaStatus; ?>">
            <?php echo tampil($siswa['status_hasil']); ?>
        </div>
        <div class="total"><?php echo tampil($siswa['total_skor']); ?> / 100</div>
        <p>Kategori: <strong><?php echo tampil($siswa['kategori']); ?></strong></p>
        <p>Fokus pengembangan: <strong><?php echo tampil($siswa['fokus_pengembangan']); ?></strong></p>
        <h3>Saran dari sistem</h3>
        <?php if ($status === 'Kondisi Positif') { ?>
            Pertahankan kebiasaan refleksi diri dan strategi pengelolaan emosi yang membantu.
        <?php } elseif ($status === 'Perlu Penguatan') { ?>
            Berikan penguatan keterampilan sosial-emosional, terutama pada aspek yang menjadi fokus pengembangan.
        <?php } elseif ($status === 'Perlu Pengembangan') { ?>
            Rencanakan latihan bertahap untuk memperkuat aspek yang menjadi fokus pengembangan.
        <?php } else { ?>
            Pertimbangkan pemantauan dan layanan BK yang sesuai dengan kebutuhan siswa. Bicarakan hasil secara pribadi dan penuh empati.
        <?php } ?>
        <p class="note">Hasil ini merupakan refleksi diri, bukan diagnosis psikologis. Tindak lanjut perlu mempertimbangkan percakapan dengan siswa dan konteksnya.</p>
    </section>

    <section class="card">
        <h2>📊 Lima Kompetensi CASEL</h2>
        <?php foreach ($aspek as $namaAspek => $nilai) {
            $nilaiAngka = is_numeric($nilai) ? (int)$nilai : 0;
            $persen = max(0, min(100, ($nilaiAngka / 20) * 100));
        ?>
            <div class="aspect">
                <div class="aspect-head">
                    <strong><?php echo e($namaAspek); ?></strong>
                    <strong><?php echo $nilai === null ? '-' : e($nilai); ?> / 20</strong>
                </div>
                <div class="bar"><div class="fill" style="width:<?php echo $persen; ?>%"></div></div>
            </div>
        <?php } ?>
    </section>

    <section class="card">
        <h2>📝 Jawaban 20 Pertanyaan Refleksi</h2>
        <?php foreach ($pertanyaan as $namaAspek => $daftar) { ?>
            <h3><?php echo e($namaAspek); ?></h3>
            <?php foreach ($daftar as $nomor => $teks) {
                $skor = $siswa['soal' . $nomor] ?? null;
            ?>
                <div class="question">
                    <strong><?php echo $nomor; ?>. <?php echo e($teks); ?></strong><br>
                    <span class="answer">
                        <?php
                        if ($skor !== null && isset($pilihanSkor[(int)$skor])) {
                            echo e($pilihanSkor[(int)$skor]) . ' (' . e($skor) . ')';
                        } else {
                            echo 'Belum tersedia';
                        }
                        ?>
                    </span>
                </div>
            <?php } ?>
        <?php } ?>
    </section>

    <section class="card">
        <h2>🕒 Riwayat Pengisian Siswa</h2>
        <p class="note">Menampilkan maksimal 30 pengisian terbaru dengan NIS yang sama. Klik ID untuk membuka detail pengisian tersebut.</p>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal & Jam</th>
                        <th>Emosi</th>
                        <th>Intensitas</th>
                        <th>Skor</th>
                        <th>Status</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($riwayat as $r) { ?>
                    <tr>
                        <td><?php echo !empty($r['created_at']) ? e(date('d-m-Y H:i', strtotime($r['created_at']))) : '-'; ?></td>
                        <td><?php echo tampil($r['emosi']); ?></td>
                        <td><?php echo tampil($r['intensitas']); ?>/5</td>
                        <td><?php echo tampil($r['total_skor']); ?>/100</td>
                        <td><?php echo tampil($r['status_hasil']); ?></td>
                        <td><a href="detail_siswa.php?id=<?php echo (int)$r['id']; ?>">Buka</a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

</main>
</body>
</html>
