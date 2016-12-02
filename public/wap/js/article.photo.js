var summaryList = Array.prototype.map.call(document.getElementsByClassName('photo-title'), function (el) {
  return el.innerText;
});
var elSummary = document.getElementsByClassName('summary')[0];
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
  }
});

function changeSummary(index) {
  elSummary.innerText = summaryList[index];
}