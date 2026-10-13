<?php

$nis = $_POST['nis'] ?? '';
$nama = $_POST['nama'] ?? '';
$kelas = $_POST['kelas'] ?? '';
$no_absen = $_POST['no_absen'] ?? '';
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';

if ($nama == '' || $kelas == '') {
    die("Data identitas belum lengkap. Silakan kembali ke halaman identitas.");
}

$emosi = [
    "Senang" => "😊",
    "Cemas" => "😟",
    "Marah" => "😡",
    "Lelah" => "😴",
    "Sedih" => "😢",
    "Takut" => "😨",
    "Tenang" => "😌"
];

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Perasaanmu - E-MOODS BK</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: linear-gradient(180deg, #dff4ff, #f7fbff);
    min-height: 100vh;
    padding: 15px;
    color: #173f5f;
}

.container {
    width: 100%;
    max-width: 560px;
    margin: 20px auto;
    background: white;
    border-radius: 28px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,80,130,.15);
}

.header {
    background: linear-gradient(135deg,#087dcc,#18a4e8);
    color: white;
    text-align: center;
    padding: 27px 18px;
}

.header-icon {
    font-size: 45px;
}

.header h1 {
    margin: 5px 0;
    font-size: 25px;
}

.header p {
    margin: 5px 0;
    font-size: 14px;
}

.content {
    padding: 25px;
}

.student {
    background: #f1f9fd;
    padding: 13px;
    border-radius: 15px;
    margin-bottom: 25px;
    font-size: 13px;
    line-height: 1.6;
}

.step {
    text-align: center;
    color: #087dcc;
    font-weight: bold;
    font-size: 13px;
    margin-bottom: 10px;
}

.progress {
    height: 8px;
    background: #e5f1f7;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 25px;
}

.progress-bar {
    width: 40%;
    height: 100%;
    background: #087dcc;
}

.question {
    text-align: center;
    font-size: 22px;
    font-weight: bold;
    color: #173f5f;
    margin-bottom: 8px;
}

.subtitle {
    text-align: center;
    color: #708794;
    font-size: 13px;
    line-height: 1.5;
    margin-bottom: 22px;
}

.emotion-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.emotion-card {
    position: relative;
}

.emotion-card input {
    position: absolute;
    opacity: 0;
}

.emotion-card label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 110px;
    padding: 12px;
    background: #fbfdff;
    border: 2px solid #dceef7;
    border-radius: 18px;
    cursor: pointer;
    transition: .2s;
}

.emotion-card label:hover {
    border-color: #168bd2;
    background: #eef9ff;
}

.emotion-card input:checked + label {
    border-color: #087dcc;
    background: #e5f6ff;
    box-shadow: 0 5px 15px rgba(8,125,204,.15);
}

.emoji {
    font-size: 42px;
    margin-bottom: 5px;
}

.emotion-name {
    font-weight: bold;
    font-size: 15px;
}

.intensity-box {
    margin-top: 28px;
    padding: 20px 15px;
    background: #f5fbfe;
    border-radius: 20px;
    border: 2px solid #e0eef5;
}

.intensity-title {
    text-align: center;
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 5px;
}

.intensity-subtitle {
    text-align: center;
    font-size: 12px;
    color: #718793;
    margin-bottom: 18px;
}

.intensity-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;
}

.intensity {
    position: relative;
}

.intensity input {
    position: absolute;
    opacity: 0;
}

.intensity label {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px 5px;
    background: white;
    border: 2px solid #dceef7;
    border-radius: 13px;
    cursor: pointer;
}

.intensity label:hover {
    border-color: #168bd2;
}

.intensity input:checked + label {
    background: #e5f6ff;
    border-color: #087dcc;
}

.number {
    font-size: 22px;
    font-weight: bold;
    color: #087dcc;
}

.level {
    font-size: 10px;
    margin-top: 3px;
    text-align: center;
}

.note {
    margin-top: 18px;
    padding: 13px;
    background: #fff8df;
    border-left: 5px solid #f0b400;
    border-radius: 10px;
    font-size: 12px;
    line-height: 1.5;
}

