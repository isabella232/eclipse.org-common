<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                      "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
  <head>
    <title>Bugzilla Main Page</title>

      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


<link rel="Top" href="https://bugs.eclipse.org/bugstest/">




    <link href="https://bugs.eclipse.org/bugstest/skins/standard/global.css?1369324972"
          rel="alternate stylesheet"
          title="Classic"><link href="https://bugs.eclipse.org/bugstest/skins/standard/global.css?1369324972" rel="stylesheet"
        type="text/css" ><link href="https://bugs.eclipse.org/bugstest/skins/standard/index.css?1369324972" rel="stylesheet"
        type="text/css" ><!--[if lte IE 7]>



  <link href="https://bugs.eclipse.org/bugstest/standard/IE-fixes.css?1369324972" rel="stylesheet"
        type="text/css" >
<![endif]-->

    <link href="https://bugs.eclipse.org/bugstest/skins/contrib/Dusk/global.css?1404323043" rel="stylesheet"
        type="text/css" title="Dusk"><link href="https://bugs.eclipse.org/bugstest/skins/contrib/Dusk/index.css?1369324972" rel="stylesheet"
        type="text/css" title="Dusk">






<script type="text/javascript" href="https://bugs.eclipse.org/bugstest/js/global.js?1369324972"></script>






    <link rel="search" type="application/opensearchdescription+xml"
                       title="Bugzilla" href="https://bugs.eclipse.org/bugstest/search_plugin.cgi">
    <link rel="shortcut icon" href="images/favicon.ico" >
  </head>



  <body onload=""
        class="bugs-eclipse-org-bugstest yui-skin-sam">



<div id="header">
<!-- 1.0@bugzilla.org -->
<?php include('header.php');?>

<table border="0" cellspacing="0" cellpadding="0" id="titles">
<tr>
    <td id="title">
      <p>Bugzilla &ndash; Main Page</p>
    </td>


    <td id="information">
      <p class="header_addl_info">version 4.4.1</p>
    </td>
</tr>
</table>

<table id="lang_links_container" cellpadding="0" cellspacing="0"
       class="bz_default_hidden"><tr><td>
</td></tr></table>
<ul class="links">
  <li><a href="./">Home</a></li>
  <li><span class="separator">| </span><a href="enter_bug.cgi">New</a></li>
  <li><span class="separator">| </span><a href="describecomponents.cgi">Browse</a></li>
  <li><span class="separator">| </span><a href="query.cgi">Search</a></li>

  <li class="form">
    <span class="separator">| </span>
    <form action="https://bugs.eclipse.org/bugstest/buglist.cgi" method="get"
        onsubmit="if (this.quicksearch.value == '')
                  { alert('Please enter one or more search terms first.');
                    return false; } return true;">
    <input type="hidden" id="no_redirect_top" name="no_redirect" value="0">
    <script type="text/javascript">
      if (history && history.replaceState) {
        var no_redirect = document.getElementById("no_redirect_top");
        no_redirect.value = 1;
      }
    </script>
    <input class="txt" type="text" id="quicksearch_top" name="quicksearch"
           title="Quick Search" value="">
    <input class="btn" type="submit" value="Search"
           id="find_top"></form>
  <a href="https://bugs.eclipse.org/bugstest/page.cgi?id=quicksearch.html" title="Quicksearch Help">[?]</a></li>

  <li><span class="separator">| </span><a href="https://bugs.eclipse.org/bugstest/report.cgi">Reports</a></li>

  <li>
      <span class="separator">| </span>
        <a href="https://bugs.eclipse.org/bugstest/request.cgi">Requests</a></li>




    <li id="mini_login_container_top">
  <span class="separator">| </span>
  <a id="login_link_top" href="https://bugs.eclipse.org/bugstest/index.cgi?GoAheadAndLogIn=1"
     onclick="return show_mini_login_form('_top')">Log In</a>


  <form action="index.cgi" method="POST"
        class="mini_login bz_default_hidden"
        id="mini_login_top"
        onsubmit="return check_mini_login_fields( '_top' );"
  >
    <input id="Bugzilla_login_top"
           class="bz_login"
           name="Bugzilla_login"
           title="Login"
           onfocus="mini_login_on_focus('_top')"
    >
    <input class="bz_password"
           id="Bugzilla_password_top"
           name="Bugzilla_password"
           type="password"
           title="Password"
    >
    <input class="bz_password bz_default_hidden bz_mini_login_help" type="text"
           id="Bugzilla_password_dummy_top" value="password"
           title="Password"
           onfocus="mini_login_on_focus('_top')"
    >
    <input type="submit" name="GoAheadAndLogIn" value="Log in"
            id="log_in_top">

    <a href="#" onclick="return hide_mini_login_form('_top')">[x]</a>
  </form>
