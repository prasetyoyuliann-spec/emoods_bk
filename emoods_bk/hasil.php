<?php

include "koneksi.php";

/* =========================
   AMBIL DATA IDENTITAS
========================= */
$nis = $_POST['nis'] ?? '';
$nama = $_POST['nama'] ?? '';
$kelas = $_POST['kelas'] ?? '';
$no_absen = $_POST['no_absen'] ?? '';
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';

$emosi = trim((string)($_POST['emosi'] ?? ''));
$intensitas = intval($_POST['intensitas'] ?? 0);

// Pastikan pilihan emosi diterima dari formulir siswa.
if ($emosi === '') {
    exit("Pilihan emosi belum diterima. Silakan kembali ke halaman asesmen dan pilih emosi terlebih dahulu.");
}

$tempat_emosi = $_POST['tempat_emosi'] ?? '';
$penyebab_emosi = $_POST['penyebab_emosi'] ?? '';
$respons_emosi = $_POST['respons_emosi'] ?? '';
$catatan_emosi = $_POST['catatan_emosi'] ?? '';

/* =========================
   AMBIL 20 SKOR CASEL
========================= */

$soal = [];

for ($i = 1; $i <= 20; $i++) {
    $soal[$i] = intval($_POST['soal' . $i] ?? 0);
}

/* =========================
   HITUNG 5 ASPEK CASEL
========================= */

$kesadaran_diri =
    $soal[1] +
    $soal[2] +
    $soal[3] +
    $soal[4];

$manajemen_diri =
    $soal[5] +
    $soal[6] +
    $soal[7] +
    $soal[8];

$kesadaran_sosial =
    $soal[9] +
    $soal[10] +
    $soal[11] +
    $soal[12];

$keterampilan_berelasi =
    $soal[13] +
    $soal[14] +
    $soal[15] +
    $soal[16];

$keputusan_bertanggung_jawab =
    $soal[17] +
    $soal[18] +
    $soal[19] +
    $soal[20];

/* =========================
   TOTAL SKOR
========================= */

$total_skor =
    $kesadaran_diri +
    $manajemen_diri +
    $kesadaran_sosial +
    $keterampilan_berelasi +
    $keputusan_bertanggung_jawab;


/* =========================
   KATEGORI DAN STATUS
========================= */

if ($total_skor >= 85) {

    $kategori = "Sangat Baik";
    $status_hasil = "Kondisi Positif";

} elseif ($total_skor >= 70) {

    $kategori = "Baik";
    $status_hasil = "Perlu Penguatan";

} elseif ($total_skor >= 55) {

    $kategori = "Perlu Pengembangan";
    $status_hasil = "Perlu Pengembangan";

} else {

    $kategori = "Perlu Pendampingan";
    $status_hasil = "Perlu Pendampingan";
}


/* =========================
   CARI ASPEK TERENDAH
========================= */

$aspek = [
    "Kesadaran Diri" => $kesadaran_diri,
    "Manajemen Diri" => $manajemen_diri,
    "Kesadaran Sosial" => $kesadaran_sosial,
    "Keterampilan Berelasi" => $keterampilan_berelasi,
    "Keputusan Bertanggung Jawab" => $keputusan_bertanggung_jawab
];

$fokus_pengembangan = array_search(min($aspek), $aspek);


/* =========================
   SARAN
========================= */

if ($status_hasil == "Kondisi Positif") {

    $saran =
        "Kemampuan sosial-emosional menunjukkan kondisi positif. "
        . "Pertahankan kebiasaan refleksi diri dan terus gunakan cara yang sehat untuk mengelola emosi.";

} elseif ($status_hasil == "Perlu Penguatan") {

    $saran =
        "Kemampuan sosial-emosional sudah cukup baik. "
        . "Berikan penguatan terutama pada aspek " . $fokus_pengembangan . ".";

} elseif ($status_hasil == "Perlu Pengembangan") {

    $saran =
        "Beberapa keterampilan sosial-emosional masih perlu diperkuat. "
        . "Fokus pengembangan utama adalah " . $fokus_pengembangan . ".";

} else {

    $saran =
        "Hasil refleksi menunjukkan beberapa aspek perlu mendapat perhatian lebih. "
        . "Disarankan dilakukan pemantauan dan, bila diperlukan, layanan BK secara lebih personal.";
}


/* =========================
   PESAN BERDASARKAN INTENSITAS
========================= */

