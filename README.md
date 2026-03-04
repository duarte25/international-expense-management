# Gestao de Despesas Internacionais

API em Laravel para:
- cadastro de usuario com validacao de CPF e consulta de CEP;
- registro de despesas em moeda estrangeira com conversao para BRL;
- isolamento de dados por usuario autenticado.

## Requisitos
- Docker e Docker Compose
- Opcional (modo local): PHP 8.4+ com extensoes `mbstring`, `xml`, `xmlwriter`, `dom`, `pdo_pgsql`
- Opcional (modo local): Composer instalado

## Stack usada
- Laravel 12
- PHP 8.4
- PostgreSQL (configuravel no `.env`)
- Sanctum (tokens para API)

## Rodar tudo so com Docker
1. Suba a aplicacao completa (API + PostgreSQL):
```bash
docker compose up --build -d
```

2. A API fica disponivel em:
```text
http://localhost:8000
```

3. Para parar os containers:
```bash
docker compose down
```

4. Para limpar tambem o volume do banco:
```bash
docker compose down -v
```

Observacoes:
- As migrations rodam automaticamente no startup do container da API.
- Se o arquivo `.env` nao existir no container, ele e criado a partir do `.env.example`.

## Rodando local (sem Docker)
1. Garanta PHP com extensoes necessarias:
```bash
php -m
```
Precisa conter: `mbstring`, `xml`, `xmlwriter`, `dom`, `pdo_pgsql`.

2. Instale dependencias:
```bash
composer install
```

3. Configure `.env` para seu Postgres local e rode:
```bash
php artisan migrate
php artisan serve
```

## Endpoints
Base (Docker): `http://127.0.0.1:8000/api`

- `POST /register`
- `POST /login`
- `POST /logout` (Bearer Token)
- `GET /expenses` (Bearer Token)
- `POST /expenses` (Bearer Token)
- `GET /expenses/{id}` (Bearer Token)
- `DELETE /expenses/{id}` (Bearer Token)

## Exemplo de payloads
### Cadastro
```json
{
  "name": "Gustavo Silva",
  "email": "gustavo@example.com",
  "password": "12345678",
  "password_confirmation": "12345678",
  "cpf": "11144477735",
  "cep": "01001000"
}
```

### Login
```json
{
  "email": "gustavo@example.com",
  "password": "12345678"
}
```

### Nova despesa
```json
{
  "amount": "120.50",
  "currency": "USD",
  "save_as_pending_on_failure": true
}
```

## Regras implementadas
- CPF valido por digitos verificadores.
- CPF unico no sistema.
- CEP validado em formato e consultado na API ViaCEP.
- Bloqueio de cadastro com CEP invalido/inexistente.
- Conversao por API de cambio (`open.er-api.com`).
- Valores monetarios em `decimal`.
- Usuario so acessa as proprias despesas.
- Em falha de API de cambio: salva como `pending` (se solicitado) ou retorna mensagem amigavel.
