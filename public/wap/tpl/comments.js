var TPL_COMMENT = '\
<div class="comment">\
  <div class="comment-inner">\
    <div class="user">\
      <img class="user-face" src="{{=it.face}}">\
    </div>\
    <div class="comment-main">\
      <div class="nickname">\
        {{=it.userNick}}{{?it.replyId}}<i class="icon-reply"></i>{{=it.replyNick}}{{?}}\
      </div>\
      <p class="content">{{=it.content}}</p>\
      <div class="panel">\
        <span class="panel-item">{{=it.time}}</span>\
        <span class="panel-item e-reply" data-userid="{{=it.userId}}" data-usernick="{{=it.userNick}}">回复</span>\
      </div>\
    </div>\
  </div>\
</div>';

var TPL_COMMENTS = '{{~it:it}}'
  + TPL_COMMENT
  + '{{~}}';