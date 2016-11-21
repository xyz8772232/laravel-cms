$(function () {
  $('.item_delete').click(function () {
    var id = $(this).data('id');
    if (confirm('确认删除?')) {
      $.post('/admin/articles/' + id, {
        _method: 'delete',
        '_token': 'cBhuCiUomMmrIvqNXzYrKnKQSUY6J06uYBAk0lkk'
      }, function (data) {
        $.pjax({
          timeout: 2000,
          url: '/admin/articles',
          container: '#pjax-container'
        });
        return false;
      });
    }
  });

  /**
   * sub form
   */
  $('.sub-form-switch').each(function () {
    var $subForm = $(this).siblings('.sub-form');
    $(this).bootstrapSwitch({
      size: 'small',
      onInit: switchSubForm,
      onSwitchChange: switchSubForm
    });
    function switchSubForm(e, state) {
      $subForm[state ? 'show' : 'hide']();
    }
  });

  /**
   * channel
   */
  function Channel(root, channelPath) {
    this.$root = $(root);
    this._$channels = this.$root.children('.e-channel');
    this._channelLevelStack = [];
    this._init(channelPath);
  }

  Channel.prototype = {
    constructor: Channel,
    _init: function (channelPath) {
      var self = this;

      // 标识频道级别
      this._$channels.each(function (index) {
        this.channelLevel = index + 1;
        self._addOptions(this);
      });
      // 读取初始化频道
      if (channelPath) {
        var parPath;
        channelPath.forEach(function (path, index) {
          var el = self._$channels[index];
          self._readChannel(el, parPath);
          parPath = path;
          el.selectedIndex = path + 1;
        });
      } else {
        this._readChannel(this._$channels[0]);
      }
      // 绑定事件
      this._bindEvent();
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
   * 切换普通新闻和图片新闻
   */
  var $typeForm = $('#typeForm');
  var $normalArticle = $('#normalArticle');
  var $picArticle = $('#picArticle');
  $('#typeCheckbox').bootstrapSwitch({
    size: 'small',
    onSwitchChange: function (event, state) {
      if (state) {
        $typeForm.val('1');
        $normalArticle.hide();
        $picArticle.show();
      } else {
        $typeForm.val('0');
        $picArticle.hide();
        $normalArticle.show();
      }
    }
  });

  /**
   * 标题
   */
  $('#titleColor').colorpicker();

  /**
   * 封面图
   */
  $('#coverPic').fileinput({
    overwriteInitial: true,
    showUpload: false,
    language: 'zh_CN',
    allowedFileTypes: ['image'],
    initialCaption: ''
  });

  /**
   * 关键词
   */
  $('#keywords').select2({allowClear: true});

  /**
   * 发布时间
   */
  $('#publishedAt').datetimepicker({
    format: 'YYYY-MM-DD HH:mm:ss',
    locale: 'zh_CN'
  });
  $('#restPublishedAt').on('click', function () {
    $('#publishedAt').data("DateTimePicker").date(new Date())
  });

  /**
   * 普通新闻正文
   */
  var ue = UE.getEditor('content');

  /**
   * 图片新闻正文
   */
  function ImgUpload(root) {
    this._$root = $(root);
    this._count = 0;
    this._init();
  }

  ImgUpload.prototype = {
    constructor: ImgUpload,
    _init: function () {
      this._buildDom();
      this._bindEvents();
    },
    _buildDom: function () {
      var strHtml = '<div class="imgup-header">'
        + '<div class="imgup-add">'
        + '<input class="imgup-add-ipt e-add" type="file" multiple accept="image/gif,image/png,image/jpeg">'
        + '<button type="button" class="btn btn-primary">添加新图</button>'
        + '</div>'
        + '</div>'
        + '<div class="imgup-list">';
      this._$root.append(strHtml);
      this._$list = this._$root.children('.imgup-list');
    },
    _insertItem: function (data) {
      var randomId = Date.now();
      var strHtml = '<div class="imgup-item ' + data.state + '">'
        + '<div class="imgup-l">'
        + '<img src="' + data.img + '" alt="" class="imgup-preview">'
        + '<span class="imgup-size">' + data.size + '</span>'
        + '<input class="imgup-img-form" type="hidden" name="contentPic[' + randomId + '][img]" value="' + data.img + '">'
        + '</div>'
        + '<div class="imgup-r">'
        + '<i class="e-delete fa fa fa-trash-o text-danger"></i>'
        + '<i class="e-sort fa fa-bars text-default"></i>'
        + '<input class="imgup-sort-form" type="hidden" name="contentPic[' + randomId + '][order]" value="' + data.sort + '">'
        + '</div>'
        + '<div class="imgup-c clearfix">'
        + '<div class="imgup-progress-box">'
        + '<div class="imgup-progress-bar"></div>'
        + '</div>'
        + '<div class="imgup-error text-danger"></div>'
        + '<textarea class="imgup-title" name="contentPic[' + randomId + '][title]" placeholder="请输入图片描述">' + data.title + '</textarea>'
        + '</div>'
        + '</div>';
      var $item = $(strHtml);
      this._$list.append($item);
      return $item;
    },
    _bindEvents: function () {
      var self = this;
      // 添加
      this._$root.find('.e-add').on('change', function () {
        self._addFiles(this.files);
      });
      // 删除
      this._$root.on('click', '.e-delete', function () {
        $(this).parents('.imgup-item').slideUp(250, function () {
          $(this).remove();
          self._count--;
          updateSort();
        });
      });
      // 排序
      this._dragulaInstance = dragula([this._$root.find('.imgup-list')[0]], {
        moves: function (el, container, handle) {
          return handle.classList.contains('e-sort');
        }
      }).on('drop', updateSort);

      // 更改排序表单
      function updateSort() {
        self._$list.children().each(function (index) {
          $(this).find('.imgup-sort-form').val(index);
        });
      }
    },
    _addFiles: function (files) {
      if (!files || !files.length) return false;
      var self = this;
      Array.prototype.forEach.call(files, function (file) {
        var data = {
          state: 'uploading',
          img: '',
          size: bytesToSize(file.size),
          title: '',
          sort: self._count++
        };
        var $item = self._insertItem(data);
        // 尝试读取文件,低版本不支持
        try {
          var reader = new FileReader();
          reader.onload = function (e) {
            $item
            .find('.imgup-preview')
            .attr('src', e.target.result);
          };
          reader.readAsDataURL(file);
        } catch (e) {
        }
        // 判断图片尺寸是否符合需求(小于10M)
        if (file.size > 10 * 1024 * 1024) {
          noticeError($item, '每张图片大小不超过10M');
        } else {
          // 提交图片文件,并在接受到结果后进行处理
          var $progressBar = $item.find('.imgup-progress-bar');
          self._uploadFile(file, function (percentComplete) {
            $progressBar.width(percentComplete * 100 + '%');
          }).done(function (res) {
            if (res && res.result.status.code === 0) {
              var path = res.result.data.path;
              $item
              .removeClass('uploading')
              .find('.imgup-img-form').val(path);
            } else {
              noticeError($item, res.result.status.msg);
            }
          }).fail(function () {
            noticeError($item, '上传失败');
          });
        }
      });

      function noticeError($item, msg) {
        $item
        .removeClass('uploading')
        .addClass('error')
        .find('.imgup-error').text(msg)
      }

      function bytesToSize(bytes) {
        var sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0 B';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        if (i == 0) return bytes + ' ' + sizes[i];
        return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
      }
    },
    _uploadFile: function (file, progress) {
      var fd = new FormData();
      fd.append('photo', file);
      return $.ajax({
        url: '/admin/photos/upload',
        type: 'POST',
        cache: false,
        data: fd,
        processData: false,
        contentType: false,
        xhr: progress && function () {
          var xhr = new window.XMLHttpRequest();
          xhr.upload.addEventListener("progress", function (evt) {
            if (evt.lengthComputable) {
              progress(evt.loaded / evt.total)
            }
          }, false);
          return xhr;
        }
      });
    },
    destroy: function () {
      this.$root.find('.e-add').off();
      this._$root.off();
      this._dragulaInstance.destroy();
    }
  };

  new ImgUpload('#contentPics');

  /**
   * 文字连接
   */
  var $newsLinkSubForm = $('#newsLinkSubForm');
  $newsLinkSubForm
  .on('click', '.e-add', function () {
    var strHtml = createSubForm_NewsLink();
    var $el = $(strHtml);
    $el[0].channelInstance = new Channel($el.find('.select-group')[0]);
    $newsLinkSubForm.children().eq(-1).before($el);
  })
  .on('click', '.e-delete', function () {
    $(this).parent('.sub-form-group').slideUp(250, function () {
      this.channelInstance && this.channelInstance.destroy();
      $(this).remove();
    })
  });

  function createSubForm_NewsLink() {
    var randomId = Date.now();
    return '<div class="sub-form-group sub-form-group-deletable clearfix news-link">'
      + '<div class="sub-form-group-l">'
      + '<label class="control-label">标题</label>'
      + '<div class="input-group">'
      + '<span class="input-group-addon"><i class="fa fa-pencil"></i></span>'
      + '<input class="form-control" type="text" name="newsLink[' + randomId + '][title]">'
      + '</div>'
      + '<label class="control-label">频道</label>'
      + '<div class="select-group">'
      + '<select class="form-control news-link-select e-channel" name="newsLink[' + randomId + '][channel][]"></select>'
      + '<select class="form-control news-link-select e-channel" name="newsLink[' + randomId + '][channel][]"></select>'
      + '<select class="form-control news-link-select e-channel" name="newsLink[' + randomId + '][channel][]"></select>'
      + '<select class="form-control news-link-select e-channel" name="newsLink[' + randomId + '][channel][]"></select>'
      + '</div>'
      + '</div>'
      + '<div class="sub-form-group-r e-delete"><i class="fa fa-trash-o"></i></div>'
      + '</div>';
  }

  /**
   * pk,投票切换 & 增删投票选项
   */
  var $pkSFS = $('#pkSFS');
  var $voteSFS = $('#voteSFS');
  var $pkSubForm = $('#pkSubForm');
  var $voteSubForm = $('#voteSubForm');

  $pkSFS.on('switchChange.bootstrapSwitch', function(event, state) {
    if (state) {
      $voteSFS.bootstrapSwitch('state', false);
      $voteSubForm.hide();
    }
  });
  $voteSFS.on('switchChange.bootstrapSwitch', function(event, state) {
    if (state) {
      $pkSFS.bootstrapSwitch('state', false);
      $pkSubForm.hide();
    }
  });

  $voteSubForm
  .on('click', '.e-add', function () {
    var strHtml = createSubForm_Vote();
    $voteSubForm.children().eq(-1).before(strHtml);
  })
  .on('click', '.e-delete', function () {
    $(this).parent('.sub-form-group').slideUp(250, function () {
      $(this).remove();
    })
  });

  function createSubForm_Vote() {
    return '<div class="sub-form-group sub-form-group-deletable clearfix vote">'
      + '<div class="sub-form-group-l">'
      + '<label class="control-label">选项</label>'
      + '<div class="input-group">'
      + '<span class="input-group-addon"><i class="fa fa-pencil"></i></span>'
      + '<input class="form-control" type="text" name="vote[options][]">'
      + '</div>'
      + '</div>'
      + '<div class="sub-form-group-r e-delete"><i class="fa fa-trash-o"></i></div>'
      + '</div>';
  }

  /**
   * 所属频道
   */
  new Channel($('#channel'), [0, 0, 1, 0]);
});