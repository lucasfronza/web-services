<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/REST_Controller.php');

class Quiz extends REST_Controller {

	public function __construct()
    {
		parent::__construct();
        $this->load->model('key_model');
        $this->load->model('quiz_model');
	}

	# Cria um novo Quiz, retornando um key
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

	# Passando key, questao, alternativas e resposta, atualiza o Quiz
	public function subject_put()
	{
		$key = $this->put('key');

		$obj = new stdClass();
		$obj->key = $key;
		$obj->question = $this->put('question');
		$obj->alternative1 = $this->put('alternative1');
		$obj->alternative2 = $this->put('alternative2');
		$obj->alternative3 = $this->put('alternative3');
		$obj->alternative4 = $this->put('alternative4');
		$obj->alternative5 = $this->put('alternative5');
		$obj->correctAnswer = $this->put('correctAnswer');

		if (!$this->key_model->_key_exists($key) || $key == FALSE)
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else {

			if ($this->quiz_model->update($obj))
			{
				$this->response(array('status' => 1, 'message' => 'Quiz updated.'), 200);
			} else {
				$this->response(array('status' => 0, 'error' => 'Could not save the quiz.'), 500); // 500 = Internal Server Error
			}
		}
	}

	# Passando um key por parametro, deleta um Quiz
	public function index_delete()
	{
		$key = isset($_SERVER['HTTP_KEY']) ? $_SERVER['HTTP_KEY'] : FALSE;

		if ( ! $this->key_model->_key_exists($key) || $key == FALSE )
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.', 'message' => 'Remember to pass the key: CURLOPT_HTTPHEADER, array("key: $key")'), 400);
		} else {
			if($this->quiz_model->delete($key) && $this->key_model->_delete_key($key))
			{
				$this->response(array('status' => 1, 'message' => 'Quiz deleted'));
			} else {
				$this->response(array('status' => 0, 'error' => 'Internal Server Error'), 500);
			}
		}
	}

	# Passando um key por parametro, retorna o Quiz com pergunta, alternativas e respostas
	public function index_get()
	{
		$key = $this->get('key');

		if ( ! $this->key_model->_key_exists($key) || $key == FALSE )
		{
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		} else {
			$this->response($this->quiz_model->get($key), 200);
		}
	}

}

/* End of file quiz.php */
/* Location: ./application/controllers/quiz.php */