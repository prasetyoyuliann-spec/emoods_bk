<?php
include "koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Identitas Siswa - E-MOODS BK</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(180deg, #dff4ff, #f7fbff);
            min-height: 100vh;
            padding: 20px;
            color: #173f5f;
        }

        .container {
            width: 100%;
            max-width: 520px;
            margin: 20px auto;
            background: white;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 80, 130, .15);
        }

        .header {
            background: linear-gradient(135deg, #087dcc, #18a4e8);
            color: white;
            text-align: center;
            padding: 28px 20px;
        }

        .header .icon {
            font-size: 48px;
            margin-bottom: 5px;
        }

        .header h1 {
            margin: 5px 0;
            font-size: 27px;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
            opacity: .95;
        }

        .content {
            padding: 28px;
        }

        .step {
            text-align: center;
            color: #087dcc;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 22px;
        }

        .progress {
            height: 8px;
            background: #e6f3fa;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .progress-bar {
            width: 20%;
            height: 100%;
            background: #087dcc;
            border-radius: 10px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
            color: #31566e;
        }

        .form-group {
            margin-bottom: 18px;
        }

        input,
        select {
            width: 100%;
            padding: 14px;
            border: 2px solid #d7eaf4;
            border-radius: 13px;
            font-size: 15px;
            outline: none;
            background: #fbfdff;
        }

        input:focus,
        select:focus {
            border-color: #168bd2;
            background: white;
        }

        .btn {
            width: 100%;
            border: none;
            border-radius: 28px;
            padding: 15px;
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-primary {
            color: white;
            background: linear-gradient(90deg, #087dcc, #159ce8);
        }

        .btn-back {
            color: #17658f;
            background: white;
            border: 2px solid #168bd2;
        }

        .note {
            margin-top: 20px;
            padding: 13px;
            background: #f1f9fd;
            border-radius: 13px;
            font-size: 12px;
            color: #637d8d;
            line-height: 1.5;
        }

        .footer {
            text-align: center;
            padding: 14px;
            font-size: 11px;
            color: #78909c;
            background: #f5fbfe;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <div class="icon">👨‍🎓</div>

        <h1>Identitas Siswa</h1>

        <p>Silakan isi data diri sebelum memulai refleksi.</p>
    </div>

    <div class="content">

        <div class="step">
            LANGKAH 1 DARI 3
        </div>

        <div class="progress">
            <div class="progress-bar"></div>
        </div>

        <form action="asesmen.php" method="POST">

            <div class="form-group">

                <label for="nis">
                    NIS / NISN
                </label>

                <input
                    type="text"
                    id="nis"
                    name="nis"
                    placeholder="Masukkan NIS / NISN"
                    required
                >

            </div>

            <div class="form-group">

                <label for="nama">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="Masukkan nama lengkap"
                    required
                >

            </div>

            <div class="form-group">

                <label for="kelas">
                    Kelas
                </label>

                <select id="kelas" name="kelas" required>

                    <option value="">
                        -- Pilih Kelas --
                    </option>

                    <optgroup label="Kelas X">

                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            echo "<option value='X.$i'>X.$i</option>";
                        }
                        ?>

                    </optgroup>

                    <optgroup label="Kelas XI">

                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            echo "<option value='XI.$i'>XI.$i</option>";
                        }
                        ?>

                    </optgroup>

                    <optgroup label="Kelas XII">

                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            echo "<option value='XII.$i'>XII.$i</option>";
                        }
                        ?>

                    </optgroup>

                </select>

            </div>

            <div class="form-group">

                <label for="no_absen">
                    Nomor Absen
                </label>

                <input
                    type="number"
                    id="no_absen"
                    name="no_absen"
                    min="1"
                    max="50"
                    placeholder="Contoh: 12"
                    required
                >

            </div>

            <div class="form-group">

                <label for="jenis_kelamin">
                    Jenis Kelamin
                </label>

                <select
                    id="jenis_kelamin"
                    name="jenis_kelamin"
                    required
                >

                    <option value="">
                        -- Pilih Jenis Kelamin --
                    </option>

                    <option value="L">
                        Laki-laki
                    </option>

                    <option value="P">
                        Perempuan
                    </option>

                </select>

            </div>

            <button type="submit" class="btn btn-primary">
                Lanjut ke Asesmen →
            </button>

        </form>

        <a href="index.php">

            <button type="button" class="btn btn-back">
                ← Kembali
            </button>

        </a>

        <div class="note">
            🔒 Data identitas digunakan untuk keperluan asesmen
            dan layanan Bimbingan dan Konseling.
        </div>

    </div>

    <div class="footer">
        E-MOODS BK • SMAN 1 Lamongan
    </div>

</div>

</body>
</html>