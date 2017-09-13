<?php ob_start(); ?>
<div class="news-list">
  <h2><a href="/community/news/eclipsenews.php">Announcements</a></h2>
  <div class="news_item_header"><a title="Subscribe to our RSS-feed" class="link-rss-feed  orange" href="http://feeds.feedburner.com/eclipse/fnews"><i class="fa fa-rss"></i> <span>Subscribe to our RSS-feed</span></a></div>
  <div class="news_item">
    <div class="news_item_date">2014/07/08</div>
    <div class="news_item_title">
      <h3><a href="https://www.eclipsecon.org/europe2014/cfp">EclipseCon Europe - Call for Papers</a></h3>
    </div>
    <div class="news_item_description">The early-bird submission deadline is on July 31. Don't wait, propose a talk now!</div>
  </div>
  <div class="news_view_all">&gt; <a href="/community/news/eclipsenews.php">View all</a><a title="Subscribe to our RSS-feed" class="link-rss-feed  orange" href="http://feeds.feedburner.com/eclipse/fnews"><i class="fa fa-rss"></i> <span>Subscribe to our RSS-feed</span></a></div>
</div>
<?php $html = ob_get_clean();?>

<h3 id="section-news-list">News list</h3>
<?php print $html; ?>
<h4>Code</h4>
<pre><?php print htmlentities($html); ?></pre>