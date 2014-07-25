<?php
class Tools extends CI_Controller {

	public function __construct () {
		parent::__construct();
		
		$this -> load -> library('Timebooking');
		
	}
	
	public function checkServiceStaus(){
		
		if(!$this->timebooking->checkServiceStaus()){
			$fl = fopen('.block', 'a');
			fwrite($fl, date("F j, Y, g:i:s a") . "\n" );
			fclose($fl);
			return false;
		} else {
			if( is_file( '.block' ) ){
				rename('.block', '.block.'. date('Ymd_His') );
			}
			return true;
		}
	}

}
?>
