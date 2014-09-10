<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ws extends CI_Controller {

	public function board($board_id = 0, $attribute = '', $attribute_id = 0)
	{
		echo "id = ".$board_id.", subject = ".$attribute.", subject_id = ".$attribute_id."<br>";

		settype($board_id, "integer");
		settype($attribute, "string");
		if($board_id == 0 && gettype($board_id) == 'integer') { // foi requisitado: /board
			//POST: cria um novo quadro de notas, retornando um board_id
			echo "foi requisitado: /board";
		} else if($attribute == '') { // foi requisitado: /board/{board_id}
			//DELETE: deleta um board
			//GET: retorna o board com as notas
			echo "foi requisitado: /board/'board_id' =".$board_id."/";
		} else if($attribute_id == 0 && $attribute == 'subject') { // foi requisitado: /board/{board_id}/attribute
			//POST: cria uma materia, passando o nome, e a nota(opcional)
			//GET: retorna os subject_id associados as materias
			echo "foi requisitado: /board/'board_id'=".$board_id."/subject/";
		} else if($attribute_id != 0 && $attribute == 'subject') { // foi requisitado: /board/{board_id}/attribute/{attribute_id}
			//DELETE: deleta a materia
			//PUT: atualiza nome e/ou nota da materia
			echo "foi requisitado: /board/'board_id' =".$board_id."/subject/"."'subject_id'=".$attribute_id;
		}
		echo "<br>id = ".$board_id.", subject = ".$attribute.", subject_id = ".$attribute_id;
	}

}

/* End of file ws.php */
/* Location: ./application/controllers/ws.php */