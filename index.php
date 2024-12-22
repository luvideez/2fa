<?php
session_start(); // Bắt đầu session

require_once 'GoogleAuthenticator.php';

$code = "";
$secret = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $secret = trim($_POST['secret']);
    $_SESSION['secret'] = $secret; // Lưu secret vào session
    $ga = new PHPGangsta_GoogleAuthenticator();
    $code = $ga->getCode($secret);
} elseif (isset($_SESSION['secret'])) {
    $secret = $_SESSION['secret']; // Lấy secret từ session
    $ga = new PHPGangsta_GoogleAuthenticator();
    $code = $ga->getCode($secret);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>2FA Generator</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 15px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .result {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #2196F3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Nhập mã bí mật:</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            Mã bí mật: <input type="text" name="secret" id="secret" value="<?php echo $secret ? htmlspecialchars($secret) : ''; ?>">
            <br><br>
            <input type="submit" name="submit" value="Tạo mã">
        </form>

        <?php if ($secret): ?>
            <h2>Mã 2FA:</h2>
            <p class="result" id="code"><?php echo $code; ?></p>
        <?php endif; ?>
    </div>

    <script>
        <?php if ($secret): ?>
            function updateCode() {
                var secret = document.getElementById('secret').value;
                if (secret) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', '2fa.php?key=' + secret, true);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var data = JSON.parse(xhr.responseText);
                            document.getElementById('code').textContent = data.code;
                        }
                    };
                    xhr.send();
                }
            }

            setInterval(updateCode, 1000);
        <?php endif; ?>
    </script>
</body>
</html>