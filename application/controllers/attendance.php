<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require(APPPATH.'libraries/REST_Controller.php');
require_once(APPPATH.'libraries/REST_Controller.php');

class Attendance extends REST_Controller {

	public function __construct()
    {
		parent::__construct();
        $this->load->model('key_model');
	}

	# Cria um novo Quadro de Presença, retornando um KEY
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

	# Passando um KEY por parametro, deleta um Quadro de Presença
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

	# Passando um KEY por parametro, retorna o Quadro com os alunos
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

	# Passando um KEY por parametro, cria um usuário, passando o identificador e a frequência(opcional)
	public function user_post()
	{
		$key 		= $this->post('key');
		$user 		= $this->post('user');
		$attendance = $this->post('attendance');
		$absence 	= $this->post('absence');

		if (!$this->key_model->_key_exists($key) || $key == FALSE)
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else if ($user == FALSE) {
			$this->response(array('status' => 0, 'error' => 'User identifier not found.'), 400);
		} else {
			$obj = new stdClass();
			$obj->key = $key;
			$obj->user = $user;

			if ($attendance != FALSE)
			{
				$obj->attendance = $attendance;
			}
			if ($absence != FALSE)
			{
				$obj->absence = $absence;
			}
			if ($this->attendance_model->insertUser($obj))
			{
				$this->response(array('status' => 1, 201); // 201 = Created
			} else {
				$this->response(array('status' => 0, 'error' => 'Could not save the subject.'), 500); // 500 = Internal Server Error
			}
		}
	}

	# Passando um board_key e um subject_id por parametro, retorna os dados associados à materia
	public function subject_get()
	{
		$key = $this->get('key');
		$subject_id = $this->get('subject_id');

		if ( ! $this->key_model->_key_exists($key) || $key == FALSE )
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else if ($subject_id == FALSE) {
			$this->response(array('status' => 0, 'error' => 'Missing subject_id'), 400);
		} else {
			$obj = new stdClass();
			$obj->key = $key;
			$obj->subject_id = $subject_id;

			if (($subject = $this->board_model->getSubject($obj)) == FALSE)
			{
				$this->response(array('status' => 0, 'error' => 'Invalid subject_id'), 400);
			} else {
				$this->response($subject, 200);
			}
		}
	}

	# Passando um board_key e um subject_id por parametro, deleta a materia
	public function subject_delete()
	{
		$key = isset($_SERVER['HTTP_KEY']) ? $_SERVER['HTTP_KEY'] : FALSE;
		$subject_id = isset($_SERVER['HTTP_ID']) ? $_SERVER['HTTP_ID'] : FALSE;

		if ( ! $this->key_model->_key_exists($key) || $key == FALSE )
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.', 'message' => 'Remember to pass the key: CURLOPT_HTTPHEADER, array("Accept: application/json","key: $key")'), 400);
		} else if ($subject_id == FALSE) {
			$this->response(array('status' => 0, 'error' => 'Missing subject_id', 'message' => 'Remember to pass the subject_id: CURLOPT_HTTPHEADER, array("Accept: application/json","id: $id")'), 400);
		} else {
			$obj = new stdClass();
			$obj->key = $key;
			$obj->subject_id = $subject_id;

			if (($subject = $this->board_model->getSubject($obj)) == FALSE)
			{
				$this->response(array('status' => 0, 'error' => 'Invalid subject_id'), 400);
			} else {
				$this->board_model->deleteSubject($obj);
				$this->response(array('status' => 1, 'message' => 'Subject deleted'));
			}
		}
	}

	# Passando um board_key e um subject_id, nome e/ou nota por parametro, atualiza nome e/ou nota da materia
	public function subject_put()
	{
		$key = $this->put('key');
		$subject_id = $this->put('subject_id');
		$subject = $this->put('subject');
		$score = $this->put('score');

		if (!$this->key_model->_key_exists($key) || $key == FALSE)
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else if ($subject_id == FALSE) {
			$this->response(array('status' => 0, 'error' => 'Missing subject_id'), 400);
		} else {
			$obj = new stdClass();
			$obj->key = $key;
			$obj->subject_id = $subject_id;

			if ($score != FALSE)
			{
				$obj->score = $score;
			}
			if ($subject != FALSE)
			{
				$obj->subject = $subject;
				if(empty($this->board_model->getSubject($obj)))
				{
					$this->response(array('status' => 0, 'error' => 'Subject_id not matching.'), 400);
				}
			}

			if ($this->board_model->updateSubject($obj))
			{
				$this->response(array('status' => 1, 'message' => 'Subject updated.'), 200);
			} else {
				$this->response(array('status' => 0, 'error' => 'Could not save the subject.'), 500); // 500 = Internal Server Error
			}
		}
	}

}

/* End of file board.php */
/* Location: ./application/controllers/board.php */