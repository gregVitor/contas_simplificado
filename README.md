
> API de demonstração de uma conta bancaria

## Linguagem de Programação
- Desenvolvido em PHP (Lumen)
- Mysql

### Como rodar o projeto

```bash
# Ajustando a .env
Copie a .env.example e crie um novo arquivo .env

# Instalando dependencias
composer install

# Executando o servidor de desenvolvimento
php -S localhost:8080 -t ./public

# Pode ser Executando tambem com Docker
docker-compose build
docker-compose up

# Rodando as migrates do banco de dados
php artisan migrate

# Rodando testes
./vendor/bin/phpunit

```
