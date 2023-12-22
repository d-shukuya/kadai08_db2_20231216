<?php
// 1. funcs.php を呼び出す
include("./funcs.php");

// 2. id の定義
$bookId12 = $_GET['book_id'];

// 3. DB接続&取得
// 3-1. Books
$pdoBooks = db_conn();
$stmtBooks = $pdoBooks->prepare(
    "SELECT * FROM gs_bm_books WHERE id = $bookId12"
);
$statusBooks = $stmtBooks->execute();

// 3-2. DogEar
$pdoDogEar = db_conn();
$stmtDogEar = $pdoDogEar->prepare(
    "SELECT * FROM gs_bm_dog_ear WHERE book_id = $bookId12 ORDER BY page_number ASC"
);
$statusDogEar = $stmtDogEar->execute();

// 4. データ表示
$bookName = "";
$bookUrl = "";
$bookMemo = "";
$createdDateBooks = "";
$updateDateBooks = "";

// 4-1. Books
if ($statusBooks == false) {
    sql_error($stmt);
} else {
    while ($resultBooks = $stmtBooks->fetch(PDO::FETCH_ASSOC)) {
        $bookName = $resultBooks['name'];
        $bookUrl = $resultBooks['url'];
        $bookMemo = $resultBooks['content'];
        $createdDateBooks = $resultBooks['created_date'];
        $updateDateBooks = $resultBooks['update_date'];
    }
}

$files = glob("../book_cover/$bookId12.*");
$BookCoverImg = (count($files) > 0) ? $files[0] : '../book_cover/sample_cover.png';

// 4-2. DogEar
$view = "";
if ($statusDogEar == false) {
    sql_error($stmt);
} else {
    while ($resultDogEar = $stmtDogEar->fetch(PDO::FETCH_ASSOC)) {
        $dogEarId12 = str_pad($resultDogEar['id'], 12, "0", STR_PAD_LEFT);
        $createdDateDogEar = substr($resultDogEar['created_date'], 0, 10);
        $updateDateDogEar = substr($resultDogEar['update_date'], 0, 10);

        $view .= "<li class='dog_ear_item'>";
        $view .=    "<div class='left_block'>";
        $view .=        "<div class='input_fmt'>";
        $view .=            "<label>ページ：</label>";
        $view .=            "<input type='text' name='page_number' value='" . h($resultDogEar['page_number']) . "'>";
        $view .=        "</div>";
        $view .=        "<div class='input_fmt'>";
        $view .=            "<label>行：</label>";
        $view .=            "<input type='text' name='line_number' value='" . h($resultDogEar['line_number']) . "'>";
        $view .=        "</div>";
        $view .=        "<div class='dog_ear_date_info'>";
        $view .=            "<p>登録日： " . h($createdDateDogEar) . "</p>";
        $view .=            "<p>更新日： " . h($updateDateDogEar) . "</p>";
        $view .=        "</div>";
        $view .=    "</div>";
        $view .=    "<div class='dog_ear_memo_box'>";
        $view .=        "<label>メモ：</label>";
        $view .=        "<textarea name='dog_ear_memo'>" . h($resultDogEar['content']) . "</textarea>";
        $view .=    "</div>";
        $view .=    "<div class='delete_dog_ear' data-dog_ear_id='" . h($dogEarId12) . "'>削除</div>";
        $view .= "</li>";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($bookName) ?></title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style_dog_ear.css">
</head>

<body>
    <header>
        <h1 id="book_name" data-book_id=<?= h($bookId12) ?> contenteditable="true"><?= h($bookName) ?></h1>
        <div id="book_cover_box"><img src="<?= h($BookCoverImg) ?>" alt=""></div>
        <div id="book_url_box" class="book_url_box_fmt">
            <a id="book_url" href='<?= h($bookUrl) ?>'>外部リンク</a>
            <img id="book_url_edit_btn" src="../img/edit.png" alt="">
        </div>
        <div id="book_url_edit_box" class="book_url_box_fmt">
            <input id="book_url_update" type="text" name="book_url_update" value="<?= h($bookUrl) ?>">
            <div id="book_url_ok" class="book_url_btn">○</div>
            <div id="book_url_cancel" class="book_url_btn">×</div>
        </div>
        <h2>書籍のメモ</h2>
        <textarea id="book_memo" name="book_memo" data-book_id=<?= h($bookId12) ?>><?= h($bookMemo) ?></textarea>
        <div id='book_date_info'>
            <p>登録日： <?= h($createdDateBooks) ?></p>
            <p>更新日： <?= h($updateDateBooks) ?></p>
        </div>
    </header>

    <nav>
        <form action="./insert_dog_ear.php" method="post">
            <fieldset>
                <input type="hidden" name="book_id" value=<?= h($bookId12) ?>></input>
                <input id="add_dog_ear_btn" type="submit" value="ドッグイヤー追加">
            </fieldset>
        </form>
    </nav>

    <main>
        <ul id="dog_ear_list"><?= $view ?></ul>
    </main>

    <!-- JSの読み込み -->
    <script src="../js/jquery-2.1.3.min.js"></script>
    <script src="../js/script_dog_ear.js"></script>
</body>

</html>