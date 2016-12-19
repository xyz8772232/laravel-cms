$(function () {
  /**
   * 添加
   */
  $('.box-header').on('click', '.e-add', addKeyWord);

  function addKeyWord() {
    swal({
      title: '添加关键词',
      type: 'input',
      confirmButtonText: '确认',
      cancelButtonText: '取消',
      showCancelButton: true,
      closeOnConfirm: false,
      animation: 'slide-from-top'
    }, function (inputValue) {
      if (inputValue === false) return false;

      if (inputValue === '') {
        swal.showInputError('不能为空');
        return false
      }

      commonPost('添加', '/admin/keywords/', {
        name: inputValue
      })(function (isSuccess) {
        if (isSuccess) {
          location.reload();
        }
      });
    });
  }

  /**
   * 修改 & 删除
   */
  $('#keywordBox')
  .on('click', '.e-edit', editKeyword)
  .on('click', '.e-delete', deleteKeyword);

  function editKeyword() {
    var id = this.parentNode.parentNode.getAttribute('data-id');
    var $ipt = $(this.parentNode).siblings('.ipt');
    var orgVal = $ipt.val();

    $ipt.removeAttr('readonly').focus()
    .on('blur', function () {
      commonAlert('确认修改', commonPost('修改', '/admin/keywords/' + id, {
        name: this.value,
        _method: 'PUT'
      }), function (isSuccess) {
        if (isSuccess) {
          location.reload();
        } else {
          $ipt.off('blur').val(orgVal).attr('readonly', '');
        }
      });
    });
  }

  function deleteKeyword() {
    var id = this.parentNode.parentNode.getAttribute('data-id');
    commonAlert('确认删除', commonPost('删除', '/admin/keywords/' + id, {
      _method: 'DELETE'
    }), function (isSuccess) {
      if (isSuccess) {
        location.reload();
      }
    });
  }

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
});