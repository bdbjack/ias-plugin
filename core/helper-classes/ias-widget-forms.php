<?php
	/**
	 * IAS Widget Form Class
	 * Used by IAS to manage Widget Options
	 */
	class ias_widget_form {
		
		public $html = NULL;

		function __construct( $fields = array() ) {
			$this->fields = $fields;
			$html = '';
			foreach ($fields as $field) {
				$html .= $this->gen_field_html( $field );
			}
			$this->html = $html;
		}

		private function gen_field_html( $field ) {
			$html = '<label style="display:block; width: 100%; margin-top: 10px; margin-bottom: 10px;" for="' . $field['id'] . '">' . $field['label'] . '</label>' . "\r\n";
			switch ( $field['type'] ) {
				case 'textarea':
				$html .= '<textarea style="display:block; width: 100%; height: 100px;" id="' . $field['id'] . '" name="' . $field['name'] . '" placeholder="' . $field['placeholder'] . '">' . $field['value'] . '</textarea>' . "\r\n";
					break;

				case 'checkbox':
				$html .= '<input type="checkbox" id="' . $field['id'] . '" name="' . $field['name'] . '" ';
				$html .= ( $field['value'] == TRUE ) ? 'checked="checked"' : '';
				$html .= '/>' . "\r\n";
					break;
				
				default:
					$html .= '<input type="' . $field['type'] . '" style="display:block; width: 100%;" id="' . $field['id'] . '" name="' . $field['name'] . '" value="' . $field['value'] . '" placeholder="' . $field['placeholder'] . '"';
					if( !is_null( $field['attributes'] ) ) {
						foreach ($field['attributes'] as $key => $value) {
							$html .= $key .'="' . $value . '" ';
						}
					}
					$html .= ' />' . "\r\n";
					break;
			}
			return $html;
		}
		
	}
?>