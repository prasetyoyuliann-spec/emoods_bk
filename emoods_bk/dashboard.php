<?php

session_start();
include "koneksi.php";

/* =========================
   CEK LOGIN
========================= */

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}


/* Menyamakan variasi penulisan emosi, misalnya "😊 Senang", "senang",
   atau "SENANG", agar tabel dan rekap harian menghitungnya dengan benar. */
function normalisasiEmosi($nilai) {
    $teks = trim((string)($nilai ?? ''));
    if ($teks === '') {
        return '';
    }

    $teksKecil = function_exists('mb_strtolower')
        ? mb_strtolower($teks, 'UTF-8')
        : strtolower($teks);

    $daftar = [
        'senang' => ['senang', 'bahagia', 'happy'],
        'cemas' => ['cemas', 'khawatir', 'anxious'],
        'marah' => ['marah', 'kesal', 'anger'],
        'lelah' => ['lelah', 'capek', 'letih'],
        'sedih' => ['sedih', 'duka'],
        'takut' => ['takut', 'ketakutan'],
        'tenang' => ['tenang', 'damai'],
        'bingung' => ['bingung'],
        'kecewa' => ['kecewa'],
        'semangat' => ['semangat', 'antusias']
    ];

    foreach ($daftar as $kanonis => $kataKunci) {
        foreach ($kataKunci as $kata) {
            if (strpos($teksKecil, $kata) !== false) {
                return $kanonis === 'senang' ? 'Senang' : ucfirst($kanonis);
            }
        }
    }

    return $teks;
}

function ikonEmosi($nama) {
    $ikon = [
        'Senang' => '😊',
        'Bahagia' => '😄',
        'Cemas' => '😟',
        'Marah' => '😡',
        'Lelah' => '😴',
        'Sedih' => '😢',
        'Takut' => '😨',
        'Tenang' => '😌',
        'Bingung' => '😕',
        'Kecewa' => '😞',
        'Semangat' => '💪'
    ];
    return $ikon[$nama] ?? '💭';
}


/* =========================
   PENCARIAN
========================= */

$cari = $_GET['cari'] ?? '';

$cari_aman = mysqli_real_escape_string(
    $koneksi,
    $cari
);


/* =========================
   TOTAL PENGISIAN
========================= */

$q_total = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS jumlah
     FROM hasil_emosi"
);

$total_pengisian =
    mysqli_fetch_assoc($q_total)['jumlah'];


/* =========================
   JUMLAH SISWA UNIK
========================= */

$q_unik = mysqli_query(
    $koneksi,
    "SELECT COUNT(DISTINCT nis) AS jumlah
     FROM hasil_emosi"
);

$total_pengisi =
    mysqli_fetch_assoc($q_unik)['jumlah'];


/* =========================
   EMOSI HARI INI
========================= */

$q_emosi = mysqli_query(
    $koneksi,
    "SELECT emosi, COUNT(*) AS jumlah
     FROM hasil_emosi
     WHERE DATE(created_at) = CURDATE()
     GROUP BY emosi
     ORDER BY jumlah DESC"
);


/* =========================
   STATUS
========================= */

$q_positif = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS jumlah
     FROM hasil_emosi
     WHERE status_hasil = 'Kondisi Positif'"
);

$positif =
    mysqli_fetch_assoc($q_positif)['jumlah'];


$q_penguatan = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS jumlah
     FROM hasil_emosi
     WHERE status_hasil = 'Perlu Penguatan'"
);

$penguatan =
    mysqli_fetch_assoc($q_penguatan)['jumlah'];


$q_pengembangan = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS jumlah
     FROM hasil_emosi
     WHERE status_hasil = 'Perlu Pengembangan'"
);

$pengembangan =
    mysqli_fetch_assoc($q_pengembangan)['jumlah'];


$q_pendampingan = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS jumlah
     FROM hasil_emosi
     WHERE status_hasil = 'Perlu Pendampingan'"
);

$pendampingan =
    mysqli_fetch_assoc($q_pendampingan)['jumlah'];


/* =========================
   RATA-RATA CASEL
========================= */

$q_rata = mysqli_query(
    $koneksi,
    "SELECT
        AVG(kesadaran_diri) AS kd,
        AVG(manajemen_diri) AS md,
        AVG(kesadaran_sosial) AS ks,
        AVG(keterampilan_berelasi) AS kb,
        AVG(keputusan_bertanggung_jawab) AS kt
     FROM hasil_emosi"
);

$rata = mysqli_fetch_assoc($q_rata);

$kd = round($rata['kd'] ?? 0, 1);
$md = round($rata['md'] ?? 0, 1);
$ks = round($rata['ks'] ?? 0, 1);
$kb = round($rata['kb'] ?? 0, 1);
$kt = round($rata['kt'] ?? 0, 1);


