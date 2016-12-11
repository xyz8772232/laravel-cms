/**
 * @author kael
 * @create 2016-12-10 22:22
 *
 * 撰写评论
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
})('Widgets.Comment.Write', window, function (exports, require) {
  var $ = require('jQuery');

  exports.new = function (opt) {
    return new Write(opt);
  };

  function Write(opt) {
    this.articleId = opt.articleId;
    this.userId = opt.userId;
    this.userNick = opt.userNick;
    this.submitCallback = opt.submitCallback;
    this._buildDom(opt.root);
    this._queryEl(opt.root);
    this._bindEvents(opt.root);
  }

  Write.prototype = {
    constructor: Write,
    _buildDom: function (root) {
      var strHTML = '<div class="write-area">'
        + '<div class="write-to">回复 <span class="reply-nick"></span> : </div>'
        + '<textarea class="write-ipt"></textarea>'
        + '</div>'
        + '<div class="write-panel">'
        + '<span class="write-submit">发表评论</span>'
        + '<div class="write-notice"></div>'
        + '</div>';

      $(root).html(strHTML).addClass('write-box');
    },
    _queryEl: function (root) {
      this._els = {
        box: root,
        area: root.getElementsByClassName('write-area')[0],
        ipt: root.getElementsByClassName('write-ipt')[0],
        writeTo: root.getElementsByClassName('write-to')[0],
        replyNick: root.getElementsByClassName('reply-nick')[0],
        submit: root.getElementsByClassName('write-submit')[0],
        notice: root.getElementsByClassName('write-notice')[0]
      };
    },
    _bindEvents: function () {
      var self = this;
      $(this._els.submit).on('click', function () {
        self._submit();
        return false;
      });
    },
    _submit: function () {
      if (this._submitting) return false;
      this._submitting = true;

      var self = this;
      var val = $.trim(this._els.ipt.value);
      if (!val) {
        this._notice('内容不能为空');
        this._submitting = false;
        return false;
      }
      this._resetNotice();
      this._els.submit.innerHTML = '<i class="icon-loading"></i>';
      this._els.ipt.readOnly = true;
      // 延迟提交,使提交状态可被用户感知
      setTimeout(function () {
        $.post('//yun.app/api/comments', {
          article_id: self.articleId,
          content: val,
          user_id: self.userId,
          user_nick: self.userNick,
          reply_to_id: self.replyId
        }).done(function () {
          self.submitCallback({
            userId: self.userId,
            userNick: self.userNick,
            replyId: self.replyId,
            replyNick: self.replyNick,
            content: val
          });
        }).fail(function () {
          self.submitCallback(false);
          self._notice('发表失败,请重试');
        }).always(function () {
          self._els.submit.innerHTML = '发表评论';
          self._els.ipt.readOnly = false;
          self._submitting = false;
        });
      }, 500);
    },
    _notice: function (msg) {
      this._els.notice.innerText = msg;
    },
    _resetNotice: function () {
      this._els.notice.innerText = '';
    },
    write: function (replyId, replyNick) {
      this.replyId = replyId;
      this.replyNick = replyNick;
      if (replyId) {
        this._els.replyNick.innerText = replyNick;
        this._els.area.classList.add('reply');
        this._els.ipt.style.textIndent = this._els.writeTo.offsetWidth + 'px';
      }
      this._els.ipt.focus();
    },
    resetWrite: function () {
      if (this.replyId) {
        this._els.replyNick.innerText = '';
        this._els.area.classList.remove('reply');
        this._els.ipt.style.textIndent = 0;
        this.replyId = undefined;
        this.replyNick = undefined;
      }
      this._els.ipt.blur();
      this._els.ipt.value = '';
      this._resetNotice();
    }
  }
});