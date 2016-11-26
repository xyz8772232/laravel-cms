$(function () {
  /**
   * 选择
   */
  var $selectAll = $('.img-select-all');
  var $selectList = $('.img-select');
  var keyControlIsActive = false;
  var keyShiftIsActive = false;
  var selectInterval = [0, 0];

  // 全选
  $selectAll.change(function () {
    if (this.checked) {
      $selectList.prop("checked", true);
    } else {
      $selectList.prop("checked", false);
    }
    selectInterval[0] = 0;
  });

  // 选中(支持ctrl + shift)
  $selectList.on('change', function () {
    var $this = $(this);
    if (keyControlIsActive) {
      selectInterval[0] = +$this.attr('data-index');
    } else if (keyShiftIsActive) {
      var selectIntervalBySort;
      $selectList.prop("checked", false);
      selectInterval[1] = +$this.attr('data-index');
      selectIntervalBySort = selectInterval.slice().sort(function (a, b) {
        return a - b;
      });
      $(Array.prototype.slice.call($selectList, selectIntervalBySort[0], selectIntervalBySort[1] + 1)).prop("checked", true);
    } else {
      $selectList.prop("checked", false);
      $this.prop("checked", true);
      selectInterval[0] = +$this.attr('data-index');
    }
    $selectAll.prop("checked", false);
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
  var clipboard = new Clipboard('.img-copy-url');

  clipboard.on('success', function (e) {
    swal({
      title: '复制成功',
      type: 'success'
    });
  }).on('error', function (e) {
    swal({
      title: '复制失败,请手动复制下面链接',
      type: 'error',
      text: '<p style="word-wrap: break-word; word-break: break-all;">' + (e.trigger.getAttribute('data-clipboard-text') || '') + '</p>',
      html: true
    });
  });

  /**
   * 上传新图片
   */
  $('.img-upload').on('click', function () {
    var $upload;
    var $swal;

    swal({
      title: '',
      text: '<input type="file" id="imgUpload" multiple>',
      html: true,
      allowOutsideClick: true,
      closeOnConfirm: false,
      confirmButtonText: '上传图片'
    }, function (isConfirm) {
      if (isConfirm) {
        var files = $upload.fileinput('getFileStack');
        if (files && files.length) {
          $swal = null;
          uploadImg(files);
        }
      } else {
        $swal = null;
        disableImgUpload($upload);
      }
    });
    $swal = $('.sweet-alert');
    $upload = $('#imgUpload');
    enableImgUpload($upload, $swal);
  });

  function uploadImg(files) {
    console.log(files);
    swal({
      title: '上传中',
      text: ''
    });
    swal.disableButtons();
    // TODO 提交
    setTimeout(function () {
      swal('上传成功', '', 'success');
    }, 1000);
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
});