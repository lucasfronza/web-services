<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require(APPPATH.'libraries/REST_Controller.php');
require_once(APPPATH.'libraries/REST_Controller.php');

class Email extends REST_Controller {

	public function __construct()
    {
		parent::__construct();
	}

	# Envia um email
	public function index_post()
	{
		
	}
}

/* End of file email.php */
/* Location: ./application/controllers/email.php */