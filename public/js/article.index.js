$(function () {
  /**
   * 频道选择
   */
  $("#channelId").select2({
    allowClear: false
  });

  /**
   * 日期选择
   */
  var $createdAtStart = $('#createdAtStart');
  var $createdAtEnd = $('#createdAtEnd');

  $createdAtStart.datetimepicker({"format":"YYYY-MM-DD HH:mm:ss","locale":"zh_CN"});
  $createdAtEnd.datetimepicker({"format":"YYYY-MM-DD HH:mm:ss","locale":"zh_CN","useCurrent":false});
  $createdAtStart.on("dp.change", function (e) {
    $createdAtEnd.data("DateTimePicker").minDate(e.date);
  });
  $createdAtEnd.on("dp.change", function (e) {
    $createdAtStart.data("DateTimePicker").maxDate(e.date);
  });

  /**
   * 选择
   */
  var $selectList = $('.news-list .e-select');

  $('#selectAll').on('change', function () {
    $selectList.prop('checked', this.checked);
  });
  function getSelectedIds() {
    var ids = [];
    $selectList.filter(':checked').each(function () {
      ids.push(this.getAttribute('data-id'));
    });
    return ids.join(',');
  }

  /**
   * 批量操作
   */
  $('#batchActions').on('click', '.e-delete', function () {
    commonAlert('是否确认删除内容', commonPost('删除', '/admin/articles/', {
      _method: 'DELETE'
    }));
  }).on('click', '.e-publish', function () {
    commonAlert('是否确认上线', commonPost('上线', '/admin/articles/online/'));
  }).on('click', '.e-top', function () {
    commonAlert('是否确认设为头条', commonPost('头条设置', '/admin/articles/headline/'));
  });

  function commonAlert(noticeText, confirmCallback) {
    var ids = getSelectedIds();

    if (ids) {
      swal({
        title: noticeText,
        type: 'warning',
        confirmButtonText: '确认',
        cancelButtonText: '取消',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        allowOutsideClick: true,
        closeOnConfirm: false
      }, function (isConfirm) {
        isConfirm && confirmCallback && confirmCallback(ids);
      });
    } else {
      swal({
        title: '请先选择新闻',
        type: 'warning',
        confirmButtonText: '确认',
        allowOutsideClick: true
      });
    }
  }

  function commonPost(actionName, postUrl, postData) {
    return function (ids) {
      $.post(postUrl + ids, postData)
      .done(function (res) {
        if (res && res.result.status.code === 0) {
          swal({
            title: actionName + '成功',
            type: 'success'
          }, function () {
            location.reload();
          });
        } else {
          failHandler(res && res.result.status.msg)
        }
      })
      .fail(failHandler);
    };

    function failHandler(failMsg) {
      swal({
        title: actionName + '失败',
        text: failMsg || '',
        type: 'error'
      })
    }
  }

  /**
   * channel
   */
  function Channel(root, channelIds) {
    this.$root = $(root);
    this._$channels = this.$root.children('.e-channel');
    this._channelLevelStack = [];
    this._init(channelIds);
  }

  Channel.prototype = {
    constructor: Channel,
    _init: function (channelIds) {
      var self = this;

      // 标识频道级别
      this._$channels.each(function (index) {
        this.channelLevel = index + 1;
        self._addOptions(this);
      });
      // 读取初始化频道
      if (channelIds) {
        var parPath;
        var parIndex;
        var el;
        channelIds.forEach(function (id, index) {
          el = self._$channels[index];
          self._readChannel(el, parPath);
          parPath = self._readPathById(id);
          parIndex = index;
          el.selectedIndex = parPath + 1;
        });
        // 读出下一级channel
        el = this._$channels[parIndex + 1];
        el && this._readChannel(el, parPath);
      } else {
        this._readChannel(this._$channels[0]);
      }
      // 绑定事件
      this._bindEvent();
    },
    _readPathById: function (id) {
      var channelLevelStack = this._channelLevelStack;
      var path;

      (channelLevelStack.length ? channelLevelStack[0].channels : CHANNEL).some(function (channel, index) {
        if (id === channel.id) {
          path = index;
          return true;
        } else {
          return false;
        }
      });
      return path;
    },
    _readChannel: function (el, parPath) {
      var channelLevelStack = this._channelLevelStack;
      var channels;

      channels = parPath !== undefined ? (parPath < 0 ? null : channelLevelStack[0].channels[parPath].children) : CHANNEL;
      this._addOptions(el, channels);
      channels && channelLevelStack.unshift({
        el: el,
        channels: channels
      });
    },
    _addOptions: function (elChannel, channels) {
      var channelLevel = elChannel.channelLevel;
      var chanelLevelNameTable = ['一', '二', '三', '四'];
      var strHtml = '<option value="0">' + chanelLevelNameTable[channelLevel - 1] + '级频道</option>';

      channels && channels.forEach(function (channel) {
        strHtml += '<option value="' + channel.id + '">' + channel.name + '</option>';
      });
      elChannel.innerHTML = strHtml;
    },
    _bindEvent: function () {
      var self = this;
      this.$root.on('change', '.e-channel', function () {
        var channelLevel = this.channelLevel;
        var channelLevelItem;

        while (channelLevel < self._channelLevelStack.length) {
          channelLevelItem = self._channelLevelStack.shift();
          self._addOptions(channelLevelItem.el);
        }

        // 读取下一级的频道
        this.nextElementSibling && self._readChannel(this.nextElementSibling, this.selectedIndex - 1);
      });
    },
    destroy: function () {
      this.$root.off('change');
    }
  };

  /**
   * 文字连接
   */
  var $newsLinkBox = createNewsLinkBox();

  $('.news-list').on('click', '.e-link', function () {
    var articleId = this.getAttribute('data-id');
    swal({
      title: '创建文字链接',
      text: '<div id="newsLinkBox"></div>',
      html: true,
      confirmButtonText: '确认',
      cancelButtonText: '取消',
      showCancelButton: true,
      showLoaderOnConfirm: true,
      allowOutsideClick: true,
      customClass: 'news-link-swal'
    }, function (isConfirm) {
      if (isConfirm) {
        postNewsLink(articleId);
      } else {
        resetNewsLinkBox();
      }
    });
    // 默认添加一个
    createNewsLink();
    // 插入弹框
    $('#newsLinkBox').append($newsLinkBox);
  });

  function createNewsLinkBox() {
    var strHtml = '<div class="news-link-box">'
      + '<span class="news-link-add e-add">+新增文字连接</span>'
      + '</div>';
    var $box = $(strHtml);
    $box
    .on('click', '.e-add', function () {
      createNewsLink();
      $box.scrollTop(9999);
    })
    .on('click', '.e-delete', function () {
      $(this).parent('.news-link').slideUp(250, function () {
        this.channelInstance && this.channelInstance.destroy();
        $(this).remove();
      })
    });
    return $box;
  }

  function resetNewsLinkBox() {
    $newsLinkBox
    .children('.news-link').each(function () {
      this.channelInstance && this.channelInstance.destroy();
    });
    $newsLinkBox.html('<span class="news-link-add e-add">+新增文字连接</span>');
  }

  function createNewsLink() {
    var strHtml = '<div class="news-link clearfix">'
      + '<div class="news-link-l">'
      + '<label class="control-label">标题</label>'
      + '<div class="input-group">'
      + '<span class="input-group-addon"><i class="fa fa-pencil"></i></span>'
      + '<input class="form-control news-link-text" type="text">'
      + '</div>'
      + '<label class="control-label">频道</label>'
      + '<div class="select-group">'
      + '<select class="form-control news-link-select e-channel"></select>'
      + '<select class="form-control news-link-select e-channel"></select>'
      + '<select class="form-control news-link-select e-channel"></select>'
      + '<select class="form-control news-link-select e-channel"></select>'
      + '</div>'
      + '</div>'
      + '<div class="news-link-r e-delete"><i class="fa fa-trash-o"></i></div>'
      + '</div>';
    var $el = $(strHtml);

    $el[0].channelInstance = new Channel($el.find('.select-group')[0]);
    $newsLinkBox.children().eq(-1).before($el);
  }

  function postNewsLink(articleId) {
    var postData = [];

    $newsLinkBox.children('.news-link').each(function () {
      var $this = $(this);
      var channels = [];
      $this.find('.news-link-select').each(function () {
        channels.push(this.value);
      });
      postData.push({
        title: $this.find('.news-link-text').val(),
        channels: channels
      });
    });
    $.post('/admin/articles/link/' + articleId, JSON.stringify(postData))
    .done(function (res) {
      if (res && res.result.status.code === 0) {
        swal({
          title: '创建成功',
          type: 'success'
        }, function () {
          location.reload();
        });
      } else {
        failHandler(res && res.result.status.msg)
      }
    })
    .fail(failHandler);

    function failHandler(failMsg) {
      swal({
        title: '创建失败',
        text: failMsg || '',
        type: 'error'
      })
    }
  }
});