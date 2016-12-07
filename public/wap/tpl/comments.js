var TPL_COMMENTS = '{{~it:data}}\
<div class="comment">\
  <div class="comment-inner">\
    <div class="user">\
      <img src="{{=data.face}}">\
    </div>\
    <div class="comment-main">\
      <p class="nickname">{{=data.nickname}}</p>\
      <p class="content">{{=data.content}}</p>\
      <div class="panel">\
        <span class="panel-item">{{=data.time}}</span>\
      </div>\
    </div>\
  </div>\
</div>\
{{~}}';