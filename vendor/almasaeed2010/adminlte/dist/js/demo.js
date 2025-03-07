(function ($) {
  'use strict';

  let $sidebar = $('.control-sidebar');

  function setCookie(cname, cvalue) {
      var d = new Date();
      d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000)); // 1 год
      var expires = "expires=" + d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  function getCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
          var c = ca[i].trim();
          if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
  }

  // Проверка сохраненного состояния "Dark Mode"
  if (getCookie('dark-mode') === 'true') {
      $('body').addClass('dark-mode');
  }

  // Очищаем содержимое `control-sidebar` перед добавлением нового контента
  $sidebar.empty();

  // Создаем контейнер для переключателя
  let $container = $('<div />', { class: 'p-3 control-sidebar-content' });

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
      setCookie('dark-mode', $(this).is(':checked'));
  });

  let $dark_mode_container = $('<div />', { class: 'mb-4' })
      .append($dark_mode_checkbox)
      .append('<span>Dark Mode</span>');

  $container.append($dark_mode_container);
  $sidebar.append($container); // Добавляем контейнер в sidebar

})(jQuery);