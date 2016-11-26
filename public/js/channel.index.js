$(function () {
  var channelLevelStack = [];

  function ChannelGenerate(subChannels, elChannel) {
    this.subChannels = subChannels;
    this.elChannel = elChannel;
    this.channelNameList = [];
    this.channelElList = [];
    this.channelLevel = null;
    this._init();
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
      var levelMatchTable = ['一', '二', '三', '四', '五'];
      var strHtml = '<div class="channel-group">'
        + '<div class="channel-header clearfix">'
        + levelMatchTable[this.channelLevel] + '级频道'
        + '<div class="btn btn-sm btn-primary pull-right channel-add">添加</div>'
        + '<div class="btn btn-sm btn-success pull-right channel-done">完成</div>'
        + '<div class="btn btn-sm btn-default pull-right channel-sort">排序</div>'
        + '</div>'
        + '<div class="channel-box clearfix"></div>'
        + '</div>';

      // 构建框架
      this.elGroup = $(strHtml)[0];
      this.elHeader = this.elGroup.getElementsByClassName('channel-header')[0];
      this.elBox = this.elGroup.getElementsByClassName('channel-box')[0];
      // 插入频道
      this.subChannels && this.subChannels.forEach(function (channel, index) {
        self.insertChannel(channel, index);
      });
      // 渲染
      $('.channel-wrapper').append(this.elGroup);
    },
    _bindEvents: function () {
      var self = this;

      $(this.elHeader).on('click', '.channel-add', function () {
        swal({
          title: '添加频道',
          type: 'input',
          showCancelButton: true,
          closeOnConfirm: false,
          animation: 'slide-from-top',
          inputPlaceholder: '字数请控制在2-5内',
          showLoaderOnConfirm: true
        }, function (inputValue) {
          if (inputValue === false) return false;
          inputValue = $.trim(inputValue);

          if (inputValue === '') {
            swal.showInputError('名称不能为空');
          } else if (self.channelNameList.indexOf(inputValue) >= 0) {
            swal.showInputError('名称已存在');
          } else {
            self._add(inputValue);
          }
          return false;
        });
      }).on('click', '.channel-sort', function () {
        if (self.drake) {
          this.innerText = '排序';
          self.elGroup.classList.remove('sort');
          restSort();
          $(self.channelElList).children('channel-ipt').prop('readonly', false);
          self.drake.destroy();
          self.drake = null;
        } else {
          this.innerText = '取消';
          self.elGroup.classList.add('sort');
          $(self.channelElList).children('channel-ipt').prop('readonly', true);
          self.drake = dragula([self.elBox]);
        }
      }).on('click', '.channel-done', function () {
        self._sort(restSort);
      });

      $(this.elBox).on('click', '.channel', function () {
        if (!self.drake && !this.classList.contains('channel-selected')) {
          var channel;
          var subChannels = self.subChannels[this.channelPos].children;
          while ((channel = channelLevelStack.pop()) !== self && channel) {
            channel.elChannel && channel.elChannel.classList.remove('channel-selected');
            channel.destroy();
          }
          channelLevelStack.push(channel);
          this.classList.add('channel-selected');
          channelLevelStack.length < 4 && new ChannelGenerate(subChannels, this)
        }
      })
      .on('click', '.channel-del', function () {
        self._del(this.parentNode.channelId);
        return false;
      })
      .on('blur', '.channel-ipt', function () {
        var inputValue = $.trim(this.value);
        var _this = this;

        if (inputValue === this.orgVal) {
          this.value = this.orgVal;
        } else if (inputValue === '') {
          dialogError('名称不能为空');
        } else if (self.channelNameList.indexOf(inputValue) >= 0) {
          dialogError('名称已存在');
        } else {
          self._modify(this.parentNode.channelId, inputValue, handlerModifyFail);
        }

        function dialogError(errorMsg) {
          swal({
            title: errorMsg,
            type: 'error',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: '确定',
            cancelButtonText: '取消'
          }, function (isConfirm) {
            handlerModifyFail(isConfirm);
          });
        }

        function handlerModifyFail(isConfirm) {
          if (isConfirm) {
            _this.focus();
          } else {
            _this.value = _this.orgVal;
          }
        }

        return false;
      });

      function restSort() {
        $(self.elBox).html('').append(self.channelElList);
      }
    },
    _unbindEvents: function () {
      $(this.elHeader).off();
      $(this.elBox).off();
      this.elGroup.parentNode.removeChild(this.elGroup);
    },
    _add: function (channelName) {
      var self = this;
      $.post('/admin/channels', {
        name: channelName,
        parent_id: this.elChannel && this.elChannel.channelId
      })
      .done(function (res) {
        self._actionSuccess('添加', res);
      })
      .fail(function () {
        self._actionFail('添加');
      });
    },
    _del: function (channelId) {
      var self = this;
      swal({
        title: '确定删除?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        closeOnConfirm: false,
        showLoaderOnConfirm: true
      }, function (isConfirm) {
        if (isConfirm) {
          $.post('/admin/channels/' + channelId, {
            _method: 'DELETE'
          })
          .done(function (res) {
            self._actionSuccess('删除', res);
          })
          .fail(function () {
            self._actionFail('删除');
          });
        }
      });
    },
    _modify: function (channelId, channelName, handlerModifyFail) {
      var self = this;
      swal({
        title: '确定修改?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        closeOnConfirm: false,
        showLoaderOnConfirm: true
      }, function (isConfirm) {
        if (isConfirm) {
          $.post('/admin/channels/' + channelId, {
            name: channelName,
            _method: 'PUT'
          })
          .done(function (res) {
            self._actionSuccess('修改', res, handlerModifyFail);
          })
          .fail(function () {
            self._actionFail('修改', '', handlerModifyFail);
          });
        } else {
          handlerModifyFail(false);
        }
      });
    },
    _sort: function (handlerSortFail) {
      var self = this;
      var tree = {
        id: this.elChannel && this.elChannel.channelId,
        children: Array.prototype.reduce.call(this.elBox.children, function (per, cur) {
          per.push({
            id: cur.channelId
          });
          return per;
        }, [])
      };
      $.post('/admin/channels/save', {
        _tree: JSON.stringify(tree)
      })
      .done(function (res) {
        self._actionSuccess('排序', res, handlerSortFail);
      })
      .fail(function () {
        self._actionFail('排序', '', handlerSortFail);
      });

    },
    _actionSuccess: function (actionName, res, failCallback) {
      if (res && res.result.status.code === 0) {
        swal({
          title: actionName + '成功',
          type: 'success'
        }, function () {
          location.reload();
        });
      } else {
        this._actionFail(actionName, res && res.result.status.msg, failCallback);
      }
    },
    _actionFail: function (actionName, failMsg, callback) {
      swal({
        title: actionName + '失败',
        text: failMsg || '',
        type: 'error'
      }, callback)
    },
    insertChannel: function (channel, index, elBox) {
      if (!this.channelOriginalNode) {
        var strHtml = '<div class="channel">'
          + '<input type="text" class="channel-ipt">'
          + '<i class="fa fa-minus-circle text-danger channel-del"></i>'
          + '</div>';
        this.channelOriginalNode = $(strHtml)[0];
      }
      var elChannel = this.channelOriginalNode.cloneNode(true);
      elChannel.channelId = channel.id;
      elChannel.channelPos = index;
      elChannel.children[0].value = elChannel.children[0].orgVal = channel.name;
      channel.deletable || elChannel.removeChild(elChannel.children[1]);
      (elBox || this.elBox).appendChild(elChannel);
      this.channelNameList.push(channel.name);
      this.channelElList.push(elChannel);
      return elChannel;
    },
    destroy: function () {
      this.drake && this.drake.destroy();
      this._unbindEvents();
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
  };

  /**
   * main
   */
  new ChannelGenerate(CHANNEL);
});