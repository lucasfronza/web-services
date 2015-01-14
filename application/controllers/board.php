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
			$this->response($this->board_model->getAllUsers($key), 200);
		}
	}

	# Passando um board_key por parametro, cria um usuario, passando o nome, e a nota(opcional)
	public function user_post()
	{
		$key = $this->post('key');
		$user = $this->post('user');
		$score = $this->post('score');

		if (!$this->key_model->_key_exists($key) || $key == FALSE)
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else if ($user == FALSE) {
			$this->response(array('status' => 0, 'error' => 'User identifier not found.'), 400);
		} else {
			$obj = new stdClass();
			$obj->key = $key;
			$obj->user = $user;

			if ($score != FALSE)
			{
				$obj->score = $score;
			}
			if ($id = $this->board_model->insertUser($obj))
			{
				$this->response(array('status' => 1, 'id' => $id), 201); // 201 = Created
			} else {
				$this->response(array('status' => 0, 'error' => 'Could not save the user.'), 500); // 500 = Internal Server Error
			}
		}
	}

	# Passando um board_key e um id por parametro, retorna os dados associados ao usuario
	public function user_get()
	{
		$key = $this->get('key');
		$id = $this->get('id');

		if ( ! $this->key_model->_key_exists($key) || $key == FALSE )
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else if ($id == FALSE) {
			$this->response(array('status' => 0, 'error' => 'Missing id'), 400);
		} else {
			$obj = new stdClass();
			$obj->key = $key;
			$obj->id = $id;

			if (($user = $this->board_model->getUser($obj)) == FALSE)
			{
				$this->response(array('status' => 0, 'error' => 'Invalid id'), 400);
			} else {
				$this->response($user, 200);
			}
		}
	}

	# Passando um board_key e um id por parametro, deleta o usuario
	public function user_delete()
	{
		$key = isset($_SERVER['HTTP_KEY']) ? $_SERVER['HTTP_KEY'] : FALSE;
		$id = isset($_SERVER['HTTP_ID']) ? $_SERVER['HTTP_ID'] : FALSE;

		if ( ! $this->key_model->_key_exists($key) || $key == FALSE )
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.', 'message' => 'Remember to pass the key: CURLOPT_HTTPHEADER, array("Accept: application/json","key: $key")'), 400);
		} else if ($id == FALSE) {
			$this->response(array('status' => 0, 'error' => 'Missing id', 'message' => 'Remember to pass the id: CURLOPT_HTTPHEADER, array("Accept: application/json","id: $id")'), 400);
		} else {
			$obj = new stdClass();
			$obj->key = $key;
			$obj->id = $id;

			if (($user = $this->board_model->getUser($obj)) == FALSE)
			{
				$this->response(array('status' => 0, 'error' => 'Invalid id'), 400);
			} else {
				$this->board_model->deleteUser($obj);
				$this->response(array('status' => 1, 'message' => 'User deleted'));
			}
		}
	}

	# Passando um board_key e um id, nome e/ou nota por parametro, atualiza nome e/ou nota da usuario
	public function user_put()
	{
		$key = $this->put('key');
		$id = $this->put('id');
		$user = $this->put('user');
		$score = $this->put('score');

		if (!$this->key_model->_key_exists($key) || $key == FALSE)
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else if ($id == FALSE) {
			$this->response(array('status' => 0, 'error' => 'Missing id'), 400);
		} else {
			$obj = new stdClass();
			$obj->key = $key;
			$obj->id = $id;

			if ($score != FALSE)
			{
				$obj->score = $score;
			}
			if ($user != FALSE)
			{
				$obj->user = $user;
				$ret = $this->board_model->getUser($obj);
				if(!isset($ret))
				{
					$this->response(array('status' => 0, 'error' => 'id not matching.'), 400);
				}
			}

			if ($this->board_model->updateUser($obj))
			{
				$this->response(array('status' => 1, 'message' => 'User updated.'), 200);
			} else {
				$this->response(array('status' => 0, 'error' => 'Could not save the user.'), 500); // 500 = Internal Server Error
			}
		}
	}

}

/* End of file board.php */
/* Location: ./application/controllers/board.php */