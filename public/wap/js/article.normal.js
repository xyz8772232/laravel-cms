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
    replyCallback: function (replyId, replyUserId, replyUserNick) {
      $(window).trigger('comment', [replyId, replyUserId, replyUserNick]);
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
        this.resetWrite();
      }
    }
  });
  $(window).on('comment', function (e, replyId, replyUserId, replyUserNick) {
    commentWrite.write(replyId, replyUserId, replyUserNick);
  });

  /**
   * 投票
   */
  (function () {
    var voteData = PAGE_CONFIG.ballot;
    if (!voteData) return false;
    var $module = $('.module-vote .vote-box');
    var showVoteRes;
    var selectedCount = 0;
    var voteMax = voteData.max;

    if (voteData.type === 2) {
      showVoteRes = showPkVoteRes;
    } else {
      showVoteRes = showNormalVoteRes;
    }

    if (voteData.agreed) {
      var voteRes = calcVoteRes();
      showVoteRes($module, $module.children('.vote-item'), voteRes.agreePercentList);
    } else {
      $module.on('click', '.e-vote', function () {
        if (this.classList.contains('selected')) {
          this.classList.remove('selected');
          selectedCount--;
        } else {
          if (selectedCount < voteMax) {
            this.classList.add('selected');
            selectedCount++;
          } else {
            alert('最多只能投' + voteMax + '票');
          }
        }
      }).on('click', '.e-submit', function () {
        // 登录用户方可投票
        //if (!userId) {
        //  callLogin();
        //  return false;
        //}
        // 未投票不能提交
        if (!selectedCount) {
          alert('请先投票');
          return false;
        }
        var $items = $module.children('.vote-item');
        var voteRes = calcVoteRes($items);
        showVoteRes($module, $items, voteRes.agreePercentList);
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

    function showNormalVoteRes($module, $items, agreePercentList) {
      $items.each(function (index) {
        var strPercent = agreePercentList[index] + '%';
        $(this).find('.percent').text(strPercent);
        $(this).find('.proportion-agree').css('width', strPercent);
      });
      $module.addClass('show-res');
    }

    function showPkVoteRes($module, $items, agreePercentList) {
      $module.find('.agree-percent').text(agreePercentList[0] + '%');
      $module.find('.disagree-percent').text(agreePercentList[1] + '%');
      $module.addClass('show-res')
      .find('.proportion-agree').css('width', agreePercentList[0] + '%');
    }
  })();

  function callLogin() {
    alert('请先登录');
  }
});