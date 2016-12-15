var transform = (function () {
  var _elementStyle = document.createElement('div').style,
    _vendor = (function () {
      var vendors = ['t', 'webkitT', 'MozT', 'msT', 'OT'],
        transform,
        i = 0,
        l = vendors.length;

      for (; i < l; i++) {
        transform = vendors[i] + 'ransform';
        if (transform in _elementStyle) return vendors[i].substr(0, vendors[i].length - 1);
      }
      return false;
    })();

  function _prefixStyle(style) {
    if (_vendor === false) return false;
    if (_vendor === '') return style;
    return _vendor + style.charAt(0).toUpperCase() + style.substr(1);
  }

  return _prefixStyle('transform');
})();

var transitionEnd = (function () {
  var t;
  var el = document.createElement('fakeelement');
  var transitions = {
    'transition': 'transitionend',
    'OTransition': 'oTransitionEnd',
    'MozTransition': 'transitionend',
    'WebkitTransition': 'webkitTransitionEnd'
  };
  for (t in transitions) {
    if (el.style[t] !== undefined) return transitions[t];
  }
  return false;
})();

var summaryList = Array.prototype.map.call(document.getElementsByClassName('photo-title'), function (el) {
  return el.innerText;
});
var elModulePhotoArticle = document.getElementsByClassName('module-photo-article')[0];
var elSummaryBox = document.getElementsByClassName('summary-box')[0];
var elSummary = document.getElementsByClassName('summary')[0];
var summaryBoxHeight = 0;
var summaryHidden = false;
var tapTid;
var swiperInstance = new Swiper('.swiper-container', {
  preloadImages: false,
  lazyLoading: true,
  lazyLoadingInPrevNext: true,
  zoom: true,
  onInit: function (swiper) {
    changeSummary(swiper.realIndex);
  },
  onSlideChangeEnd: function (swiper) {
    changeSummary(swiper.realIndex);
  },
  pagination: '.pagination',
  paginationType: 'custom',
  paginationCustomRender: function (swiper, current, total) {
    return '<span class="current-page">' + current + '</span>' + '/' + total;
  },
  onTap: function () {
    clearTimeout(tapTid);
    tapTid = setTimeout(function () {
      if (summaryHidden) {
        elModulePhotoArticle.classList.remove('hide-summary');
        elSummaryBox.style.visibility = 'visible';
        elSummaryBox.style[transform] = '';
      } else {
        elModulePhotoArticle.classList.add('hide-summary');
        elSummaryBox.style[transform] = 'translateY(' + summaryBoxHeight + 'px)';
      }
      summaryHidden = !summaryHidden;
    }, 200);
  },
  onDoubleTap: function () {
    clearTimeout(tapTid);
  }
});

elSummaryBox.addEventListener(transitionEnd, function () {
  if (summaryHidden) elSummaryBox.style.visibility = 'hidden';
});

function changeSummary(index) {
  elSummary.innerText = summaryList[index];
  summaryBoxHeight = elSummaryBox.offsetHeight;
  if (summaryHidden) {
    elSummaryBox.style[transform] = 'translateY(' + summaryBoxHeight + 'px)';
  }
}