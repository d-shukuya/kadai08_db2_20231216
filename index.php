<?php
// 1. funcs.php を呼び出す
include("./php/funcs.php");

// 2. DB接続
$pdo = db_conn();

// 3. データ取得
$stmt = $pdo->prepare('SELECT * FROM gs_bm_books');
$status = $stmt->execute();

// 4. データ表示
$view = "";
if ($status == false) {
    sql_error($stmt);
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id12 = str_pad($result['id'], 12, "0", STR_PAD_LEFT);
        $files = glob("./book_cover/$id12.*");
        $coverImgPath = (count($files) > 0) ? $files[0] : './book_cover/sample_cover.png';
        $createdDate = substr($result['created_date'], 0, 10);
        $updateDate = substr($result['update_date'], 0, 10);

        $view .= "<li class='book_item' data-book_id='" . h($id12) . "'>";
        $view .=    "<h3>" . h($result['name']) . "</h3>";
        $view .=    "<div class='book_cover'><img src='" . $coverImgPath . "'></div>";
        if (!is_null($result['url']) && $result['url'] != "") {
            $view .= "<a href='" . h($result['url']) . "'>外部リンク</a>";
        };
        $view .=    "<div class='date_info'>";
        $view .=        "<p>登録日： " . h($createdDate) . "</p>";
        $view .=        "<p?>更新日： " . h($updateDate) . "</p>";
        $view .=    "</div>";
        $view .= "</li>";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DogEarApp.</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style_book.css">
</head>

<body>
    <header>
        <img src="./img/logo.png" alt="">
        <h1>DogEarApp.</h1>
    </header>

    <nav>
        <form action="./php/insert_book.php" method="post" enctype="multipart/form-data">
            <fieldset>
                <div id="register_book_box">
                    <h2>書籍の登録</h2>
                    <p id="book_name_label" class="label_fmt">書籍名：</p>
                    <div id="book_name" class="textbox_fmt"><input class="input_textbox" type="text" name="name" required></div>
                    <p id="book_url_label" class="label_fmt">リンク：</p>
                    <div id="book_url" class="textbox_fmt"><input class="input_textbox" type="text" name="url"></div>
                    <div id="book_cover_box"><img id="book_cover_img" src="./img/input_img.png" alt=""></div>
                    <input type="file" id="img_upload" accept="image/*" name="book_cover_img">
                    <div id="book_register_btn"><input class="button" type="submit" value="登録"></div>
                </div>
            </fieldset>
        </form>
    </nav>

    <main>
        <ul id="book_list"><?= $view ?></ul>
    </main>

    <!-- JSの読み込み -->
    <script src="./js/jquery-2.1.3.min.js"></script>
    <script src="./js/script_book.js"></script>
</body>

</html>