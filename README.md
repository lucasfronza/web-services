web-services
============
#Checklist
- [ ] email/index_post()
- [x] Quadro de Notas

#Planejamento Notificações por email

/email
	- index_post(), POST: recebe email de envio, senha, mensagem, título, email de destino e envia o email

#Planejamento Quadro de Notas de acordo com as funções do controller

/board
	- index_post(), POST: cria um novo quadro de notas, retornando um board_id

/board
	- index_delete(), passando um board_id por parametro, DELETE: deleta um board
	- index_get(), passando um board_id por parametro, GET: retorna o board com as notas

/board/subject
	- subject_post(), passando um board_id por parametro, POST: cria uma materia, passando o nome, e a nota(opcional)
	- subject_get(), passando um board_id e um subject_id por parametro, GET: retorna os dados associados à materia

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
	- GET: retorna os dados associados à materia

/board/{board_id}/subject/{subject_id}
	- DELETE: deleta a materia
	- PUT: atualiza nome e/ou nota da materia
