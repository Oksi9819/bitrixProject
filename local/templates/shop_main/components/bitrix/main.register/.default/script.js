$(document).ready(function () {
    $(".btn-reg").on("click", function () {
        alert('HERE');
        //let data = $("#register").serialize();
        if ($("#reg_pass").val().length < 6) {
            alert('Password should at least consist of 6 characters');
        } else {
            console.log($('#reg_name').val() + $('#reg_lastname').val() + $('#reg_email').val() + $('#reg_pass').val() + $('#reg_pass_confirm').val());
            // Отправляем данные на сервер с помощью AJAX
            $.ajax({
                url: "", // Здесь указываем URL-адрес серверного обработчика
                type: "post",
                data: {
                    action: 'REGISTER_USER',
                    reg_name: $('#reg_name').val(),
                    reg_lastname: $('#reg_lastname').val(),
                    reg_email: $('#reg_email').val(),
                    reg_pass: $('#reg_pass').val(),
                    reg_pass_confirm: $('#reg_pass_confirm').val(),
                },
                success: function (response) {
                    // Обработка успешной отправки данных
                    console.log("Данные успешно отправлены!");
                },
                error: function (error) {
                    // Обработка ошибок при отправке данных
                    console.error("Ошибка при отправке данных: ", error);
                },
            });
        }
    });
})
;