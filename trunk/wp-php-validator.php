<?php
if (!class_exists("PhpValidator")) {

  class PhpValidator {

    var $isPro, $strPro, $plgDir, $plgURL;
    var $ezTran, $domain;

    function __construct() { //constructor
      $this->plgDir = __DIR__;
      $this->plgURL = plugin_dir_url(__FILE__);
      $this->isPro = file_exists("{$this->plgDir}/admin/options-advanced.php");
      $this->strPro = ' Lite';
      if ($this->isPro) {
        $this->strPro = ' Pro';
      }
      if (is_admin()) {
        require_once($this->plgDir . '/EzTran.php');
        $this->domain = 'php-validator';
        $this->ezTran = new EzTran(__FILE__, "PHP Validator{$this->strPro}", $this->domain);
        $this->ezTran->setLang();
      }
    }

    function __destruct() {

    }

    function PhpValidator() {
      if (version_compare(PHP_VERSION, "5.0.0", "<")) {
        $this->__construct();
        register_shutdown_function(array($this, "__destruct"));
      }
    }

    static function install() {
      $mOptions = "phpvalidator";
      $ezOptions = get_option($mOptions);
      if (empty($ezOptions)) {
        // create the necessary tables
        $GLOBALS['isInstallingWP'] = true;
        chdir(__DIR__ . '/admin');
        require_once('dbSetup.php');
        $ezOptions['isSetup'] = true;
      }
      update_option($mOptions, $ezOptions);
    }

    static function uninstall() {
      $mOptions = "phpvalidator";
      delete_option($mOptions);
    }

    function printAdminPage() {
      if (!empty($_POST['phpvalidator_force_admin'])) {
        update_option('phpvalidator_force_admin', true);
      }
      $forceAdmin = get_option('phpvalidator_force_admin');
      if (!empty($_POST['phpvalidator_force_admin_again'])) {
        update_option('phpvalidator_force_admin_again', true);
      }
      $forceAdminAgain = get_option('phpvalidator_force_admin_again');
      $testFile = plugins_url("admin/promo.php", __FILE__);
      if (!$forceAdmin && !@file_get_contents($testFile)) { // index cannot be used for testing
        ?>
        <div class='error' style='padding:10px;margin:10px;font-size:1.3em;color:red;font-weight:500'>
          <p>This plugin needs direct access to its files so that they can be loaded in an iFrame. Looks like you have some security setting denying the required access. If you have an <code>.htaccess</code> file in your <code>wp-content</code> or <code>wp-content/plugins</code>folder, please remove it or modify it to allow access to the php files in <code><?php echo $this->plgDir; ?>/</code>.
          </p>
          <p>
            If you would like the plugin to try to open the admin page, please set the option here:
          </p>
          <form method="post">
            <input type="submit" value="Force Admin Page" name="phpvalidator_force_admin">
          </form>
          <p>
            <strong>
              Note that if the plugin still cannot load the admin page after forcing it, you may see a blank or error page here upon reload. If that happens, please deactivate and delete the plugin. It is not compatible with your blog setup.
            </strong>
          </p>
        </div>
        <?php
        return;
      }
      if ($forceAdmin && !$forceAdminAgain) {
        ?>
        <script>
          var errorTimeout = setTimeout(function () {
          jQuery('#the_iframe').replaceWith("<div class='error' style='padding:10px;margin:10px;font-size:1.3em;color:red;font-weight:500'><p>This plugin needs direct access to its files so that they can be loaded in an iFrame. Looks like you have some security setting denying the required access. If you have an <code>.htaccess</code> file in your <code>wp-content</code> or <code>wp-content/plugins</code>folder, please remove it or modify it to allow access to the php files in <code><?php echo $this->plgDir; ?>/</code>.</p><p><strong>If PHP Validator still cannot load the admin page after forcing it, please deactivate and delete the plugin. It is not compatible with your blog setup.</strong></p><p><b>You can try forcing the admin page again, which will kill this message and try to load the admin page. <form method='post'><input type='submit' value='Force Admin Page Again' name='phpvalidator_force_admin_again'></form><br><br>If you still have errors on the admin page or if you get a blank admin page, this plugin really is not compatible with your blog setup.</b></p></div>");
          }, 1000);        </script>
          <?php
        }
        $src = plugins_url("admin/index.php?inframe", __FILE__);
        ?>
      <script>
                function calcHeight() {
                var w = window,
                        d = document,
                        e = d.documentElement,
                        g = d.getElementsByTagName('body')[0],
                        y = w.innerHeight || e.clientHeight || g.clientHeight;
                        document.getElementById('the_iframe').height = y - 70;
                }
        if (window.addEventListener) {
        window.addEventListener('resize', calcHeight, false);
        }
        else if (window.attachEvent) {
        window.attachEvent('onresize', calcHeight);
        }
      </script>
      <?php
      echo "<iframe src='$src' frameborder='0' style='width:100%;position:absolute;top:5px;left:-10px;right:0px;bottom:0px' width='100%' height='900px' id='the_iframe' onLoad='calcHeight();'></iframe>";
    }

  }

} //End Class PhpValidator

if (class_exists("PhpValidator")) {
  $phpvalidator = new PhpValidator();

  add_action('admin_menu', 'phpvalidator_admin_menu');

  if (!function_exists('phpvalidator_admin_menu')) {

    function phpvalidator_admin_menu() {
      global $phpvalidator;
      $mName = 'PHP Validator ' . $phpvalidator->strPro;
      add_options_page($mName, $mName, 'activate_plugins', basename(__FILE__), array($phpvalidator, 'printAdminPage'));
    }

  }

  $file = __DIR__ . '/php-validator.php';
  register_activation_hook($file, array("PhpValidator", 'install'));
  register_deactivation_hook($file, array("PhpValidator", 'uninstall'));
}

