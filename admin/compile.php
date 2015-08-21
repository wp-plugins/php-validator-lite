<?php require 'header.php'; ?>
<div>
  <ul class="breadcrumb">
    <li>
      <a href="#">Home</a>
    </li>
    <li>
      <a href="#">Dashboard</a>
    </li>
  </ul>
</div>

<?php
insertAlerts(12);
openBox("PHP Pseudo Compiler", "play", 12);
$pwd = realpath("../..");
$modes = array();

$modes['filelist'] = array('name' => 'List of Files',
    'help' => "Give the list of files you would like to validate as comma-separated values. The file name has to be a path relative to the current location (<code style=\"word-wrap:break-word;\">$pwd</code>), or an absolute path name.",
    'value' => '');

$modes['folder'] = array('name' => 'Folder Location',
    'help' => 'Give a folder (a path relative to the current location, or an absolute path name) where you have already uploaded the application to validate. Specify a path relative to the current location (<code style="word-wrap:break-word;">' . $pwd . '</code>), or as an absolute path.',
    'value' => '');

$modes['file'] = array('name' => 'Upload Application',
    'help' => '<p class="red">Pro Feature</p>Upload a zipped archive of the application you would like to validate. The zip file will be uploaded to your server and unzipped in a temporary random location (so that there is not security issues of hackers executing their code on your server through this vector).',
    'type' => 'file',
    'value' => '',
    'validator' => 'notNull');

if (function_exists('get_plugins')) {
  $allPlugins = get_plugins() + get_plugins('/../mu-plugins/');
  $plugins = array();
  foreach ($allPlugins as $slug => $p) {
    $pruned = dirname($slug);
    if (!empty($pruned) && $pruned != '.') {
      $plugins[] = $pruned;
    }
  }
}
else {
  $plugins = array('No plugins found');
}

$modes['plugin'] = array('name' => 'Select a Plugin',
    'help' => '<p class="red">Pro Feature</p>Select one of these plugins on your WordPress installation. The Pseudo Compiler will go through the files and report any undefined functions or methods.',
    'type' => 'select',
    'options' => $plugins,
    'value' => '');

$modes['eval_includes'] = array('name' => 'Execute the Files',
    'help' => '<p class="red">Pro Feature</p>The uploaded files are parsed and examined statically by default. If you would like to do dynamic analysis by executing the files, please check here. <span class="red">Please note that executing uploaded files may have side effects, and it may be a security hole as well. For that reason, this <em>Pro option</em> self-disables after execution. It also requires that the <em>Dynamic Mode</em> (in the <strong>Advanced Options</strong> section) as well as this check box be checked.</span>',
    'type' => 'checkbox',
    'value' => '');
?>
<table class="table table-striped table-bordered responsive">
  <tbody>
    <?php
    foreach ($modes as $slug => $attribute) {
      $attribute['slug'] = $slug;
      echo EZ::renderRow(0, $attribute);
    }
    ?>
  </tbody>
</table>
<p class="center-text">
  <a class="btn btn-success center-text launchIt" href="compile.php" data-toggle='tooltip' title='Clear any previous output and relaunch the PHP Pseudo Compiler'><i class="glyphicon glyphicon-refresh icon-white"></i>&nbsp; Restart <strong>PHP Pseudo Compiler</strong></a>

</p>
<script>
  var xeditHandler = 'ajax/success.php';
  $(document).ready(function () {
    $(".xedit").editable({success: function (response, newValue) {
        showSuccess("&emsp;<img src='img/loading.gif' alt='loading' />&emsp;Loading... Please wait!");
        hideWarning();
        hideError();
        var evalIncludes = 1;
        if ($("#eval_includes").hasClass('btn-danger')) {
          evalIncludes = '';
        }
        $.ajax({
          url: 'ajax/compile.php',
          type: 'GET',
          data: {action: $(this).attr('id'),
            value: newValue,
            evalIncludes: evalIncludes},
          success: function (response) {
            if (response.success)
              showSuccess(response.success);
            if (response.warning)
              showWarning(response.warning);
            if (response.error)
              showError(response.error);
          },
          error: function (a) {
            hideSuccess();
            hideWarning();
            showError(a.responseText);
          },
          complete: function (a) {
            if (typeof a !== "object")
              flashWarning(a);
            $("#eval_includes").editable('setValue', 0);
          }
        });
      }
    });
  });
  function ajaxUpload(_file) {
    showSuccess("&emsp;<img src='img/loading.gif' alt='loading' />&emsp;Working... Please wait!");
    hideWarning();
    hideError();
    if (!_file) {
      flashWarning("No file uploaded.");
      return;
    }
    var data = new FormData();
    data.append('file', _file);
    data.append('action', 'file');
    $.ajax({
      url: 'ajax/compile.php',
      type: 'POST',
      data: data,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.success)
          showSuccess(response.success);
        if (response.warning)
          showWarning(response.warning);
        if (response.error)
          showError(response.error);
      },
      error: function (a) {
        showError(a.responseText);
      },
      complete: function (a) {
        if (typeof a !== "object")
          flashWarning(a);
      }
    });
  }
  $('body').on('change', "#fileinput", function (e) {
    file = event.target.files[0];
    if (file) {
      bootbox.confirm("Are you sure you want to upload <code>" + file.name + "</code> and validate it?", function (result) {
        if (result) {
          ajaxUpload(file);
        }
        else {
          flashWarning("File not uploaded. Browse again to upload and validate a file.");
        }
      });
    }
  });
</script>
<?php
closeBox();
require 'promo.php';
require 'footer.php';
