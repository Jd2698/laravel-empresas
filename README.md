# Aplicación laravel

Pruebas técnica que tienen como objetivo crear un web service para gestionar Empresas. Utiliza **SQLite** como sistema de base de datos, cuenta con migraciones y testing de los endpoints.

## Instalación

1. **Instala las dependencias de PHP**:

    ```bash
    composer install
    ```

2. **Crear el archivo .env**:

    ```bash
    cp .env.example .env
    ```


3. **Ejecuta las migraciones para crear las tablas en la base de datos**:

    ```bash
    php artisan migrate
    ```

4. **Ejecuta la aplicación**:

    ```bash
    php artisan serve
    ```

## Ejecutar pruebas

Para ejecutar los tests, sigue estos pasos:

1. **Corre los tests de la aplicación**:

    ```bash
    php artisan test
    ```


## Endpoints

### Endpoint 1: Obtener las empresas registradas

- URL: `/api/empresas`
- Método: `GET`

### Endpoint 2: Obtener una empresa por NIT

- URL: `/api/empresas/nit/{nit}`
- Método: `GET`

### Endpoint 3: Eliminar las empresas inactivas

- URL: `/api/empresas/inactivas`
- Método: `DELETE`

### Endpoint 4: Crear una empresa

- URL: `/api/empresas`
- Método: `POST`
- Parámetros:

  ```json
  {
    "nombre": "Nombre de la empresa",
    "nit": "123456789",
    "direccion": "Dirección de la empresa",
    "telefono": "1234567890"
  }

### Endpoint 5: Actualizar una empresa

- URL: `/api/empresas/{id}`
- Método: `PATCH`
- Descripción: Actualiza los campos, menos el NIT.
- Parámetros:

  ```json
  {
    "nombre": "Nombre de la empresa",
    "direccion": "Dirección de la empresa",
    "telefono": "1234567890",
    "estado": "Activo" o "Inactivo"
  }