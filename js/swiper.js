// swiper

// トップヘッダー用スライダー
const swiper = new Swiper(".swiper-tophead", {
    loop: true,
    speed: 400,
    loopAdditionalSlides: 5, //スライダーを複製してループを滑らかにする
    allowTouchMove: true, //マウスでのスワイプを禁止
    centeredSlides: true,
    slidesPerView: "1.5",
    spaceBetween: 20,

    pagination: { //円形のページネーションを有効にする
        el: ".swiper-pagination",
        clickable: true //クリックを有効にする
    },

    // ナビボタンが必要なら追加
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev"
    },
});

// トップ新商品スライダー
const swiper2 = new Swiper(".swiper-newitem", {
    loop: true,
    speed: 400,
    loopAdditionalSlides: 5, //スライダーを複製してループを滑らかにする
    allowTouchMove: true, //マウスでのスワイプを禁止
    centeredSlides: true,
    slidesPerView: "2.5",
    spaceBetween: 10,

    pagination: { //円形のページネーションを有効にする
        el: ".swiper-pagination",
        clickable: true //クリックを有効にする
    },
});