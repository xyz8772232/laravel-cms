$(function () {
  var $sortBox = $('#sortBox');

  /**
   * 颜色 & 粗体
   */
  $sortBox.on('focus', '.e-color', function() {
    this.orgVal = this.chgVal = this.value;
  }).on('input', '.e-color', function() {
    var val = this.value;
    if (/[^0-9a-fA-F]/.test(val)) {
      this.value = this.chgVal;
    } else {
      this.chgVal = val;
    }
  }).on('blur', '.e-color', function() {
    var self = this;
    var val = this.value;
    if (this.orgVal !== val) {
      commonAlert('确认修改', commonPost('修改', '/admin/articles/change/', {
        article_id: this.parentNode.parentNode.getAttribute('data-article_id'),
        title_color: '#' + val.toLowerCase()
      }), function (isSuccess) {
        if (isSuccess) {
          location.reload();
        } else {
          self.value = self.orgVal;
        }
      });
    }
  }).on('change', '.e-bold', function(){
    var self = this;
    commonAlert('确认修改', commonPost('修改', '/admin/articles/change/', {
      article_id: this.parentNode.parentNode.getAttribute('data-article_id'),
      title_bold: this.checked ? '1' : '0'
    }), function (isSuccess) {
      if (isSuccess) {
        location.reload();
      } else {
        self.checked = !self.checked;
      }
    });
  });

  function commonAlert(noticeText, confirmCallback, callback) {
    swal({
      title: noticeText,
      type: 'warning',
      confirmButtonText: '确认',
      cancelButtonText: '取消',
      showCancelButton: true,
      showLoaderOnConfirm: true,
      closeOnConfirm: false
    }, function (isConfirm) {
      if (isConfirm) {
        confirmCallback(callback);
      } else {
        callback(false);
      }
    });
  }

  function commonPost(actionName, postUrl, postData) {
    return function (callback) {
      $.post(postUrl, postData)
      .done(function (res) {
        if (res && res.result.status.code === 0) {
          swal({
            title: actionName + '成功',
            type: 'success'
          }, function () {
            callback(true);
          });
        } else {
          failHandler(res && res.result.status.msg, callback);
        }
      })
      .fail(function () {
        failHandler(null, callback);
      });
    };

    function failHandler(failMsg, callback) {
      swal({
        title: actionName + '失败',
        text: failMsg || '',
        type: 'error'
      }, function () {
        callback(false);
      });
    }
  }

  /**
   * 绑定拖拽事件
   */
  //new Sortable($sortBox[0], {
  //  handle: '.e-drag',
  //  forceFallback: false
  //});
  $sortBox.sortable({
    handle: '.e-drag'
  });

  /**
   * 绑定排序事件
   */
  var orgElList = Array.prototype.slice.call($sortBox.children(), 0);
  $('.box-footer').on('click', '.e-sort', function (e) {
    $sortBox.addClass('active');
    e.delegateTarget.classList.add('action-sort');
  }).on('click', '.e-submit', function (e) {
    swal({
      title: '',
      text: '<i class="icon-submit-loading">',
      customClass: 'submit-loading',
      showConfirmButton: false,
      html: true
    });
    setTimeout(function () {
      submitSort();
    }, 250);
  }).on('click', '.e-cancel', function (e) {
    $sortBox.removeClass('active');
    e.delegateTarget.classList.remove('action-sort');
    $sortBox.append(orgElList);
  });

  function submitSort(){
    var tree = [];

    $sortBox.children().each(function () {
      tree.push(this.getAttribute('data-id'));
    });
    $.post('/admin/sort_links/save', {
      _tree: JSON.stringify(tree)
    })
    .done(function (res) {
      if (res && res.result.status.code === 0) {
        swal({
          title: '修改成功',
          type: 'success'
        }, function () {
          location.reload();
        });
      } else {
        submitFail(res && res.result.status.msg);
      }
    })
    .fail(function (){
      submitFail();
    });
  }

  function submitFail(failMsg) {
    swal({
      title: '修改失败',
      type: 'error',
      text: failMsg || ''
    });
  }
});