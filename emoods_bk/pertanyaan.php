<?php

$nis = $_POST['nis'] ?? '';
$nama = $_POST['nama'] ?? '';
$kelas = $_POST['kelas'] ?? '';
$no_absen = $_POST['no_absen'] ?? '';
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';

$emosi = $_POST['emosi'] ?? '';
$intensitas = $_POST['intensitas'] ?? '';

if ($nama == '' || $kelas == '' || $emosi == '' || $intensitas == '') {
    die("Data belum lengkap. Silakan kembali ke halaman sebelumnya.");
}

/*
|--------------------------------------------------------------------------
| 20 PERTANYAAN REFLEKSI
|--------------------------------------------------------------------------
*/

$pertanyaan = [
    "Saya dapat mengenali apa yang sedang saya rasakan.",
    "Saya dapat mengenali hal yang memicu emosi saya.",
    "Saya dapat menyadari perubahan pada tubuh saya ketika emosi muncul.",
    "Ketika emosi saya meningkat, saya dapat berhenti sejenak sebelum bertindak.",
    "Saya mengetahui cara yang dapat membantu saya menjadi lebih tenang.",
    "Saya dapat mengungkapkan perasaan dengan cara yang baik.",
    "Ketika menghadapi masalah, saya dapat mencari solusi tanpa memperburuk keadaan.",
    "Saya dapat meminta bantuan ketika masalah terasa sulit.",
    "Saya mempertimbangkan akibat sebelum mengambil keputusan.",
    "Saya dapat menerima bahwa orang lain mungkin memiliki perasaan yang berbeda.",
    "Saya berusaha memahami perasaan teman ketika terjadi masalah.",
    "Saya dapat menyampaikan ketidaksetujuan tanpa menyakiti orang lain.",
    "Setelah mengalami emosi yang kuat, saya dapat kembali fokus pada kegiatan.",
    "Saya dapat belajar dari pengalaman yang membuat saya kesal, sedih, atau cemas.",
    "Saya mengetahui hal-hal yang biasanya membantu saya merasa lebih baik.",
    "Saya dapat memilih respons yang lebih aman ketika sedang sangat emosi.",
    "Saya mampu menjaga hubungan dengan teman ketika sedang menghadapi masalah.",
    "Saya tahu kapan perlu meminta bantuan Guru BK atau orang dewasa yang dipercaya.",
    "Saya memiliki rencana sederhana untuk mengelola emosi ketika masalah muncul lagi.",
    "Saya merasa mampu belajar mengelola emosi dengan lebih baik."
];

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Refleksi Emosi - E-MOODS BK</title>

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
    max-width: 650px;
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
    padding: 28px 18px;
}

.header-icon {
    font-size: 45px;
}

.header h1 {
    margin: 5px 0;
    font-size: 26px;
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
    margin-bottom: 22px;
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
    width: 65%;
    height: 100%;
    background: #087dcc;
}

.section-title {
    font-size: 21px;
    font-weight: bold;
    color: #173f5f;
    margin: 25px 0 8px;
}

.section-subtitle {
    font-size: 13px;
    color: #718793;
    line-height: 1.5;
    margin-bottom: 15px;
}

.emotion-result {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 18px;
    background: #eaf7ff;
    border: 2px solid #ccebf9;
    border-radius: 20px;
    margin-bottom: 25px;
}

.emotion-emoji {
    font-size: 52px;
}

.emotion-text {
    font-size: 14px;
}

.emotion-text strong {
    display: block;
    font-size: 21px;
    color: #087dcc;
    margin-top: 4px;
}

.intensity {
    background: #f5fbfe;
    border-radius: 15px;
    padding: 15px;
    margin-bottom: 28px;
}

.intensity-title {
    font-weight: bold;
    font-size: 15px;
}

.intensity-number {
    font-size: 27px;
    font-weight: bold;
    color: #087dcc;
}

.form-group {
    margin-bottom: 22px;
}

label.main-label {
    display: block;
    font-weight: bold;
    font-size: 15px;
    margin-bottom: 9px;
}

select,
textarea {
    width: 100%;
    padding: 14px;
    border: 2px solid #d7eaf4;
    border-radius: 13px;
    font-size: 14px;
    background: #fbfdff;
    outline: none;
}

