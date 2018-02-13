$(function(){
    var $ichecks = $('.ichecks'),
        $iradios = $('.iradios');

    if ($ichecks.length) $ichecks.iCheck({checkboxClass: 'icheckbox_square-blue'});
    if ($iradios.length) $iradios.iCheck({radioClass: 'iradio_square-blue'});

    // просмотр миниатюр в таблицах
    var $slider = $('.a-slider');
    if ($slider.length) {
        $slider.colorbox({
            photo: true,
            maxWidth: '80%',
            maxHeight: '90%'
        });
    }

    // отправка формы
    $('form').on('beforeSubmit', function(){
        loader.show('.auth-form');
    });

    set_datepickers();
});

function set_datepickers(sel) {
    var $dt = sel ? $(sel) : $('.datepickers');
    if ($dt.length) {
        $dt.datepicker({
            format: 'dd.mm.yyyy',
            language: 'ru'
        }).on('change', function (o, n) {
            $(this).datepicker("hide");
        });
    }
}

$.ajaxSetup({
    type: 'POST',
    dataType: 'JSON',
    data: {'_csrf': $('meta[name="csrf-token"]').attr('content')},
    beforeSend: function(){

    },
    complete: function(){
        loader.hide();
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
    }
});

jQuery.fn.outerHTML = function(s) {
    return s
        ? this.before(s).remove()
        : jQuery("<p>").append(this.eq(0).clone()).html();
};

var loader = {
    show: function(selector, timer, options) {
        var $selector = $(selector);
        var t = timer || 0;

        $selector.css({'position':'relative'});
        $selector.append('<div class="loader"></div>');
        var $loader = $('.loader', $selector);

        if (options) $loader.css(options);

        setTimeout(function(){
            $loader.css({height: $selector.outerHeight(),
                width: $selector.outerWidth()});
            $loader.show();
        }, t);
    },
    hide: function() {
        $('.loader').remove();
    }
};

function ajaxData(from, to, url, data, callback) {
    $.ajax({
        url: url,
        data: data,
        beforeSend: function(){
            loader.show(from);
        },
        success: function(resp) {
            if (callback) {
                callback(resp);
            } else {
                $(to).html(resp.content);
            }
        }
    });
}

function scrollTo(top) {
    $('html,body').stop().animate({scrollTop: top});
}

function word_amount(amount, words, full) {
    var w;

    switch (amount % 10) {
        case 1:
            w = words[1];
            break;

        case 2:
        case 3:
        case 4:
            w = words[2];
            break;

        default:
            w = words[0];
    }

    if (amount % 100 >= 11 && amount % 100 <= 20) w = words[0];
    if (full) w = (amount + ' ' + w);

    return w;
}