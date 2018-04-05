/*
 *  quicksilver.js
 *  Various functionality for the quicksilver theme
 *
 *  Made by Christopher Guindon <chris.guindon@eclipse-foundation.org>
 *  Under EPL-v2 License
 */
(function($, document) {
  feather.replace();
  $('.featured-highlights-item').matchHeight();
  $('.news-list-media .media-link').matchHeight({byRow: false});

  // Focus on the Google search bar when dropdown menu is being shown
  $('.eclipse-search').on('shown.bs.dropdown', function () {
    $('.gsc-input').focus();
  });
})(jQuery, document);