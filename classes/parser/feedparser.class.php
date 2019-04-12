<?php
/**
 * *****************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - Initial implementation
 * *****************************************************************************
 */

class FeedParser {

  /**
   * List of rss paths
   *
   * @var string
   */
  private $path = array();

  /**
   * Default display count
   *
   * @var integer
   */
  private $count = 4;

  /**
   * Feed object
   *
   * @var object
   */
  private $feeds = array();

  /**
   * Flag to match height on items
   *
   * @var bool
   */
  private $match_height = FALSE;

  /**
   * Flag to only display press_releases
   *
   * @var string
   */
  private $press_release = FALSE;

  /**
   * Array of news item
   *
   * @var array
   */
  private $items = array();

  /**
   * View more link values
   *
   * @var string
   */
  private $view_more = array();

  /**
   * Link to feedburner feed
   *
   * @var string
   */
  private $rss_link = "";

  /**
   * Default date_format
   *
   * @var string
   */
  private $date_format = "Y/m/d";

  /**
   * Default news item limit
   *
   * @var integer
   */
  private $limit = 200;

  /**
   * Set date format
   *
   * @param string $format
   */
  public function setDateFormat($format = "Y/m/d") {
    $this->date_format = $format;
    return TRUE;
  }

  /**
   * Get date format
   *
   * @return string
   */
  public function getDateFormat() {
    return $this->date_format;
  }

