$(function () {
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
    reset: function () {
      while (1 < this._channelLevelStack.length) {
        var channelLevelItem = this._channelLevelStack.shift();
        this._addOptions(channelLevelItem.el);
      }
      this._channelLevelStack[0].el.selectedIndex = 0;
    },
    destroy: function () {
      this.$root.off('change');
    }
  };


  /**
   * 页数选择
   */
  $("#perPage").select2({
    minimumResultsForSearch: -1,
    allowClear: false
  }).on("select2:select", function() {
    location.href = this.value;
  });


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

  $createdAtStart.datetimepicker({"format": "YYYY-MM-DD HH:mm:ss", "locale": "zh_CN"});
  $createdAtEnd.datetimepicker({"format": "YYYY-MM-DD HH:mm:ss", "locale": "zh_CN", "useCurrent": false});
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
  var $transferBox;

  $('#batchActions').on('click', '.e-delete', function () {
    commonAlert('是否确认删除内容', commonPost('删除', '/admin/articles/', {
      _method: 'DELETE'
    }));
  }).on('click', '.e-publish', function () {
    commonAlert('是否确认上线', commonPost('上线', '/admin/articles/online/'));
  }).on('click', '.e-top', function () {
    commonAlert('是否确认设为头条', commonPost('头条设置', '/admin/articles/headline/'));
  }).on('click', '.e-audit', function () {
    commonAlert('是否通过', commonPost('通过', '/admin/articles/audit/'));
  }).on('click', '.e-transfer', function () {
    if (!$transferBox) {
      $transferBox = createTransferBox();
    }
    transferAlert(commonPost('转移', '/admin/articles/transfer/'));
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
        closeOnConfirm: false
      }, function (isConfirm) {
        isConfirm && confirmCallback && confirmCallback(ids);
      });
    } else {
      swal({
        title: '请先选择新闻',
        type: 'warning',
        confirmButtonText: '确认'
      });
    }
  }

  function commonPost(actionName, postUrl, postData1) {
    return function (ids, postData2) {
      var postData = postData2 || postData1;
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
      .fail(function () {
        failHandler();
      });
    };

    function failHandler(failMsg) {
      swal({
        title: actionName + '失败',
        text: failMsg || '',
        type: 'error'
      })
    }
  }

  function transferAlert(confirmCallback) {
    var ids = getSelectedIds();

    if (ids) {
      swal({
        title: '内容转移',
        text: '<div id="transferBox"></div>',
        html: true,
        confirmButtonText: '确认',
        cancelButtonText: '取消',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        closeOnConfirm: false,
        customClass: 'transfer-swal'
      }, function (isConfirm) {
        if (isConfirm) {
          var channels = [];
          $transferBox.find('.e-channel').each(function () {
            channels.push(this.value);
          });
          confirmCallback(ids, {channels: JSON.stringify(channels)});
        }
        resetTransferBox();
      });
      // 插入弹框
      $('#transferBox').append($transferBox);
    } else {
      swal({
        title: '请先选择新闻',
        type: 'warning',
        confirmButtonText: '确认'
      });
    }
  }

  function createTransferBox() {
    var strHtml = '<div class="transfer-box channel-box clearfix">'
      + '<label class="control-label">频道</label>'
      + '<div class="select-group">'
      + '<select class="form-control channel-select e-channel"></select>'
      + '<select class="form-control channel-select e-channel"></select>'
      + '<select class="form-control channel-select e-channel"></select>'
      + '<select class="form-control channel-select e-channel"></select>'
      + '</div>'
      + '</div>';
    var $box = $(strHtml);

    $box[0].channelInstance = new Channel($box.find('.select-group')[0]);
    return $box;
  }

  function resetTransferBox() {
    $transferBox && $transferBox[0].channelInstance.reset();
  }

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
      closeOnConfirm: false,
      customClass: 'news-link-swal'
    }, function (isConfirm) {
      isConfirm && postNewsLink(articleId);
      resetNewsLinkBox();
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
    var strHtml = '<div class="news-link channel-box clearfix">'
      + '<div class="news-link-l">'
      + '<label class="control-label">标题</label>'
      + '<div class="input-group">'
      + '<span class="input-group-addon"><i class="fa fa-pencil"></i></span>'
      + '<input class="form-control news-link-text" type="text">'
      + '</div>'
      + '<label class="control-label">频道</label>'
      + '<div class="select-group">'
      + '<select class="form-control channel-select e-channel"></select>'
      + '<select class="form-control channel-select e-channel"></select>'
      + '<select class="form-control channel-select e-channel"></select>'
      + '<select class="form-control channel-select e-channel"></select>'
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
      $this.find('.e-channel').each(function () {
        channels.push(this.value);
      });
      postData.push({
        title: $this.find('.news-link-text').val(),
        channels: channels
      });
    });
    $.post('/admin/articles/link/' + articleId, {_tree: JSON.stringify(postData)})
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

  /**
   * 预览
   */
  $('.news-list').on('click', '.e-preview', function () {
    var url = this.getAttribute('data-href');
    preview(url);
  });

  function preview(url) {
    swal({
      title: '内容转移',
      text: '<div class="preview-box"><iframe class="preview-iframe" src="' + url + '"></iframe></div>',
      html: true,
      showCancelButton: false,
      showLoaderOnConfirm: false,
      allowOutsideClick: true,
      customClass: 'preview-swal'
    });
  }
});