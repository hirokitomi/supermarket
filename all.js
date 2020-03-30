
$(function() {

  //セレクトボックスが切り替わったら発動
  $('.fruit').change(function() {

    //選択したvalue値を変数に格納
    var fruit = $(this).val();

    //選択したvalue値をp要素に出力
    $('#fruit').text(fruit);
  });
});

$(function() {

  //セレクトボックスが切り替わったら発動
  $('.vegetable').change(function() {

    //選択したvalue値を変数に格納
    var vegetable = $(this).val();

    //選択したvalue値をp要素に出力
    $('#vegetable').text(vegetable);
  });
})

$(function() {

  //セレクトボックスが切り替わったら発動
  $('.seafood').change(function() {

    //選択したvalue値を変数に格納
    var seafood = $(this).val();

    //選択したvalue値をp要素に出力
    $('#seafood').text(seafood);
  });
})

$(function() {

  //セレクトボックスが切り替わったら発動
  $('.meet').change(function() {

    //選択したvalue値を変数に格納
    var meet = $(this).val();

    //選択したvalue値をp要素に出力
    $('#meet').text(meet);
  });
})

$(function() {

  //セレクトボックスが切り替わったら発動
  $('.milkegg').change(function() {

    //選択したvalue値を変数に格納
    var milkegg = $(this).val();

    //選択したvalue値をp要素に出力
    $('#milkegg').text(milkegg);
  });
})


$(function() {
    $('input[name="fruit"]').change(function() {
    var fruit_list = [];
    $('input[name="fruit"]:checked').each(function() {
      fruit_list.push($(this).val());
    });
    $('#fruit').text(fruit_list);
  });
});

$(function() {
    $('input[name="seafood"]').change(function() {
    var seafood_list = [];
    $('input[name="seafood"]:checked').each(function() {
      seafood_list.push($(this).val());
    });
    $('#seafood').text(seafood_list);
  });
});

$(function() {
    $('input[name="meet"]').change(function() {
    var meet_list = [];
    $('input[name="meet"]:checked').each(function() {
      meet_list.push($(this).val());
    });
    $('#meet').text(meet_list);
  });
});

$(function() {
    $('input[name="milkegg"]').change(function() {
    var milkegg_list = [];
    $('input[name="milkegg"]:checked').each(function() {
      milkegg_list.push($(this).val());
    });
    $('#milkegg').text(milkegg_list);
  });
});