  /**
   * Set path for RSS feed
   *
   * @param string $url
   */
  public function addPath($path = "") {
    if (is_string($path)) {
      $this->path[] = $path;
      $this->_setFeeds($path);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get path for RSS feed
   *
   * @return string
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * Set RSS link
   *
   * @param string $url
   */
  public function setRssLink($url = "") {
    if (is_string($url)) {
      $this->rss_link = $url;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get RSS link
   *
   * @param string $html
   *
   * @return string
   */
  public function getRssLink() {
    return $this->rss_link;
  }

  /**
   * Get RSS link
   *
   * @param string $html
   *
   * @return string
   */
  public function getRssLinkHTML() {
    $url = $this->getRssLink();
    if (empty($url)) {
      return "";
    }
    return '<a href="' . $url . '" class="link-rss-feed  orange" title="Subscribe to our RSS-feed"><i class="fa fa-rss"></i> <span>Subscribe to our RSS-feed</span></a>';
  }

  /**
   * Set Press Release Flag
   *
   * @param string $flag
   */
  public function setPressRelease($flag = FALSE) {
    if (is_bool($flag)) {
      $this->press_release = $flag;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get Press Release Flag
   *
   * @return string
   */
  public function getPressRelease() {
    return $this->press_release;
  }

  /**
   * Set item count
   *
   * @param number $count
   */
  public function setCount($count = 4) {
    if (is_numeric($count)) {
      $this->count = $count;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get item count
   *
   * @return number $count
   */
  public function getCount() {
    return $this->count;
  }

  /**
   * Get page number
   *
   * @return string
   */
  public function getPage() {
    if (!empty($_GET['page']) && is_numeric($_GET['page'])) {
      return $_GET['page'];
    }
    return 1;
  }

  /**
   * Set description limit
   *
   * @param number $limit
   */
  public function setLimit($limit = 200) {
    if (is_numeric($limit)) {
      $this->limit = $limit;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get description limit
   *
   * @return number $limit
   */
  public function getLimit() {
    return $this->limit;
  }

  /**
   * Set view_more link
   *
   * @param string $url
   * @param string $caption
   * @param string $prefix
   */
  public function setViewMoreLink($url = "", $caption = 'View all', $prefix = '> ') {
    if (is_string($url) && is_string($caption) && is_string($prefix)) {
      $this->view_more = array(
        'url' => $url,
        'caption' => $caption,
        'prefix' => $prefix
      );
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Set Match Height
   *
   * @param bool $match_height
   */
  public function setMatchHeight($match_height) {
    if (is_bool($match_height)) {
      $this->match_height = $match_height;
    }
  }

  /**
   * Get Match Height
   *
   * @return bool
   */
  public function getMatchHeight() {
    return $this->match_height;
  }

  /**
   * Get ViewMore link
   *
   * @return string
   */
  public function getViewMoreLink() {
    $view_more = $this->view_more;

    if (empty($view_more['url']) || empty($view_more['caption'])) {
      return array();
    }

    if (!isset($view_more['prefix'])) {
      $view_more['prefix'] = "";
    }

    return $view_more;
  }

  /**
   * Get view_more link (HTML)
   *
   * @return string
   */
  public function getViewMoreLinkHTML() {
    $view_more = $this->getViewMoreLink();
    if (empty($view_more)) {
      return "";
    }
    return $view_more['prefix'] . '<a href="' . $view_more['url'] . '">' . $view_more['caption'] . '</a>';
  }

  /**
   * Html Output
   *
   * @return string
   */
  public function output() {
    if (!$this->_parseFeeds()) {
      return '<p>This news feed is currently empty. Please try again later.</p>';
    }

    $output = '';
    if (!empty($this->items)) {
      $output .= '<div class="block-summary">';
      foreach ($this->items as $item) {
        $output .= '<div class="block-summary-item '. ($this->getMatchHeight() ? 'match-height-item' : "") .'">';
        $output .= '<p>' . $item['date'] . '</p>';
        $output .= '<h4><a href="'. $item['link'] .'">' . $item['title'] . '</a></h4>';
        if ($this->getLimit() > 0) {
          $output .= '<p>' . $item['description'] . '</p>';
        }
        $output .= '</div>';
      }
      $output .= '</div>';
    }

    return $output;
  }

  /**
   * Get the Next page link
   *
   * @return string
   */
  public function getPagination() {

    $feeds = $this->_getFeeds();
    if (empty($feeds)) {
      return "";
    }

    // Count all the items of all feeds
    $feed_items = 0;
    foreach ($feeds as $feed) {
      $feed_items += count($feed->channel->item);
    }

    // If the feed contains less items than the maximum allowed,
    // we don't need pagination
    if ($feed_items < $this->getCount()) {
      return "";
    }

    $current_page = $this->getPage();
    if (empty($current_page)) {
      $current_page = 1;
    }

    $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?';

    $url_queries = array(
      'page' => 1
    );

    $links = array();

    // Add the previous page link if needed
    $previous_page = $current_page - 1;
    if ($previous_page > 1) {
      $url_queries['page'] = $previous_page;
      $links[] = '<li><a aria-label="Previous" href="' . $url_path . http_build_query($url_queries) . '"><span aria-hidden="true">&laquo;</span></a></li>';
    }

    // Get the number of pages
    $number_of_pages = ceil($feed_items / $this->count);

    // Put the current page at the center of the pagination items
    $start_pagination = $current_page - 5;

    // Or make it the first item if its within the first 5 items
    if ($start_pagination <= 0) {
      $start_pagination = 1;
    }

    // Add the numerical links
    for ($i = $start_pagination; $i <= $start_pagination + 9; $i++) {
      $active = "";
      if ($current_page == $i) {
        $active = ' class="active"';
      }
      $url_queries['page'] = $i;
      $links[] = '<li'. $active .'><a aria-label="Previous" href="' . $url_path . http_build_query($url_queries) . '">' . $i . '</a></li>';

      if ($i >= $number_of_pages) {
        break;
      }
    }

    // Add the next page link if needed
    $next_page = $current_page + 1;
    if (!empty($feed_items) && $next_page <= $number_of_pages) {
      $url_queries['page'] = $next_page;
      $links[] = '<li><a aria-label="Next" href="' . $url_path . http_build_query($url_queries). '"><span aria-hidden="true">&raquo;</span></a></li>';
    }

    return '<nav aria-label="Page navigation"><ul class="pagination">' . implode($links) . '</ul></nav>';
  }

  /**
   * Parse the Feed
   *
   * @return boolean
   */
  private function _parseFeeds() {
    $feeds = $this->_getFeeds();
    if (empty($feeds)) {
      return FALSE;
    }

    $count = 0;
    foreach ($feeds as $feed) {

      $start_range = 0;
      $page = $this->getPage();
      if (!empty($page) && $page > 1) {
        $start_range = ($page - 1) * $this->count;
      }

      if (isset($feed) && $feed != FALSE) {
        foreach ($feed->channel->item as $item) {

          // Skip the items that are part of the previous page
          if ($count < $start_range) {
            $count++;
            continue;
          }

          // Break if we have enough feed items in the array
          if (count($this->items) >= $this->count) {
            break;
          }

          if ($this->getPressRelease() && $item->pressrelease != 1) {
            continue;
          }

          $date = strtotime((string) $item->pubDate);
          $date = date($this->getDateFormat(), $date);

          $description = (string) strip_tags($item->description);
          if (strlen($description) > $this->getLimit()) {
            $description = substr($description, 0, $this->limit);
            $description .= "...";
          }

          $item_array = array(
            'title' => (string) $item->title,
            'description' => $description,
            'link' => (string) $item->link,
            'date' => $date
          );

          $this->items[] = $item_array;
        }
      }
    }

    if (!empty($this->items)) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Set the feeds based on the path
   */
  private function _setFeeds($path = "") {

    if (empty($path)) {
      return FALSE;
    }

    $feed = simplexml_load_file($path);
    if (empty($feed)) {
      return FALSE;
    }

    $this->feeds[] = $feed;
  }

  /**
   * Get the feeds
   *
   * @return array
   */
  private function _getFeeds() {
    return $this->feeds;
  }

}
