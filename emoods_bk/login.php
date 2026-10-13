<?php
session_start();

include "koneksi.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

    $stmt = mysqli_prepare($koneksi, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $username,
        $password
    );

    mysqli_stmt_execute($stmt);

    $hasil = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($hasil) == 1) {

        $user = mysqli_fetch_assoc($hasil);

        $_SESSION['login'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php");
        exit;

    } else {

        $error = "Username atau password salah.";

    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Login Guru BK</title>

<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    font-family: Arial, sans-serif;

    min-height: 100vh;

    display: flex;

    justify-content: center;

    align-items: center;

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #38bdf8
        );

}

.login-box {

    width: 90%;

    max-width: 420px;

    background: white;

    padding: 35px;

    border-radius: 25px;

    box-shadow:
        0 15px 40px
        rgba(0,0,0,0.20);

}

.logo {

    text-align: center;

    font-size: 65px;

}

h1 {

    text-align: center;

    color: #1e3a8a;

    margin-bottom: 5px;

}

.subtitle {

    text-align: center;

    color: #64748b;

    margin-bottom: 25px;

}

label {

    display: block;

    font-weight: bold;

    margin-bottom: 7px;

}

input {

    width: 100%;

    padding: 13px;

    border: 1px solid #cbd5e1;

    border-radius: 10px;

    margin-bottom: 18px;

    font-size: 16px;

}

button {

    width: 100%;

    padding: 14px;

    border: none;

    border-radius: 10px;

    background: #2563eb;

    color: white;

    font-size: 17px;

    font-weight: bold;

    cursor: pointer;

}

button:hover {

    background: #1d4ed8;

}

.error {

    background: #fee2e2;

    color: #b91c1c;

    padding: 12px;

    border-radius: 10px;

    margin-bottom: 18px;

    text-align: center;

}

.back {

    text-align: center;

    margin-top: 20px;

}

.back a {

    color: #2563eb;

    text-decoration: none;

}

.small {

    text-align: center;

    margin-top: 20px;

    font-size: 13px;

    color: #64748b;

}

</style>

</head>

<body>

<div class="login-box">

    <div class="logo">🧠</div>

    <h1>Login Guru BK</h1>

    <div class="subtitle">
        E-MOODS BK<br>
        SMAN 1 LAMONGAN
    </div>

    <?php if ($error != "") { ?>

        <div class="error">
            ❌ <?php echo $error; ?>
        </div>

    <?php } ?>

    <form method="POST">

        <label>Username</label>

        <input
            type="text"
            name="username"
            placeholder="Masukkan username"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Masukkan password"
            required
        >

        <button type="submit">
            🔐 Masuk ke Dashboard
        </button>

    </form>

    <div class="back">

        <a href="index.php">
            ← Kembali ke Beranda
        </a>

    </div>

    <div class="small">

        Data siswa hanya dapat diakses oleh Guru BK/Admin.

    </div>

</div>

</body>

</html>