<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload PDF</title>
    <style>
 
        /* Container */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        label {
            font-size: 1.2em;
            margin-bottom: 10px;
            display: block;
            color: #333;
        }

        input[type="file"] {
            padding: 10px;
            border-radius: 5px;
            border: 2px solid #ddd;
            width: 100%;
            font-size: 1em;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 1em;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            form {
                padding: 15px;
            }

            input[type="submit"] {
                font-size: 0.9em;
                padding: 10px;
            }

            label {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file">Upload PDF:</label>
        <input type="file" name="file" id="file" accept=".pdf" required>
        <input type="submit" value="Upload">
    </form>
</body>
</html>
