let oldVal;
$("#book_name")
  .on("focus", function () {
    oldVal = $(this).text();
  })
  .on("blur", function () {
    newVal = $(this).text();
    if (newVal != oldVal) {
      postUpdateBooks("book_name", newVal, $(this).data("book_id"));
    }
  });

$("#book_url_edit_btn").on("click", function () {
  box = $("#book_url_box");
  editBox = $('#book_url_edit_box');
  

  $("#book_url_box").css('display', 'none');
  $('#book_url_edit_box').css('display', 'flex');
});

$("#book_url_cancel").on("click", function () {
  $("#book_url_box").css('display', 'flex');
  $('#book_url_edit_box').css('display', 'none');
});

$("#book_url").on("change", function () {
  postUpdateBooks("book_url", $(this).val(), $(this).data("book_id"));
});

$("#book_memo").on("change", function () {
  postUpdateBooks("book_memo", $(this).val(), $(this).data("book_id"));
});

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
