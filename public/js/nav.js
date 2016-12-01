$(function () {
  /**
   * 用户
   */
  $('#eUser').on('click', function () {
    $(this).siblings('.user-menu').slideToggle(200);
  });

  /**
   * 侧边导航
   */
  // 展开收缩侧边栏
  var miniNav = false;
  $('#eSideBar').on('click', function () {
    $('body')[miniNav ? 'removeClass' : 'addClass']('mini-nav');
    miniNav = !miniNav;
  });

  // 一级菜单
  var $menu2List = $('.menu-2');
  var $curMenu1 = $('.menu-1 .active');
  var $curMenu2 = $menu2List.filter('.active');
  $('#eMenu1').on('click', '.e-select', function () {
    var $this = $(this);
    var index = +this.getAttribute('data-index');

    if (!$this.hasClass('active')) {
      $curMenu1.removeClass('active');
      $curMenu1 = $this;
      $curMenu1.addClass('active');
      $curMenu2.removeClass('active');
      $curMenu2 = $menu2List.eq(index);
      $curMenu2.addClass('active');
    }
  });

  // 二级菜单
  $('#eMenu2').on('click', '.e-select', function (e) {
    var $this = $(this);

    $this.toggleClass('active')
    .children('.menu-3').slideToggle(250);
    $this.siblings('.active').removeClass('active')
    .children('.menu-3').slideUp(250);
  }).on('click', '.menu-3', function (e) {
    e.stopPropagation();
  });
});