/* =========================
   DATA SISWA
========================= */

$sql_data = "
    SELECT *
    FROM hasil_emosi
";

if ($cari != '') {

    $sql_data .= "
        WHERE
        nama LIKE '%$cari_aman%'
        OR kelas LIKE '%$cari_aman%'
    ";

}

$sql_data .= "
    ORDER BY created_at DESC
    LIMIT 100
";

$q_data = mysqli_query(
    $koneksi,
    $sql_data
);

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Dashboard Guru BK</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f1f5f9;
    color: #1e293b;
}


/* HEADER */

.header {
    background:
        linear-gradient(
            135deg,
            #1d4ed8,
            #38bdf8
        );

    color: white;
    padding: 25px;
}

.header-inner {
    max-width: 1200px;
    margin: auto;
}

.header h1 {
    margin: 5px 0;
}

.header p {
    margin: 5px 0;
}


/* NAV */

.nav {
    background: white;
    box-shadow:
        0 2px 10px
        rgba(0,0,0,.08);
}

.nav-inner {
    max-width: 1200px;
    margin: auto;
    padding: 13px 20px;

    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav a {
    text-decoration: none;
    font-weight: bold;
    color: #2563eb;
}

.logout {
    color: #dc2626 !important;
}


/* CONTAINER */

.container {
    max-width: 1200px;
    margin: 25px auto;
    padding: 0 15px;
}


/* STAT */

.stats {
    display: grid;

    grid-template-columns:
        repeat(6, 1fr);

    gap: 15px;

    margin-bottom: 25px;
}

.card {
    background: white;
    border-radius: 18px;
    padding: 20px;

    box-shadow:
        0 5px 18px
        rgba(0,0,0,.06);
}

.stat {
    text-align: center;
}

.icon {
    font-size: 32px;
}

.number {
    font-size: 30px;
    font-weight: bold;
    margin: 5px;
}

.label {
    color: #64748b;
    font-size: 14px;
}


/* STATUS */

.positif {
    border-top: 5px solid #16a34a;
}

.penguatan {
    border-top: 5px solid #2563eb;
}

.pengembangan {
    border-top: 5px solid #ca8a04;
}

.pendampingan {
    border-top: 5px solid #dc2626;
}


/* SECTION */

.title {
    color: #1e3a8a;
    margin-top: 0;
}


/* EMOSI */

.emotions {
    display: grid;

    grid-template-columns:
        repeat(7, 1fr);

    gap: 10px;
}

.emotion {
    text-align: center;
    padding: 15px 8px;

    background: #f8fafc;

    border-radius: 12px;
}

.emotion-icon {
    font-size: 30px;
}

.emotion-number {
    font-size: 24px;
    font-weight: bold;
    color: #2563eb;
}


/* CASEL */

.casels {
    display: grid;

    grid-template-columns:
        repeat(5, 1fr);

    gap: 15px;
}

.casel {
    text-align: center;
}

.casel-number {
    font-size: 27px;
    font-weight: bold;
    color: #2563eb;
}

.progress {
    height: 12px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    margin-top: 10px;
}

.fill {
    height: 100%;
    background: #2563eb;
}


/* SEARCH */

.search-box {
    display: flex;
    gap: 10px;
}

.search-box input {
    flex: 1;

    padding: 13px;

    border: 1px solid #cbd5e1;

    border-radius: 10px;

    font-size: 16px;
}

.btn {
    padding: 13px 18px;

    background: #2563eb;

    color: white;

    border: none;

    border-radius: 10px;

    text-decoration: none;

    cursor: pointer;
}

.btn-reset {
    background: #64748b;
}


/* TABLE */

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1050px;
}

th {
    background: #eff6ff;
    color: #1e3a8a;
}

th, td {
    padding: 12px;

    border-bottom:
        1px solid #e2e8f0;

    text-align: left;
}

tr:hover {
    background: #f8fafc;
}


/* BADGE */

.badge {
    display: inline-block;

    padding: 6px 10px;

    border-radius: 20px;

    font-size: 12px;

    font-weight: bold;
}

.b-positive {
    background: #dcfce7;
    color: #166534;
}

.b-strength {
    background: #dbeafe;
    color: #1e40af;
}

.b-development {
    background: #fef3c7;
    color: #92400e;
}

.b-support {
    background: #fee2e2;
    color: #991b1b;
}


/* MOBILE */

@media(max-width:1000px) {

    .stats {
        grid-template-columns:
            repeat(3, 1fr);
    }

    .casels {
        grid-template-columns:
            repeat(2, 1fr);
    }

    .emotions {
        grid-template-columns:
            repeat(4, 1fr);
    }

}

