<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true ||
    !in_array($_SESSION['role'] ?? '', ['guru_bk','admin'], true)) {
    header("Location: login.php");
    exit;
}
function h($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }

$nis = trim((string)($_GET['nis'] ?? ''));
$siswa = []; $bulan = []; $rata = []; $emosi = []; $jumlah = []; $riwayat = [];
$nama = ''; $kelas = '';

$q = mysqli_query($koneksi, "SELECT nis, MAX(nama) nama, MAX(kelas) kelas FROM hasil_emosi WHERE nis IS NOT NULL AND TRIM(nis)<>'' GROUP BY nis ORDER BY nama");
if ($q) while ($r = mysqli_fetch_assoc($q)) $siswa[] = $r;

$q = mysqli_query($koneksi, "SELECT DATE_FORMAT(created_at,'%Y-%m') bulan, AVG(total_skor) rata FROM hasil_emosi WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH) GROUP BY DATE_FORMAT(created_at,'%Y-%m') ORDER BY bulan");
if ($q) while ($r = mysqli_fetch_assoc($q)) { $bulan[] = date('M Y', strtotime($r['bulan'].'-01')); $rata[] = round((float)$r['rata'],1); }

$q = mysqli_query($koneksi, "SELECT TRIM(emosi) nama_emosi, COUNT(*) jumlah FROM hasil_emosi WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND emosi IS NOT NULL AND TRIM(emosi)<>'' AND TRIM(emosi)<>'0' GROUP BY TRIM(emosi) ORDER BY jumlah DESC");
if ($q) while ($r = mysqli_fetch_assoc($q)) { $emosi[] = $r['nama_emosi']; $jumlah[] = (int)$r['jumlah']; }

if ($nis !== '') {
    $st = mysqli_prepare($koneksi, "SELECT nama,kelas,total_skor,kategori,status_hasil,created_at FROM hasil_emosi WHERE nis=? ORDER BY created_at ASC");
    if ($st) {
        mysqli_stmt_bind_param($st, "s", $nis);
        mysqli_stmt_execute($st);
        $rs = mysqli_stmt_get_result($st);
        while ($r = mysqli_fetch_assoc($rs)) { $riwayat[] = $r; $nama = $r['nama']; $kelas = $r['kelas']; }
        mysqli_stmt_close($st);
    }
}
$label_riwayat = []; $nilai_riwayat = [];
foreach ($riwayat as $r) { $label_riwayat[] = date('d/m/Y H:i', strtotime($r['created_at'])); $nilai_riwayat[] = (int)$r['total_skor']; }

