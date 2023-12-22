<?php
// 画像ファイルの削除
$id12 = $_POST['book_id'];
$bookCoverDir = '../book_cover';
echo $id12;

$files = glob("$bookCoverDir/$id12.*");
var_dump($files);
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}

exit();