.btn {
    width: 100%;
    border: none;
    border-radius: 28px;
    padding: 16px;
    margin-top: 22px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    background: linear-gradient(90deg,#087dcc,#159ce8);
    cursor: pointer;
}

.footer {
    text-align: center;
    padding: 14px;
    font-size: 11px;
    color: #78909c;
    background: #f5fbfe;
}

@media(max-width:430px) {

    .content {
        padding: 18px;
    }

    .emotion-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .intensity-grid {
        gap: 5px;
    }

    .intensity label {
        padding: 10px 3px;
    }

}

</style>

</head>

<body>

<div class="container">

<div class="header">

    <div class="header-icon">🧠</div>

    <h1>E-MOODS BK</h1>

    <p>Refleksi & Regulasi Emosi</p>

</div>


<div class="content">

<div class="student">

    👤 <b>Nama:</b>
    <?php echo htmlspecialchars($nama); ?><br>

    🏫 <b>Kelas:</b>
    <?php echo htmlspecialchars($kelas); ?><br>

    🆔 <b>NIS:</b>
    <?php echo htmlspecialchars($nis); ?>

</div>


<div class="step">
    LANGKAH 2 DARI 3
</div>

<div class="progress">

    <div class="progress-bar"></div>

</div>


<div class="question">

    Bagaimana perasaanmu hari ini?

</div>

<div class="subtitle">

    Pilih satu perasaan yang paling menggambarkan
    keadaanmu saat ini.

</div>


<form action="pertanyaan.php" method="POST">


<!-- IDENTITAS -->

<input type="hidden"
name="nis"
value="<?php echo htmlspecialchars($nis); ?>">

<input type="hidden"
name="nama"
value="<?php echo htmlspecialchars($nama); ?>">

<input type="hidden"
name="kelas"
value="<?php echo htmlspecialchars($kelas); ?>">

<input type="hidden"
name="no_absen"
value="<?php echo htmlspecialchars($no_absen); ?>">

<input type="hidden"
name="jenis_kelamin"
value="<?php echo htmlspecialchars($jenis_kelamin); ?>">


<!-- PILIH EMOSI -->

<div class="emotion-grid">

<?php foreach ($emosi as $nama_emosi => $emoji): ?>

<div class="emotion-card">

<input
type="radio"
id="emosi_<?php echo $nama_emosi; ?>"
name="emosi"
value="<?php echo $nama_emosi; ?>"
required
>

<label for="emosi_<?php echo $nama_emosi; ?>">

<span class="emoji">
<?php echo $emoji; ?>
</span>

<span class="emotion-name">
<?php echo $nama_emosi; ?>
</span>

</label>

</div>

<?php endforeach; ?>

</div>


<!-- INTENSITAS -->

<div class="intensity-box">

<div class="intensity-title">

    Seberapa kuat perasaanmu?

</div>

<div class="intensity-subtitle">

    Pilih angka yang paling menggambarkan
    kuatnya emosi yang kamu rasakan.

</div>


<div class="intensity-grid">


<div class="intensity">

<input
type="radio"
id="int1"
name="intensitas"
value="1"
required
>

<label for="int1">

<span class="number">1</span>

<span class="level">Sangat<br>ringan</span>

</label>

</div>


<div class="intensity">

<input
type="radio"
id="int2"
name="intensitas"
value="2"
>

<label for="int2">

<span class="number">2</span>

<span class="level">Ringan</span>

</label>

</div>


<div class="intensity">

<input
type="radio"
id="int3"
name="intensitas"
value="3"
>

<label for="int3">

<span class="number">3</span>

<span class="level">Sedang</span>

</label>

</div>


<div class="intensity">

<input
type="radio"
id="int4"
name="intensitas"
value="4"
>

<label for="int4">

<span class="number">4</span>

<span class="level">Kuat</span>

</label>

</div>


<div class="intensity">

<input
type="radio"
id="int5"
name="intensitas"
value="5"
>

<label for="int5">

<span class="number">5</span>

<span class="level">Sangat<br>kuat</span>

</label>

</div>


</div>

</div>


<div class="note">

💡 Tidak ada perasaan yang salah.
Mengenali perasaan adalah langkah pertama
untuk belajar mengelolanya.

</div>


<button type="submit" class="btn">

Lanjut ke Pertanyaan Refleksi →

</button>


</form>

</div>


<div class="footer">

E-MOODS BK • SMAN 1 Lamongan

</div>

</div>

</body>

</html>