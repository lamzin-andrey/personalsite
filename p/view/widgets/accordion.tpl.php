<div id="accordion" role="tablist" aria-multiselectable="true">
	  <div class="panel panel-default">
		<div class="panel-heading" role="tab" id="<?=$accordCollapseHeadingId?>">
		  <h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#<?=$accordCollapseId?>" aria-expanded="true" aria-controls="<?=$accordCollapseId?>">
			  <?php echo $accordCollapseHeading ?>
			</a>
		  </h4>
		</div>
		<div id="<?=$accordCollapseId ?>" class="panel-collapse collapse " role="tabpanel" aria-labelledby="<?=$accordCollapseHeadingId?>">
			<?php include DOC_ROOT. $accordContent?>
		</div>
  </div>
