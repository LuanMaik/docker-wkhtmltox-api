# Docker Container - Wkhtmltox API
Possibilita a criação de pdfs ou imagens (png/jpg), a partir de parâmetros enviados via JSON para o conteiner do serviço.
A api utiliza o wkhtmltopdf através de linhas de comando para realizar a conversão do html para pdf/image.
https://cloud.docker.com/u/luanmaik/repository/docker/luanmaik/wkhtmltox-api


## Exemplo de formato de dados da requisição em json ##
```json
{
       "format": "PDF",
       "pages": [
           "<html><body><h1>Page number 1</h1></body></html>",
           "https://google.com",
           "<html><body><h1>Page number 3</h1></body></html>"
       ],
       "options": {
           "page-size"   : "A4",
           "margin-top"  : "20mm",
           "orientation" : "Portrait",
           "header-html" : "<!DOCTYPE html><html><head></head><body><b>This is the header</b></body></html>",
           "footer-html" : "<p>This is the footer</p>"
       }
}
```
Para saber quais são todas as possibilidades de parâmetros 'options', veja a documentação oficial do WKHTMLTOPDF: https://wkhtmltopdf.org/usage/wkhtmltopdf.txt


## Configuração da requisição ##
É obrigatório que a requisição para a api seja feita utilizando o `Content-Type` igual a `application/json`.


## Respostas de erro ##
Os erros de parâmetros que puderem ser indentificados pela aplicação terá o status 400, e os demais erros, como o do retorno do comando para o binário do wkhtmltopdf, terão o status 500, porém todos serão respondidos no formato json.
Ex:
```json
{
       "success": false,
       "message": "It's necessary set the param 'pages' as array."
}
```
