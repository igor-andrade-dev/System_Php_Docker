

# 🛍️ Integrated Management System with E-commerce Website

**Autor:** Igor César de Andrade  
**Data:** Junho/2025  

---

## 📌 Descrição Geral

This project is an integrated management system combined with an e-commerce website, developed with a strong focus on code organization, clarity, and execution in a Docker-based environment. It was built using:

PHP + Apache

MySQL

Docker + Docker Compose

phpMyAdmin

The application is divided into:

Public e-commerce website – for product display

Internal system (/sistema) – for data management (admin panel)
---

## 🎯 Objetive

To simulate an e-commerce environment with an administrative dashboard, applying good coding practices, a clear project structure, and integration with a relational database (MySQL).

---

## 🔧 Technologies Used:

| Technology       | Function                              |
|------------------|---------------------------------------|
| PHP 8.1 + Apache | Application backend                   |
| MySQL 5.7        | Data storage                          |
| Docker / Compose | Isolated and reproducible environments|
| phpMyAdmin       | Bank management interface             |
| PDO / mysqli     | Secure access to the database         |

---

## 🚀 How to Run the Project with Docker

### 1.Prerequisites
- Docker installed (Docker Desktop)

### 2. Clone the repository
```bash
git clone https://github.com/IgorDevFullstack/Sistema_Php_Docker

3.Run the Docker environment

docker-compose up --build

4. Access in browser

Site E-commerce	http://localhost:8080
Sistema Interno	http://localhost:8080/sistema
phpMyAdmin	http://localhost:8081

    phpMyAdmin Login
    User: root
    password: root

🧠 Folder Structure

/Sistema_Php_Docker
├── index.php              # Página principal do e-commerce
├── /sistema               # Painel administrativo do sistema
│   ├── conexao.php        # Conexão com o banco
│   ├── páginas internas   # (ex: cadastro, edição)
├── Dockerfile             # Configuração PHP + Apache
├── docker-compose.yml     # Orquestração dos serviços
├── README.md              # Este documento

🔐 Demo credentials:

    Email: igormtba@gmail.com
    Password: demo123
    Deploys: https://igorecommerce.alwaysdata.net/
    system: https://igorecommerce.alwaysdata.net/sistema



Unauthorized use, copying, or distribution, in whole or in part, is prohibited.

All rights reserved © Igor César de Andrade – 2025.
