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

  var userId = PAGE_CONFIG.userId;
  var userNick = PAGE_CONFIG.username;

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
  //(function () {
  //  var voteData = PAGE_CONFIG.ballot;
  //  if (!voteData || voteData.type !== 2) return false;
  //  var $module = $('.module-vote .pk');
  //
  //  if (voteData.agreed) {
  //    var voteRes = calcVoteRes();
  //    showVoteRes($module.children('.pk-item'), voteRes.agreePercentList);
  //  } else {
  //    $module.on('click', '.e-pk', function () {
  //      // 登录用户方可投票
  //      if (!userId) {
  //        callLogin();
  //        return false;
  //      }
  //      var $items = $module.children('.pk-item');
  //      var voteRes = calcVoteRes($items);
  //      showVoteRes($items, voteRes.agreePercentList);
  //      // 移除投票事件监听
  //      $module.off('click', '.e-pk');
  //      // 提交投票
  //      $.post('//yun.app/api/ballots/answer', {
  //        choice_ids: voteRes.agreeIds,
  //        user_id: userId
  //      });
  //    });
  //  }
  //
  //  function calcVoteRes(vote) {
  //    var agreeCount = voteData.agree;
  //    var disagreeCount = voteData.disagree;
  //    if (vote === 1) {
  //      agreeCount += 1;
  //    } else if (vote === 0) {
  //      disagreeCount += 1;
  //    }
  //    return +(agreeCount / (agreeCount + disagreeCount) * 100).toFixed(1);
  //  }
  //
  //  function showVoteRes($items, agreePercentList) {
  //    $items.eq(0).text(agreePercentList[0] + '%');
  //    $items.eq(1).text(agreePercentList[1] + '%');
  //    $items.parent().addClass('show-res')
  //    .find('.proportion-agree').css('width', agreePercentList[0] + '%');
  //  }
  //})();

  /**
   * 投票
   */
  (function () {
    var voteData = PAGE_CONFIG.ballot;
    if (!voteData) return false;
    var $module = $('.module-vote .vote-box');
    var showVoteRes;

    if (voteData.type === 2) {
      showVoteRes = showPkVoteRes;
    } else {
      showVoteRes = showNormalVoteRes;
    }

    if (voteData.agreed) {
      var voteRes = calcVoteRes();
      showVoteRes($module.children('.vote-item'), voteRes.agreePercentList);
    } else {
      $module.on('click', '.e-vote', function () {
        $(this).toggleClass('selected');
      }).on('click', '.e-submit', function () {
        // 登录用户方可投票
        if (!userId) {
          callLogin();
          return false;
        }
        var $items = $module.children('.vote-item');
        var voteRes = calcVoteRes($items);
        showVoteRes($items, voteRes.agreePercentList);
        // 移除投票事件监听
        $module.off('click', '.e-vote').off('click', '.e-submit');
        // 提交投票
        $.post('//yun.app/api/ballots/answer', {
          choice_ids: voteRes.agreeIds,
          user_id: userId
        });
      });
    }

    function calcVoteRes($items) {
      var agreeCountList = voteData.agree;
      var agreeTotalCount;
      var agreePercentList;
      var agreeIds = [];
      if ($items) {
        $items.each(function (index) {
          if (this.classList.contains('selected')) {
            agreeCountList[index] += 1;
            agreeIds.push(this.getAttribute('data-vote'));
          }
        });
      }
      agreeTotalCount = agreeCountList.reduce(function (per, cur) {
        return per + cur;
      }, 0);
      agreePercentList = agreeCountList.map(function (agreeCount) {
        return (agreeCount / agreeTotalCount * 100).toFixed(1);
      });

      return {
        agreePercentList: agreePercentList,
        agreeIds: agreeIds
      }
    }

    function showNormalVoteRes($items, agreePercentList) {
      $items.each(function (index) {
        var strPercent = agreePercentList[index] + '%';
        $(this).find('.percent').text(strPercent);
        $(this).find('.proportion-agree').css('width', strPercent);
      }).parent().addClass('show-res');
    }

    function showPkVoteRes($items, agreePercentList) {
      $items.eq(0).text(agreePercentList[0] + '%');
      $items.eq(1).text(agreePercentList[1] + '%');
      $items.parent().addClass('show-res')
      .find('.proportion-agree').css('width', agreePercentList[0] + '%');
    }
  })();

  function callLogin() {

  }
});