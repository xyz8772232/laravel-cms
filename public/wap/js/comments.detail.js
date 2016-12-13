/**
 * @author kael
 * @create 2016-12-11 17:06
 *
 * 评论详情页
 * @version 1.0
 */

;(function (namespace, root, factory) {
  var objToStr = Object.prototype.toString;
  if (!(namespace && objToStr.call(namespace) === '[object String]' && factory && objToStr.call(factory) === '[object Function]')) return false;
  var createSuccess = false;
  var require = function (modulePath, rootPath, create) {
    try {
      var modulePathArr = modulePath.split('.');
      var module = rootPath || window;
      while (modulePathArr.length) {
        var subPath = modulePathArr[0];
        if (module[subPath] === undefined) {
          if (!create) return;
          module[subPath] = {};
          createSuccess = true;
        }
        module = module[subPath];
        modulePathArr.shift();
      }
      return module;
    } catch (e) {
      throw new Error(e);
    }
  };
  var exports = require(namespace, root, true);
  createSuccess && factory(exports, require);
})('Main.Comments.Detail', window, function (exports, require) {
  var $ = require('jQuery');
  var CommentShow = require('Widgets.Comment.Show');
  var CommentWrite = require('Widgets.Comment.Write');
  var transitionEnd = detectTransitionEvents();

  var userId = PAGE_CONFIG.userId;
  var userNick = PAGE_CONFIG.username;

  function detectTransitionEvents() {
    var t;
    var el = document.createElement('fakeelement');
    var transitions = {
      'transition': 'transitionend',
      'OTransition': 'oTransitionEnd',
      'MozTransition': 'transitionend',
      'WebkitTransition': 'webkitTransitionEnd'
    };
    for (t in transitions) {
      if (el.style[t] !== undefined) return transitions[t];
    }
    return false;
  }

  /**
   * 评论列表
   */
  var $commentsCount = $('.header .title');
  var commentShow = CommentShow.new({
    root: document.getElementById('comments'),
    articleId: PAGE_CONFIG.articleId,
    pageSize: 10,
    infinite: true,
    replyCallback: function (replyId, replyUserId, replyUserNick) {
      $(window).trigger('comment', [replyId, replyUserId, replyUserNick]);
    },
    commentsCountUpdateCallback: function (count) {
      $commentsCount.text(count + '条评论');
    }
  });

  /**
   * 发表评论
   */
  var commentWrite = CommentWrite.new({
    root: document.getElementById('writeComment'),
    articleId: PAGE_CONFIG.articleId,
    userId: userId,
    userNick: userNick,
    submitCallback: function (submitData) {
      if (submitData) {
        commentShow && commentShow.add({
          user_id: submitData.userId,
          user_nick: submitData.userNick,
          parent: submitData.replyId && {
            id: submitData.replyId,
            user_id: submitData.replyUserId,
            user_nick: submitData.replyUserNick
          },
          content: submitData.content
        });
        $(window).trigger('closeComment');
      }
    }
  });

  /**
   * 监听评论触发
   */
  var $moduleComment = $('.module-comment');
  var $writeComment = $('#writeComment');
  var $edit = $('#edit');
  var editLock = false;
  $(window).on('comment', function (e, replyId, replyUserId, replyUserNick) {
    if (editLock) return false;
    $edit.removeClass('e-edit').addClass('e-cancel');
    $moduleComment.addClass('show');
    $writeComment.addClass('show');
    commentWrite.write(replyId, replyUserId, replyUserNick);
  }).on('closeComment', function () {
    if (editLock) return false;
    editLock = true;
    commentWrite.resetWrite();
    $edit.removeClass('e-cancel').addClass('e-edit');
    $writeComment.removeClass('show').on(transitionEnd, function () {
      $(this).off(transitionEnd);
      $moduleComment.removeClass('show');
      editLock = false;
    });
  });
  // 评论时禁止遮罩层touchmove事件
  $moduleComment.on('touchmove', function () {
    return false;
  }).on('touchmove', '#writeComment', function (e) {
    e.stopPropagation();
  });
  // 添加头部导航评论事件
  $('.header').on('click', '.e-edit', function () {
    $(window).trigger('comment');
    return false;
  }).on('click', '.e-cancel', function () {
    $(window).trigger('closeComment');
    return false;
  });
});