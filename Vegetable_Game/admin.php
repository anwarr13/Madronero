<?php
require_once 'config.php';

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vegetable_name = mysqli_real_escape_string($conn, $_POST['vegetable_name']);
    $target_dir = "images/";
    
    // Create images directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($_FILES["vegetable_image"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . str_replace(' ', '_', strtolower($vegetable_name)) . '.' . $file_extension;
    
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Check if image file is actual image
    if(isset($_FILES["vegetable_image"])) {
        $check = getimagesize($_FILES["vegetable_image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "File is not an image.";
            $uploadOk = 0;
        }
    }
    
    // Check file size
    if ($_FILES["vegetable_image"]["size"] > 5000000) {
        $error = "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["vegetable_image"]["tmp_name"], $target_file)) {
            // Insert into database
            $sql = "INSERT INTO fruits_vegetables (name, image_url, type) VALUES (?, ?, 'vegetable')";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $vegetable_name, $target_file);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "The vegetable " . $vegetable_name . " has been added successfully.";
            } else {
                $error = "Sorry, there was an error adding the vegetable to database.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    }
}

// Get all vegetables
$sql = "SELECT * FROM fruits_vegetables WHERE type = 'vegetable' ORDER BY name";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Vegetable Quiz</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #e9ecef;
            color: #333;
        }
        .form-container {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        .form-container:hover {
            transform: translateY(-5px);
        }
        .vegetables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .vegetable-card {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        .vegetable-card:hover {
            transform: scale(1.05);
        }
        .vegetable-card img {
            max-width: 150px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .success {
            color: green;
            margin: 10px 0;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
        input, button {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: calc(100% - 22px);
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #666;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-link">← Back to Game</a>
    <h1>Vegetable Quiz Admin</h1>
    
    <div class="form-container">
        <h2>Add New Vegetable</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div>
                <label for="vegetable_name">Vegetable Name:</label><br>
                <input type="text" id="vegetable_name" name="vegetable_name" required>
            </div>
            <div>
                <label for="vegetable_image">Vegetable Image:</label><br>
                <input type="file" id="vegetable_image" name="vegetable_image" required>
            </div>
            <button type="submit">Add Vegetable</button>
        </form>
    </div>
    
    <h2>Current Vegetables</h2>
    <div class="vegetables-grid">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="vegetable-card">
                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" 
                     alt="<?php echo htmlspecialchars($row['name']); ?>">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>
