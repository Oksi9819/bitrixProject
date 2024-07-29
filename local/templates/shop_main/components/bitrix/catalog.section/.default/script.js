$(document).ready(function () {
    /*$('.cont').css('display', 'flex');
    $('.row').css('flex-wrap', 'wrap');
    $('.col-sm-4').css('flex-basis', '33.33%');*/

    let imagesWrap = $('.img-wrap');
    let imagesWrapCnt = imagesWrap.length;
    for (let i = 0; i < imagesWrapCnt; i++) {
        $(imagesWrap[i]).css('background', 'url(' + $(imagesWrap[i]).attr('data-img-source') + ')');
        $(imagesWrap[i]).css('height', '300px');
        $(imagesWrap[i]).css('background-size', 'cover');
    }

    $('.ref-to-detail').on('click', function () {
        document.location.href = $(this).attr('data-href');
    });

    $('.add-to-cart[data-cart-status = N]').on('click', function () {
        let id = $(this).attr('data-id');
        let name = $(this).attr('data-name');
        let price = $(this).attr('data-price');
        let currency = $(this).attr('data-currency');
        alert(id + ' ' + name + ' ' + price);
        $.ajax({
            url: '',
            data: {
                action: 'ADD2BASKET',
                itemId: id,
                itemName: name,
                itemPrice: price,
                currency: currency
            },
            type: 'POST',
            success: function (data) {
                alert('Товар добавлен в корзину.');
                location.reload();
                location.href = ' #' + id;
            },
            error: function () {
                alert('Товар в корзину добавить не удалось.');
            }
        });
    });

    $('.act-with-wishlist').on('click', function () {
        let id = $(this).attr('data-id');
        //console.log(id + ' ' + name + ' ' + price);
        $.ajax({
            url: '',
            data: {
                action: 'ACT_WITH_WISHLIST',
                itemId: id
            },
            type: 'POST',
            success: function (data) {
                alert('Товар добавлен в ваш Wishlist.');
                location.reload();
                location.href = ' #' + id;
            },
            error: function () {
                alert('Товар добавить в Wishlist не удалось.');
            }
        });
    });

    $('.add-to-compare[data-compare-status = Y]').on('click', function () {
        let id = $(this).attr('data-id');
        let href = $(this).attr('data-href');
        alert(href);

        $.ajax({
            url: href,
            type: 'GET',
            success: function (data) {
                alert('Товар убран из сравнения');
                location.reload();
                location.href = ' #' + id;
            },
            error: function () {
                alert('Товар убрать из сравнения не удалось');
            }
        });
    });

    $('.add-to-compare[data-compare-status = N]').on('click', function () {
        let id = $(this).attr('data-id');
        let href = $(this).attr('data-href');
        alert(href);

        $.ajax({
            url: href,
            type: 'GET',
            success: function (data) {
                alert('Товар добавлен для сравнения');
                location.reload();
                location.href = ' #' + id;
            },
            error: function () {
                alert('Товар добавить для сравнения не удалось');
            }
        });
    });

})
;