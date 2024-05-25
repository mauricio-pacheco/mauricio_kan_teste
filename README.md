<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

Documentação de Apresentação de Conclusão Projeto
Implementação
Frontend
Formulário de Upload
- Componente Formulário: Implementado com React para permitir o upload de arquivos `.csv`.
- Gerenciamento de Estado: Utilização do Context API e Reducer para gerenciamento de estados e ações.
- Atualização da Listagem: A listagem de arquivos é atualizada automaticamente após o upload bem-sucedido.
Listagem de Arquivos
- Histórico de Arquivos: Manutenção de um histórico de arquivos recebidos, exibido em uma tabela.
- Interface Usuário: Melhorias nos componentes fornecidos para garantir uma experiência de usuário fluida e responsiva.
Backend
Processamento do Arquivo
- Endpoint de Upload: Endpoint criado para receber e processar o arquivo `.csv`.
- Processamento Escalável: Lógica de processamento otimizada para garantir que muitos registros sejam processados em menos de 60 segundos.
- Geração de Boletos: Baseado nos dados recebidos, o sistema gera boletos de cobrança regularmente.
- Disparo de E-mails: E-mails são enviados automaticamente para os destinatários com as informações da cobrança.
 
Contêinerização Docker:
- Dockerfile: Configuração do ambiente de desenvolvimento e produção para o projeto.
- docker-compose.yml: Configuração do `docker-compose` para rodar o projeto em contêineres, incluindo a aplicação e o banco de dados.

Conclusão:

O sistema de cobranças desenvolvido atende aos requisitos estabelecidos pela Kanastra, fornecendo uma interface de upload de arquivos `.csv`, processamento eficiente e escalável dos dados, e geração automática de boletos e e-mails. A utilização de contêineres Docker facilita a implantação e manutenção do sistema, garantindo flexibilidade e robustez.

Próximos Passos:
- Testes e Validação: Realizar testes exaustivos para garantir a confiabilidade do sistema em diferentes cenários.
- Documentação Adicional: Documentar detalhadamente os endpoints da API e as configurações do Docker para facilitar futuras manutenções e atualizações.

