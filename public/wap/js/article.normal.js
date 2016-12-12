/**
 * @author kael
 * @create 2016-12-11 17:06
 *
 * 普通正文页
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
})('Main.Article.Normal', window, function (exports, require) {
  var $ = require('jQuery');
  var CommentShow = require('Widgets.Comment.Show');
  var CommentWrite = require('Widgets.Comment.Write');

  /**
   * 评论列表
   */
  var commentShow = CommentShow.new({
    root: document.getElementById('comments'),
    articleId: PAGE_CONFIG.articleId,
    pageSize: 3,
    replyCallback: function (replyId, replyNick) {
      $(window).trigger('reply', [replyId, replyNick]);
    }
  });

  /**
   * 发表评论
   */
  var userId = '1102';
  var userNick = 'Dr. Sabryna Lehner';
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
            user_id: submitData.replyId,
            user_nick: submitData.replyNick
          },
          content: submitData.content
        });
        this.resetWrite();
      }
    }
  });
  $(window).on('reply', function (e, replyId, replyNick) {
    commentWrite.write(replyId, replyNick);
  });

  /**
   * 投票 -- pk
   */
  (function () {
    var pkData = PAGE_CONFIG.pk;
    if (!pkData) return false;
    var $module = $('.module-vote');
    var $pkItem = $module.find('.pk-item');

    if (pkData.vote != undefined) {
      var agreePercent = calcAgreePercent();
      showVoteRes($pkItem.eq(pkData.vote ? 0 : 1), agreePercent);
    } else {
      $module.on('click', '.e-pk', function () {
        var vote = +this.getAttribute('data-vote');
        var agreePercent = calcAgreePercent(vote);
        showVoteRes($(this), agreePercent);

        $module.off('click', '.e-pk');
      });
    }

    function calcAgreePercent(vote) {
      var agreeCount = pkData.agree;
      var disagreeCount = pkData.disagree;
      if (vote === 1) {
        agreeCount += 1;
      } else if (vote === 0) {
        disagreeCount += 1;
      }
      return +(agreeCount / (agreeCount + disagreeCount) * 100).toFixed(1);
    }

    function showVoteRes($el, agreePercent) {
      $module.find('.agree-percent').text(agreePercent + '%');
      $module.find('.disagree-percent').text((100 - agreePercent).toFixed(1) + '%');

      $el.addClass('selected')
      .siblings('.proportion').addClass('show')
      .parent().addClass('disable');

      $module.find('.proportion-agree').css('width', agreePercent + '%');
    }
  })();
});