<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            text-align: center;
            padding: 50px;
            animation: fadeIn 1s ease-in-out;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            transform: scale(0.9);
            animation: popUp 0.5s ease-in-out forwards;
        }

        h1 {
            font-size: 80px;
            color: #ffcc00;
            margin: 0;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            animation: bounce 1s infinite alternate;
        }

        p {
            font-size: 20px;
            color: #333;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: white;
            background: #ffcc00;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 18px;
            transition: 0.3s;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }

        a:hover {
            background: #ff9900;
            transform: scale(1.1);
        }

        .image {
            width: 100%;
            height: auto;
            margin-bottom: 20px;
            border-radius: 10px;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes popUp {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes bounce {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(-10px);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <img class="image" src="https://source.unsplash.com/600x300/?error,technology" alt="404 Error">
        <h1>404</h1>
        <p>Oops! Trang bạn tìm kiếm không tồn tại.</p>
        <a href="?module=home&action=dashboard&userId=<?php echo !empty($data['userId']) ? $data['userId'] : ''; ?>&count=<?php echo !empty($data['count']) ? $data['count'] : ''; ?>" class="nav-link px-2 link-body-emphasis">Quay lại trang chủ</a>

    </div>
</body>

</html>