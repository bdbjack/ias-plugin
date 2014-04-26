<?php
/**
 * ias-forms class
 * This is the abstract class which all other forms use for generation
 * Includes inline-css and js for use with the specific form.
 */

abstract class ias_forms {
	// Set up return variable
	public $html = NULL;
	// Set up protected variables (used to generate the HTML)
	protected $recaptchaKey = '6LeKivISAAAAAEQWD7n5Szjf1FuDc3dvibxGszvV';
	// https://developers.google.com/recaptcha/docs/php
	protected $id = NULL;
	protected $atts = array(
		'useChosen' => FALSE,		// Use Chosen Styling
		'useValidate' => FALSE,		// Use jQuery Validate plugin
		'useCaptcha' => TRUE,
	);
	protected $form_head = array(
		'html' => NULL,
		'css' => NULL,
		'js' => NULL,
	);
	protected $form_foot = array(
		'html' => NULL,
		'css' => NULL,
		'js' => NULL,
	);
	protected $form_attr = array(
		'action' => NULL,
		'method' => 'POST',
		'accept-charset' => 'UTF-8',
		'autocomplete' => 'off',
		'enctype' => 'application/x-www-form-urlencoded',
		'name' => NULL,
		'id' => NULL,
		'target' => '_self',
	);
	protected $fieldTypes = array(
		'button',
		'checkbox',
		'date',
		'datetime',
		'datetime-local',
		'email',
		'hidden',
		'month',
		'number',
		'password',
		'radio',
		'range',
		'search',
		'submit',
		'tel',
		'text',
		'time',
		'url',
		'week',
		'select',
		'textarea',
	);
	protected $fields = array();
	protected $layout = array();
	protected $validationRules = array();
	protected $validatedionMessages = array();
	
	// Set up core functions
	protected function gen_field_html( $field ) {
		if(!isset($this->fields) || !is_array($this->fields)) {
			return NULL;
		}
		$field_info = $this->fields[$field];
		$html .= '<div class="form-group">' . "\r\n";
		$html .= '	<label for="' . $this->id . '_' . $field_info['name'] . '">' . $this->id . '_' . $field_info['label'] . '</label>' . "\r\n";
		switch ($field_info['type']) {
			case 'select':
				$html .= '<select class="form-control" name="' . $field_info['name'] . '" id="' . $this->id . '_' . $field_info['name'] . ' "';
				if(!isset($field_info['placeholder']) || !is_null($field_info['placeholder']) || strlen($field_info['placeholder']) != 0 ) {
					$html .= 'data-placeholder="' . $field_info['placeholder'] . '" ';
				}
				foreach ($field_info['attributes'] as $key => $value) {
					$html .= $key . '="' . $vaulue . '" ';
				}
				$html .= '>' . "\r\n";
				if(is_array($field_info['value'])) {
					foreach ($field_info['value'] as $key => $option) {
						if(is_numeric($key) || !isset($option['value'])) {
							$val = $key;
							$text = __($option,IAS_TEXTDOMAIN);
						} else {
							$val = $option['value'];
							if( !isset( $option['text'] ) ) {
								$text = __($option['value'],IAS_TEXTDOMAIN);
							} else {
								$text = __($option['text'],IAS_TEXTDOMAIN);
							}
						}
						$html .= '<option value="' . $val . '" ';
						foreach ($option as $attKey => $attval) {
							if($attKey !== 'value' && $attKey !== 'text') {
								$html .= $attKey . '="' . $attval . '" ';
							}
						}
						$html .'>' . $text . '</option>' . "\r\n";
					}
				}
				$html .= '</select>' . "\r\n";
				break;
			
			default:
				$html .= '<input type="' . $field_info['type'] . '" class="form-control" name="' . $field_info['name'] . '" id="' . $this->id . '_' . $field_info['name'] . ' "';
				if(!isset($field_info['placeholder']) || !is_null($field_info['placeholder']) || strlen($field_info['placeholder']) != 0 ) {
					$html .= 'placeholder="' . $field_info['placeholder'] . '" ';
				}
				foreach ($field_info['attributes'] as $key => $value) {
					$html .= $key . '="' . $vaulue . '" ';
				}
				$html .= '/>' . "\r\n";
				break;
		}
		$html .= '</div>' . "\r\n";
		if($field_info['validate'] !== FALSE) {
			array_push($this->validationRules, $field_info['validate']['rules']);
			array_push($this->validatedionMessages, $field_info['validate']['messages']);
		}
		return $html;
	}

} // end of ias_forms class
?>