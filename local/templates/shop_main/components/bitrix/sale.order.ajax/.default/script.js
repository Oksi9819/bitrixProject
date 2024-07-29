$(document).ready(function () {
    let fio = $('.full-name').val();
    let email = $('.email').val();
    let phone = $('.phone').val();
    let address1 = $('.address1').val();
    let zip = '';
    let locationId = '';
    let cityCode = '';
    let address2 = '';
    let checkedDeliveryCode = '';
    let checkedPaySystemId = '';

    $('.order-message').css("display", "none");
    $('.payment-options').css("display", "none");
    //$('.payment-options').css("display", "block");

    $('.form-one form input[type=text]').on('change', function () {
        let inputVal = $(this).val();
        if ($(this).attr('class') === 'full-name') {
            fio = inputVal;
        } else if ($(this).attr('class') === 'email') {
            email = inputVal;
        } else if ($(this).attr('class') === 'phone') {
            phone = inputVal;
        } else if ($(this).attr('class') === 'address1') {
            address1 = inputVal;
        } else if ($(this).attr('class') === 'address2') {
            address2 = inputVal;
        }
    });

    $('.zip').on('change', function () {
        zip = $(this).val();
    });

    $('.select-country').on('change', function () {
        let id = $(this).val();
        $.ajax({
            url: '',
            data: {
                action: 'SELECT_COUNTRY',
                region_id: id,
            },
            type: 'POST',
            success: function (data) {
                let info = $(data);
                locationId = id;
                let newInfo = info.find('.select-region').html();
                $('.select-delivery').attr('data-id', id);
                $('.select-region').html(newInfo);
                $('.select-region option[value=example]').prop('selected', true);
                $('.select-city option[value=example]').prop('selected', true);
                $('.order-message').css("display", "none");
                checkedDeliveryCode = ''
            },
            error: function () {
                alert('Failure!');
            }
        });
    });

    $('.select-region').on('change', function () {
        let id = $(this).val();
        $.ajax({
            url: '',
            data: {
                action: 'SELECT_REGION',
                region_id: id,
            },
            type: 'POST',
            success: function (data) {
                let info = $(data);
                let newInfo = info.find('.select-city').html();
                $('.select-city').html(newInfo);
                $('.select-city option[value=example]').prop('selected', true);
                $('.order-message').css("display", "none");
                checkedDeliveryCode = ''
            },
            error: function () {
                alert('Failure!');
            }
        });
    });

    $('.select-city').on('change', function () {
        let city = $('.select-city').val();

        if (city !== 'example') {
            let code = $('.select-city option:selected').attr('data-code');
            $.ajax({
                url: '',
                data: {
                    action: 'OPEN_DELIVERY_OPTIONS',
                    country_code: code,
                },
                type: 'POST',
                success: function (data) {
                    let info = $(data);
                    cityCode = code;
                    let newInfo = info.find('.select-delivery').html();
                    $('.select-delivery').html(newInfo);
                    $('.order-message').css("display", "block");

                    $('input[type = "radio"][data-type="delivery"]').on('change', function () {
                        checkedDeliveryCode = $(this).val();
                        $.ajax({
                            url: '',
                            data: {
                                action: 'OPEN_PAYMENT_OPTIONS',
                                delivery_code: checkedDeliveryCode,
                            },
                            type: 'POST',
                            success: function (data) {
                                let info = $(data);
                                let newInfo = info.find('.payment-options').html();
                                let paymentField = $('.payment-options');
                                paymentField.html(newInfo);
                                paymentField.css("display", "block");

                                $('input[type=checkbox]').on('click', function () {
                                    if ($(this).attr('class') === 'special') {
                                        $(this).removeAttr('class');
                                        checkedPaySystemId = '';
                                    } else {
                                        let $alreadyChecked = $('.special');
                                        $alreadyChecked.prop("checked", false);
                                        $alreadyChecked.removeAttr('class');
                                        $(this).addClass('special');
                                        checkedPaySystemId = $(this).val();
                                    }
                                });

                                $('.finish-checkout-btn').on('click', function () {
                                    if (fio !== ''
                                        && email !== ''
                                        && phone !== ''
                                        && zip !== ''
                                        && locationId !== ''
                                        && cityCode !== ''
                                        && address1 !== ''
                                        && checkedDeliveryCode !== ''
                                        && checkedPaySystemId !== '') {
                                        let address = address1 + '; ' + address2;

                                        /*alert(cityCode);
                                        alert(locationId);
                                        alert(checkedDeliveryCode);
                                        alert(checkedPaySystemId);*/

                                        $.ajax({
                                            url: '',
                                            data: {
                                                action: 'FINISH_CHECKOUT',
                                                fio: fio,
                                                email: email,
                                                phone: phone,
                                                zip: zip,
                                                locationId: locationId,
                                                cityCode: cityCode,
                                                address: address,
                                                deliveryCode: checkedDeliveryCode,
                                                paySystemId: checkedPaySystemId,
                                            },
                                            type: 'POST',
                                            success: function (data) {
                                                alert('Заказ оформлен');
                                                location.reload();
                                            },
                                            error: function () {
                                                alert('Failure!');
                                            }
                                        });
                                    } else alert('Должны быть заполнены все поля!');
                                });
                            }
                            ,
                            error: function () {
                                alert('Failure!');
                            }
                        });
                    });
                }
                ,
                error: function () {
                    alert('Failure!');
                }
            });
        } else {
            $('.order-message').css("display", "none");
            checkedDeliveryCode = '';
        }
    })
    ;
})
;