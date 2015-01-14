<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require(APPPATH.'libraries/REST_Controller.php');
require_once(APPPATH.'libraries/REST_Controller.php');

class Attendance extends REST_Controller {

	public function __construct()
    {
		parent::__construct();
        $this->load->model('key_model');
        $this->load->model('attendance_model');
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
			if($this->attendance_model->delete($key) && $this->key_model->_delete_key($key))
			{
				$this->response(array('status' => 1, 'message' => 'Key deleted'));
			} else {
				$this->response(array('status' => 0, 'error' => 'Internal Server Error'), 500);
			}
		}
	}

	# Passando um KEY por parametro, retorna o Quadro com os usuários
	public function index_get()
	{
		$key = $this->get('key');

		if ( ! $this->key_model->_key_exists($key) || $key == FALSE )
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else {
			$this->response($this->attendance_model->getAllUsers($key), 200);
		}
	}

	# Cria um usuário, passando por parâmetro um KEY, um identificador do usuário,
	# 	presença(opcional) e ausência(opcional)
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
				$this->response(array('status' => 1, 201)); // 201 = Created
			} else {
				$this->response(array('status' => 0, 'error' => 'Could not save the subject.'), 500); // 500 = Internal Server Error
			}
		}
	}

	# Passando um KEY e um identificador do usuário, retorna os dados associados ao mesmo
	public function user_get()
	{
		$key 	= $this->get('key');
		$user 	= $this->get('user');

		if ( ! $this->key_model->_key_exists($key) || $key == FALSE )
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else if ($subject_id == FALSE) {
			$this->response(array('status' => 0, 'error' => 'User identifier not found.'), 400);
		} else {
			$obj = new stdClass();
			$obj->key = $key;
			$obj->user = $user;

			if (($attendance = $this->attendance_model->getUser($obj)) == FALSE)
			{
				$this->response(array('status' => 0, 'error' => 'Invalid user identifier.'), 400);
			} else {
				$this->response($attendance, 200);
			}
		}
	}

	# Passando um KEY e o identificador do usuário, deleta o mesmo
	public function user_delete()
	{
		$key = isset($_SERVER['HTTP_KEY']) ? $_SERVER['HTTP_KEY'] : FALSE;
		$user = isset($_SERVER['HTTP_USER']) ? $_SERVER['HTTP_USER'] : FALSE;

		if ( ! $this->key_model->_key_exists($key) || $key == FALSE )
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.', 'message' => 'Remember to pass the key: CURLOPT_HTTPHEADER, array("Accept: application/json","key: $key")'), 400);
		} else if ($subject_id == FALSE) {
			$this->response(array('status' => 0, 'error' => 'Missing user identifier.', 'message' => 'Remember to pass the user: CURLOPT_HTTPHEADER, array("Accept: application/json","user: $user")'), 400);
		} else {
			$obj = new stdClass();
			$obj->key = $key;
			$obj->user = $user;

			if (($attendance = $this->attendance_model->getUser($obj)) == FALSE)
			{
				$this->response(array('status' => 0, 'error' => 'Invalid user identifier.'), 400);
			} else {
				$this->attendance_model->deleteUser($obj);
				$this->response(array('status' => 1, 'message' => 'User deleted'));
			}
		}
	}

	# Passando um KEY e um idendificador do usuário, presença(opcional) e ausência(opcional),
	# 	atualiza os dados associados ao mesmo
	public function user_put()
	{
		$key 		= $this->put('key');
		$user 		= $this->put('user');
		$attendance = $this->put('attendance');
		$absence 	= $this->put('absence');

		if (!$this->key_model->_key_exists($key) || $key == FALSE)
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else if ($user == FALSE) {
			$this->response(array('status' => 0, 'error' => 'User identifier not found.'), 400);
		} else {
			$obj = new stdClass();
			$obj->key = $key;
			$obj->user = $user;

			$ret = $this->attendance_model->getUser($obj);
			if(!isset($ret))
			{
				$this->response(array('status' => 0, 'error' => 'User identifier not matching.'), 400);
			}
			if ($attendance != FALSE)
			{
				$obj->attendance = $attendance;
			}
			if ($absence != FALSE)
			{
				$obj->absence = $absence;
			}
			if ($this->attendance_model->updateUser($obj))
			{
				$this->response(array('status' => 1, 200));
			} else {
				$this->response(array('status' => 0, 'error' => 'Could not save changes.'), 500); // 500 = Internal Server Error
			}
		}
	}

	# Passando um KEY e um idendificador do usuário, presença ou ausência,
	#	soma 1 à presença ou ausência
	public function userattendance_put()
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

			$user_ = $this->attendance_model->getUser($obj);
			if (!isset($user_))
			{
				$this->response(array('status' => 0, 'error' => 'User identifier not matching.'), 400);
			}
			if ($attendance == 1)
			{
				$obj->attendance = $user_->attendance + 1;
			}
			if ($absence == 1)
			{
				$obj->absence = $user_->absence + 1;
			}
			if ($this->attendance_model->updateUser($obj))
			{
				$this->response(array('status' => 1, 200));
			} else {
				$this->response(array('status' => 0, 'error' => 'Could not save changes.'), 500); // 500 = Internal Server Error
			}
		}
	}
}

/* End of file attendance.php */
/* Location: ./application/controllers/attendance.php */