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
		$email = $this->post('email');
		$password = $this->post('password');
		$name = $this->post('name');
		$subject = $this->post('subject');
		$message = $this->post('message');
		$to = $this->post('to');

		//TODO fazer verificacoes dos campos obrigatorios

		$domain = array_pop(explode('@', $email));
		$domain =  explode('.', $domain)[0];

		if($domain == 'gmail') {
			$config = Array(		
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.googlemail.com',
				'smtp_port' => 465,
				'smtp_user' => $email,
				'smtp_pass' => $password,
				'smtp_timeout' => '4',
				'mailtype'  => 'text', 
				'charset'   => 'utf-8'
			);
		} else {
			$this->response(array('status' => 0, 'message' => 'Email not sent. Email domain not accepted.'), 500);
		}

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");

		$this->email->from($email, $name);
		$this->email->to($to);

		$this->email->subject($subject);
		$this->email->message($message);	

		if($this->email->send())
		{
			$this->response(array('status' => 1, 'message' => 'Email sent.'), 200);
		}
		else
		{
			$this->response(array('status' => 0, 'message' => 'Email not sent.'), 500);
		}
	}
}

/* End of file email.php */
/* Location: ./application/controllers/email.php */