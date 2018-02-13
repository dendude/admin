$(document).ready(function(){

    // клик по кнопке получения алиаса
    var $btn_alias = $('.btn-alias');
    if ($btn_alias.length) {
        $btn_alias.on('click', function (e) {
            e.preventDefault();
            var $a = $(this);
            $.ajax({
                url: $a.attr('href'),
                dataType: 'text',
                data: {
                    str: $('#' + $a.data('from')).val()
                },
                beforeSend: function(){
                    loader.show($btn_alias.closest('.form-group'));
                },
                success: function(alias) {
                    $('#' + $a.data('to')).val(alias);
                }
            })
        });
    }

    // отправка формы
    $('form').on('beforeSubmit', function(){
        loader.show($('.box', this));
    });

    // просмотр миниатюр в таблицах
    var $slider = $('.a-slider');
    if ($slider.length) {
        $slider.colorbox({
            photo: true,
            ajax: true,
            maxWidth: '80%',
            maxHeight: '90%'
        });
    }

    // select2
    set_select2();
});

function set_select2(obj) {
    var $sel = $(obj || '.select2');

    $sel.css('width', '100%');
    var $select2 = $sel.filter(':visible').select2({
        selectOnClose: true
    });

    $select2.on('select2:select', function (e) {
        var data = e.params.data;

        $('option', $sel).removeAttr('selected');
        $('option', $sel).prop('selected', false);

        $('option[value=' + data.id + ']', $sel).attr('selected', 'selected');
        $('option[value=' + data.id + ']', $sel).prop('selected', true);

        $sel.val(data.id);
    });
}

function set_field(type, field, url, obj) {
    $.ajax({
        url: url,
        data: {
            type: type,
            field: field,
            value: obj.value
        },
        beforeSend: function(){
            loader.show($(obj).closest('tr'));
        },
        success: function() {
            location.reload();
        }
    });
}

function charsCalculate(obj) {
    var $obj = $(obj);
    var $group = $obj.closest('.form-group');
    var length = $obj.val().length;

    if (length > 0) {
        $('.help-block', $group).text('Введено символов: ' + length);
    } else {
        $('.help-block', $group).text('');
    }
}

/**
 * вставка хлебных крошек навигации
 */
function set_crumb_top(select) {
    var content = '';
    var $row = $('#breads_top'),
        $res = $('#bread_top_result');

    $('.crumb-item', $res).remove();

    if (select) {
        var $s = $(select);
        var $i = $s.closest('tr').find('input');
        if ($s.val()) {
            $i.val($('option:selected', $s).text());
        } else {
            $i.val('');
        }
    }

    $('tr', $row).each(function(){
        var urls, url;
        var $i = $('input', this),
            $s = $('select', this);

        try {
            urls = $s.data('urls');
            url = urls[$s.val()] || '#';
        } catch (e) {
            console.log(e.message);
            return;
        }

        if ($i.val() && $s.val()) {
            content += $('<li class="crumb-item"><a href="' + url + '" target="_blank">' + $i.val() + '</a></li>').outerHTML();
        }
    });

    $(content).insertAfter($('.home-crumb', $res));
}

/**
 * вставка дополнительных хлебных крошек
 */
function set_crumb_bottom(select) {
    var content = '';
    var $row = $('#breads_bottom'),
        $res = $('#bread_bottom_result');

    $('li', $res).remove();

    if (select) {
        var $s = $(select);
        var $i = $s.closest('tr').find('input');
        if ($s.val()) {
            $i.val($('option:selected', $s).text());
        } else {
            $i.val('');
        }
    }

    $('tr', $row).each(function(){
        var urls, url;
        var $i = $('input', this),
            $s = $('select', this);

        try {
            urls = $s.data('urls');
            url = urls[$s.val()] || '#';
        } catch (e) {
            console.log(e.message);
            return;
        }

        if ($i.val() && $s.val()) {
            content += $('<li><a href="' + url + '" target="_blank">' + $i.val() + '</a></li>').outerHTML();
        }
    });

    $res.html(content);
}

/**
 * вставка страниц для инфоблоков
 */
function set_infoblocks_pages(select) {
    var content = '';
    var $row = $('#infoblocks_pages'),
        $res = $('#infoblocks_pages_result');

    $('li', $res).remove();

    if (select) {
        var $s = $(select);
        var $i = $s.closest('tr').find('input');
        if ($s.val()) {
            $i.val($('option:selected', $s).text());
        } else {
            $i.val('');
        }
    }

    $('tr', $row).each(function(){
        var urls, url;
        var $i = $('input', this),
            $s = $('select', this);

        try {
            urls = $s.data('urls');
            url = urls[$s.val()] || '#';
            // use for change order
            $i.attr('value', $i.val());
        } catch (e) {
            console.log(e.message);
            return;
        }

        if ($i.val() && $s.val()) {
            content += $('<li><a href="' + url + '" target="_blank">' + $i.val() + '</a></li>').outerHTML();
        }
    });

    $res.html(content);

    if (content == '') {
        $('#infoblocks_content').addClass('hidden');
    } else {
        $('#infoblocks_content').removeClass('hidden');
    }
}

var Gallery = {
    selector: '.gallery-item',

    up: function(obj) {
        var $current = $(obj).closest(this.selector);
        var $prev = $current.prev();

        var tmp = $current.html();

        $current.html($prev.html());
        $prev.html(tmp);
    },
    down: function(obj) {
        var $current = $(obj).closest(this.selector);
        var $next = $current.next();

        var tmp = $current.html();

        $current.html($next.html());
        $next.html(tmp);
    }
};