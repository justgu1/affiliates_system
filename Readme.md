# Affiliates System

Sistema de gestão de afiliados desenvolvido com Laravel 11 no backend e Vue.js no frontend. O sistema permite gerenciar afiliados, cadastrar comissões, alterar status, editar informações e exibir dados paginados.

## Estrutura do Projeto

### Backend
- **Tecnologia**: Laravel 11
- **Autenticação**: Sanctum API
- **Banco de Dados**: MySQL (ou outro de sua escolha)
- **Roteamento**: API Restful para gerenciamento de afiliados e usuários
- **Migrações e Seeders**: Inclusão de tabelas e criação de dados iniciais
- **Autorização**: Controle de acesso baseado em roles (admin) para operações críticas

### Frontend
- **Tecnologia**: Vue.js
- **Estado**: Gerenciamento de estado local para dados de afiliados e usuários
- **Biblioteca de UI**: Tailwind CSS
- **Data-binding**: Vue 3 com v-model
- **Componente de DataPicker**: VueDatePicker para escolher datas de nascimento

## Funcionalidades

### Backend
1. **Gerenciamento de Afiliados**:
    - Listagem de afiliados com paginação.
    - Edição de informações dos afiliados.
    - Alteração do status dos afiliados (Ativo/Inativo).
    - Cadastro de comissões para afiliados.
  
2. **Modelo de Dados**:
    - Usuário (User) com nome e e-mail.
    - Afiliado (Affiliate) com nome, e-mail, status e comissão.
  
3. **Autenticação**:
    - Utilização do Sanctum para autenticação e gerenciamento de sessões API.

### Frontend
1. **Dashboard**:
    - Exibição de dados dos afiliados em uma tabela paginada.
    - Opções de filtro por nome e status.
    - Formulários para editar informações dos afiliados.
    - Popup de confirmação para alteração de status.
  
2. **Gerenciamento de Dados**:
    - Criação de afiliados através de um formulário.
    - Edição de dados de afiliados.
    - Alteração de status de afiliados.

## Backend: Laravel Setup

### Requisitos

- PHP 8.1 ou superior
- Composer
- Node.js (para compilação de assets, se necessário)
- MySQL (ou outro banco de dados relacional de sua escolha)

### Instalação

1. Clone o repositório:
    ```bash
    git clone https://github.com/justgui1/affiliates_system.git
    ```

2. Navegue até o diretório do backend:
    ```bash
    cd affiliates_system/backend
    ```

3. Instale as dependências do Composer:
    ```bash
    composer install
    ```

4. Crie o arquivo `.env` a partir do `.env.example`:
    ```bash
    cp .env.example .env
    ```

5. Gere a chave de aplicação do Laravel:
    ```bash
    php artisan key:generate
    ```

6. Configure seu banco de dados no arquivo `.env`:
    - Defina `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD`.

7. Rode as migrações para criar as tabelas no banco de dados:
    ```bash
    php artisan migrate
    ```

8. (Opcional) Rode os seeders para criar dados iniciais:
    ```bash
    php artisan db:seed --class=UserSeeder
    ```

9. Inicie o servidor de desenvolvimento:
    ```bash
    php artisan serve
    ```

    O backend estará rodando em `http://localhost:8000`.

### Rotas da API

- **POST /api/login**: Realiza o login de um usuário.
- **POST /api/register**: Realiza o cadastro de um novo usuário.
- **POST /api/logout**: Realiza o logout de um usuário autenticado.
- **GET /api/verify**: Verifica se o usuário está autenticado.

#### Rotas de Usuário

- **GET /api/user**: Lista os usuários.
- **GET /api/user/{id}**: Exibe detalhes de um usuário específico.
- **PUT /api/user/{id}**: Atualiza os dados de um usuário.
- **POST /api/user/{id}/status**: Altera o status de um usuário.

#### Rotas de Afiliado

- **GET /api/affiliate**: Lista os afiliados.
- **GET /api/affiliate/{id}**: Exibe detalhes de um afiliado específico.
- **PUT /api/affiliate/{id}**: Atualiza os dados de um afiliado.
- **POST /api/affiliate/{id}/status**: Altera o status de um afiliado.
- **POST /api/affiliate/{id}/commissions**: Adiciona uma comissão a um afiliado.

#### Rotas de Comissão

- **GET /api/commission**: Lista as comissões.
- **POST /api/commission**: Cria uma nova comissão.
- **PUT /api/commission/{commission}**: Atualiza uma comissão existente.
- **DELETE /api/commission/{commission}**: Exclui uma comissão.

### Autenticação

O sistema utiliza o **Laravel Sanctum** para autenticação da API. Para autenticar, um token de API deve ser gerado e incluído nos headers das requisições.

## Frontend: Vue.js Setup

### Requisitos

- Node.js (para instalação de pacotes)
- NPM ou Yarn (para gerenciamento de pacotes)

### Instalação

1. Clone o repositório:
    ```bash
    git clone https://github.com/justgui1/affiliates_system.git
    ```

2. Navegue até o diretório do frontend:
    ```bash
    cd affiliates_system/frontend
    ```

3. Instale as dependências:
    ```bash
    npm install
    ```

4. Inicie o servidor de desenvolvimento:
    ```bash
    npm run dev
    ```

    O frontend estará rodando em `http://localhost:5173`.

### Componentes do Frontend

1. **Tabela de Afiliados**:
    - Exibe os afiliados com paginação e permite filtrar por nome e status.

2. **Popups de Edição**:
    - Popups para editar os dados dos afiliados e alterar seu status.

3. **DataPicker**:
    - Utiliza o componente `VueDatePicker` para selecionar a data de nascimento dos afiliados no formato brasileiro (dd/mm/yyyy).

## Licença

Este projeto está licenciado sob a **Apache License 2.0**. Você pode usar, modificar e redistribuir este código, mas deve manter a atribuição e não usar o código para fins ilegais.

Veja mais detalhes no arquivo [LICENSE](LICENSE).
