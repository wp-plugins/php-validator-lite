<?php
if (isset($_REQUEST['inframe'])) {
  ?>
  <style scoped>
    .tour-step-background {
      background: transparent;
      border: 2px solid blue;
    }
    .tour-backdrop {
      opacity:0.2;
    }
  </style>
  <?php
}
?>

<div class="col-lg-8 col-sm-12">
  <h4>Quick Start</h4>
  <p>PHP Pseudo Compiler is a developer tool that helps you locate undefined functions and methods. PHP is not a compiled language. It looks for functions during runtime. So if you have a segment of code not covered by your normal testing, and you have an undefined function in there, you will never know of the undefined function until it hits production when the particular conditions activating that particular code segment are met.</p>
  <p>
    You have multiple modes of scanning files for possible errors.
  </p>
  <ol>
    <li>Type in the list of files on your server to scan, separated by commas.</li>
    <li>Type in a folder location on your server.</li>
    <li>Upload a zipped PHP application (<a href="http://buy.thulasidas.com/php-validator" title="Get PHP Pseudo Compiler Pro for $4.95" class="goPro">Pro version</a>).</li>
    <li>Select a WordPress plugin installed on your blog (<a href="http://buy.thulasidas.com/php-validator" title="Get PHP Pseudo Compiler Pro for $4.95" class="goPro">Pro version</a>).</li>
  </ol>
  <p>
    Note that the files and folders need to be on the server that is running this application, and the paths should be relative to the parent location of this application (<code><?php echo realpath("../.."); ?></code>). You can also list absolute path names. When you upload a zipped package, it will end up on your server on a temporary random location (and is therefore harmless).
  </p>

  <h4>Context-Aware Help</h4>
  <p>Most of the admin pages of this application have a blue help button near the right hand side top corner. Clicking on it will give instructions and help specific to the task you are working on. All configuration options have a help button associated with it, which gives you a popover help bubble when you hover over it. If you need further assistance, please see the <a href='#' id='showSupportChannels'>support channels</a> available.</p>
</div>

<?php require 'support.php'; ?>

<hr />
<p class="center-text"> <a class="btn btn-primary center-text restart" href="#" data-toggle='tooltip' title='Start or restart the tour any time' id='restart'><i class="glyphicon glyphicon-globe icon-white"></i>&nbsp; Start Tour</a>
  <a class="btn btn-primary center-text showFeatures" href="#" data-toggle='tooltip' title='Show the features of this plugin and its Pro version'><i class="glyphicon glyphicon-thumbs-up icon-white"></i>&nbsp; Show Features</a>
  <a class="btn btn-success center-text launchIt" href="compile.php" data-toggle='tooltip' title='Hide this Quick Start and launch the application'><i class="glyphicon glyphicon-play icon-white"></i>&nbsp; Start <strong>PHP Pseudo Compiler</strong></a>
</p>

<script>
  $(document).ready(function () {
    if (!$('.tour').length && typeof (tour) === 'undefined') {
      var tour = new Tour({backdrop: true,
        onShow: function (t) {
          var current = t._current;
          var toShow = t._steps[current].element;
          var dad = $(toShow).parent('ul');
          var gdad = dad.parent();
          dad.slideDown();
          if (dad.hasClass('accordion')) {
            gdad.siblings('.accordion').find('ul').slideUp();
          }
          else if (dad.hasClass('dropdown-menu')) {
            gdad.siblings('.dropdown').find('ul').hide();
          }
        }
      });
      tour.addStep({
        element: "#dashboard",
        placement: "right",
        title: "Dashboard",
        content: "Welcome to PHP Pseudo Compiler! When you login to your PHP Pseudo Compiler Admin interface, you will find yourself in the Dashboard. Depending on the version of our app, you may see informational messages, quick start etc on this page."
      });
      tour.addStep({
        element: "#account",
        placement: "left",
        title: "Quick Access to Your Account",
        content: "Click here if you would like to logout or modify your profile (your password and email Id)."
      });
      tour.addStep({
        element: "#update",
        placement: "left",
        title: "Updates and Upgrades",
        content: "If you would like to check for regular updates, or install a purchased  Pro upgrade, visit the update page by clicking this button."
      });
      tour.addStep({
        element: "#standAloneMode",
        placement: "left",
        title: "Standalone Mode",
        content: "Open PHP Pseudo Compiler Admin in a new window independent of WordPress admin interface. The standalone mode still uses WP authentication, and cannot be accessed unless logged in."
      });
      tour.addStep({
        element: "#tour",
        placement: "right",
        title: "Tour",
        content: "This page is the starting point of your tour. You can always come here to relaunch the tour, if you wish."
      });
      tour.addStep({
        element: "#goPro",
        placement: "right",
        title: "Upgrade Your App to Pro",
        content: "To unlock the full potential of this app, you may want to purchase the Pro version. You will get an link to download it instantly. It costs only $15.95 and adds tons of features. These Pro features are highlighted by a red icon on this menu bar."
      });
      tour.addStep({// The first on ul unroll is ignored. Bug in BootstrapTour?
        element: "#options",
        placement: "right",
        title: "Configuration",
        content: "In this section, you can configure your PHP Pseudo Compiler installation."
      });
      tour.addStep({
        element: "#options",
        placement: "right",
        title: "Configuration Options",
        content: "On this page, you will set up your PHP Pseudo Compiler by providing the configuration options."
      });
      tour.addStep({
        element: "#advanced",
        placement: "right",
        title: "Advanced Tools and Options",
        content: "<p class='red'>This is a Pro feature.</p><p>On this page, you will find advanced options like suppressing duplicates, displaying detected tokens etc.</p>"
      });
      tour.addStep({// The first on ul unroll is ignored. Bug in BootstrapTour?
        element: "#profile",
        placement: "right",
        title: "Manage Your Account",
        content: "Set your account parameters or log off."
      });
      tour.addStep({
        element: "#profile",
        placement: "right",
        title: "Manage Your Profile",
        content: "Click here if you would like to modify your profile (your password and email Id)."
      });
      tour.addStep({
        element: ".launchIt",
        placement: "right",
        title: "Launch the Pseudo Compilier",
        content: "Click here to launch PHP Pseudo Compiler and validate files, plugins or applications."
      });
      tour.addStep({
        orphan: true,
        placement: "right",
        title: "Done",
        content: "<p>You now know the PHP Pseudo Compiler interface. Congratulations!</p>"
      });
    }
    $(".restart").click(function (e) {
      e.preventDefault();
      tour.restart();
    });
    $(".restart").click(function (e) {
      e.preventDefault();
      tour.restart();
    });
    $(".showFeatures").click(function (e) {
      e.preventDefault();
      $("#features").toggle();
      if ($("#features").is(":visible")) {
        $(this).html('<i class="glyphicon glyphicon-thumbs-up icon-white"></i>&nbsp; Hide Features');
      }
      else {
        $(this).html('<i class="glyphicon glyphicon-thumbs-up icon-white"></i>&nbsp; Show Features');
      }
    });
  });
</script>
