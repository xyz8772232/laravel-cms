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
    var $switchIpt = $(this.nextElementSibling);
    var $subForm = $(this.nextElementSibling.nextElementSibling);
    $(this).bootstrapSwitch({
      size: 'small',
      onSwitchChange: function (event, state) {
        if (state) {
          $switchIpt.val('1');
          $subForm.show();
        } else {
          $switchIpt.val('0');
          $subForm.hide();
        }
      }
    });
  });

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
        });
      });
      // 排序
      this._dragulaInstance = dragula([this._$root.find('.imgup-list')[0]], {
        moves: function (el, container, handle) {
          return handle.classList.contains('e-sort');
        }
      });
    },
    _addFiles: function (files) {
      if (!files || !files.length) return false;
      var self = this;
      Array.prototype.forEach.call(files, function (file) {
        var data = {
          state: 'uploading',
          img: '',
          size: bytesToSize(file.size),
          title: ''
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
          self._uploadFile(file).done(function (res) {
            if (res && res.status.code === 0) {
              $item
              .removeClass('uploading')
              .find('.imgup-preview').attr('src', res.url)
              .siblings('.imgup-img-form').val(res.url);
            } else {
              noticeError($item, res.status.msg);
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
    _uploadFile: function (file) {
      var fd = new FormData();
      fd.append('photo', file);
      return $.ajax({
        url: '/admin/photos/upload',
        type: 'POST',
        cache: false,
        data: fd,
        processData: false,
        contentType: false
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
    $newsLinkSubForm.children().eq(-1).before(strHtml);
  })
  .on('click', '.e-delete', function () {
    $(this).parent('.sub-form-group').slideUp(250, function () {
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
      + '<select class="form-control news-link-select" name="newsLink[' + randomId + '][channel1]"></select>'
      + '<select class="form-control news-link-select" name="newsLink[' + randomId + '][channel2]"></select>'
      + '<select class="form-control news-link-select" name="newsLink[' + randomId + '][channel3]"></select>'
      + '<select class="form-control news-link-select" name="newsLink[' + randomId + '][channel4]"></select>'
      + '</div>'
      + '</div>'
      + '<div class="sub-form-group-r e-delete"><i class="fa fa-trash-o"></i></div>'
      + '</div>';
  }

  /**
   * 投票
   */
  var $voteSubForm = $('#voteSubForm');
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
    var randomId = Date.now();
    return '<div class="sub-form-group sub-form-group-deletable clearfix vote">'
      + '<div class="sub-form-group-l">'
      + '<label class="control-label">选项</label>'
      + '<div class="input-group">'
      + '<span class="input-group-addon"><i class="fa fa-pencil"></i></span>'
      + '<input class="form-control" type="text" name="vote[option][' + randomId + ']">'
      + '</div>'
      + '</div>'
      + '<div class="sub-form-group-r e-delete"><i class="fa fa-trash-o"></i></div>'
      + '</div>';
  }
});