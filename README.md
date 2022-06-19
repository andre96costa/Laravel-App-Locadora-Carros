# APP LOCADORA CARROS
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.JS](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D)
![Bootstrap](https://img.shields.io/badge/bootstrap-%23563D7C.svg?style=for-the-badge&logo=bootstrap&logoColor=white)
![GitHub](https://img.shields.io/badge/github-%23121011.svg?style=for-the-badge&logo=github&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Udemy](https://img.shields.io/badge/Udemy-A435F0?style=for-the-badge&logo=Udemy&logoColor=white)


Projeto para estudos desenvolvido no curso [Desenvolvimento Web Avançado 2022 com PHP, Laravel e Vue.JS
](https://www.udemy.com/course/curso-completo-do-desenvolvedor-laravel/) ministrado pelo Jorge sant ana. Este projeto é uma aplicação full-stack desenvolvida com Laravel e VueJs. Trata-se de uma aplicação de locadora de carros. É possível realizar varias operações como cadastro de marcas, modelos, clientes, carros e realizar a locação de um carro. O projeto foi realizado em 2 etapas, a criação de uma API Rest com Laravel e o desenvolvimento das telas com VueJS, tudo integrado no mesmo projeto atraves do pacote laravel/ui. Outros recursos implementados no projeto foram, autorização utilizando JWT e o desing pattern repository.

## Rodando o projeto
1. Cloar o projeto usando o git
2. Instalar as dependencias usando o composer
    ```bash
    composer install
    ```
3. Instalar as dependencias do NPM
    ```bash
    npm install
    ```
4. Criar o arquivo .env na rais do projeto, configurar os acessos ao banco de dados.
5. Gerar a chave da aplicação laravel
    ```bash
    php artisan key:generate
    ```
6. Gerar a chave JWT para o projeto
    ```bash
    php artisan jwt:secret 
    ```
7. Executar as migrations
    ```bash
    php artisan migrate
    ```
8. Criar um link simbolico
    ```bash
    php artisan storage:link
    ```
## Erro no primeiro acesso!
Ao configurar o projeto como especificado a cima, será necessario criar um usúario para realizar acesso a plataforma, quando o usúario é criado o sistema redireciona para a página home do projeto, com o usúario já logado. O problema pode acontecer da seguinte forma, ao tentar realizar uma requisição ao servidor o token gerado para o usúario pode não ser reconhecido pelo servidor. Para poder utilizar a plicação após criar uma conta, fassa logout e login novamente, assim o token será gerado novamente. Esse procedimento só é necessario na criação da conta.
## Certificado de conclusão do curso
> [Centificado conclusão](https://www.udemy.com/certificate/UC-b63250ab-388b-4bb5-9894-7e4dd0710ed3/)

