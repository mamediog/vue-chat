# Vue chat
Aplicativo Web similar ao Whatsapp Web. O Intuito desse projeto é ter uma experiência completa de desenvolvimento de um whatsapp web com diferentes tecnologias. O Projeto é totalmente educativo e sem fins lucrativos. Tiro do meu tempo livre para aprender e aplicar novas funcionalidades.

## Funcionamento
Após clonar o projeto, basta executar:
- No back-end:
    * `composer install`
    * configure seu arquivo .env apontando para seu banco de dados em MongoDB configurado no arquivo */config/database.php*. Dúvidas? Aqui vai uma ajudinha.. https://stackoverflow.com/questions/44979599/connect-laravel-jenssegers-to-mongodb-atlas-cluster.
    * Rode o comando `nohup php artisan websocket:init > websocket.log &` para executar o serviço de websocket
    * rode `php -S 0.0.0.0:{porta} -t public` para iniciar seu back-end.

    Segue abaixo o arquivo .env de exemplo do back-end.
    
       APP_NAME=LumenAPP_ENV=local  
       APP_KEY=  
       APP_DEBUG=true  
       APP_URL=http://localhost:5050  
       APP_TIMEZONE=UTC  

       FRONT_URL=http://localhost:9090  

       #MAIL_DRIVER=smtp  
       #MAIL_HOST=smtp.gmail.com  
       #MAIL_PORT=465  
       #MAIL_USERNAME=  
       #MAIL_PASSWORD=  
       #MAIL_ENCRYPTION=ssl  
       #MAIL_FROM_ADDRESS=  
       #MAIL_FROM_NAME=  

       LOG_CHANNEL=stack  
       LOG_SLACK_WEBHOOK_URL=  

       DB_CONNECTION=seu_mongo_db  
       #DB_HOST=localhost  
       #DB_PORT=27017  
       #DB_DATABASE=  
       #DB_USERNAME=  
       #DB_PASSWORD=  

       CACHE_DRIVER=file  
       QUEUE_CONNECTION=database  

       JWT_SECRET=  

- No front-end
    * Rode o comando `yarn` para instalar as dependencias
 
    * Configure seu arquivo .env.development como mostra abaixo:

       VUE_APP_API=http://localhost:5050  
       VUE_APP_API_TIMEOUT=10000  
    
    * arquivo .env:  

         VUE_APP_I18N_LOCALE=pt  
         VUE_APP_I18N_FALLBACK_LOCALE=pt  
         BASE_URL=/
         
    
    * Para iniciar o projeto basta executar o comando `yarn serve --port 9090`, lembrando que `9090` é a porta que estamos utilizando no exemplo do arquivo *.env* do *back-end*.

## Sprint
- Interface Replicada Whatsappweb (FEITO)
- API + BD Mongo (FEITO)
- Criação de cadastro (FEITO)
- Login + Autenticação por token (FEITO)
- Adicionar novo amigo (FEITO)
- Iniciar uma nova conversa (FEITO)
- Enviar mensagens (FEITO)
- Listar Mensagens (FEITO)
- Aplicar WEBSOCKET (FEITO)
- Correções de bugs (EM DESENVOLVIMENTO)
- Aplicar novas funcionalidades e melhorias (NA FILA)

<img src="https://user-images.githubusercontent.com/19272954/116827217-49edb700-ab6e-11eb-8ddf-f4b9f1eb0c8f.png" width="70%">
<img src="https://user-images.githubusercontent.com/19272954/116827224-4d813e00-ab6e-11eb-8149-a622a5b57694.png" width="70%">
<img src="https://user-images.githubusercontent.com/19272954/116827226-4f4b0180-ab6e-11eb-9b70-682a8328f27e.png" width="70%">
<img src="https://user-images.githubusercontent.com/19272954/116827228-507c2e80-ab6e-11eb-89c9-025cced284f2.png" width="70%">
<img src="https://user-images.githubusercontent.com/19272954/116827229-51ad5b80-ab6e-11eb-8e9f-5ae7d5f198c0.png" width="70%">


