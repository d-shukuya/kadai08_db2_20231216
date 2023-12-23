<?php
// 1. funcs.php を呼び出す
include("./funcs.php");

// 2．データ登録
// 2-1. Books の登録
$pdo = db_conn();
$stmt = $pdo->prepare(
    'INSERT INTO
        gs_bm_books(id, name, url, content, created_date, update_date)
    VALUES
        (NULL, :name, :url, "", sysdate(), sysdate())'
);

$stmt->bindValue(':name', $_POST["name"], PDO::PARAM_STR);
$stmt->bindValue(':url', $_POST["url"], PDO::PARAM_STR);

$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
} else {
    // 画像ファイルの保管
    $id = $pdo->lastInsertId();
    $id12 = str_pad($id, 12, "0", STR_PAD_LEFT);
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
}

// 2-2. BooksOrder の更新
$booksOrderAry = json_decode($_POST['books_order'], true);
$booksOrderAry[] = (int)$id;
$booksOrderJSON = json_encode($booksOrderAry);

// update_order.php へ POST
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/gs_code/kadai/kadai08_db2_20231216/php/update_order.php');
curl_setopt($ch, CURLOPT_POST, true);
$data = array(
    'type' => 'books',
    'book_id' => 'NULL',
    'order' => $booksOrderJSON,
);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$res = curl_exec($ch);

if($res === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    echo 'Operation completed without any errors';
}

curl_close($ch);

// 2-3. dog_ear_order の登録
$pdoDogEarOrder = db_conn();
$stmtDogEarOrder = $pdoDogEarOrder->prepare(
    'INSERT INTO
        gs_bm_order(id, `type`, book_id, `order`)
    VALUES
        (NULL, "dog_ear", :book_id, "[]")'
);

$stmtDogEarOrder->bindValue(':book_id', $id, PDO::PARAM_INT);
$statusDogEarOrder = $stmtDogEarOrder->execute();
if ($statusDogEarOrder == false) {
    sql_error($stmtDogEarOrder);
}

// 4. index.php に遷移
redirect('../');
