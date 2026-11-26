(function ($) {
  $('#menu-toggle').on('click', function () {
    var open = $('#menu').toggleClass('is-open').hasClass('is-open');
    $(this).attr('aria-expanded', open ? 'true' : 'false');
  });

  $('#account-toggle').on('click', function (e) {
    e.stopPropagation();
    $(this).closest('.account').toggleClass('is-open');
  });

  $(document).on('click', function () {
    $('.account').removeClass('is-open');
  });

  $(document).on('click', '.tab-nav a', function (e) {
    e.preventDefault();
    var id = $(this).attr('href');
    var tabs = $(this).closest('.tabs');
    tabs.find('.tab-nav li').removeClass('is-active');
    $(this).parent().addClass('is-active');
    tabs.find('.tab-pane').removeClass('is-active');
    tabs.find(id).addClass('is-active');
  });
})(jQuery);
