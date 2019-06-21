<form id="signupform">
  <?=i('name', l('First_name'), l('enter-first-name'), '', 'text', true)?>
  <?=i('surname', l('Last_name'), l('enter-last-name'), '', 'text', true)?>
  <?=i('email', l('Email'), l('enter-email'), '', 'email', true)?>
  <?=i('passwordL', l('Password'), l('enter-password'), '', 'password', true)?>
  <?=i('passwordLC', l('Password_repeat'), l('enter-password-again'), '', 'password', true)?>
  <?=cb('agree', l('I-read-and-accept') . ' <a href="/agreement">' . l('user-agreement') . '</a>', '', false, true)?>
  <?=ifile('photo', l('upload-file'), '', false, false, 'data-url="' . ROOT . 'upload" data-success="bpOnSuccess" data-fail="bpOnFail" data-progress="bpOnProgress" data-select="bpOnSelect"')?>
  <div id="previews">
	  
  </div>
  <button id="breg" type="submit" class="btn btn-primary"><?=l('Register_now')?></button>
</form>