if ($intensitas >= 4) {

    $pesan_emosi =
        "Emosi yang sedang dirasakan cukup kuat. "
        . "Sebaiknya lakukan teknik menenangkan diri sebelum mengambil keputusan atau bertindak.";

} elseif ($intensitas >= 2) {

    $pesan_emosi =
        "Emosi yang dirasakan berada pada tingkat ringan sampai sedang. "
        . "Tetap kenali penyebab dan gunakan cara yang membantu menjaga diri tetap tenang.";

} else {

    $pesan_emosi =
        "Emosi yang dirasakan relatif ringan. Tetap lakukan refleksi dan kenali kebutuhan diri.";
}


/* =========================
   SIMPAN KE DATABASE
========================= */

$sql = "INSERT INTO hasil_emosi (
    nis,
    nama,
    kelas,
    no_absen,
    jenis_kelamin,
    emosi,
    intensitas,
    tempat_emosi,
    penyebab_emosi,
    respons_emosi,
    catatan_emosi,

    soal1, soal2, soal3, soal4, soal5,
    soal6, soal7, soal8, soal9, soal10,
    soal11, soal12, soal13, soal14, soal15,
    soal16, soal17, soal18, soal19, soal20,

    total_skor,
    kategori,

    kesadaran_diri,
    manajemen_diri,
    kesadaran_sosial,
    keterampilan_berelasi,
    keputusan_bertanggung_jawab,

    status_hasil,
    fokus_pengembangan
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
    ?, ?,
    ?, ?, ?, ?, ?,
    ?, ?
)";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Gagal menyiapkan database: " . mysqli_error($koneksi));
}


/* =========================
   BIND PARAMETER
========================= */

mysqli_stmt_bind_param(
    $stmt,
    "ssssssi" .
    "ssss" .
    "iiiiiiiiiiiiiiiiiiii" .
    "is" .
    "iiiii" .
    "ss",

    $nis,
    $nama,
    $kelas,
    $no_absen,
    $jenis_kelamin,
    $emosi,
    $intensitas,

    $tempat_emosi,
    $penyebab_emosi,
    $respons_emosi,
    $catatan_emosi,

    $soal[1],
    $soal[2],
    $soal[3],
    $soal[4],
    $soal[5],
    $soal[6],
    $soal[7],
    $soal[8],
    $soal[9],
    $soal[10],
    $soal[11],
    $soal[12],
    $soal[13],
    $soal[14],
    $soal[15],
    $soal[16],
    $soal[17],
    $soal[18],
    $soal[19],
    $soal[20],

    $total_skor,
    $kategori,

    $kesadaran_diri,
    $manajemen_diri,
    $kesadaran_sosial,
    $keterampilan_berelasi,
    $keputusan_bertanggung_jawab,

    $status_hasil,
    $fokus_pengembangan
);


/* =========================
   JALANKAN
========================= */

if (!mysqli_stmt_execute($stmt)) {

    die("Data gagal disimpan: " . mysqli_stmt_error($stmt));

}

// Ambil ID data asesmen yang baru disimpan
$id_hasil = mysqli_insert_id($koneksi);

mysqli_stmt_close($stmt);


/* =========================
   FUNGSI WARNA STATUS
========================= */

if ($status_hasil == "Kondisi Positif") {

    $warna = "#16a34a";
    $ikon = "🟢";

} elseif ($status_hasil == "Perlu Penguatan") {

    $warna = "#2563eb";
    $ikon = "🔵";

} elseif ($status_hasil == "Perlu Pengembangan") {

    $warna = "#ca8a04";
    $ikon = "🟡";

} else {

    $warna = "#dc2626";
    $ikon = "🔴";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Hasil E-MOODS BK</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #eef5ff;
    color: #1e293b;
}

.container {
    max-width: 850px;
    margin: 30px auto;
    padding: 15px;
}

.card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

