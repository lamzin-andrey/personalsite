<?php
class FormView {
	static public function imguploaded($src, $id = '') {
		$tpl = '<div class="imguploaded">
			<div class="pull-left uploaded-preview">
				<img src="' . $src . '"' . ($id ? (' data-id="' . $id . '"') : '' ) . '>
			</div>
			<div class="pull-left">
				<img src="/i/std/close.png"  class="uploaded-preview-close">
			</div>
			<div class="clearfix"></div>
			<div class="imageuploader-pbar hide">
				<div class="imageuploader-pbar-c">
					&nbsp;
				</div>
			</div>
		</div>';
		return $tpl;
	}
}
