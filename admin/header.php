<?php
if (version_compare(PHP_VERSION, '5.4') < 0) {
  echo 'PHP Pseudo Compiler requires PHP version 5.4 or greater. You are using: ' . PHP_VERSION .
          "<br>Please ask your hosting provider to update your PHP.";
  exit();
}
error_reporting(E_ALL);

require_once 'header-functions.php';

if (menuHidden()) {
  require_once 'lock.php';
}

include_once('../debug.php');

function getHeader() {
  http_response_code(200);
  if (class_exists('EZ') && property_exists('EZ', 'isPro')) {
    $isPro = EZ::$isPro;
  }
  else {
    $isPro = false;
  }
  if (class_exists('EZ') && !empty(EZ::$options['theme'])) {
    $themeCSS = "css/bootstrap-" . strtolower(EZ::$options['theme']) . ".min.css";
  }
  else {
    $themeCSS = "css/bootstrap-cerulean.min.css";
  }
  ?>
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <title>PHP Validator - Pseudo Complier</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="PHP Validator - PHP Pseudo Compiler.">
      <meta name="author" content="Manoj Thulasidas">

      <!-- The styles -->
      <link id="bs-css" href="<?php echo $themeCSS; ?>" rel="stylesheet">
      <link href="css/bootstrap-editable.css" rel="stylesheet">
      <link href="css/charisma-app.css" rel="stylesheet">
      <link href='css/bootstrap-tour.min.css' rel='stylesheet'>
      <link href='css/bootstrapValidator.css' rel='stylesheet'>
      <link href="css/font-awesome.min.css" rel="stylesheet">
      <link href="css/fileinput.min.css" rel="stylesheet">
      <style type="text/css">
        .popover{width:600px;}
        <?php
        if (class_exists('EZ') && empty(EZ::$options['breadcrumbs'])) {
          ?>
          .breadcrumb {display:none;}
          <?php
        }
        ?>
      </style>
      <!-- jQuery -->
      <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

      <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
      <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->

      <!-- The fav icon -->
      <link rel="shortcut icon" href="img/favicon.ico">

    </head>

    <body>
  <?php if (menuHidden()) { ?>
        <!-- topbar starts -->
        <div class="navbar navbar-default" role="navigation">

          <div class="navbar-inner">
            <a id="index" class="navbar-brand" href="index.php"> <img alt="PHP Pseudo Compiler Logo" src="img/php-validator.png" class="hidden-xs"/>
              <span>PHP Pseudo Compiler</span></a>
            <div class="btn-group pull-right">
              <?php
              if (!EZ::$isInWP) {
                ?>

                <!-- user dropdown starts -->
                <button id="account" class="btn btn-default dropdown-toggle pull-right" data-toggle="dropdown">
                  <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"> admin</span>
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="profile.php">Profile</a></li>
                  <li class="divider"></li>
                  <li><a href="login.php?logout">Logout</a></li>
                </ul>
                <!-- user dropdown ends -->
                <?php
              }
              else {
                $standaloneURL = plugins_url('index.php', __FILE__);
                ?>
                <a id="standAloneMode" href="<?php echo $standaloneURL; ?>" target="_blank" data-content="Open PHP Pseudo Compiler Admin in a new window independent of WordPress admin interface. The standalone mode still uses WP authentication, and cannot be accessed unless logged in." data-toggle="popover" data-trigger="hover" data-placement="left"  title='Standalone Admin Screen'><span class="btn btn-info"><i class="glyphicon glyphicon-resize-full"></i> Standalone Mode</span></a>
                <?php
              }
              ?>
              <a id="update" href="update.php" data-content="If you would like to check for regular updates, or install a purchased module or Pro upgrade, visit the update page." data-toggle="popover" data-trigger="hover" data-placement="left" title='Update Page'><span class="btn btn-info"><i class="fa fa-cog fa-spin"></i> Updates
                  <?php
                  if (!$isPro) {
                    ?>
                    &nbsp;<span class="badge red">Pro</span>
                    <?php
                  }
                  ?>
                </span>
              </a>&nbsp;
            </div>
          </div>
        </div>
        <!-- topbar ends -->
  <?php } ?>
      <div class="ch-container">
        <div class="row">
          <?php
          if (menuHidden()) {
            ob_start();
            ?>
            <!-- left menu starts -->
            <div class="col-sm-2 col-lg-2">
              <div class="sidebar-nav">
                <div class="nav-canvas">
                  <div class="nav-sm nav nav-stacked">

                  </div>
                  <ul class="nav nav-pills nav-stacked main-menu">
                    <li id="dashboard"><a href="index.php"><i class="glyphicon glyphicon-home"></i><span> Dashboard</span></a>
                    </li>
                    <?php
                    if (!$isPro) {
                      ?>
                      <li id='goPro'><a href="pro.php" class="red goPro" data-toggle="popover" data-trigger="hover" data-content="Get the Pro version of this app for <i>only</i> $4.95. Tons of extra features. Instant download." data-placement="right" title="Upgrade to Pro"><i class="glyphicon glyphicon-shopping-cart"></i><span><b> Go Pro!</b></span></a></li>
                      <?php
                    }
                    ?>
                    <li id='launchIt'><a href="compile.php" class="launchIt" data-toggle="popover" data-trigger="hover" data-content="Launch the PHP Pseudo Compiler now, where you can specify or update files and validate them." data-placement="right" title="Launch It!"><i class="glyphicon glyphicon-play"></i><span><b> Launch It!</b></span></a></li>
                    <li class="accordion">
                      <a href="options.php"><i class="glyphicon glyphicon-plus"></i><span> Configuration</span></a>
                      <ul class="nav nav-pills nav-stacked">
                        <li id="options"><a href="options.php"><i class="glyphicon glyphicon-cog"></i><span> Options</span></a>
                        <li id="advanced"><a class="ajax-link" href="advanced.php"><i class="glyphicon glyphicon-cog red"></i><span> Advanced Tools</span></a></li>
                      </ul>
                    </li>
                    <?php
                    if (!EZ::$isInWP) {
                      ?>
                      <li class="accordion">
                        <a href="profile.php"><i class="glyphicon glyphicon-plus"></i><span> Your Account</span></a>
                        <ul class="nav nav-pills nav-stacked">
                          <li id="profile"><a href="profile.php"><i class="glyphicon glyphicon-lock"></i><span> Your Profile</span></a>
                          </li>
                          <li id="logout"><a href="login.php?logout"><i class="glyphicon glyphicon-ban-circle"></i><span> Logout</span></a>
                        </ul>
                      </li>
                      <?php
                    }
                    ?>
                  </ul>
                </div>
              </div>
            </div>
            <!--/span-->
            <!-- left menu ends -->

            <noscript>
            <div class="alert alert-block col-md-12">
              <h4 class="alert-heading">Warning!</h4>

              <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                enabled to use this site.</p>
            </div>
            </noscript>

            <div id="content" class="col-lg-10 col-sm-10">
              <!-- content starts -->
              <?php
              if (EZ::isUpdateAvailable()) {
                ?>
                <div class="alert alert-info">
                  <a href="#" class="close" data-dismiss="alert">&times;</a>
                  <strong>Updates Available!</strong> Please update your PHP Pseudo Compiler server.
                </div>
                <?php
              }
            }
            $header = ob_get_clean();
            return $header;
          }

          $header = getHeader();
          if (method_exists('EZ', 'toggleMenu')) {
            $header = EZ::toggleMenu($header);
          }
          echo $header;
