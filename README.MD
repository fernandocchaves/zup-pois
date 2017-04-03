 REST API GPS
=====================

Projeto REST API GPS.

Tecnologias utilizadas
========================

* PHP 5.6+
* PDO_SQLite

Como iniciar o projeto
========================

1. Clonar o repositório;
2. Checar as versões das tecnologias utilizadas;
3. Va para a pasta do projeto;
4. run: $ composer install;
5. run: $ php -S localhost:8090;
6. Execute em outra sessão do terminal - LIST: $ curl http://localhost:8090/ -H 'Content-Type: application/json'
7. Execute em outra sessão do terminal - ADD: $ curl -s --request POST http://localhost:8090/ -d '{"name":"teste","latitude":"10", "longitude": "10"}' -H 'Content-Type: application/json'
8. Execute em outra sessão do terminal - EDIT: $ curl -s --request PUT http://localhost:8090/ -d '{"name":"teste","latitude":"10", "longitude": "10"}' -H 'Content-Type: application/json'
9. Execute em outra sessão do terminal - REMOVE: $ curl -s --request DELETE http://localhost:8090/ -H 'Content-Type: application/json'

© Fernando Cezar Chaves
