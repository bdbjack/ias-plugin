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
		'useValidate' => TRUE,		// Use jQuery Validate plugin
		'useCaptcha' => FALSE,
		'responsiveSize' => 'sm',	// Class to use for col- divs for responsive styling
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

	protected function gen_row_html( $row ) {
		$cellCount = count($row);
		$cell_class_number = floor(12 / $cellCount);
	}
	
	// Set up modification functions
	public function use_chosen( $value = TRUE ) {
		do_action('ias_form_set_use_chosen',$value);
		if( $value == TRUE || $value == FALSE ) {
			$this->atts['useChosen'] = $value;
			return true;
		} else {
			return FALSE;
		}
	}

	public function use_validate( $value = TRUE ) {
		do_action('ias_form_set_use_validate',$value);
		if( $value == TRUE || $value == FALSE ) {
			$this->atts['useValidate'] = $value;
			return true;
		} else {
			return FALSE;
		}
	}

	public function use_captcha( $value = TRUE ) {
		do_action('ias_form_set_use_captcha',$value);
		if( $value == TRUE || $value == FALSE ) {
			$this->atts['useCaptcha'] = $value;
			return true;
		} else {
			return FALSE;
		}
	}

	public function set_response_size( $size = 'sm' ) {
		do_action('ias_form_set_response_size',$size);
		if( $size == 'xs' || $size == 'sm' || $size == 'md' || $size == 'lg' ) {
			$this->atts['responsiveSize'] = $size;
			return true;
		} else {
			return FALSE;
		}
	}
	
	public function update_form_head_html( $html = NULL , $overwrite = FALSE ) {
		if(!is_null( $this->form_head['html'] )) {
			if( $overwrite == FALSE ) {
				return FALSE;
			}
		}
		do_action('ias_update_form_head_html',$html);
		$this->form_head['html'] = $html;
		return TRUE;
	}

	public function update_form_head_css( $css = NULL , $overwrite = FALSE ) {
		if(!is_null( $this->form_head['css'] )) {
			if( $overwrite == FALSE ) {
				return FALSE;
			}
		}
		do_action('ias_update_form_head_css',$css);
		$this->form_head['css'] = $css;
		return TRUE;
	}

	public function update_form_head_js( $js = NULL , $overwrite = FALSE ) {
		if(!is_null( $this->form_head['js'] )) {
			if( $overwrite == FALSE ) {
				return FALSE;
			}
		}
		do_action('ias_update_form_head_js',$js);
		$this->form_head['js'] = $js;
		return TRUE;
	}

	public function update_form_foot_html( $html = NULL , $overwrite = FALSE ) {
		if(!is_null( $this->form_foot['html'] )) {
			if( $overwrite == FALSE ) {
				return FALSE;
			}
		}
		do_action('ias_update_form_foot_html',$html);
		$this->form_foot['html'] = $html;
		return TRUE;
	}

	public function update_form_foot_css( $css = NULL , $overwrite = FALSE ) {
		if(!is_null( $this->form_foot['css'] )) {
			if( $overwrite == FALSE ) {
				return FALSE;
			}
		}
		do_action('ias_update_form_foot_css',$css);
		$this->form_foot['css'] = $css;
		return TRUE;
	}

	public function update_form_foot_js( $js = NULL , $overwrite = FALSE ) {
		if(!is_null( $this->form_foot['js'] )) {
			if( $overwrite == FALSE ) {
				return FALSE;
			}
		}
		do_action('ias_update_form_foot_js',$js);
		$this->form_foot['js'] = $js;
		return TRUE;
	}

	public function set_form_action( $input = NULL ) {
		do_action('ias_set_form_action',$input);
	}

	public function set_form_method( $input = 'POST' ) {
		do_action('ias_set_form_method',$input);
	}

	public function set_form_charset( $input = 'UTF-8' ) {
		do_action('ias_set_form_charset',$input);
	}

	public function set_form_autocomplete( $input = TRUE ) {
		do_action('ias_set_form_autocomplete',$input);
	}

	public function set_form_enctype( $input = 'application/x-www-form-urlencoded' ) {
		do_action('ias_set_form_enctype',$input);
	}

	public function set_form_target( $input = '_self' ) {
		do_action('ias_set_form_target',$input);
	}

} // end of ias_forms class
?>