.header {
    text-align: center;
    background: linear-gradient(135deg, #2563eb, #38bdf8);
    color: white;
}

.header h1 {
    margin: 5px 0;
}

.header p {
    margin: 5px 0;
}

.identity {
    line-height: 1.8;
}

.status {
    text-align: center;
    border: 3px solid <?php echo $warna; ?>;
}

.status h2 {
    color: <?php echo $warna; ?>;
}

.total {
    font-size: 45px;
    font-weight: bold;
    color: #2563eb;
}

.aspect {
    margin: 18px 0;
}

.aspect-title {
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    margin-bottom: 7px;
}

.bar {
    width: 100%;
    height: 14px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}

.fill {
    height: 100%;
    background: #2563eb;
    border-radius: 10px;
}

.emotion {
    font-size: 22px;
    text-align: center;
}

.saran {
    background: #eff6ff;
    border-left: 5px solid #2563eb;
    padding: 15px;
    border-radius: 10px;
    line-height: 1.7;
}

.btn {
    display: inline-block;
    padding: 13px 20px;
    background: #2563eb;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    margin-top: 10px;
}

.btn:hover {
    background: #1d4ed8;
}

.center {
    text-align: center;
}

.small {
    color: #64748b;
    font-size: 14px;
}

</style>

</head>

<body>

<div class="container">

    <div class="card header">

        <div style="font-size:50px;">🧠</div>

        <h1>E-MOODS BK</h1>

        <p>Hasil Refleksi & Regulasi Emosi</p>

        <p>Berbasis CASEL</p>

    </div>


    <div class="card identity">

        <h2>👤 Identitas Siswa</h2>

        <b>Nama:</b>
        <?php echo htmlspecialchars($nama); ?><br>

        <b>NIS:</b>
        <?php echo htmlspecialchars($nis); ?><br>

        <b>Kelas:</b>
        <?php echo htmlspecialchars($kelas); ?><br>

        <b>No. Absen:</b>
        <?php echo htmlspecialchars($no_absen); ?>

    </div>


    <div class="card emotion">

        <h2>😊 Emosi Saat Ini</h2>

        <p>
            Kamu memilih:
            <strong>
                <?php echo htmlspecialchars($emosi); ?>
            </strong>
        </p>

        <p>
            Intensitas:
            <strong>
                <?php echo $intensitas; ?>/5
            </strong>
        </p>

        <p class="small">
            <?php echo htmlspecialchars($pesan_emosi); ?>
        </p>

    </div>


    <div class="card status">

        <div style="font-size:50px;">
            <?php echo $ikon; ?>
        </div>

        <h2>
            <?php echo htmlspecialchars($status_hasil); ?>
        </h2>

        <div class="total">
            <?php echo $total_skor; ?>/100
        </div>

        <p>
            Kategori:
            <strong>
                <?php echo htmlspecialchars($kategori); ?>
            </strong>
        </p>

    </div>


    <div class="card">

        <h2>🧠 Profil 5 Kompetensi CASEL</h2>

        <?php

        $data_aspek = [
            "Kesadaran Diri" => $kesadaran_diri,
            "Manajemen Diri" => $manajemen_diri,
            "Kesadaran Sosial" => $kesadaran_sosial,
            "Keterampilan Berelasi" => $keterampilan_berelasi,
            "Keputusan Bertanggung Jawab" => $keputusan_bertanggung_jawab
        ];

        foreach ($data_aspek as $nama_aspek => $nilai) {

            $persen = ($nilai / 20) * 100;

            echo "
            <div class='aspect'>

                <div class='aspect-title'>

                    <span>$nama_aspek</span>

                    <span>$nilai / 20</span>

                </div>

                <div class='bar'>

                    <div class='fill'
                         style='width: {$persen}%;'>
                    </div>

                </div>

            </div>
            ";
        }

        ?>

    </div>


    <div class="card">

        <h2>🎯 Fokus Pengembangan</h2>

        <p>
            Aspek yang memperoleh skor relatif paling rendah:
        </p>

        <h3>
            <?php echo htmlspecialchars($fokus_pengembangan); ?>
        </h3>

    </div>


    <div class="card">

        <h2>💡 Saran</h2>

        <div class="saran">

            <?php echo htmlspecialchars($saran); ?>

        </div>

    </div>


    <div class="card center">

     
<p>
    Terima kasih sudah melakukan refleksi hari ini. 🌱
</p>

<p class="small">
    Hasil ini merupakan gambaran refleksi diri,
    bukan diagnosis psikologis.
</p>

<!-- TOMBOL BARU: OTAK RAPTOR -->
<a href="relaksasi.php?id=<?php echo (int)$id_hasil; ?>" class="btn">
    🧠 Lanjut ke Ruang Tenang Otak Raptor
</a>

<!-- TOMBOL LAMA: TETAP DIPERTAHANKAN -->
<a href="index.php" class="btn">
    🏠 Kembali ke Beranda
</a>
</div>
</div>
</body>
</html>


    </div>

</div>

</body>
</html>