/**
 * @author kael
 * @create 2016-12-10 22:22
 *
 * 显示评论
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
})('Widgets.Comment.Show', window, function (exports, require) {
  var $ = require('jQuery');
  var doT = require('doT');

  var DEFAULT_FACE = '/wap/img/user.svg';
  var commentTplFn = doT.template(TPL_COMMENT);
  var commentsTplFn = doT.template(TPL_COMMENTS);
  var userId = PAGE_CONFIG.userId;

  exports.new = function (opt) {
    if (opt && opt.root) {
      return new Show(opt);
    }
  };

  function Show(opt) {
    this.articleId = opt.articleId;
    this.replyCallback = opt.replyCallback;
    this.commentsCountUpdateCallback = opt.commentsCountUpdateCallback;
    this._pageSize = opt.pageSize;
    this._page = 1;
    this._buildDom(opt.root, opt.infinite);
    this._queryEl(opt.root);
    this._bindEvents(opt.root);
    this._fetchCommentList();
  }

  Show.prototype = {
    constructor: Show,
    _buildDom: function (root, infinite) {
      var strHTML = '<div class="comment-list"></div>'
        + '<div class="comment-loading"><i class="icon-loading"></i></div>'
        + '<div class="comment-empty"><i class="icon-empty"></i><p class="empty-words">暂无评论，快来抢沙发！</p></div>';

      if (infinite) {
        strHTML += '<div class="comment-footer">'
          + '<div class="load load-pending e-load">点击加载更多</div>'
          + '<div class="load load-fail e-load">加载失败,点击重试</div>'
          + '<div class="load load-end">没有更多了</div>'
          + '</div>';
      }

      $(root).html(strHTML).addClass('comment-box');
    },
    _queryEl: function (root) {
      this._els = {
        box: root,
        list: root.getElementsByClassName('comment-list')[0],
        footer: root.getElementsByClassName('comment-footer')[0]
      };
    },
    _bindEvents: function () {
      var self = this;
      $(this._els.list).on('click', '.e-reply', function () {
        if (self.replyCallback) {
          var id = this.getAttribute('data-id');
          var userId = this.getAttribute('data-userid');
          var userNick = this.getAttribute('data-usernick');
          self.replyCallback(id, userId, userNick);
        }
        return false;
      });
      $(this._els.footer).on('click', '.e-load', function () {
        self._fetchCommentList();
        return false;
      });
    },
    _fetchCommentList: function () {
      var self = this;
      this._switchStatus('loading');
      $.get('//yun.app/api/comments', {
        article_id: self.articleId,
        page: self._page,
        pageSize: self._pageSize
      }).done(fetchSuccess).fail(fetchFail);

      // 请求成功
      function fetchSuccess(res) {
        if (res && res.data && res.data.length) {
          var strHTML = commentsTplFn(batchAdapter(res.data));
          self._render(strHTML);
          self._switchStatus('pending');
          self._updateCommentsCount(res.meta.pagination.total);
          self._page++;
        } else {
          self._switchStatus(self._page === 1 ? 'empty' : 'end');
        }
      }

      // 请求失败
      function fetchFail() {
        self._switchStatus('fail');
      }
    },
    _render: function (strHTML, insertBefore) {
      $(this._els.list)[insertBefore ? 'prepend' : 'append'](strHTML);
    },
    _switchStatus: function (status) {
      $(this._els.box).removeClass(this._curStatus).addClass(status);
      this._curStatus = status;
    },
    _updateCommentsCount: function (count) {
      this.commentCount = count;
      this.commentsCountUpdateCallback && this.commentsCountUpdateCallback(count);
    },
    add: function (data) {
      data.created_at = formatTime();
      var strHTML = commentTplFn(adapter(data));
      this._render(strHTML, true);
      this._updateCommentsCount(this.commentCount + 1);
    }
  };

  // 数据适配器
  function batchAdapter(data) {
    return data.map(adapter);
  }

  function adapter(comment) {
    var replyComment = comment.parent;
    return {
      face: comment.user_avatar || DEFAULT_FACE,
      userId: comment.user_id,
      userNick: comment.user_nick,
      id: comment.id,
      replyUserId: replyComment && replyComment.user_id,
      replyUserNick: replyComment && replyComment.user_nick,
      content: comment.content,
      time: comment.created_at,
      isSelf: comment.user_id === userId
    };
  }

  function formatTime() {
    var dateObj = new Date();
    return dateObj.getFullYear() + '-' + completeZero(dateObj.getMonth() + 1) + '-' + completeZero(dateObj.getDate()) + ' ' + completeZero(dateObj.getHours()) + ':' + completeZero(dateObj.getMinutes()) + ':' + completeZero(dateObj.getSeconds());
  }

  function completeZero(number, count) {
    count = count || 2;
    number = number + '';
    var len = number.length;

    if (len < count) {
      number = (Math.pow(10, count - len) + '').substring(1) + number;
    }
    return number;
  }
});