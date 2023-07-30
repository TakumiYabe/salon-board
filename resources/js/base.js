$(function () {

    $('.js-open-table').on('change', function() {
        var isChecked = $(this).prop('checked');
        var element = $('.hidden-table');

        // チェックボックスがオンの場合は要素を表示し、オフの場合は非表示にする
        if (isChecked) {
            element.show();
        } else {
            element.hide();
        }

    });
});