</li>
<li id="forgot_container_top">
  <span class="separator">| </span>
  <a id="forgot_link_top" href="https://bugs.eclipse.org/bugstest/index.cgi?GoAheadAndLogIn=1#forgot"
     onclick="return show_forgot_form('_top')">Forgot Password</a>
  <form action="token.cgi" method="post" id="forgot_form_top"
        class="mini_forgot bz_default_hidden">
    <label for="login_top">Login:</label>
    <input type="text" name="loginname" size="20" id="login_top">
    <input id="forgot_button_top" value="Reset Password"
           type="submit">
    <input type="hidden" name="a" value="reqpw">
    <input type="hidden" id="token_top" name="token" value="1406066068-Ws4rAH-VVuUanihHjkwrKMgUdsUAche0Ed8sfbxtQzs">
    <a href="#" onclick="return hide_forgot_form('_top')">[x]</a>
  </form>
</li>
  <span class="separator">| </span>
  <li><a href="http://www.eclipse.org/legal/termsofuse.php">Terms of Use</a></li>
  <span class="separator">| </span>
  <li><a href="http://www.eclipse.org/legal/copyright.php">Copyright Agent</a></li>
</ul>
</div>

<div id="bugzilla-body">
<div id="message">Email notifications are disabled here!</div>



<div id="page-index">
  <table>
    <tr>
      <td>
        <h1 id="welcome"> Welcome to Bugzilla</h1>
        <div class="intro"></div>

        <div class="bz_common_actions">
          <ul>
            <li>
              <a id="enter_bug" href="enter_bug.cgi"><span>File a Bug</span></a>
            </li>
            <li>
              <a id="query" href="query.cgi"><span>Search</span></a>
            </li>
            <li>
              <a id="account" href="https://dev.eclipse.org/site_login/createaccount.php"><span>Create a New Account</span></a>
            </li>
          </ul>
          <p><b><a href="duplicates.cgi?sortby=count&reverse=1&changedsince=30&openonly=1">Most requested bugs</a></b></p>
        </div>

        <form id="quicksearchForm" name="quicksearchForm" action="buglist.cgi"
              onsubmit="return checkQuicksearch(this);">
          <div>
            <input id="quicksearch_main" type="text" name="quicksearch"
              title="Quick Search"
              onfocus="quicksearchHelpText(this.id, 'hide');"
              onblur="quicksearchHelpText(this.id, 'show');"
            >
            <input id="find" type="submit" value="Quick Search">
            <ul class="additional_links" id="quicksearch_links">
              <li>
                <a href="page.cgi?id=quicksearch.html">Quick Search help</a>
              </li>
              <li  id="quicksearch_plugin">
                |
                <a href="javascript:window.external.AddSearchProvider('https://bugs.eclipse.org/bugstest/search_plugin.cgi')">
                 Install the Quick Search plugin
                </a>
              </li>
            </ul>
            <ul class="additional_links">
              <li>
                <a href="http://www.bugzilla.org/docs/4.2/en/html/using.html">Bugzilla User's Guide</a>
              </li>
              <li>
                |
                <a href="https://bugs.eclipse.org/bugstest/page.cgi?id=release-notes.html">Release Notes</a>
              </li>
            </ul>
          </div>
        </form>
        <div class="outro"></div>
      </td>
    </tr>
  </table>
</div>
</div>



