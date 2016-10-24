var CHANNEL = [
  {
    id: 'A',
    name: 'A',
    children: [
      {
        id: 'A1',
        name: 'A1',
        children: [
          {
            id: 'A11',
            name: 'A11',
            deletable: true
          },
          {
            id: 'A12',
            name: 'A12'
          }
        ]
      },
      {
        id: 'A2',
        name: 'A2',
        children: [
          {
            id: 'A21',
            name: 'A21'
          },
          {
            id: 'A22',
            name: 'A22',
            deletable: true
          },
          {
            id: 'A23',
            name: 'A23'
          }
        ]
      }
    ]
  },
  {
    id: 'B',
    name: 'B'
  }
];
$(function () {
  var channelEditInstance;
  var channelLevelStack = [];

  function ChannelGenerate(root, subChannels) {
    this.root = root;
    this.subChannels = subChannels;
  }

  ChannelGenerate.prototype = {
    _init: function () {
      this._pushToStack();
      this._render();
      this._bindEvents();
    },
    _pushToStack: function () {
      this.channelLevel = channelLevelStack.length;
      channelLevelStack.push(this);
    },
    _render: function () {
      var self = this;
      var levelMatchTable = ['-', '二', '三', '四', '五'];
      var strHtml = '<div class="channel-group">'
        + '<div class="channel-header">'
        + levelMatchTable[this.channelLevel] + '级频道'
        + '<div class="btn btn-sm btn-primary channel-edit">编辑</div>'
        + '</div>'
        + '<div class="channel-box clearfix"></div>'
        + '</div>';

      // 构建框架
      this.elGroup = $(strHtml)[0];
      this.elBox = this.elGroup.getElementsByClassName('channel-box')[0];
      // 插入频道
      this.subChannels.forEach(function (channel, index) {
        self.insertChannel(channel, index);
      });
      // 渲染
      this.root.appendChild(strHtml);
    },
    _bindEvents: function () {
      var self = this;
      $(this.elGroup).find('.channel-edit').on('click', function () {
        if (this.classList.contains('btn-success')) {
          if (channelEditInstance) {
            channelEditInstance.save().destroy();
            channelEditInstance = null;
          }
        } else {
          if (channelEditInstance) {
            if (!confirm('是否放弃修改')) return false;
            channelEditInstance.destroy();
          }
          channelEditInstance = new ChannelEdit(self.elGroup);
        }
      });
      $(this.elBox).on('click', '.channel', function () {
        if (!channelEditInstance || !this.classList.contains('channel-selected')) {
          var channel;
          while ((channel = channelLevelStack.pop()) !== this) {
            channel.destroy();
          }
          channelLevelStack.push(channel);
          this.classList.add('channel-selected');
        }
      });
    },
    insertChannel: function (channel, index, elBox) {
      if (!this.channelOriginalNode) {
        var strHtml = '<div class="channel">'
          + '<input type="text" class="channel-ipt" readonly>'
          + '<i class="fa fa-minus-circle text-danger channel-btn-del"></i>'
          + '</div>';
        this.channelOriginalNode = $(strHtml)[0];
      }
      var elChannel = this.channelOriginalNode.cloneNode(true);
      elChannel.channelId = channel.id;
      elChannel.channelPos = index;
      elChannel.children[0].value = channel.name;
      channel.deletable || elChannel.removeChild(elChannel.children[1]);
      (elBox || this.elBox).appendChild(elChannel);
      return elChannel;
    },
    destroy: function () {

    }
  };

  function ChannelEdit(root) {
    this.root = root;
    this.toBeAddedChannelList = [];
    this.toBeDeletedChannelList = [];
    this._init();
  }

  ChannelEdit.prototype = {
    _init: function () {
      this.elEditBtn = this.root.getElementsByClassName('channel-edit')[0];
      this.elBox = this.root.getElementsByClassName('channel-box')[0];

      this._cacheChannelInfo();
      this._switchModel(true);
      this._bindEvents();
    },
    _cacheChannelInfo: function () {
      var channelKeySplit = this.elBox.channelKey.split('_');
      var channelInfoList = CHANNEL;

      channelKeySplit.slice(1).reduce(function (key, id) {
        var subKey = key + '_' + id;
        channelInfoList = channelInfoList[subKey];
        return subKey;
      }, channelKeySplit[0]);

      this.channelInfoList = channelInfoList;
    },
    _bindEvents: function () {
      var self = this;
      this.drake = dragula([this.elBox], {
        moves: function (el, source) {
          return source.classList.contains('editable');
        }
      });
      $(this.elBox).on('click', '.channel-btn-del', function () {
        var elChannel = this.parentNode;
        self._delChannel(elChannel);
        elChannel.parentNode.removeChild(elChannel);
      }).on('click', '.channel-btn-add', function () {
        var newChannelName = prompt('请输入新的频道名称');
        var checkRes = self._checkChannel(newChannelName);
        if (checkRes.pass) {
          var elChannel = self._insertChannel(checkRes.val);
          self._addChannel(elChannel);
        } else {
          alert(checkRes.msg);
        }
      }).on('blur', '.channel-ipt', function () {
        var checkRes = self._checkChannel(this.value);
        if (!checkRes.pass) {
          alert(checkRes.msg);
          this.focus();
        }
      });
    },
    _addChannel: function (el) {
      var tempKey = Date.now();
      el.channelKey = tempKey;
      this.toBeAddedChannelList.push({
        key: tempKey,
        el: el
      });
    },
    _delChannel: function (el) {
      this.toBeDeletedChannelList.push({
        key: el.channelKey,
        el: el
      });
    },
    _modifyChannel: function () {

    },
    _insertChannel: function (name, key) {
      if (!this.channelOriginalNode) {
        var strHtml = '<div class="channel">'
          + '<input type="text" class="channel-ipt">'
          + '<i class="fa fa-minus-circle text-danger channel-btn-del"></i>'
          + '</div>';
        this.channelOriginalNode = $(strHtml)[0];
      }
      var elChannel = this.channelOriginalNode.cloneNode(true);

      this.elBox.appendChild(elChannel);
      elChannel.channelKey = key;
      return elChannel;
    },
    _insertAddBtn: function () {
      var strHtml = '<div class="channel channel-add">'
        + '<i class="fa fa-plus fa-lg channel-btn-add"></i>'
        + '</div>';
      var elAddBtn = $(strHtml)[0];

      this.elBox.appendChild(elAddBtn);
      return elAddBtn;
    },
    _switchModel: function (editable) {
      if (editable) {
        this.elEditBtn.classList.add('btn-success');
        this.elEditBtn.innerText = '完成';
        this.elBox.classList.add('editable');
        this.elAddBtn = this._insertAddBtn();
      } else {
        this.elEditBtn.classList.remove('btn-success');
        this.elEditBtn.innerText = '编辑';
        this.elBox.classList.remove('editable');
        this.elAddBtn && this.elBox.removeChild(this.elAddBtn);
      }
    },
    _checkChannel: function () {

    },
    save: function () {
      return this;
    },
    destroy: function () {
      // 修改模式
      this._switchModel(false);
      // 卸载拖拽实例
      this.drake.destroy();
      return this;
    }
  }
});