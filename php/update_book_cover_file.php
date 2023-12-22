<?php
// 画像ファイルの保管
$id12 = $_POST['book_id'];
$bookCoverDir = '../book_cover';
if ($_FILES["book_cover_img"]["error"] == UPLOAD_ERR_OK) {
    // 既存のファイルを削除
    $files = glob("$bookCoverDir/$id12.*");
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    // 新しいファイルを保管
    $tmp_name = $_FILES["book_cover_img"]["tmp_name"];
    $name = basename($_FILES["book_cover_img"]["name"]);
    $extension = pathinfo($name, PATHINFO_EXTENSION);
    $new_name = "$id12.$extension";
    move_uploaded_file($tmp_name, "$bookCoverDir/$new_name");
} else {
    echo "Failed to save img file";
}

exit();