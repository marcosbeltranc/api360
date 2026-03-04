# API360 - Backend 🚀

Este es el repositorio del backend para el proyecto **API360**, una API robusta y escalable diseñada con Laravel y contenerizada con Docker.

## 🛠️ Stack Tecnológico

* **Framework:** [Laravel 11](https://laravel.com) (PHP 8.3)
* **Base de Datos:** [PostgreSQL 16](https://www.postgresql.org/)
* **Contenerización:** [Docker](https://www.docker.com/) & Docker Compose
* **Autenticación:** Laravel Sanctum (Planeado)

## 🐳 Infraestructura (Docker)

El proyecto utiliza una arquitectura de microservicios básica dividida en dos contenedores principales:
1.  **app**: Servidor PHP-FPM con las extensiones necesarias para PostgreSQL.
2.  **db**: Instancia de PostgreSQL para la persistencia de datos.



##  Instalación y Despliegue

Sigue estos pasos para levantar el entorno de desarrollo:

### 1. Clonar el repositorio
```bash
git clone [https://github.com/marcosbeltranc/api360.git](https://github.com/marcosbeltranc/api360.git)
cd api360
```

### 2. Configurar variables de entorno
```bash
cp .env.example .env
```

### 3. Levantar contenedores
Este comando construirá las imágenes e iniciará los servicios en segundo plano:
```bash
docker compose up -d --build
```

### 4. Inicializar base de datos
Ejecuta las migraciones para crear la estructura de tablas inicial:
```bash
docker compose exec app php artisan migrate
```

### Comandos Útiles
Ver logs:
```bash
docker compose logs -f
```

Detener servicios:
```bash
docker compose down
```

Ejecutar comandos de Artisan:
```bash
docker compose exec app php artisan [comando]
```

Entrar al contenedor de la app:
```bash
docker compose exec app bash
```









1. Crear Migraciones
Estos comandos solo crean el archivo en database/migrations.
Tabla nueva:
docker compose exec app php artisan make:migration create_devices_table

Modificar tabla existente (Añadir columnas):
docker compose exec app php artisan make:migration add_status_to_devices_table --table=devices

2. Ejecutar Migraciones (Subir)
Correr todo lo pendiente:
docker compose exec app php artisan migrate

Ver el estado (Qué se ha corrido y qué no):
docker compose exec app php artisan migrate:status

3. Revertir Migraciones (Bajar/Rollback)
Deshacer el último lote (Batch):
docker compose exec app php artisan migrate:rollback

Deshacer un número específico de migraciones:
docker compose exec app php artisan migrate:rollback --step=2

Reset total (Ejecuta down() de TODAS las migraciones):
docker compose exec app php artisan migrate:reset

4. Comandos de "Refresco" (Limpieza)
Borrar todo y volver a correr todo:
docker compose exec app php artisan migrate:refresh

Borrar todo, volver a correr y ejecutar los Seeders (Recomendado):
docker compose exec app php artisan migrate:fresh --seed

Diferencia: refresh corre los metodos down(), fresh simplemente hace un DROP ALL TABLES (es más rápido y evita errores de claves foráneas).



docker compose exec app php artisan make:model ServerDevice

docker compose exec app php artisan make:controller ServerDeviceController --api






wsl -d Ubuntu

sudo chown -R $USER:$USER .