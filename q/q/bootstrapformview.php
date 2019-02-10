<?php
/**
 * //TODO переписать для 4,2,1 при  необходимости
 * Реализует хелперы для вывода компонента форм в стиле bootstrap4
*/
/**
 * Стандартный bootstrap инпут file form-group
*/
function ifile($id, $label, $css = '', $disabled = false, $isRequired = false, $attributes = '', $errorText = '') {
	$errorHtml = '';
	if ($errorText) {
		$errorHtml = '<div class="form-control-feedback">' . $errorText . '</div>';
	}
	$tpl = '<style>
	.ifile-group .custom-file-control:lang(en)::before {
		content:\'' . $label . '\';
	}
	</style><div class="form-group ifile-group' . ($disabled ? ' disabled' : '') . ' ' . ($errorText ? ' has-danger' : '') . '">
  <label class="custom-file">
    <input class="custom-file-input ' . $css . '" type="file"  id="' . $id . '" name="' . $id . '" ' . $attributes . ' ' . ($disabled ? ' disabled' : '') . '>'  . '
    <span class="custom-file-control"></span>
  </label>
  ' . $errorHtml . '
</div>';
	return $tpl;
	$tplExample = '
  <input type="file" id="file" class="custom-file-input">
  <span class="custom-file-control"></span>
</label>';
}
/**
 * Стандартный bootstrap инпут в form-group
*/
function cb($id, $label, $css = '', $disabled = false, $isRequired = false, $attributes = '', $errorText = '') {
	$errorHtml = '';
	if ($errorText) {
		$errorHtml = '<div class="form-control-feedback">' . $errorText . '</div>';
	}
	$tpl = '<div class="form-check ' . ($disabled ? ' disabled' : '') . ' ' . ($errorText ? ' has-danger' : '') . '">
  <label class="form-check-label">
    <input class="form-check-input" type="checkbox" value="1" id="' . $id . '" name="' . $id . '" ' . $attributes . ' ' . ($disabled ? ' disabled' : '') . '>' . ($isRequired ? ' <span class="cb-required">*</span> ' : '') . '
    ' . $label . '
  </label>
  ' . $errorHtml . '
</div>';
	return $tpl;
}
/**
 * Стандартный bootstrap инпут в form-group
*/
function i($id, $label, $placeholder = '', $css = '', $type = 'text', $isRequired = false, $attributes = '', $errorText = '', $subhelp = '') {
	$data = BaseApp::getDataObject();
	$value = isset($data->$id) ? $data->$id : '';
	$danger = $errorText ? ' has-danger ' : '';
	$tpl = '<div class="form-group ' . $danger . ' ' . $css . '">
<label for="'. $id .'">' . $label . '</label>[required]
<input type="' . $type . '" class="form-control" id="' . $id . '" ' . $attributes . ' aria-describedby="emailHelp" placeholder="' . $placeholder . '" name="' . $id . '" value="' . ($value ? $value : '') . '">
[error]
<small id="' . $id . 'Help" class="form-text text-muted">' . $subhelp . '</small>
</div>';
	$errorHtml = '';
	if ($errorText) {
		$errorHtml = '<div class="form-control-feedback">' . $errorText . '</div>';
	}
	$requiredHtml = '';
	if($isRequired) {
		$requiredHtml = '<span class="required">*</span>';
	}
	$tpl = str_replace('[error]', $errorHtml, $tpl);
	$tpl = str_replace('[required]', $requiredHtml, $tpl);
	return $tpl;
}

