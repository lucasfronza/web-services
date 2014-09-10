web-services
============
#Planejamento Quadro de Notas

/board
	- POST: cria um novo quadro de notas, retornando um board_id

/board/board_id
	- DELETE: deleta um board
	- GET: retorna o board com as notas

/board/board_id/subject
	- POST: cria uma materia, passando o nome, e a nota(opcional)
	- GET: retorna os subject_id associados as materias

/board/board_id/subject/subject_id
	- DELETE: deleta a materia
	- PUT: atualiza nome e/ou nota da materia
