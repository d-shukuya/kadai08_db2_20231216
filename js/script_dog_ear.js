// イベント
let oldBookName;
$("#book_name")
  .on("focus", function () {
    oldBookName = $(this).text();
  })
  .on("blur", function () {
    const newBookName = $(this).text();
    if (newBookName != oldBookName) {
      postUpdateBooks("book_name", newBookName, $(this).data("book_id"));
    }
  });

$("#book_cover_box").on("click", "#book_cover_img", function () {
  $("#img_upload").click();
});

$("#img_upload").on("change", function (e) {
  const reader = new FileReader();
  reader.onload = function (e) {
    $("#book_cover_img").attr("src", e.target.result);
  };
  reader.readAsDataURL(e.target.files[0]);

  let formData = new FormData();
  formData.append("book_cover_img", e.target.files[0]);
  formData.append("book_id", $(this).data("book_id"));

  $.ajax({
    url: "./book_cover_update.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
  })
    .done(function (data) {
      console.log(data);
    })
    .fail(function () {
      console.log("Upload failed.");
    });
});

$("#book_url_edit_btn").on("click", function () {
  const aTagHref = $('#book_url').attr('href');
  $('#book_url_update').val(aTagHref);
  $("#book_url_box").css("display", "none");
  $("#book_url_edit_box").css("display", "flex");
});

$("#book_url_ok").on("click", function () {
  const aTag = $('#book_url');
  const input = $('#book_url_update');
  const oldUrl = aTag.attr('href');
  const newUrl = input.val();

  if (oldUrl == newUrl) return;

  postUpdateBooks("book_url", newUrl, $(this).data("book_id"));
  aTag.attr('href', newUrl);

  $("#book_url_box").css("display", "flex");
  $("#book_url_edit_box").css("display", "none");
});

$("#book_url_cancel").on("click", function () {
  $("#book_url_box").css("display", "flex");
  $("#book_url_edit_box").css("display", "none");
});

$("#book_url").on("change", function () {
  postUpdateBooks("book_url", $(this).val(), $(this).data("book_id"));
});

$("#book_memo").on("change", function () {
  postUpdateBooks("book_memo", $(this).val(), $(this).data("book_id"));
});

$("#book_delete_btn").on("click", function () {

});

// 関数
function postUpdateBooks(type, changeVal, bookId) {
  $.ajax({
    url: "./update_book.php",
    type: "post",
    data: {
      type: type,
      change_val: changeVal,
      book_id: bookId,
    },
    success: function (response) {
      console.log(response);
    },
  });
}
