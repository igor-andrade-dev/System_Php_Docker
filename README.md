

# 🛍️ Sistema de Gerenciamento Integrado com Site de E-commerce

**Autor:** Igor César de Andrade  
**Data:** Junho/2025  
**Projeto desenvolvido exclusivamente para fins de avaliação técnica**

---

## 📌 Descrição Geral

Este projeto é um **sistema de gerenciamento integrado com um site de e-commerce**, desenvolvido com foco em organização, clareza do código e execução em ambiente Docker. Foi construído com:

- PHP + Apache
- MySQL
- Docker + Docker Compose
- phpMyAdmin

A aplicação é dividida em:

- **Site público do e-commerce** – para exibição de produtos
- **Sistema interno (/sistema)** – para gerenciamento dos dados (admin)

---

## 🎯 Objetivo

Simular um ambiente de e-commerce com painel administrativo, utilizando boas práticas de organização de código, estrutura clara e integração com banco de dados relacional (MySQL).

---

## 🔧 Tecnologias Utilizadas

| Tecnologia      | Função                              |
|------------------|-------------------------------------|
| PHP 8.1 + Apache | Backend da aplicação                |
| MySQL 5.7        | Armazenamento de dados              |
| Docker / Compose | Ambientes isolados e reprodutíveis  |
| phpMyAdmin       | Interface de gerenciamento do banco |
| PDO / mysqli     | Acesso seguro ao banco de dados     |

---

## 🚀 Como Executar o Projeto com Docker

### 1. Pré-requisitos
- Docker instalado (Docker Desktop)

### 2. Clonar o repositório
```bash
git clone https://github.com/igorprogrammer93/testePHP_Pleno.git
cd testePHP_Pleno

3. Executar o ambiente Docker

docker-compose up --build

4. Acessar no navegador
Seção	URL
Site E-commerce	http://localhost:8080
Sistema Interno	http://localhost:8080/sistema
phpMyAdmin	http://localhost:8081

    phpMyAdmin Login
    Usuário: root
    Senha: root

🧠 Estrutura de Pastas

/testePHP_Pleno
├── index.php              # Página principal do e-commerce
├── /sistema               # Painel administrativo do sistema
│   ├── conexao.php        # Conexão com o banco
│   ├── páginas internas   # (ex: cadastro, edição)
├── Dockerfile             # Configuração PHP + Apache
├── docker-compose.yml     # Orquestração dos serviços
├── README.md              # Este documento

🛡️ Direitos Autorais e Uso

Este projeto foi desenvolvido exclusivamente para o processo seletivo da empresa Alphacode.

    Proibido o uso, cópia ou distribuição parcial ou total sem autorização expressa.

Todos os direitos reservados © Igor César de Andrade – 2025.
