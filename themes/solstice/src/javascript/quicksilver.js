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
  $('.featured-story-block').matchHeight();
  $('.news-list-media .media-link').matchHeight({byRow: false});

  // Focus on the Google search bar when dropdown menu is being shown
  $('.eclipse-search').on('shown.bs.dropdown', function () {
    $('.gsc-input').focus();
  });
  
  // Hide search on ESC key.
  // @todo: Find a way to make it work when focus is on an input field.
  $(document).bind('keydown', '27', function (e) {
    $('.eclipse-search a').dropdown("toggle");
  });

})(jQuery, document);

