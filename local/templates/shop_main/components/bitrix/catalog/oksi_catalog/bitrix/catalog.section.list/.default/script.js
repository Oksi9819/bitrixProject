$(document).ready(function () {
    $('a[data-toggle = collapse]').on('click', function () {
        let parentID = $(this).attr('data-id');
        let subMenu = $('div[data-parent = ' + parentID + ']');
        if ($(this).hasClass('collapsed')) {
            $(this).attr('class', 'not-collapsed');
            subMenu.removeClass('collapse');
            subMenu.addClass('in');
        } else {
            subMenu.removeClass('in');
            subMenu.addClass('collapse');
            $(this).attr('class', 'collapsed');
        }
    });
});