<?php
/**
 * ias_table class
 * This class is used to generate WordPress tables + forms with automatic pagination and search functions for use with IAS Plugin Admin Pages
 * It can and SHOULD be used for all sub-modules
 */

class ias_table {
	// Object Variables
	private $data = NULL;
	private $isForm = FALSE;
	private $addAction = NULL;
	private $editAction = NULL;
	private $deleteAction = NULL;
	private $searchKey = NULL;
	private $untouchables = array();
	private $untouchablesKey = 'id';
	private $useFancyClass = FALSE;
	private $fields = array();
	private $page = 0;
	private $pagination = 20;
	private $table_title = NULL;
	private $showWarning = array();
	public $html = NULL;

	// Object Functions
	
	function __construct( $data = NULL , $fields = array() ) {
		$this->data = $data;
		$this->fields = $fields;
		$this->genHtml();
	}

	function setProperty( $key , $value ) {
		$this->$key = $value;
		$this->genHtml();
	}

	private function genHtml() {
		$html = '';
		// Show Title //
		if(!is_null($this->table_title)) {
			$html .= '<h3>' . __($this->table_title,IAS_TEXTDOMAIN) . '</h3>' . "\r\n";
		}
		// Show Information + Search + Pagination Row
		$html .= '<div style="float:none; clear:both; overflow-x: auto;">' . "\r\n";
		$html .= '	<div style="float:left; display:inline-block;">		' . "\r\n";
		$html .= '		<p>' . __('Total',IAS_TEXTDOMAIN) . ': ' . count($this->data) . '</p>' . "\r\n";
		$html .= '	</div>' . "\r\n";
		if(!is_null($this->searchKey)) {
		$html .= '	<div style="float:right; display:inline-block;">		' . "\r\n";
		$html .= '		<form action="' . $_SERVER['REQUEST_URI'] . '" METHOD="GET" role="form">' . "\r\n";
		foreach ($_GET as $key => $value) {
		$html .= '				<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\r\n";
		}
		$html .= '			<p class="search-box"><input type="search" name="search" ';
		if(isset($_GET['search'])) {
			$html .= 'value="' . $_GET['search'] . '" />' . "\r\n";
		} else {
			$html .= '/>' . "\r\n";
		}
		$html .= '			<input class="button" type="submit" value="' . __('Search' , IAS_TEXTDOMAIN) . '" /></p>' . "\r\n";
		$html .= '		</form>' . "\r\n";
		$html .= '	</div>' . "\r\n";	
		}
		$html .= '</div>' . "\r\n";
		// Form action buttons //
		if($this->isForm == TRUE) {
			$html .= '<form action="' . $this->deleteAction . '" method="POST" role="form">' . "\r\n";
			if(!is_null($this->addAction) || !is_null($this->deleteAction)) {
				$html .= '<p>' . "\r\n";
				if(!is_null($this->addAction)) {
					$html .= '<a href="' . $this->addAction . '" class="button button-primary';
					if($this->useFancyClass == TRUE) {
						$html .= ' fancyopen';
					}
					$html .= '">' . __('Add', IAS_TEXTDOMAIN) . '</a>' . "\r\n";
				}
				if(!is_null($this->deleteAction)) {
					$html .= '<input type="submit" class="button" value="' . __('Remove Selected' , IAS_TEXTDOMAIN) . '" />' . "\r\n";
				}
				$html .= '</p>' . "\r\n";
			}
		}
		// Set up Table
		$html .= '<table role="table" class="widefat">' . "\r\n";
		$html .= '<thead>' . "\r\n";
		$html .= '	<tr>' . "\r\n";
		if(!is_null($this->editAction)) {
			$html .= '<th> &nbsp; </th>' . "\r\n";
		}
		foreach ($this->fields as $name => $info) {
		$html .= '<th>' . __($info['name'],IAS_TEXTDOMAIN) . '</th>' . "\r\n";
		}
		$html .= '	</tr>' . "\r\n";
		$html .= '</thead>' . "\r\n";
		$html .= '<tfoot>' . "\r\n";
		$html .= '	<tr>' . "\r\n";
		if(!is_null($this->editAction)) {
			$html .= '<th width="50"> &nbsp; </th>' . "\r\n";
		}
		foreach ($this->fields as $name => $info) {
		$html .= '<th>' . __($info['name'],IAS_TEXTDOMAIN) . '</th>' . "\r\n";
		}
		$html .= '	</tr>' . "\r\n";
		$html .= '</tfoot>' . "\r\n";
		$html .= '<tbody>' . "\r\n";
		foreach ($this->data as $row) {
			$html .= '<tr>' . "\r\n";
				if(!is_null($this->editAction)) {
					$html .= '<th width="50"><a href="' . $this->editAction . '&id=' . $row[$this->untouchablesKey] . '" class="button">' . __('Edit' , IAS_TEXTDOMAIN) . '</a></th>' . "\r\n";
				}
				foreach ($this->fields as $key => $info) {
					$html .= '<td>' . "\r\n";
					switch ($info['type']) {
						case 'bool':
							$html .= '<input type="checkbox" name="selected[]" value="' . $row[$this->untouchablesKey] . '"';
							if(in_array($row[$this->untouchablesKey], $this->untouchables)) {
								$html .= 'disabled="disabled"';
							}
							$html .= ' />' . "\r\n";
							break;

						case 'view-bool':
							$html .= '<input type="checkbox"';
							if($row[$key] == 1) {
								$html .= 'checked="checked"';
							}
							$html .= ' disabled="disabled" />' . "\r\n";
							break;

						case 'image':
							$html .= '<a href="' . $row[$key] . '" class="fancyimage"> ' . __('View Logo', IAS_TEXTDOMAIN) . '</a>' . "\r\n";
							break;

						case 'view-url':
							$html .= '<a href="' . $row[$key] . '" class="fancyopen"> ' . $row[$key] . '</a>' . "\r\n";
							break;
						
						default:
							if(!is_null($this->editAction)) {
								$html .= '<a href="' . $this->editAction . '&id=' . $row[$this->untouchablesKey] . '">' . "\r\n";
							}
							$html .=  $row[$key] . "\r\n";
							if(!is_null($this->editAction)) {
								$html .= '</a>' . "\r\n";
							}
							break;
					}
					$html .= '</td>' . "\r\n";
				}
			$html .= '</tr>' . "\r\n";
		}
		$html .= '</tbody>' . "\r\n";
		$html .= '</table>' . "\r\n";
		// Form action buttons //
		if($this->isForm == TRUE) {
			if(!is_null($this->addAction) || !is_null($this->deleteAction)) {
				$html .= '<p>' . "\r\n";
				if(!is_null($this->addAction)) {
					$html .= '<a href="' . $this->addAction . '" class="button button-primary';
					if($this->useFancyClass == TRUE) {
						$html .= ' fancyopen';
					}
					$html .= '">' . __('Add', IAS_TEXTDOMAIN) . '</a>' . "\r\n";
				}
				if(!is_null($this->deleteAction)) {
					$html .= '<input type="submit" class="button" value="' . __('Remove Selected' , IAS_TEXTDOMAIN) . '" />' . "\r\n";
				}
				$html .= '</p>' . "\r\n";
			}
			$html .= '</form>' . "\r\n";
		}
		$this->html = $html;
	}

	private function needsPagination() {
		if( count( $this->data ) > $pagination ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
} // end of ias_table class
?>