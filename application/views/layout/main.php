<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja">
    <head>
        <title><?php echo $title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <link href="/css/bootstrap.css" rel="stylesheet" media="screen">
        <link href="/css/bootstrap-responsive.css" rel="stylesheet" media="screen">
        <script src="/js/bootstrap.js"></script>
        <script type="text/javascript">
          window.onload = function() {
            setTimeout(scrollTo, 100, 0, 1);
          }
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-36724858-1']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();

        </script>
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
          <div class="navbar-inner">
            <div class="container">
              <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </a>
              <a class="brand" href="/index/index">&#128081; iOS Review King</a>
              <div class="nav-collapse collapse">
                <ul class="nav">
                  <li><a href="/top_reviewers">&#128120; Top Reviewers</a></li>
                  <li><a href="/reviewed_apps">&#128221; Most Reviewed Apps</a></li>
                  <li><a href="/index/app_ranking">&#127775; App Ranking</a></li>
                </ul>
              </div><!--/.nav-collapse -->
            </div>
          </div>
        </div>

        <?php echo $content_for_layout; ?>
    </body>
</html>
