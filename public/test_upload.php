<?php
// Test upload configuration
echo "<h2>Test Upload Configuration</h2>";
echo "<p><strong>Upload Max Filesize:</strong> " . ini_get('upload_max_filesize') . "</p>";
echo "<p><strong>Post Max Size:</strong> " . ini_get('post_max_size') . "</p>";
echo "<p><strong>Max File Uploads:</strong> " . ini_get('max_file_uploads') . "</p>";

// Check upload directory
$uploadDir = __DIR__ . '/uploads/products';
echo "<p><strong>Upload Directory:</strong> $uploadDir</p>";
echo "<p><strong>Directory Exists:</strong> " . (is_dir($uploadDir) ? 'Yes' : 'No') . "</p>";
echo "<p><strong>Directory Writable:</strong> " . (is_writable($uploadDir) ? 'Yes' : 'No') . "</p>";

// List files in upload directory
if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    echo "<p><strong>Files in directory:</strong></p>";
    echo "<ul>";
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "<li>$file</li>";
        }
    }
    echo "</ul>";
}

// Test form
?>
<h3>Test Upload Form</h3>
<form action="test_upload_process.php" method="post" enctype="multipart/form-data">
    <input type="file" name="test_image" accept="image/*"><br><br>
    <button type="submit">Test Upload</button>
</form>
