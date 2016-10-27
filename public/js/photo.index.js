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
      console.log('control');
      selectInterval[0] = +$this.attr('data-index');
    } else if (keyShiftIsActive) {
      console.log('shift');
      var selectIntervalBySort;
      $selectList.prop("checked", false);
      selectInterval[1] = +$this.attr('data-index');
      console.log(selectInterval);
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
  $('.box-body').on('click', '.img-copy-url', function (e) {
    var url = this.parentNode.previousElementSibling.src;
    console.log(e);
    if (e.clipboardData) {
      e.clipboardData.setData("text/plain", url);
    } else if (window.clipboardData) {
      window.clipboardData.setData("text", url);
    } else {
      alert('当前浏览器不支持直接复制,请手动复制:' + url);
      return false;
    }
    alert('复制成功');
  });

  /**
   * 上传新图片
   */
  $('.img-upload').on('click', function () {
    swal({
      title: '上传',
      text: '<input type="file" id="cover_pic" name="cover_pic"/><input type="hidden" id="cover_pic_action" name="cover_pic_action" value="0"/>',
      html: true,
      allowOutsideClick: true,
      confirmButtonText: "上传"
    }, function(isConfirm){
      console.log(isConfirm);
    });
    $('#cover_pic').fileinput({
      "overwriteInitial": true,
      "showUpload": false,
      "language": "zh_CN",
      "allowedFileTypes": ["image"],
      "initialCaption": "",
      minFileCount: 0
    }).on('filecleared', function (event) {
      $("#cover_pic_action").val(1);
    });
  });

  function upload() {
    
  }

  function enableImgUpload() {
    
  }

  function disableImgUpload() {
    
  }
});