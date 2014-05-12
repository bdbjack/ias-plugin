<?php
/**
 * IAS Customer Notes API
 * Add notes for the broker to view to the customer
 */
 class ias_customer_notes_api {
	private $subject = NULL;
	private $content = NULL;
	private $employeeId = 0;
	private $clientId = NULL;
	private $broker = NULL;

	function __construct( $client, $broker, $content = 'Content of message from IAS Site' , $subject = 'System Message' ) {
		$this->clientId = $client;
		$this->broker = $broker;
		$this->content = $content;
		$this->subject = $subject;
	}

	function write_note() {
		if( !is_numeric( $this->clientId ) || !is_numeric( $this->broker ) ) {
			return FALSE;
		}
		$query = array(
			'MODULE' => 'Call',
			'COMMAND' => 'add',
			'subject' => $this->subject,
			'content' => $this->content,
			'employeeId' => $this->employeeId,
			'clientId' => $this->clientId,
		);
		$results = ias_so_api::return_query( $this->broker , $query );
		if( !isset($results['connection_status']) || !isset($results['operation_status']) || $results['operation_status'] !== 'successful' ) {
			$rm_error = '';
			$rm_error .= 'The following query:' . "\r\n";
			$rm_error .= '<pre>' . "\r\n";
			$rm_error .= print_r( $query , TRUE ) . "\r\n";
			$rm_error .= '</pre>' . "\r\n";
			$rm_error .= 'returned with the following results:' . "\r\n";
			$rm_error .= '<pre>' . "\r\n";
			$rm_error .= print_r( $results , true ) . "\r\n";
			$rm_error .= '</pre>' . "\r\n";
			report_ias_bug( 'API Error on ' . get_bloginfo('wpurl') , $rm_error );
			return FALSE;
		}
		else {
			return TRUE;
		}
	}
	public static function note( $client , $broker , $content , $subject ) {
		$class = __CLASS__;
		$obj = new $class( $client , $broker , $content , $subject );
		$obj->write_note();
	}
 } // end of ias_customer_notes_api
?>