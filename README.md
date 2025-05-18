# 4VChef — RESTful API en Symfony

Este proyecto es una API desarrollada en **Symfony** que permite la gestión de recetas para chefs, incluyendo sus ingredientes, pasos, nutrientes y valoraciones.  
Está basada en la especificación `4VChef_WithVotes.yaml` oficial proporcionada para el proyecto de Desarrollo Web.

---

## ⚙️ Requisitos

- PHP
- Composer
- Symfony CLI
- MySQL (recomendado con XAMPP)
- Navegador web moderno
- Postman (opcional para testeo)

---

## 🚀 Instalación y puesta en marcha

```bash
# Clonar el repositorio
git clone https://github.com/usuario/4vchef.git
cd 4vchef

# Instalar dependencias
composer install

# Configurar .env con la URL de base de datos (ejemplo con XAMPP)
DATABASE_URL="mysql://root:@127.0.0.1:3306/4vchef"

# Crear base de datos
php bin/console doctrine:database:create

# Generar esquema de base de datos
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# Levantar el servidor local
symfony serve
