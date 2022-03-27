// 네비게이션 - header
$('nav > a').hover(function() {
    $('nav > a').css('opacity', '0.3');
    $('nav > a:hover').css('opacity', '1');
}, function() {
    $('nav > a').css('opacity', '1');
});

// 모바일네비게이션 - header
// $('.m_nav_btn > a').click(function() {
//     if ($('#header02, nav, .acc_section').hasClass('n_open')) {
//         $('#header02, nav, .acc_section').removeClass('n_open');
//     } else {
//         $('#header02, nav, .acc_section').addClass('n_open');
//     }
//     $(this).toggleClass('n_close');
// });
