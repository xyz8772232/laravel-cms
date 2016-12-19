$(function () {
  $('#keywordBox')
  .on('click', '.e-edit', editKeyword)
  .on('click', '.e-delete', deleteKeyword);

  function editKeyword() {
    var $ipt = $(this).siblings('.ipt');
    var orgVal = $ipt.val();

    $ipt.focus()
    .on('blur', function () {
      commonAlert('确认修改', commonPost('修改', '/admin/photos/', {
       val: this.value
      }, function (isSuccess) {
        if (isSuccess) {
          location.reload();
        } else {
          $ipt.off('blur').val(orgVal);
        }
      }));
    });
  }

  function deleteKeyword(e) {
    commonAlert('确认删除', commonPost('删除', '/admin/photos/', {
    }, function (isSuccess) {
      if (isSuccess) {
        location.reload();
      }
    }));
  }

  function commonAlert(noticeText, confirmCallback) {
    swal({
      title: noticeText,
      type: 'warning',
      confirmButtonText: '确认',
      cancelButtonText: '取消',
      showCancelButton: true,
      showLoaderOnConfirm: true,
      closeOnConfirm: false
    }, function (isConfirm) {
      isConfirm && confirmCallback && confirmCallback();
    });
  }

  function commonPost(actionName, postUrl, postData, callback) {
    return function () {
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
          failHandler(res && res.result.status.msg);
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
      }, function () {
        callback(false);
      });
    }
  }
});