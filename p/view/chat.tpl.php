<div class="pull-left contacts" id="contacts"></div>
<div class="pull-left messages" id="messages"></div>
<div class="clearfix"></div>
<form id="chatsendform" >
<div class="form-group  enter-message">
<label for="messageMy"><?php echo l('Message');?></label>
  <s id="smile" class="smilechooser-toggle"> </s>
  <div class="smileschooser hide">
	<s id="handshake" class="smilechooser-add"> </s>
	<s id="cool" class="smilechooser-add"> </s>
	<s id="GGG" class="smilechooser-add"> </s>
	<s id="like" class="smilechooser-add"> </s>
	<s id="dislike" class="smilechooser-add"> </s>
	<s id="laught" class="smilechooser-add"> </s>
	<s id="rolf" class="smilechooser-add"> </s>
	<s id="smile" class="smilechooser-add"> </s>
	<s id="movember" class="smilechooser-add"> </s>
	<s id="bandit" class="smilechooser-add"> </s>
	<s id="ninja" class="smilechooser-add"> </s>
	<s id="forge" class="smilechooser-add"> </s>
	<s id="santa" class="smilechooser-add"> </s>
  </div>
  
  <label id="chatUploadBtn" class="chat-upload-label">
	<img class="b " src="/i/std/fileopen.png" width="20" height="20">
	<input class="hide" type="file" id="chatfile" name="chatfile" data-url="<?php echo ROOT . 'chatupload'?>" data-progress="chatOnUploadProgress" data-success="chatOnUploadFile" data-fail="chatOnFailUploadFile" data-select-off="chatOnSelectFile">
  </label>
  <?php if ($app->isP2pAllowedUser()): ?>
  <label id="chatUploadBtn" class="chat-pp-idc">
	<img src="/i/std/i/gray.png" id="p2pidc" title="Соединение p2p закрыто">
  </label>
  <?php endif ?>
  
	<div id="chatUploadProcessView" class="relative chat-upload-token-anim-block" style="display:none">
		<div id="chatUploadProcessLeftSide" class="pull-left chat-upload-token-anim-color">&nbsp;</div>
		<div id="chatUploadProcessRightSide" class="pull-left chat-upload-token-anim-color">&nbsp;</div>
		<div class="clearfix"></div>
		<img id="chatUploadProcessTokenImage" src="/i/std/token.png">
		<div id="chatUploadProcessText" style="">9</div>
	</div>
  
<textarea class="messageText" id="messageMy" autocomplete="off" maxlength="1024" aria-describedby="emailHelp" placeholder="<?php echo l('enter-message'); ?>" name="messageMy"></textarea>
<button id="mbin" type="submit" class="btn btn-primary" disabled="disabled">^^^</button>
<div class="clearfix"></div>
<small id="messageMyHelp" class="form-text text-muted"></small>
</div>
  

  <button id="bsend" type="submit" class="btn btn-primary" disabled="disabled"><?=l('Send')?></button>
</form>
