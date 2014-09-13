<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require(APPPATH.'libraries/REST_Controller.php');
require_once(APPPATH.'libraries/REST_Controller.php');

class Board extends REST_Controller {

	public function __construct()
    {
		parent::__construct();
        $this->load->model('key_model');
        $this->load->model('board_model');
	}

	# Cria um novo quadro de notas, retornando um board_key
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

	# Passando um board_key por parametro, deleta um board
	public function index_delete()
	{
		$key = isset($_SERVER['HTTP_KEY']) ? $_SERVER['HTTP_KEY'] : FALSE;

		if ( ! $this->key_model->_key_exists($key) || $key == FALSE )
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.', 'message' => 'Remember to pass the key: CURLOPT_HTTPHEADER, array("Accept: application/json","key: $key")'), 400);
		} else {
			if($this->board_model->deleteBoard($key) && $this->key_model->_delete_key($key))
			{
				$this->response(array('status' => 1, 'message' => 'Key deleted'));
			} else {
				$this->response(array('status' => 0, 'error' => 'Internal Server Error'), 500);
			}
		}
	}

	# Passando um board_key por parametro, retorna o board com as notas
	public function index_get()
	{
		$key = $this->get('key');

		if ( ! $this->key_model->_key_exists($key) || $key == FALSE )
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else {
			$this->response($this->board_model->getAllSubjects($key), 200);
		}
	}

	# Passando um board_key por parametro, cria uma materia, passando o nome, e a nota(opcional)
	public function subject_post()
	{

	}

	# Passando um board_key por parametro, retorna os subject_id associados as materias
	public function subject_get()
	{

	}

	# Passando um board_key e um subject_id por parametro, deleta a materia
	public function subject_delete()
	{

	}

	# Passando um board_key e um subject_id, nome e/ou nota por parametro, atualiza nome e/ou nota da materia
	public function subject_put()
	{

	}

}

/* End of file board.php */
/* Location: ./application/controllers/board.php */