select:focus,
textarea:focus {
    border-color: #168bd2;
    background: white;
}

textarea {
    min-height: 100px;
    resize: vertical;
}

.option-box {
    display: grid;
    gap: 9px;
}

.option {
    position: relative;
}

.option input {
    position: absolute;
    opacity: 0;
}

.option label {
    display: block;
    padding: 13px;
    border: 2px solid #dceef7;
    border-radius: 13px;
    background: #fbfdff;
    cursor: pointer;
    font-size: 14px;
}

.option input:checked + label {
    border-color: #087dcc;
    background: #e7f7ff;
    font-weight: bold;
}

.question-card {
    background: #ffffff;
    border: 2px solid #e1eef5;
    border-radius: 18px;
    padding: 18px;
    margin-bottom: 16px;
}

.question-number {
    color: #087dcc;
    font-weight: bold;
    font-size: 13px;
    margin-bottom: 8px;
}

.question-text {
    font-size: 15px;
    line-height: 1.5;
    font-weight: bold;
    margin-bottom: 14px;
}

.likert {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 6px;
}

.likert-item {
    position: relative;
}

.likert-item input {
    position: absolute;
    opacity: 0;
}

.likert-item label {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    min-height: 55px;
    padding: 5px;
    border: 2px solid #dceef7;
    border-radius: 10px;
    background: #fbfdff;
    font-size: 10px;
    cursor: pointer;
    line-height: 1.2;
}

.likert-item input:checked + label {
    border-color: #087dcc;
    background: #e5f6ff;
    color: #087dcc;
    font-weight: bold;
}

