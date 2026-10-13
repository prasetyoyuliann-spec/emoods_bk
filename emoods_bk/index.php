<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-MOODS BK</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            background: linear-gradient(180deg, #dff4ff 0%, #f7fbff 55%, #e8f6ff 100%);
            color: #123b5d;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .app {
            width: 100%;
            max-width: 470px;
            min-height: 700px;
            background: rgba(255,255,255,0.96);
            border: 5px solid #168bd2;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 12px 35px rgba(0, 91, 145, 0.18);
            position: relative;
        }

        .top-decoration {
            height: 145px;
            background:
                radial-gradient(circle at 15% 35%, rgba(255,255,255,.8) 0 18px, transparent 19px),
                radial-gradient(circle at 85% 20%, rgba(255,255,255,.65) 0 25px, transparent 26px),
                linear-gradient(135deg, #bfeeff, #eafaff);
            position: relative;
            overflow: hidden;
        }

        .leaf {
            position: absolute;
            font-size: 48px;
            opacity: .85;
        }

        .leaf.one { left: 15px; bottom: 8px; }
        .leaf.two { right: 15px; top: 25px; font-size: 42px; }

        .content {
            padding: 0 28px 28px;
            text-align: center;
            margin-top: -20px;
            position: relative;
        }

        .logo {
            width: 105px;
            height: 105px;
            margin: -65px auto 10px;
            border-radius: 50%;
            background: white;
            border: 5px solid #168bd2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 52px;
            box-shadow: 0 5px 18px rgba(0,0,0,.12);
        }

        .school {
            font-size: 13px;
            font-weight: bold;
            color: #2577a9;
            margin-bottom: 8px;
        }

        h1 {
            margin: 4px 0;
            color: #07538b;
            font-size: 34px;
            letter-spacing: .5px;
        }

        .subtitle {
            font-size: 17px;
            font-weight: bold;
            margin: 8px 0 4px;
        }

        .based {
            color: #4d6f83;
            font-size: 14px;
            margin-bottom: 22px;
        }

        .motto {
            font-style: italic;
            line-height: 1.55;
            font-weight: bold;
            color: #315f78;
            margin: 18px 10px 28px;
        }

        .student-illustration {
            font-size: 80px;
            margin: 4px 0 20px;
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 28px;
            padding: 15px 20px;
            margin-top: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: block;
            transition: transform .15s, box-shadow .15s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            color: white;
            background: linear-gradient(90deg, #087dcc, #159ce8);
            box-shadow: 0 5px 13px rgba(8,125,204,.25);
        }

        .btn-secondary {
            color: #12618d;
            background: white;
            border: 2px solid #168bd2;
        }

        .privacy {
            margin-top: 24px;
            padding: 12px;
            border-radius: 14px;
            background: #f1f9fd;
            color: #648095;
            font-size: 11px;
            line-height: 1.5;
        }

        .footer {
            padding: 13px;
            background: #07538b;
            color: white;
            text-align: center;
            font-size: 11px;
        }

        @media (max-width: 480px) {
            body {
                padding: 0;
            }

            .app {
                min-height: 100vh;
                border-width: 0;
                border-radius: 0;
            }

            .content {
                padding-left: 24px;
                padding-right: 24px;
            }
        }
    </style>
</head>

<body>

<div class="app">

    <div class="top-decoration">
        <div class="leaf one">🌿</div>
        <div class="leaf two">🍃</div>
    </div>

    <div class="content">

        <!-- Sementara menggunakan ikon. Logo sekolah asli kita masukkan nanti. -->
        <div class="logo">🧠</div>

        <div class="school">SMAN 1 LAMONGAN</div>

        <h1>E-MOODS BK</h1>

        <div class="subtitle">
            Refleksi & Regulasi Emosi
        </div>

        <div class="based">
            Berbasis CASEL
        </div>

        <div class="motto">
            “Kenali Emosimu,<br>
            Kelola Dirimu,<br>
            Raih Masa Depanmu”
        </div>

        <div class="student-illustration">
            🧑‍🎓
        </div>

        <a href="identitas.php" class="btn btn-primary">
            ▶ &nbsp; Mulai Refleksi
        </a>

        <a href="login.php" class="btn btn-secondary">
            👤 &nbsp; Login Guru BK
        </a>

        <div class="privacy">
            🔒 Data Anda aman dan hanya digunakan untuk keperluan
            layanan dan pendampingan BK.
        </div>

    </div>

    <div class="footer">
        🌿 E-MOODS BK &nbsp; | &nbsp; Refleksi Hari Ini, Masa Depan Lebih Baik
    </div>

</div>

</body>
</html>
