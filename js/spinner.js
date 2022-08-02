jQuery(function ($) {
  var arySpinnerCtrl = [];
  var spin_speed = 20; //変動スピード

  //長押し押下時
  $(".btnspinner").on("touchstart mousedown click", function (e) {
    if (arySpinnerCtrl["interval"]) return false;

    arySpinnerCtrl["target"] = $(this).attr('targetId');
    arySpinnerCtrl["timestamp"] = e.timeStamp;
    arySpinnerCtrl["cal"] = Number($(this).data("cal"));
    //クリックは単一の処理に留める
    if (e.type == "click") {
      spinnerCal();
      arySpinnerCtrl = [];
      return false;
    }
    //長押し時の処理
    setTimeout(function () {
      //インターバル未実行中 + 長押しのイベントタイプスタンプ一致時に計算処理
      if (
        !arySpinnerCtrl["interval"] &&
        arySpinnerCtrl["timestamp"] == e.timeStamp
      ) {
        arySpinnerCtrl["interval"] = setInterval(spinnerCal, spin_speed);
      }
    }, 500);
  });

  //長押し解除時 画面スクロールも解除に含む
  $(document).on("touchend mouseup scroll", function (e) {
    if (arySpinnerCtrl["interval"]) {
      clearInterval(arySpinnerCtrl["interval"]);
      arySpinnerCtrl = [];
    }
  });

  function spincal() {
    var target = $(arySpinnerCtrl["id"]);
  }
  //変動計算関数
  function spinnerCal() {
    // console.log(arySpinnerCtrl["target"] + ":" + arySpinnerCtrl["cal"]);

    // var target = $('#'+arySpinnerCtrl["target"]);
    // var target2 = $('#'+"quant[132][bobo_119141-8-9years]");
    var target = $(document.getElementById(arySpinnerCtrl["target"]));
    // console.log(target.val());
    // console.log(target2.val());
    // console.log($(target3).val());

    var num = Number(target.val());
    num = num + arySpinnerCtrl["cal"];
    if (num > Number(target.data("max"))) {
      target.val(Number(target.data("max")));
    } else if (Number(target.data("min")) > num) {
      target.val(Number(target.data("min")));
    } else {
      target.val(num);
    }
  }
});