<div id="footer">
  <div class="intro"></div>




<ul id="useful-links">
  <li id="links-actions"><ul class="links">
  <li><a href="./">Home</a></li>
  <li><span class="separator">| </span><a href="enter_bug.cgi">New</a></li>
  <li><span class="separator">| </span><a href="describecomponents.cgi">Browse</a></li>
  <li><span class="separator">| </span><a href="query.cgi">Search</a></li>

  <li class="form">
    <span class="separator">| </span>
    <form action="buglist.cgi" method="get"
        onsubmit="if (this.quicksearch.value == '')
                  { alert('Please enter one or more search terms first.');
                    return false; } return true;">
    <input type="hidden" id="no_redirect_bottom" name="no_redirect" value="0">
    <script type="text/javascript">
      if (history && history.replaceState) {
        var no_redirect = document.getElementById("no_redirect_bottom");
        no_redirect.value = 1;
      }
    </script>
    <input class="txt" type="text" id="quicksearch_bottom" name="quicksearch"
           title="Quick Search" value="">
    <input class="btn" type="submit" value="Search"
           id="find_bottom"></form>
  <a href="https://bugs.eclipse.org/bugstest/page.cgi?id=quicksearch.html" title="Quicksearch Help">[?]</a></li>

  <li><span class="separator">| </span><a href="https://bugs.eclipse.org/bugstest/report.cgi">Reports</a></li>

  <li>
      <span class="separator">| </span>
        <a href="https://bugs.eclipse.org/bugstest/request.cgi">Requests</a></li>




    <li id="mini_login_container_bottom">
  <span class="separator">| </span>
  <a id="login_link_bottom" href="https://bugs.eclipse.org/bugstest/index.cgi?GoAheadAndLogIn=1"
     onclick="return show_mini_login_form('_bottom')">Log In</a>


  <form action="index.cgi" method="POST"
        class="mini_login bz_default_hidden"
        id="mini_login_bottom"
        onsubmit="return check_mini_login_fields( '_bottom' );"
  >
    <input id="Bugzilla_login_bottom"
           class="bz_login"
           name="Bugzilla_login"
           title="Login"
           onfocus="mini_login_on_focus('_bottom')"
    >
    <input class="bz_password"
           id="Bugzilla_password_bottom"
           name="Bugzilla_password"
           type="password"
           title="Password"
    >
    <input class="bz_password bz_default_hidden bz_mini_login_help" type="text"
           id="Bugzilla_password_dummy_bottom" value="password"
           title="Password"
           onfocus="mini_login_on_focus('_bottom')"
    >
    <input type="submit" name="GoAheadAndLogIn" value="Log in"
            id="log_in_bottom">

    <a href="#" onclick="return hide_mini_login_form('_bottom')">[x]</a>
  </form>
</li>
<li id="forgot_container_bottom">
  <span class="separator">| </span>
  <a id="forgot_link_bottom" href="https://bugs.eclipse.org/bugstest/index.cgi?GoAheadAndLogIn=1#forgot"
     onclick="return show_forgot_form('_bottom')">Forgot Password</a>
  <form action="token.cgi" method="post" id="forgot_form_bottom"
        class="mini_forgot bz_default_hidden">
    <label for="login_bottom">Login:</label>
    <input type="text" name="loginname" size="20" id="login_bottom">
    <input id="forgot_button_bottom" value="Reset Password"
           type="submit">
    <input type="hidden" name="a" value="reqpw">
    <input type="hidden" id="token_bottom" name="token" value="1406066068-Ws4rAH-VVuUanihHjkwrKMgUdsUAche0Ed8sfbxtQzs">
    <a href="#" onclick="return hide_forgot_form('_bottom')">[x]</a>
  </form>
</li>
  <span class="separator">| </span>
  <li><a href="http://www.eclipse.org/legal/termsofuse.php">Terms of Use</a></li>
  <span class="separator">| </span>
  <li><a href="http://www.eclipse.org/legal/copyright.php">Copyright Agent</a></li>
</ul>
  </li>






</ul>

  <div class="outro"></div>
</div>


</body>
</html>