$total = 0; $q = mysqli_query($koneksi, "SELECT COUNT(*) jumlah FROM hasil_emosi"); if ($q) $total = (int)(mysqli_fetch_assoc($q)['jumlah'] ?? 0);
$bulan_ini = 0; $q = mysqli_query($koneksi, "SELECT COUNT(*) jumlah FROM hasil_emosi WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)"); if ($q) $bulan_ini = (int)(mysqli_fetch_assoc($q)['jumlah'] ?? 0);
?>
<!doctype html>
<html lang="id"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Grafik E-MOODS BK</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
*{box-sizing:border-box}body{margin:0;font-family:Arial,sans-serif;background:#f1f5f9;color:#1e293b}
header{padding:24px;background:linear-gradient(135deg,#1d4ed8,#38bdf8);color:#fff}
.wrap{max-width:1200px;margin:auto}.nav{background:white;padding:14px 18px;box-shadow:0 2px 8px #0001}.navin{max-width:1200px;margin:auto;display:flex;gap:16px;justify-content:space-between;flex-wrap:wrap}.nav a{color:#2563eb;text-decoration:none;font-weight:bold}
main{max-width:1200px;margin:auto;padding:20px 16px}.stats,.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,360px),1fr));gap:16px}
.stats{grid-template-columns:repeat(auto-fit,minmax(180px,1fr))}.card{background:white;border-radius:15px;padding:20px;box-shadow:0 5px 18px #0f172a0c;margin-bottom:18px}
.num{font-size:30px;font-weight:bold;color:#1d4ed8;margin:8px 0}.muted{color:#64748b;font-size:14px;line-height:1.6}.chart{height:300px;position:relative}
select,button{padding:11px;border-radius:9px;border:1px solid #cbd5e1;font:inherit}select{width:100%;max-width:600px;background:#fff}button{background:#2563eb;color:white;border:0;cursor:pointer;font-weight:bold}.btn{display:inline-block;padding:10px 13px;background:#e0f2fe;border-radius:9px;text-decoration:none;color:#075985;font-weight:bold}
table{border-collapse:collapse;width:100%;min-width:600px}th,td{text-align:left;padding:10px;border-bottom:1px solid #e2e8f0}th{background:#eff6ff}.scroll{overflow-x:auto}
@media(max-width:600px){header{padding:20px 16px}.card{padding:15px}.chart{height:250px}}
@media print{.nav,.filter,.noprint{display:none!important}body{background:white}.card{box-shadow:none;border:1px solid #ddd;break-inside:avoid}}
</style></head><body>
<header><div class="wrap"><h1>📈 Grafik Perkembangan E-MOODS BK</h1><p>SMAN 1 Lamongan — Pemantauan refleksi emosi siswa</p><p>Selamat datang, <b><?= h($_SESSION['nama'] ?? 'Guru BK') ?></b></p></div></header>
<div class="nav"><div class="navin"><b>📊 Analisis E-MOODS</b><div><a href="dashboard.php">← Dashboard</a> &nbsp; <a href="javascript:window.print()">🖨 Cetak</a> &nbsp; <a href="logout.php">Keluar</a></div></div></div>
<main>
<div class="stats">
 <div class="card"><div>📝 Total pengisian</div><div class="num"><?= $total ?></div></div>
 <div class="card"><div>📅 Pengisian 30 hari terakhir</div><div class="num"><?= $bulan_ini ?></div></div>
 <div class="card"><div>👥 Siswa dengan NIS tercatat</div><div class="num"><?= count($siswa) ?></div></div>
</div>
<section class="card filter">
<h2>🔎 Riwayat skor per siswa</h2>
<form method="get" action="grafik_emosi.php">
<label for="nis">Pilih siswa berdasarkan NIS</label><br>
<select name="nis" id="nis"><option value="">-- Tidak memilih siswa --</option>
<?php foreach ($siswa as $s): ?><option value="<?= h($s['nis']) ?>" <?= $nis === (string)$s['nis'] ? 'selected' : '' ?>><?= h($s['nama']) ?> — <?= h($s['kelas']) ?> (<?= h($s['nis']) ?>)</option><?php endforeach; ?>
</select> <button type="submit">Tampilkan</button>
<?php if ($nis !== ''): ?> <a class="btn" href="grafik_emosi.php">Reset</a><?php endif; ?>
</form><p class="muted">Siswa harus memiliki NIS yang tersimpan agar muncul dalam daftar pilihan.</p>
</section>
<div class="grid">
<section class="card"><h2>📊 Rata-rata skor CASEL per bulan</h2><p class="muted">Semua pengisian dalam 12 bulan terakhir. Rentang skor 20–100.</p><div class="chart"><canvas id="chartBulan"></canvas></div><?php if (!$bulan): ?><p class="muted">Belum ada data pada periode ini.</p><?php endif; ?></section>
<section class="card"><h2>😊 Pilihan emosi — 30 hari terakhir</h2><p class="muted">Nilai kosong dan angka 0 tidak dihitung sebagai nama emosi.</p><div class="chart"><canvas id="chartEmosi"></canvas></div><?php if (!$emosi): ?><p class="muted">Belum ada emosi berbentuk teks pada periode ini.</p><?php endif; ?></section>
</div>
<?php if ($nis !== ''): ?><section class="card"><h2>📈 Riwayat: <?= h($nama ?: $nis) ?> <?= $kelas ? '— Kelas '.h($kelas) : '' ?></h2><p class="muted">Gunakan grafik sebagai bahan refleksi dan tindak lanjut BK, bukan sebagai diagnosis.</p>
<?php if ($riwayat): ?><div class="chart"><canvas id="chartSiswa"></canvas></div><div class="scroll"><table><thead><tr><th>No.</th><th>Tanggal & jam</th><th>Skor</th><th>Kategori</th><th>Status</th></tr></thead><tbody>
<?php foreach ($riwayat as $i=>$r): ?><tr><td><?= $i+1 ?></td><td><?= h(date('d-m-Y H:i',strtotime($r['created_at']))) ?></td><td><?= (int)$r['total_skor'] ?>/100</td><td><?= h($r['kategori']) ?></td><td><?= h($r['status_hasil']) ?></td></tr><?php endforeach; ?>
</tbody></table></div><?php else: ?><p>Belum ada riwayat untuk NIS tersebut.</p><?php endif; ?></section><?php endif; ?>
<section class="card"><h2>ℹ️ Cara membaca grafik</h2><ul style="line-height:1.8"><li>Grafik CASEL merangkum rata-rata skor dari semua pengisian per bulan.</li><li>Grafik emosi menghitung pilihan emosi teks yang tersimpan selama 30 hari terakhir.</li><li>Grafik siswa menampilkan skor dari beberapa pengisian untuk NIS yang dipilih.</li><li>Hasil adalah bahan refleksi, bukan diagnosis psikologis; pertimbangkan konteks siswa.</li></ul><p class="muted">Chart memakai Chart.js CDN, sehingga koneksi internet diperlukan saat membuka halaman.</p></section>
</main>
<script>
const lb=<?= json_encode($bulan,JSON_UNESCAPED_UNICODE) ?>, vb=<?= json_encode($rata) ?>;
const le=<?= json_encode($emosi,JSON_UNESCAPED_UNICODE) ?>, je=<?= json_encode($jumlah) ?>;
const lr=<?= json_encode($label_riwayat,JSON_UNESCAPED_UNICODE) ?>, vr=<?= json_encode($nilai_riwayat) ?>;
if(typeof Chart!=='undefined'){
new Chart(document.getElementById('chartBulan'),{type:'line',data:{labels:lb,datasets:[{label:'Rata-rata skor CASEL',data:vb,borderColor:'#2563eb',backgroundColor:'rgba(37,99,235,.15)',fill:true,tension:.3}]},options:{responsive:true,maintainAspectRatio:false,scales:{y:{min:0,max:100}}}});
new Chart(document.getElementById('chartEmosi'),{type:'bar',data:{labels:le,datasets:[{label:'Jumlah pengisian',data:je,backgroundColor:['#60a5fa','#fbbf24','#f87171','#a78bfa','#34d399','#fb923c','#94a3b8','#f472b6']}]},options:{responsive:true,maintainAspectRatio:false,scales:{y:{beginAtZero:true,ticks:{precision:0}}}}});
const c=document.getElementById('chartSiswa');if(c)new Chart(c,{type:'line',data:{labels:lr,datasets:[{label:'Skor CASEL',data:vr,borderColor:'#0f766e',backgroundColor:'rgba(15,118,110,.15)',fill:true,tension:.25}]},options:{responsive:true,maintainAspectRatio:false,scales:{y:{min:0,max:100}}}});
}
</script></body></html>
