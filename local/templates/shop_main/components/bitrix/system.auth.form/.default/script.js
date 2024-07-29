$(document).ready(function () {

    $(".auth-submit").on("click", function () {
        let checkbox = $('input[type=checkbox]');
        let remember = 'N';
        if (checkbox.prop('checked'))
            remember = 'Y';

        let login = $("#login").val();
        let pass = $("#pass").val();

        if (login === '' || pass === '') {
            alert('everything should be fullfiled');
        } else {
            // Отправляем данные на сервер с помощью AJAX
            $.ajax({
                url: '/local/ajax/auth_reg_profile.php', // Здесь указываем URL-адрес серверного обработчика
                type: 'POST',
                data: {
                    action: 'AUT',
                    login: login,
                    password: pass,
                    remember: remember,
                },
                success: function (response) {
                    console.log(response);
                    // Обработка успешной отправки данных
                    console.log("Данные успешно отправлены!");
                },
                error: function (error) {
                    // Обработка ошибок при отправке данных
                    console.error("Ошибка при отправке данных: ", error);
                    console.log("MISTAKE");
                },
            });
        }
    });
})
;