<?php if (menuHidden()) { ?>
  <!-- content ends -->
  </div><!--/#content.col-md-0-->
<?php } ?>
</div><!--/fluid-row-->
<hr>

<?php if (menuHidden()) { ?>
  <footer class="row">
    <p class="col-md-9 col-sm-9 col-xs-12 copyright">&copy; <a href="http://www.thulasidas.com" target="_blank">Manoj Thulasidas</a> 2013 - <?php echo date('Y') ?></p>

    <p class="col-md-3 col-sm-3 col-xs-12 powered-by"><a
        href="http://ads-ez.com/ads/pub.php">PHP Pseudo Compiler Server</a> from <a href="http://ads-ez.com/" target="_blank">Ads EZ Classifieds</a></p>
  </footer>
<?php } ?>

</div><!--/.fluid-container-->

<!-- external javascript -->

<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-editable.min.js"></script>
<script src="js/bootstrap-tour.min.js"></script>
<script src="js/bootstrapValidator.min.js"></script>
<script src="js/fileinput.min.js"></script>
<script src="js/bootbox.min.js"></script>
<!-- application specific -->
<script src="js/php-validator.js"></script>
<script src="js/charisma.js"></script>
<script>
  $(document).ready(function(){
    parent.clearTimeout(parent.errorTimeout);
  });
</script>
</body>
</html>
