var TPL_COMMENT = '\
<div class="comment">\
  <div class="comment-inner">\
    <div class="user">\
      <img class="user-face" src="{{=it.face}}">\
    </div>\
    <div class="comment-main">\
      <div class="nickname">\
        {{=it.userNick}}{{?it.replyUserId}}<i class="icon-reply"></i>{{=it.replyUserNick}}{{?}}\
      </div>\
      <p class="content">{{=it.content}}</p>\
      <div class="panel">\
        <span class="panel-item">{{=it.time}}</span>\
      </div>\
    </div>\
  </div>\
</div>';

// {{?it.id && !it.isSelf}}<span class="panel-item e-reply" data-id="{{=it.id}}" data-userid="{{=it.userId}}" data-usernick="{{=it.userNick}}">回复</span>{{?}}\

var TPL_COMMENTS = '{{~it:it}}'
  + TPL_COMMENT
  + '{{~}}';