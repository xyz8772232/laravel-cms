$(function () {
  var INIT_CONFIG = window.INIT_CONFIG;
  var IS_EDIT = INIT_CONFIG.status === 1;

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
   * 标题
   */
  $('#titleColor').colorpicker();

  /**
   * 封面图
   */
  var coverPic = INIT_CONFIG.coverPic;
  $('#coverPic').fileinput({
    overwriteInitial: true,
    showUpload: false,
    language: 'zh_CN',
    allowedFileTypes: ['image'],
    initialPreview: coverPic ? [
      '<img src="' + coverPic.img + '" class="file-preview-image">'
      + '<input type="hidden" name="cover_pic_old" value="' + coverPic.img + '">'
    ] : undefined,
    initialCaption: coverPic ? coverPic.title : ''
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
   * 切换普通新闻和图片新闻
   */
  var $normalArticle = $('#normalArticle');
  var $picArticle = $('#picArticle');
  $('#typeCheckbox').bootstrapSwitch({
    size: 'small',
    onInit: function (e, state) {
      state || UE.getEditor('content');
      switchArticleType(e, state);
    },
    onSwitchChange: switchArticleType
  });

  function switchArticleType(e, state) {
    if (state) {
      $normalArticle.hide();
      $picArticle.show();
    } else {
      $picArticle.hide();
      $normalArticle.show();
    }
  }

  /**
   * 图片新闻正文
   */
  function ImgUpload(root, initPics) {
    this._$root = $(root);
    this._count = 0;
    this._init(initPics);
  }

  ImgUpload.prototype = {
    constructor: ImgUpload,
    _init: function (initPics) {
      this._buildDom();
      this._bindEvents();
      this._initPics(initPics);
    },
    _initPics: function (initPics) {
      var self = this;

      if (initPics && initPics.length) {
        initPics.forEach(function (pic) {
          var data = {
            state: '',
            img: pic.img,
            title: pic.title,
            sort: self._count++
          };
          self._insertItem(data);
        });
      }
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
        + '<div class="imgup-l' + (data.size ? '' : ' no-size') + '">'
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
      //this._dragInstance = new Sortable(this._$root.find('.imgup-list')[0], {
      //  handle: '.e-sort',
      //  onUpdate: updateSort
      //});
      this._$root.find('.imgup-list').sortable({
        handle: '.e-sort',
        onUpdate: updateSort
      });

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
      //this._dragInstance.destroy();
      this._$root.find('.imgup-list').sortable("destroy");
    }
  };

  new ImgUpload('#contentPics', INIT_CONFIG.contentPics);

  /**
   * 文字连接
   */
  var $newsLinkSubForm = $('#newsLinkSubForm');

  // 初始化文字连接
  INIT_CONFIG.newsLinks && INIT_CONFIG.newsLinks.forEach(function (newsLink) {
    createSubForm_NewsLink(newsLink);
  });

  $newsLinkSubForm
  .on('click', '.e-add', function () {
    createSubForm_NewsLink();
  })
  .on('click', '.e-delete', function () {
    $(this).parent('.sub-form-group').slideUp(250, function () {
      this.channelInstance && this.channelInstance.destroy();
      $(this).remove();
    })
  });

  function createSubForm_NewsLink(newsLink) {
    var randomId = Date.now();
    var strDisabled = IS_EDIT ? 'disabled' : '';
    var strReadonly = IS_EDIT ? 'readonly' : '';
    var strDeletable = IS_EDIT ? '' : 'deletable';
    var strHtml = '<div class="' + strDeletable + ' sub-form-group clearfix news-link">'
      + '<div class="sub-form-group-l">'
      + '<label class="control-label">标题</label>'
      + '<div class="input-group">'
      + '<span class="input-group-addon"><i class="fa fa-pencil"></i></span>'
      + '<input ' + strReadonly + ' class="form-control" type="text" name="newsLink[' + randomId + '][title]" value="' + (newsLink ? newsLink.title : '') + '">'
      + '<input type="hidden" name="newsLink[' + randomId + '][id]" value="' + (newsLink ? newsLink.id : '') + '">'
      + '</div>'
      + '<label class="control-label">频道</label>'
      + '<div class="select-group">'
      + '<select ' + strDisabled + ' class="form-control news-link-select e-channel" name="newsLink[' + randomId + '][channels][]"></select>'
      + '<select ' + strDisabled + ' class="form-control news-link-select e-channel" name="newsLink[' + randomId + '][channels][]"></select>'
      + '<select ' + strDisabled + ' class="form-control news-link-select e-channel" name="newsLink[' + randomId + '][channels][]"></select>'
      + '<select ' + strDisabled + ' class="form-control news-link-select e-channel" name="newsLink[' + randomId + '][channels][]"></select>'
      + '</div>'
      + '</div>'
      + '<div class="sub-form-group-r e-delete"><i class="fa fa-trash-o"></i></div>'
      + '</div>';
    var $el = $(strHtml);

    $el[0].channelInstance = new Channel($el.find('.select-group')[0], newsLink && newsLink.channel);
    $newsLinkSubForm.children().eq(-1).before($el);
  }

  /**
   * pk,投票切换 & 增删投票选项
   */
  var $pkSFS = $('#pkSFS');
  var $voteSFS = $('#voteSFS');
  var $pkSubForm = $('#pkSubForm');
  var $voteSubForm = $('#voteSubForm');

  // 初始化投票
  INIT_CONFIG.voteOptions && INIT_CONFIG.voteOptions.forEach(function (option) {
    createSubForm_Vote(option);
  });

  $pkSFS.on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
      $voteSFS.bootstrapSwitch('state', false);
      $voteSubForm.hide();
    }
  });
  $voteSFS.on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
      $pkSFS.bootstrapSwitch('state', false);
      $pkSubForm.hide();
    }
  });

  $voteSubForm
  .on('click', '.e-add', function () {
    createSubForm_Vote();
  })
  .on('click', '.e-delete', function () {
    $(this).parent('.sub-form-group').slideUp(250, function () {
      $(this).remove();
    })
  });

  function createSubForm_Vote(option) {
    var randomId = Date.now();
    var strReadonly = IS_EDIT ? 'readonly' : '';
    var strDeletable = IS_EDIT ? '' : 'deletable';
    var strHtml = '<div class="' + strDeletable + ' sub-form-group clearfix vote">'
      + '<div class="sub-form-group-l">'
      + '<label class="control-label">选项</label>'
      + '<div class="input-group">'
      + '<span class="input-group-addon"><i class="fa fa-pencil"></i></span>'
      + '<input ' + strReadonly + ' class="form-control" type="text" name="vote[options][' + randomId + '][content]" value="' + (option ? option.option : '') + '">'
      + '<input type="hidden" name="vote[options][' + randomId + '][id]" value="' + (option ? option.id : '') + '">'
      + '</div>'
      + '</div>'
      + '<div class="sub-form-group-r e-delete"><i class="fa fa-trash-o"></i></div>'
      + '</div>';

    $voteSubForm.children().eq(-1).before(strHtml);
  }

  /**
   * 所属频道
   */
  new Channel($('#channel'), INIT_CONFIG.channel);

  /**
   * common fns
   */
  function bytesToSize(bytes) {
    var sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0 B';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    if (i == 0) return bytes + ' ' + sizes[i];
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
  }
});
