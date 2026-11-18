// Rocky Mount NC — minimal JS: header shrink on scroll only.
// No reveal, no content hiding, no scroll-triggered anything.
(function () {
  var header = document.getElementById('site-header');
  if (!header) return;
  var onScroll = function () {
    if (window.scrollY > 8) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();
