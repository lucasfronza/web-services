<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/REST_Controller.php');

class Quiz extends REST_Controller {

	public function __construct()
    {
		parent::__construct();
        $this->load->model('key_model');
        $this->load->model('quiz_model');
	}

	# Cria um novo quiz, retornando um key
	public function index_post()
	{
		// Build a new key
		$key = $this->key_model->_generate_key();

		// Insert the new key
		if ($this->key_model->_insert_key($key, array()))
		{
			$this->response(array('status' => 1, 'key' => $key), 201); // 201 = Created
		}

		else
		{
			$this->response(array('status' => 0, 'error' => 'Could not save the key.'), 500); // 500 = Internal Server Error
		}
	}

	# Passando um key por parametro, deleta um quiz
	public function index_delete()
	{
		$key = isset($_SERVER['HTTP_KEY']) ? $_SERVER['HTTP_KEY'] : FALSE;

		if ( ! $this->key_model->_key_exists($key) || $key == FALSE )
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.', 'message' => 'Remember to pass the key: CURLOPT_HTTPHEADER, array("Accept: application/json","key: $key")'), 400);
		} else {
			/*if($this->board_model->deleteBoard($key) && $this->key_model->_delete_key($key))
			{
				$this->response(array('status' => 1, 'message' => 'Key deleted'));
			} else {
				$this->response(array('status' => 0, 'error' => 'Internal Server Error'), 500);
			}*/
		}
	}

	# Passando um key por parametro, retorna o quiz com as perguntas e respostas
	public function index_get()
	{
		$key = $this->get('key');

		/*if ( ! $this->key_model->_key_exists($key) || $key == FALSE )
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else {
			$this->response($this->board_model->getAllSubjects($key), 200);
		}*/
	}

}

/* End of file quiz.php */
/* Location: ./application/controllers/quiz.php */