$(function () {
    $('form input, form select, form textarea').on('change', function () {
        $(this).addClass('edited');
    });

    $('.js-open-table').on('change', function () {
        var isChecked = $(this).prop('checked');
        var element = $('.hidden-table');

        // チェックボックスがオンの場合は要素を表示し、オフの場合は非表示にする
        if (isChecked) {
            element.show();
        } else {
            element.hide();
        }
    });

    $('.js-mt-add').on('click', function () {
        var template = $('.js-mt-table-template');
        var rowCount = $('.js-mt-table tbody').find('tr').length.toString();
        var addRow = template.clone()
            .removeClass();

        addRow.find('td').each(function () {
            var input = $(this).find('input');
            input.attr('name', rowCount + input.attr('name'))
                .addClass('edited')
                .prop('disabled', false);
        });

        $('.js-mt-last-row').before(addRow);
    });

    $(document).on('click', '.js-delete-row-icon', function() {
        $(this).closest('tr').remove();
    })

});
