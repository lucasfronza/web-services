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

	}

	# Passando um board_key por parametro, retorna o board com as notas
	public function index_get()
	{
		$key = $this->get('key');
		//var_dump($key);

		// Does this key even exist?
		if ( ! $this->key_model->_key_exists($key) || $key == FALSE)
		{
			// NOOOOOOOOO!
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else {
			echo 'dfafs';
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