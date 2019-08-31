
Tresle API
==============


Instalação
----------

 1) Baixar a última versão: [https://github.com/treslebr/laravel-api/releases](https://github.com/treslebr/laravel-api/releases)
 2) Descompactar o projeto.
 3) Acessar o projeto pelo terminal. 
 4) Executar `composer install`
 5) Criar um banco de dados (Ex: tresle)
 6) Renomear o arquivo **.env.example** (localizado na raiz do projeto) para **.env**
 7) Acessar o arquivo .env e inserir as suas configurações do banco de dados 
 

    DB_CONNECTION=mysql
    
    DB_HOST=127.0.0.1
    
    DB_PORT=3306
    
    DB_DATABASE=tresle
    
    DB_USERNAME=root
    
    DB_PASSWORD=

Executar no terminal:

8) `php artisan migrate`
9) `php artisan db:seed`
10) `php artisan key:generate`
11) `php artisan passport:install`
12) `php artisan serve`
