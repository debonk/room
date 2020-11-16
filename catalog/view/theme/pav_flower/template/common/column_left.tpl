<?php if ($modules) { ?>
<div class="col-lg-3 col-md-3" id="sidebar-left">
	<column id="column-left" class="hidden-xs sidebar">
	  <?php foreach ($modules as $module) { ?>
	  <?php echo $module; ?>
	  <?php } ?>
	</column>
</div>
<?php } ?>
