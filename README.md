web-services
============
#Checklist
- [x] index_post()
- [ ] index_delete()
- [x] index_get()
- [ ] subject_post()
- [ ] subject_get()
- [ ] subject_delete()
- [ ] subject_put()

#Planejamento Quadro de Notas de acordo com as funções do controller

/board
	- index_post(), POST: cria um novo quadro de notas, retornando um board_id

/board
	- index_delete(), passando um board_id por parametro, DELETE: deleta um board
	- index_get(), passando um board_id por parametro, GET: retorna o board com as notas

/board/subject
	- subject_post(), passando um board_id por parametro, POST: cria uma materia, passando o nome, e a nota(opcional)
	- subject_get(), passando um board_id por parametro, GET: retorna os subject_id associados as materias

/board/subject
	- subject_delete(), passando um board_id e um subject_id por parametro, DELETE: deleta a materia
	- subject_put(), , passando um board_id e um subject_id, nome e/ou nota por parametro, PUT: atualiza nome e/ou nota da materia


#Planejamento Quadro de Notas

/board
	- POST: cria um novo quadro de notas, retornando um board_id

/board/{board_id}
	- DELETE: deleta um board
	- GET: retorna o board com as notas

/board/{board_id}/subject
	- POST: cria uma materia, passando o nome, e a nota(opcional)
	- GET: retorna os subject_id associados as materias

/board/{board_id}/subject/{subject_id}
	- DELETE: deleta a materia
	- PUT: atualiza nome e/ou nota da materia
