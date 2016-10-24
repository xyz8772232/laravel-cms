$(function () {
  $('#created_at_start').datetimepicker({"format": "YYYY-MM-DD HH:mm:ss", "locale": "zh_CN"});
  $('#created_at_end').datetimepicker({"format": "YYYY-MM-DD HH:mm:ss", "locale": "zh_CN", "useCurrent": false});
  $("#created_at_start").on("dp.change", function (e) {
    $('#created_at_end').data("DateTimePicker").minDate(e.date);
  });
  $("#created_at_end").on("dp.change", function (e) {
    $('#created_at_start').data("DateTimePicker").maxDate(e.date);
  });
  $('._delete').click(function () {
    var id = $(this).data('id');
    if (confirm("确认删除?")) {
      $.post('/admin/articles/' + id, {
        _method: 'delete',
        '_token': 'Jr5SNyPLbN90mspGD0X042QqwVNVi787k08pqcS8'
      }, function (data) {
        $.pjax.reload('#pjax-container');
      });
    }
  });

  $('.grid-select-all').change(function () {
    if (this.checked) {
      $('.grid-item').prop("checked", true);
    } else {
      $('.grid-item').prop("checked", false);
    }
  });

  $('.batch-delete').on('click', function () {
    var selected = [];
    $('.grid-item:checked').each(function () {
      selected.push($(this).data('id'));
    });

    if (selected.length == 0) {
      return;
    }

    if (confirm("确认删除?")) {
      $.post('/admin/articles/' + selected.join(), {
        _method: 'delete',
        '_token': 'Jr5SNyPLbN90mspGD0X042QqwVNVi787k08pqcS8'
      }, function (data) {
        $.pjax.reload('#pjax-container');
      });
    }
  });

  $('.grid-refresh').on('click', function () {
    $.pjax.reload('#pjax-container');
  });
});