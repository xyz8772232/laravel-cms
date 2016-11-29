$(function () {
  /**
   * 选择
   */
  var $selectAll = $('.img-select-all');
  var $selectList = $('.e-select');
  var keyControlIsActive = false;
  var keyShiftIsActive = false;
  var selectInterval = [0, 0];

  // 全选
  $selectAll.change(function () {
    if (this.checked) {
      $selectList.addClass('selected');
    } else {
      $selectList.removeClass('selected');
    }
    selectInterval[0] = 0;
  });

  // 选中(支持ctrl + shift)
  $('#photoBox').on('click', '.e-select', function () {
    var $this = $(this);
    if (keyControlIsActive) {
      $this.toggleClass('selected');
      selectInterval[0] = +$this.attr('data-index');
    } else if (keyShiftIsActive) {
      var selectIntervalBySort;
      $selectList.removeClass('selected');
      selectInterval[1] = +$this.attr('data-index');
      selectIntervalBySort = selectInterval.slice().sort(function (a, b) {
        return a - b;
      });
      $(Array.prototype.slice.call($selectList, selectIntervalBySort[0], selectIntervalBySort[1] + 1)).addClass('selected');
    } else {
      $selectList.removeClass('selected');
      $this.toggleClass('selected');
      selectInterval[0] = +$this.attr('data-index');
    }
    $selectAll.prop('checked', false);
  });

  $(document).on('keydown', function (e) {
    var keyCode = e.keyCode;
    if (keyCode === 17 || (91 <= keyCode && keyCode <= 93)) {
      keyControlIsActive = true;
    } else if (keyCode === 16) {
      keyShiftIsActive = true;
    }
  }).on('keyup', function (e) {
    var keyCode = e.keyCode;
    if (keyCode === 17 || (91 <= keyCode && keyCode <= 93)) {
      keyControlIsActive = false;
    } else if (keyCode === 16) {
      keyShiftIsActive = false;
    }
  });

  /**
   * 复制地址
   */
  var clipboard = new Clipboard('.e-copy');

  clipboard.on('success', function (e) {
    swal({
      title: '复制成功',
      type: 'success'
    });
  }).on('error', function (e) {
    swal({
      title: '复制失败,请手动复制下面链接',
      type: 'error',
      text: '<p style="word-wrap:break-word;word-break:break-all;">' + (e.trigger.getAttribute('data-clipboard-text') || '') + '</p>',
      html: true
    });
  });

  /**
   * 上传新图片
   */
  $('.e-upload').on('click', function () {
    buildUploadBox();
  });

  function buildUploadBox() {
    var $upload;
    var $swal;

    swal({
      title: '',
      text: '<input type="file" id="imgUpload" multiple><div class="watermark"><input type="checkbox" id="watermark"> 添加水印</div>',
      html: true,
      confirmButtonText: '上传图片',
      cancelButtonText: '取消',
      showCancelButton: true,
      showLoaderOnConfirm: true,
      closeOnConfirm: false
    }, function (isConfirm) {
      if (isConfirm) {
        var files = $upload.fileinput('getFileStack');
        var watermark = $('#watermark').attr('disable', 'disable').prop('checked');
        if (files && files.length) {
          uploadImg(files, watermark);
        } else {
          disableImgUpload($upload);
          setTimeout(function () {
            swal({
              title: '请先添加图片',
              type: 'error',
              confirmButtonText: '确定',
              cancelButtonText: '取消',
              showCancelButton: true,
              closeOnConfirm: false
            }, function (isConfirm) {
              isConfirm && buildUploadBox();
            });
          }, 25);
        }
      } else {
        disableImgUpload($upload);
      }
    });
    $swal = $('.sweet-alert');
    $upload = $('#imgUpload');
    enableImgUpload($upload, $swal);
  }

  function uploadImg(files, watermark) {
    var fd = new FormData();

    fd.append('watermark', watermark ? 1 : 0);
    files.forEach(function (file) {
      fd.append('photos[]', file);
    });
    $.ajax({
      url: '/admin/photos/batch_upload',
      type: 'POST',
      cache: false,
      data: fd,
      processData: false,
      contentType: false
    })
    .done(function (res) {
      if (res && res.result.status.code === 0) {
        swal({
          title: '上传成功',
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

    function failHandler(failMsg) {
      swal({
        title: '上传失败',
        text: failMsg || '',
        type: 'error'
      });
    }
  }

  function enableImgUpload($el, $swal) {
    $el.fileinput({
      overwriteInitial: true,
      showUpload: false,
      language: 'zh_CN',
      allowedFileTypes: ['image'],
      initialCaption: '',
      minFileCount: 0
    }).on('filecleared', function (event) {
      fixSwalStyle($swal);
    }).on('change', function () {
      fixSwalStyle($swal);
    });
  }

  function disableImgUpload($el) {
    $el.fileinput('destroy');
  }

  function fixSwalStyle($swal) {
    $swal.css('marginTop', -$swal.height() / 2 + 'px');
  }

  /**
   * 删除图片
   */
  $('.e-delete').on('click', function () {
    commonAlert('是否确认删除图片', commonPost('删除', '/admin/photos/', {
      _method: 'DELETE'
    }));
  });

  function getSelectedIds() {
    var ids = [];
    $selectList.filter('.selected').each(function () {
      ids.push(this.getAttribute('data-id'));
    });
    return ids.join(',');
  }

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
});