.note {
    margin: 22px 0;
    padding: 14px;
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
    margin-top: 10px;
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

@media(max-width:500px) {

    .content {
        padding: 18px;
    }

    .likert {
        grid-template-columns: 1fr;
    }

    .likert-item label {
        min-height: 45px;
        font-size: 12px;
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

<!-- IDENTITAS -->

<div class="student">

👤 <b>Nama:</b>
<?php echo htmlspecialchars($nama); ?><br>

🏫 <b>Kelas:</b>
<?php echo htmlspecialchars($kelas); ?><br>

🆔 <b>NIS:</b>
<?php echo htmlspecialchars($nis); ?>

</div>


<div class="step">
LANGKAH 3 DARI 3
</div>

<div class="progress">
<div class="progress-bar"></div>
</div>


<!-- EMOSI YANG DIPILIH -->

<div class="section-title">
💭 Emosi yang kamu pilih
</div>

<div class="emotion-result">

<div class="emotion-emoji">

<?php

$emoji = [
    "Senang" => "😊",
    "Cemas" => "😟",
    "Marah" => "😡",
    "Lelah" => "😴",
    "Sedih" => "😢",
    "Takut" => "😨",
    "Tenang" => "😌"
];

echo $emoji[$emosi] ?? "🙂";

?>

</div>

<div class="emotion-text">

Kamu memilih emosi:

<strong>
<?php echo htmlspecialchars($emosi); ?>
</strong>

</div>

</div>


<!-- INTENSITAS -->

<div class="intensity">

Seberapa kuat perasaanmu?

<div class="intensity-number">
<?php echo htmlspecialchars($intensitas); ?> / 5
</div>

</div>


<form action="hasil.php" method="POST">


<!-- IDENTITAS TERSEMBUNYI -->

<input type="hidden" name="nis"
value="<?php echo htmlspecialchars($nis); ?>">

<input type="hidden" name="nama"
value="<?php echo htmlspecialchars($nama); ?>">

<input type="hidden" name="kelas"
value="<?php echo htmlspecialchars($kelas); ?>">

<input type="hidden" name="no_absen"
value="<?php echo htmlspecialchars($no_absen); ?>">

<input type="hidden" name="jenis_kelamin"
value="<?php echo htmlspecialchars($jenis_kelamin); ?>">

<input type="hidden" name="emosi"
value="<?php echo htmlspecialchars($emosi); ?>">

<input type="hidden" name="intensitas"
value="<?php echo htmlspecialchars($intensitas); ?>">


<!-- DIMANA -->

<div class="section-title">
📍 Di mana emosi itu paling sering muncul?
</div>

<div class="section-subtitle">
Pilih tempat atau situasi yang paling sesuai dengan pengalamanmu.
</div>

<div class="form-group">

<div class="option-box">

<?php

$tempat = [
    "Di sekolah",
    "Di rumah",
    "Saat bersama teman",
    "Saat bersama keluarga",
    "Saat menggunakan media sosial",
    "Saat belajar atau mengerjakan tugas",
    "Di tempat lain"
];

foreach ($tempat as $i => $item) {

    $id = "tempat" . $i;

    echo "
    <div class='option'>
        <input type='radio'
               id='$id'
               name='tempat_emosi'
               value='$item'
               required>

        <label for='$id'>
            $item
        </label>
    </div>
    ";
}

?>

</div>

</div>


<!-- PENYEBAB -->

<div class="section-title">
🔎 Apa yang membuatmu merasakan emosi tersebut?
</div>

<div class="section-subtitle">
Pilih hal yang paling berkaitan dengan munculnya emosimu.
</div>

<div class="form-group">

<select name="penyebab_emosi" required>

<option value="">
-- Pilih penyebab --
</option>

<option>Pelajaran atau tugas sekolah</option>

<option>Teman</option>

<option>Keluarga</option>

<option>Guru</option>

<option>Nilai atau hasil belajar</option>

<option>Masalah pribadi</option>

<option>Media sosial</option>

<option>Masa depan atau karier</option>

<option>Masalah dengan diri sendiri</option>

<option>Tidak tahu penyebabnya</option>

<option>Lainnya</option>

</select>

</div>


<!-- RESPONS -->

<div class="section-title">
💭 Apa yang biasanya kamu lakukan saat emosi itu muncul?
</div>

<div class="form-group">

<div class="option-box">

<?php

$respons = [
    "Diam atau menyendiri",
    "Berbicara dengan teman",
    "Berbicara dengan orang tua",
    "Berbicara dengan Guru BK",
    "Menenangkan diri",
    "Menangis",
    "Tetap melakukan aktivitas",
    "Lainnya"
];

foreach ($respons as $i => $item) {

    $id = "respons" . $i;

    echo "
    <div class='option'>
        <input type='radio'
               id='$id'
               name='respons_emosi'
               value='$item'
               required>

        <label for='$id'>
            $item
        </label>
    </div>
    ";
}

?>

</div>

</div>


<!-- CATATAN TAMBAHAN -->

<div class="section-title">
✍️ Ceritakan sedikit jika kamu ingin
</div>

<div class="section-subtitle">
Tidak wajib. Tuliskan hal yang menurutmu penting tentang perasaanmu hari ini.
</div>

<textarea
name="catatan_emosi"
placeholder="Tulis ceritamu di sini..."></textarea>


<!-- 20 REFLEKSI -->

<div class="section-title">
📝 Pertanyaan Refleksi
</div>

<div class="section-subtitle">
Pilih jawaban yang paling sesuai dengan keadaan dirimu.
Tidak ada jawaban benar atau salah.
</div>


<?php foreach ($pertanyaan as $index => $soal): ?>

<?php $nomor = $index + 1; ?>

<div class="question-card">

<div class="question-number">
PERTANYAAN <?php echo $nomor; ?>
</div>

<div class="question-text">
<?php echo $soal; ?>
</div>

<div class="likert">

<?php

$jawaban = [
    5 => "Sangat Sesuai",
    4 => "Sesuai",
    3 => "Cukup",
    2 => "Kurang",
    1 => "Tidak Sesuai"
];

foreach ($jawaban as $nilai => $teks) {

    $id = "q{$nomor}_{$nilai}";

    echo "
    <div class='likert-item'>

        <input
            type='radio'
            id='$id'
            name='soal$nomor'
            value='$nilai'
            required
        >

        <label for='$id'>
            $teks
        </label>

    </div>
    ";
}

?>

</div>

</div>

<?php endforeach; ?>


<div class="note">

💙 Ingat, tidak ada jawaban yang salah.
Jawablah sesuai dengan keadaan dirimu yang sebenarnya.
Hasil refleksi digunakan untuk membantu layanan
Bimbingan dan Konseling.

</div>


<button type="submit" class="btn">

Lanjut ke Hasil Refleksi →

</button>


</form>

</div>


<div class="footer">

E-MOODS BK • SMAN 1 Lamongan

</div>

</div>

</body>

</html>