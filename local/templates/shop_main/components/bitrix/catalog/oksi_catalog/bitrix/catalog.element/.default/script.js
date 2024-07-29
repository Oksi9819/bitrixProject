$(document).ready(function () {
    let starBlock = ($('.stars-block'))[0];
    let voteValue = starBlock.getAttribute('data-value');
    let mVV = Math.floor(voteValue);
    let txt = '';
    let txt2 = '';
    let txt3 = '';
    let newVoteStar = '';

    if ($(starBlock).is(':empty')) {
        txt = '<i class="fa fa-star"></i>';
        txt2 = '<i class="fa fa-star stars-form" data-id-star="1"></i><i class="fa fa-star stars-form" data-id-star="2"></i><i class="fa fa-star stars-form" data-id-star="3"></i><i class="fa fa-star stars-form" data-id-star="4"></i><i class="fa fa-star stars-form" data-id-star="5"></i>';
    } else {
        let lastStar = 0;
        for (let j = 0; j < mVV; j++) {
            lastStar += 1;
            txt += '<i class="fa fa-star"></i>';
            txt2 += '<i class="fa fa-star stars-form color" data-id-star="' + lastStar + '"></i>';

        }
        if (voteValue > mVV) {
            txt += '<i class="fa fa-star-half-o"></i>';
        }
        if (voteValue < 5) {
            let emptyStars = 5 - mVV;
            for (let n = 0; n < emptyStars; n++) {
                lastStar += 1;
                txt2 += '<i class="fa fa-star stars-form" data-id-star="' + lastStar + '"></i>';
            }
        }
        $(starBlock).html(txt);
        $('.stars-block-form').html(txt2);
    }

    let comm = $('.comment-rating');
    for (let b = 0; b < comm.length; b++) {
        let valComm = comm[b].getAttribute('data-value');
        let commStarHtml = '';
        for (let k = 0; k < valComm; k++) {
            commStarHtml += '<i class="fa fa-star color" ></i>';
        }
        comm[b].innerHTML = commStarHtml;
    }


    $('.add-to-cart[data-cart-status = N]').on('click', function () {
        let id = $(this).attr('data-id');
        let name = $(this).attr('data-name');
        let price = $(this).attr('data-price');
        //alert(id + ' ' + name + ' ' + price);
        $.ajax({
            url: '',
            data: {
                action: 'ADD2BASKET',
                itemId: id,
                itemName: name,
                itemPrice: price
            },
            type: 'POST',
            success: function (data) {
                alert('Товар добавлен в корзину.');
                location.reload();
            },
            error: function () {
                alert('Товар в корзину добавить не удалось.');
            }
        });
    });

    $('.stars-form').on('click', function () {
        newVoteStar = $(this).attr('data-id-star');
        let newVoteTxt = '';
        let lStar = 0;
        for (let l = 0; l < newVoteStar; l++) {
            lStar += 1;
            newVoteTxt += '<i class="fa fa-star stars-form color" data-id-star="' + lStar + '"></i>';
        }
        if (newVoteStar < 5) {
            let emptyStars = 5 - newVoteStar;
            for (let m = 0; m < emptyStars; m++) {
                lStar += 1;
                newVoteTxt += '<i class="fa fa-star stars-form" data-id-star="' + lStar + '"></i>';
            }
        }
        $('.stars-block-form').html(newVoteTxt);
        console.log(newVoteStar);
    });

    $('.submit-send-review').on('click', function () {
        let name = $('#review_user_name').val();
        let id = $('#review_user_name').attr('data-user-id');
        let email = $('#review_user_email').val();
        let text = $('#review_user_text').val();
        alert(id + ' ' + name + ' ' + email + ' ' + text + ' ' + newVoteStar);

        if (newVoteStar !== '' && name !== '' && email !== '' && text !== '') {
            $.ajax({
                url: '',
                data: {
                    action: 'WRITE_REVIEW',
                    user_id: id,
                    user_name: name,
                    review_text: text,
                    vote_value: newVoteStar,
                    user_email: email
                },
                type: 'POST',
                success: function (data) {
                    alert('Спасибо за отзыв!');
                    location.reload();
                },
                error: function () {
                    alert('Не удалось оставить отзыв');
                }
            });
        } else {
            alert('Все поля должны быть заполнены!');
        }
    });
})
;