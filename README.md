README.md
markdown
Copiar
Editar
# 🛒 Ecommerce Backend

Este é um projeto de backend para um sistema de ecommerce, desenvolvido em **Laravel** com suporte a **Docker** e **Docker Compose** para fácil execução e configuração do ambiente.

## 📦 Tecnologias Utilizadas

- PHP 8.x
- Laravel 10.x
- MySQL
- Docker
- Docker Compose

## 🐳 Executando com Docker

### 🔥 Passo a passo:

1. Clone o repositório:

```bash
git clone https://github.com/marcos-26/ecommerce-backend.git
cd ecommerce-backend
Execute o Docker:

bash
Copiar
Editar
docker compose up -d --build
Acesse o container para rodar as migrations:

bash
Copiar
Editar
docker exec -it ecommerce-backend-app bash
php artisan migrate
Se desejar, pode também rodar os seeders:

bash
Copiar
Editar
php artisan db:seed
Acesse o projeto no navegador:

bash
Copiar
Editar
http://localhost:8000
🔗 A porta pode variar conforme seu docker-compose.yml.

⚙️ Scripts Úteis
Parar os containers:

bash
Copiar
Editar
docker compose down
Ver os logs:

bash
Copiar
Editar
docker compose logs -f
Acessar o container do PHP:

bash
Copiar
Editar
docker exec -it ecommerce-backend-app bash
🗄️ Estrutura Principal
/app → Código fonte Laravel

/database → Migrations e Seeders

/public → Pasta pública (index.php)

/docker → Arquivos de configuração Docker (se houver)

📝 Funcionalidades
✅ CRUD de Produtos

✅ Controle de Variações

✅ Gerenciamento de Estoque

✅ Carrinho de Compras em Sessão

✅ Cálculo de Frete baseado no subtotal:

🟩 Subtotal entre R$52,00 e R$166,59 → Frete R$15,00

🟩 Subtotal acima de R$200,00 → Frete grátis

🟩 Caso contrário → Frete R$20,00

✅ Validação de CEP via API ViaCEP

🛠️ Em desenvolvimento...
🛍️ Checkout com persistência de pedidos

📦 Integração com gateways de pagamento

🤝 Contribuição
Sinta-se livre para abrir issues ou enviar pull requests!

Feito com 💙 por Marcos

yaml
Copiar
Editar
