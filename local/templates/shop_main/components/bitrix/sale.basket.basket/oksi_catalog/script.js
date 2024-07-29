$(document).ready(function () {
    function decreaseAmount(id, amount) {
        $.ajax({
            url: '',
            data: {
                ajax: 'Y',
                action: 'DECREASE_IN_BASKET',
                id: id,
                amount: amount
            },
            type: 'POST',
            success: function (data) {
                location.reload();
                alert('Success');
            },
            error: function () {
                alert('Failure!');
            }
        });
    }

    function increaseAmount(id, amount) {
        $.ajax({
            url: '',
            data: {
                ajax: 'Y',
                action: 'INCREASE_IN_BASKET',
                id: id,
                amount: amount
            },
            type: 'POST',
            success: function (data) {
                location.reload();
                alert('Success!');
            },
            error: function () {
                alert('Failure!');
            }
        });
    }

    $('.cart_quantity_delete').on('click', function () {
        let id = $(this).attr('data-id');
        $.ajax({
            url: '',
            data: {
                ajax: 'Y',
                action: 'DELETE_FROM_BASKET',
                id: id
            },
            type: 'POST',
            success: function (data) {
                //location.reload();
                alert('Product was deleted from cart.');
            },
            error: function () {
                alert('Failure!');
            }
        });
    });

    $('.cart_quantity_down').on('click', function () {
        let id = $(this).attr('data-id');
        decreaseAmount(id, 1);
    });

    $('.cart_quantity_up').on('click', function () {
        let id = $(this).attr('data-id');
        increaseAmount(id, 1);
    });

    $('.cart_quantity_input').on('change', function () {
        let id = $(this).attr('data-id');
        let amount = $(this).val();
        let prevAmount = parseInt($(this).attr('data-prev-value'));
        let amountInt = parseInt(amount);
        let maxlength = $(this).attr('maxlength');
        //alert(id + ' ' + prevAmount + ' ' + amountInt + ' ' + maxlength);
        if (amount == amountInt && amountInt !== 0) {
            if (amountInt > maxlength) {
                alert('К сожалению на данный момент доступно к заказу ' + maxlength + 'шт.');
                location.reload();
            } else {
                if (amountInt > prevAmount) {
                    let diff = amountInt - prevAmount;
                    //alert(diff);
                    increaseAmount(id, diff);
                } else if (amountInt < prevAmount) {
                    let diff = prevAmount - amountInt;
                    //alert(diff);
                    decreaseAmount(id, diff);
                } else {
                    alert('Введенное значение соответствует текущему количеству данного товара в корзине.');
                    location.reload();
                }
            }
        } else {
            alert('Введенное значение должно быть числом больше 0');
            location.reload();
        }
    });

})
;