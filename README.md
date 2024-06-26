<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

Requisitos:
Docker instalado na máquina local.

Clonar Repositório
git clone https://github.com/mauricio-pacheco/mauricio_kan_teste.git

cd mauricio_kan_teste

Construir e Iniciar o Contêiner via Docker Compose:

docker-compose up --build

Acessar a Aplicação:

Depois que o contêiner estiver em execução, a aplicação estará acessível em http://localhost:8000.

OBS: Caso a Docker obter erros no ambiente local, clonar o projeto em um Servidor Web, com PHP 8.2 e executar 'composer install' e 'npm-install' no diretório clonado.
* Após a instalação do composer, subir os serviços:
* Backend: php artisan migrate (Subir as Tabelas no Banco de Dados)
* Configurar Webpack
npm install --save-dev vite laravel-vite-plugin
* Backend: php artisan serve
* Frontend: npm run dev

#

Documentação de Apresentação de Conclusão Projeto

Implementação

Frontend

Formulário de Upload

- Componente Formulário: Implementado com React para permitir o upload de arquivos `.csv`.

`.\mauricio_kan_teste\resources\js\components\Example.jsx`
`.\mauricio_kan_teste\resources\js\components\FileList.jsx`
`.\mauricio_kan_teste\resources\js\components\PreLoader.jsx`

- Gerenciamento de Estado: Utilização do Context API e Reducer para gerenciamento de estados e ações.

`.\mauricio_kan_teste\resources\js\components\Example.jsx`

- Atualização da Listagem: A listagem de arquivos é atualizada automaticamente após o upload bem-sucedido.

`.\mauricio_kan_teste\resources\js\components\FileList.jsx`
 
Listagem de Arquivos
- Interface Usuário: Melhorias nos componentes fornecidos para garantir uma experiência de usuário fluida e responsiva.

Backend

Processamento do Arquivo

- Endpoint de Upload: Endpoint criado para receber e processar o arquivo `.csv`.

`Route::post('/api/upload', [UploadCsvController::class, 'upload']);`
  
- Processamento Escalável: Lógica de processamento otimizada para garantir que muitos registros sejam processados em menos de 60 segundos.

`ProcessCsvFile::dispatch($path)->onQueue('high');`

- Geração de Boletos: Baseado nos dados recebidos, o sistema gera boletos de cobrança regularmente.

`.\mauricio_kan_teste\app\Jobs\ProcessCsvFile.php`
 
- Disparo de E-mails: E-mails são enviados automaticamente para os destinatários com as informações da cobrança.

`.\mauricio_kan_teste\app\Services\CsvProcessorService.php`

Testes:
- Testes e Validação: Realizar testes para garantir a confiabilidade do sistema em diferentes cenários.

`.\mauricio_kan_teste\tests\Jobs\ProcessCsvFileTest.php`

- Utilize o comando php artisan test para testes

OBS: Configurar o dados do e-mail no arquivo.env para o envio.
  
Contêinerização Docker:

- Dockerfile: Configuração do ambiente de desenvolvimento e produção para o projeto.
- docker-compose.yml: Configuração do `docker-compose` para rodar o projeto em contêineres, incluindo a aplicação e o banco de dados.
- Executar `docker-compose up --build` dentro do diretório clonado.

Conclusão:

O sistema de cobranças desenvolvido atende aos requisitos estabelecidos pela Kanastra, fornecendo uma interface de upload de arquivos `.csv`, processamento eficiente e escalável dos dados, e geração automática de boletos e e-mails. A utilização de contêineres Docker facilita a implantação e manutenção do sistema, garantindo flexibilidade e robustez.

Próximos Passos:
- Documentação Adicional: Documentar detalhadamente os endpoints da API e as configurações do Docker para facilitar futuras manutenções e atualizações.

