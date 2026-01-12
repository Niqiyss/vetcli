<?php
$image = $_FILES['image']['tmp_name'];
$exif = @exif_read_data($image);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Metadata Result</title>

    <style>
        body {
            font-family: Arial;
            background: #f3f4f6;
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        .card {
            width: 600px;
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #111827;
            margin-bottom: 20px;
        }

        pre {
            background: #f9fafb;
            padding: 20px;
            border-radius: 12px;
            max-height: 450px;
            overflow-y: auto;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: white;
            background: #4f46e5;
            padding: 10px 20px;
            border-radius: 8px;
        }

        a:hover {
            background: #4338ca;
        }
    </style>

</head>

<body>

    <div class="card">
        <h2>📄 Metadata Results</h2>

        <pre><?php print_r($exif); ?></pre> <!-- read all metadata of an image jpeg/jpg -->

        <a href="upload.html">⬅ Back</a>
    </div>

</body>

</html>