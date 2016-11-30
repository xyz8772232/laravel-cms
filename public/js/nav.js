$(function () {
  /**
   * 用户
   */
  var userMenuFold = true;
  $('#eUser').on('click', function () {
    $(this).parent()[userMenuFold ? 'addClass' : 'removeClass']('unfold');
    userMenuFold = !userMenuFold;
  });

  /**
   * 侧边导航
   */
  var miniNav = false;
  $('#eSideBar').on('click', function () {
    $('body')[miniNav ? 'removeClass' : 'addClass']('mini-nav');
    miniNav = !miniNav;
  });
});