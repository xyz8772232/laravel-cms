$(function () {
  /**
   * 评论
   */
  (function () {
    var $ = window.jQuery;
    var doT = window.doT;
    var ARTICLE_ID = PAGE_CONFIG.articleId;
    var DEFAULT_FACE = '';
    var feedTplFn = doT.template(TPL_COMMENTS);
    var page = 1;
    var $commentList = $('.comment-list');

    init();

    // 初始化
    function init() {
      bindEvents();
      fetchCommentList();
    }

    // 事件绑定
    function bindEvents() {
      $commentList.on('click', '.e-load', function () {
        fetchCommentList();
        return false;
      });
    }

    // 发起请求
    function fetchCommentList() {
      switchStatus('loading');
      $.get('http://yun.app/api/comments', {
        article_id: ARTICLE_ID,
        page: page
      }).done(fetchSuccess).fail(fetchFail);
    }

    // 请求成功
    function fetchSuccess(res) {
      if (res && res.data && res.data.length) {
        var htmlStr = feedTplFn(adapter(res.data));
        render(htmlStr);
        switchStatus('pending');
        page++;
      } else {
        switchStatus(page === 1 ? 'empty' : 'no-more');
      }
    }

    // 请求失败
    function fetchFail() {
      switchStatus('fail');
    }

    // 渲染
    var $box = $commentList.children('.box');
    function render(htmlStr) {
      $box.append(htmlStr);
    }

    // 数据适配器
    function adapter(data) {
      return data.map(function (comment) {
        return {
          face: comment.face || DEFAULT_FACE,
          nickname: comment.user_nick,
          content: comment.content,
          time: comment.created_at
        };
      });
    }

    // 切换状态
    var curStatus;
    function switchStatus(status) {
      $commentList.removeClass(curStatus).addClass(status);
      curStatus = status;
    }
  })();
});