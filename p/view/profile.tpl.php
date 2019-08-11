<form id="profileform">
  <?=i('name', l('First_name'), l('enter-first-name'), '', 'text', true)?>
  <?=i('surname', l('Last_name'), l('enter-last-name'), '', 'text', true)?>
  
  <?php
	$accordCollapseId      = 'logindata';
	$accordCollapseHeadingId = 'loginheading';
	$accordCollapseHeading   = l('Change-password');
	$accordContent           = '/view/profile/login.tpl.php';
	include DOC_ROOT . '/view/widgets/accordion.tpl.php';
  ?>
  <?=ifile('photo', l('upload-file'), '', false, false, 'data-url="' . ROOT . 'upload" data-success="bpOnSuccess" data-fail="bpOnFail" data-progress="bpOnProgress" data-select="bpOnSelect"')?>
	
  <div id="previews">
	  <?php foreach ($app->photos as $photo):?>
	  <?php echo FormView::imguploaded($photo['path'], $photo['id']); ?>
	  <?php endforeach ?>
  </div>
  <button id="bep" type="submit" class="btn btn-primary"><?=l('Save')?></button>
</form>