@media(max-width:600px) {

    .stats {
        grid-template-columns: 1fr 1fr;
    }

    .emotions {
        grid-template-columns:
            repeat(2, 1fr);
    }

    .casels {
        grid-template-columns: 1fr;
    }

    .nav-inner {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .search-box {
        flex-direction: column;
    }

}

</style>

</head>


<body>


<!-- HEADER -->

<div class="header">

    <div class="header-inner">

        <div style="font-size:45px;">
            🧠
        </div>

        <h1>
            Dashboard Guru BK
        </h1>

        <p>
            E-MOODS BK — SMAN 1 LAMONGAN
        </p>

        <p>
            Selamat datang,
            <strong>
                <?php
                echo htmlspecialchars(
                    $_SESSION['nama']
                );
                ?>
            </strong>
        </p>

    </div>

</div>


<!-- NAV -->

<div class="nav">

    <div class="nav-inner">

        <div>
            📊 Dashboard E-MOODS BK
        </div>

        <div>

          <a href="index.php">
    🏠 Beranda
</a>

&nbsp;&nbsp;

<a href="grafik_emosi.php">
    📈 Grafik Perkembangan
</a>

&nbsp;&nbsp;

<a href="logout.php" class="logout">
    🚪 Keluar
</a>

        </div>

    </div>

</div>


<div class="container">


<!-- STATISTIK -->

<div class="stats">


    <div class="card stat">

        <div class="icon">👥</div>

        <div class="number">
            <?php echo $total_pengisi; ?>
        </div>

        <div class="label">
            Siswa Pengisi
        </div>

    </div>


    <div class="card stat">

        <div class="icon">📝</div>

        <div class="number">
            <?php echo $total_pengisian; ?>
        </div>

        <div class="label">
            Total Pengisian
        </div>

    </div>


    <div class="card stat positif">

        <div class="icon">🟢</div>

        <div class="number">
            <?php echo $positif; ?>
        </div>

        <div class="label">
            Kondisi Positif
        </div>

    </div>


    <div class="card stat penguatan">

        <div class="icon">🔵</div>

        <div class="number">
            <?php echo $penguatan; ?>
        </div>

        <div class="label">
            Perlu Penguatan
        </div>

    </div>


    <div class="card stat pengembangan">

        <div class="icon">🟡</div>

        <div class="number">
            <?php echo $pengembangan; ?>
        </div>

        <div class="label">
            Perlu Pengembangan
        </div>

    </div>


    <div class="card stat pendampingan">

        <div class="icon">🔴</div>

        <div class="number">
            <?php echo $pendampingan; ?>
        </div>

        <div class="label">
            Perlu Pendampingan
        </div>

    </div>


</div>


<!-- EMOSI HARI INI -->

<div class="card">

    <h2 class="title">
        😊 Emosi Harian — Hari Ini
    </h2>

    <div class="emotions">

    <?php

    $emoji = [
        "Senang" => "😊",
        "Cemas" => "😟",
        "Marah" => "😡",
        "Lelah" => "😴",
        "Sedih" => "😢",
        "Takut" => "😨",
        "Tenang" => "😌",
        "Bingung" => "😕",
        "Kecewa" => "😞",
        "Semangat" => "💪"
    ];

    $emosi_data = array_fill_keys(array_keys($emoji), 0);

    while ($e = mysqli_fetch_assoc($q_emosi)) {
        $emosiNormal = normalisasiEmosi($e['emosi'] ?? '');
        if ($emosiNormal !== '' && isset($emosi_data[$emosiNormal])) {
            $emosi_data[$emosiNormal] += (int)$e['jumlah'];
        }
    }

    foreach ($emoji as $nama_emosi => $icon) {
        $jumlah = $emosi_data[$nama_emosi] ?? 0;

    ?>

        <div class="emotion">

            <div class="emotion-icon">
                <?php echo $icon; ?>
            </div>

            <strong>
                <?php echo $nama_emosi; ?>
            </strong>

            <div class="emotion-number">
                <?php echo $jumlah; ?>
            </div>

            <small>
                siswa
            </small>

        </div>

    <?php } ?>

    </div>

</div>


<!-- CASEL -->

<div class="card">

    <h2 class="title">
        🧠 Rata-rata 5 Kompetensi CASEL
    </h2>

    <div class="casels">


<?php

$casel_data = [

    "Kesadaran Diri" => $kd,

    "Manajemen Diri" => $md,

    "Kesadaran Sosial" => $ks,

    "Keterampilan Berelasi" => $kb,

    "Keputusan Bertanggung Jawab" => $kt

];


foreach ($casel_data as $nama => $nilai) {

    $persen =
        ($nilai / 20) * 100;

?>

        <div class="casel">

            <div>
                <?php echo $nama; ?>
            </div>

            <div class="casel-number">
                <?php echo $nilai; ?>/20
            </div>

            <div class="progress">

                <div
                    class="fill"
                    style="
                        width:
                        <?php echo $persen; ?>%;
                    "
                ></div>

            </div>

        </div>

<?php } ?>

    </div>

</div>


<!-- PENCARIAN -->

<div class="card">

    <h2 class="title">
        🔎 Cari Data Siswa
    </h2>

    <form method="GET">

        <div class="search-box">

            <input
                type="text"
                name="cari"
                value="<?php
                echo htmlspecialchars($cari);
                ?>"
                placeholder="Ketik nama siswa atau kelas, contoh: Budi atau X.1"
            >

            <button
                type="submit"
                class="btn"
            >
                🔎 Cari
            </button>

            <a
                href="dashboard.php"
                class="btn btn-reset"
            >
                ↻ Reset
            </a>

        </div>

    </form>

</div>


<!-- TABEL -->

<div class="card">

    <h2 class="title">

        📋

        <?php if ($cari != '') { ?>

            Hasil Pencarian:
            "<?php echo htmlspecialchars($cari); ?>"

        <?php } else { ?>

            Data Pengisian Terbaru

        <?php } ?>

    </h2>


    <div class="table-wrapper">

        <table>

            <thead>

                <tr>

                    <th>No</th>

                    <th>Nama</th>

                    <th>Kelas</th>

                    <th>Emosi</th>

                    <th>Intensitas</th>

                    <th>Skor CASEL</th>

                    <th>Keterangan Kondisi</th>

                    <th>Fokus</th>

                    <th>Tanggal & Jam</th>

                </tr>

            </thead>


            <tbody>

<?php

$no = 1;

if (mysqli_num_rows($q_data) > 0) {

    while ($row =
        mysqli_fetch_assoc($q_data)
    ) {

        $status =
            $row['status_hasil'] ?? '';

        // Normalisasi emosi agar emoji/penggunaan huruf besar-kecil tetap terbaca
        $emosi_asli = trim((string)($row['emosi'] ?? ''));
        $nama_emosi = normalisasiEmosi($emosi_asli);
        if ($nama_emosi === '') {
            $nama_emosi = 'Belum tercatat';
        }
        $ikon = ikonEmosi($nama_emosi);


        if (
            $status ==
            "Kondisi Positif"
        ) {

            $badge =
                "b-positive";

        } elseif (
            $status ==
            "Perlu Penguatan"
        ) {

            $badge =
                "b-strength";

        } elseif (
            $status ==
            "Perlu Pengembangan"
        ) {

            $badge =
                "b-development";

        } else {

            $badge =
                "b-support";

        }

?>

                <tr>

                    <td>
                        <?php echo $no++; ?>
                    </td>

                    <td>
    <strong>
        <a
            href="detail_siswa.php?id=<?php echo (int)$row['id']; ?>"
            style="color:#2563eb;text-decoration:none;"
        >
            <?php echo htmlspecialchars((string)$row['nama'], ENT_QUOTES, 'UTF-8'); ?>
        </a>
    </strong>
</td>

                    <td>
                        <?php
                        echo htmlspecialchars((string)($row['kelas'] ?? '-'), ENT_QUOTES, 'UTF-8');
                        ?>
                    </td>

                    <td>
                        <span style="font-size:20px;" aria-hidden="true"><?php echo $ikon; ?></span>
                        <strong><?php echo htmlspecialchars($nama_emosi, ENT_QUOTES, 'UTF-8'); ?></strong>
                    </td>

                    <td>
                        <?php
                        echo $row['intensitas'];
                        ?>/5
                    </td>

                    <td>

                        <strong>
                            <?php
                            echo $row['total_skor'];
                            ?>/100
                        </strong>

                    </td>

                    <td>

                        <span
                            class="badge <?php
                            echo $badge;
                            ?>"
                        >

                            <?php
                            echo htmlspecialchars((string)$status, ENT_QUOTES, 'UTF-8');
                            ?>

                        </span>

                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars((string)($row['fokus_pengembangan'] ?? '-'), ENT_QUOTES, 'UTF-8');
                        ?>
                    </td>

                    <td>

                        <?php
                        echo date(
                            'd-m-Y H:i',
                            strtotime(
                                $row['created_at']
                            )
                        );
                        ?>

                    </td>

                </tr>

<?php

    }

} else {

?>

                <tr>

                    <td
                        colspan="9"
                        style="
                            text-align:center;
                            padding:30px;
                        "
                    >

                        😕 Tidak ada data
                        yang ditemukan.

                    </td>

                </tr>

<?php

}

?>

            </tbody>

        </table>

    </div>

</div>


</div>


<div class="footer"
     style="
        text-align:center;
        padding:30px;
        color:#64748b;
     "
>

    E-MOODS BK — SMAN 1 LAMONGAN

</div>


</body>

</html>