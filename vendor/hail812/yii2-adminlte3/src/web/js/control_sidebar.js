(function ($) {
    let $sidebar = $('.control-sidebar');
    let classNameControll = [
        'dark-mode',
    ];
    let $container = $('<div />', {
        class: 'p-3 control-sidebar-content'
    });
    function setCookie(cname, cvalue) {
        var d = new Date();
        d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }
    for (let idx in classNameControll) { 
        let ckn = getCookie(classNameControll[idx]);
        if (ckn=='true') { 
            $('body').addClass(classNameControll[idx]);
        }
    }
    $sidebar.append($container);

    let $dark_mode_checkbox = $('<input />', {
        type: 'checkbox',
        value: 1,
        checked: $('body').hasClass('dark-mode'),
        class: 'mr-1'
    }).on('click', function () {
        if ($(this).is(':checked')) {
            $('body').addClass('dark-mode');
        } else {
            $('body').removeClass('dark-mode');
        }
        setCookie('dark-mode', $(this).is(':checked')) 
    });
    let $dark_mode_container = $('<div />', {class: 'mb-4'}).append($dark_mode_checkbox).append('<span>Dark Mode</span>');
    $container.append($dark_mode_container);
})(jQuery);