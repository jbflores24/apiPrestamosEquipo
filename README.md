# API de Préstamo de Equipo

## Descripción

API REST desarrollada con Laravel 13 para controlar el préstamo de equipo del Centro de Cómputo: laptops, proyectores, cámaras, tabletas y otros dispositivos que los alumnos solicitan por un tiempo determinado.

La API administra cinco tablas:

| Tabla | Descripción | Relaciones |
|---|---|---|
| `avisos` | Avisos generales para los alumnos (`titulo`, `texto`, `fecha`). | Ninguna. |
| `alumnos` | Alumnos que solicitan equipo (`matricula`, `nombre`, `carrera`). | 1:N con `prestamos`. |
| `equipos` | Equipo disponible para préstamo (`codigo`, `nombre`, `tipo`, `estado`). | N:M con `prestamos`. |
| `prestamos` | Préstamos realizados (`alumno_id`, `fecha_salida`, `fecha_devolucion_esperada`). | Pertenece a un alumno, N:M con `equipos`. |
| `detalle_prestamos` | Tabla pivote entre préstamos y equipos (`fecha_devolucion_real`, `observaciones`). | Une `prestamos` y `equipos`. |

En el curso se usa como base para practicar el diseño de una API REST con Laravel: migraciones, modelos, relaciones, validación con `FormRequest`, `Resources` y respuestas con un formato uniforme. Más adelante será el backend de una aplicación móvil hecha con Ionic y Angular.

### Reglas de negocio

- No se puede prestar un equipo que ya está prestado o que está en mantenimiento.
- Al registrar la devolución se guarda `fecha_devolucion_real` y el equipo vuelve a estar `disponible`.
- Los días de retraso se calculan comparando `fecha_devolucion_esperada` con `fecha_devolucion_real`, o con la fecha actual si el equipo aún no se devuelve.
- No se puede eliminar un alumno ni un equipo que tenga préstamos registrados.

### Formato de las respuestas

Todas las respuestas tienen la misma forma:

```json
{
    "message": "Listado de avisos",
    "statusCode": 200,
    "error": false,
    "data": {}
}
```

## Prerrequisitos

| Herramienta | Versión requerida | Comando para verificar |
|---|---|---|
| PHP | 8.3 o superior | `php -v` |
| Composer | 2.x | `composer --version` |
| MySQL | 8 con motor InnoDB | `mysql --version` |
| Git | Cualquier versión reciente | `git --version` |

> **Nota:** el comando `mysql` debe funcionar desde PowerShell. Si al ejecutar `mysql --version` aparece un error como "el término 'mysql' no se reconoce", hay que agregar la carpeta `bin` de MySQL a la variable de entorno `Path`:
>
> 1. Localiza la carpeta `bin` donde está instalado MySQL. Normalmente es similar a `C:\Program Files\MySQL\MySQL Server 8.0\bin` y debe contener el archivo `mysql.exe`.
> 2. Presiona la tecla de Windows, escribe **Editar las variables de entorno del sistema** y abre esa opción.
> 3. En la ventana **Propiedades del sistema**, haz clic en **Variables de entorno**.
> 4. En **Variables de usuario**, selecciona **Path** y haz clic en **Editar**. (Si prefieres que aplique a todos los usuarios del equipo, usa **Path** en **Variables del sistema**; requiere permisos de administrador.)
> 5. Haz clic en **Nuevo**, pega la ruta de la carpeta `bin` y acepta todas las ventanas con **Aceptar**.
> 6. Cierra PowerShell y ábrelo de nuevo, porque las terminales abiertas no reciben el cambio.
> 7. Verifica con `mysql --version`.

Para comprobar que MySQL tiene disponible el motor InnoDB, entra al cliente de MySQL y ejecuta:

```sql
SHOW ENGINES;
```

En la fila `InnoDB` la columna `Support` debe decir `DEFAULT` o `YES`.

Las extensiones de PHP `pdo_mysql`, `mbstring`, `openssl` y `fileinfo` deben estar habilitadas. Puedes revisarlas con `php -m`.

## Instalación en Windows

Abre una terminal (PowerShell) y ejecuta los pasos en este orden.

1. Clona el repositorio y entra a la carpeta:

   ```powershell
   git clone https://github.com/jbflores24/apiPrestamosEquipo.git
   cd apiPrestamosEquipo
   ```

2. Instala las dependencias:

   ```powershell
   composer install
   ```

3. Copia el archivo de configuración:

   ```powershell
   copy .env.example .env
   ```

   Revisa en `.env` que los datos de conexión correspondan a tu servidor de MySQL. Por defecto son:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=prestamos_equipo
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   Si tu usuario `root` tiene contraseña, escríbela en `DB_PASSWORD`.

4. Genera la clave de la aplicación:

   ```powershell
   php artisan key:generate
   ```

5. Crea la base de datos `prestamos_equipo` en MySQL con codificación `utf8mb4`:

   ```powershell
   mysql -u root -p -e "CREATE DATABASE prestamos_equipo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

6. Crea las tablas y carga los datos de prueba:

   ```powershell
   php artisan migrate --seed
   ```

7. Levanta el servidor de desarrollo:

   ```powershell
   php artisan serve
   ```

   El servidor queda disponible en `http://127.0.0.1:8000`.

## Verificación

Con el servidor en marcha, abre en el navegador o ejecuta:

```powershell
curl http://127.0.0.1:8000/api/aviso
```

La petición `GET http://127.0.0.1:8000/api/aviso` debe responder con código **200** y la lista de avisos cargados por el seeder.

## Endpoints

Todas las rutas llevan el prefijo `/api`. Los listados están paginados de 15 en 15; puedes pedir otra página con `?page=2`.

| Método | Ruta | Descripción | Código |
|---|---|---|---|
| GET | `/api/aviso` | Lista los avisos. | 200 |
| POST | `/api/aviso` | Crea un aviso. | 201 |
| GET | `/api/aviso/{aviso}` | Consulta un aviso. | 200 |
| PUT/PATCH | `/api/aviso/{aviso}` | Actualiza un aviso. | 200 |
| DELETE | `/api/aviso/{aviso}` | Elimina un aviso. | 200 |
| GET | `/api/alumno` | Lista los alumnos. | 200 |
| POST | `/api/alumno` | Crea un alumno. | 201 |
| GET | `/api/alumno/{alumno}` | Consulta un alumno con sus préstamos. | 200 |
| PUT/PATCH | `/api/alumno/{alumno}` | Actualiza un alumno. | 200 |
| DELETE | `/api/alumno/{alumno}` | Elimina un alumno sin préstamos. | 200 |
| GET | `/api/equipo` | Lista los equipos. | 200 |
| POST | `/api/equipo` | Crea un equipo. | 201 |
| GET | `/api/equipo/{equipo}` | Consulta un equipo. | 200 |
| PUT/PATCH | `/api/equipo/{equipo}` | Actualiza un equipo. | 200 |
| DELETE | `/api/equipo/{equipo}` | Elimina un equipo sin préstamos. | 200 |
| GET | `/api/prestamo` | Lista los préstamos con su alumno y sus equipos. | 200 |
| POST | `/api/prestamo` | Registra un préstamo con uno o más equipos. | 201, o 422 si un equipo no está disponible |
| GET | `/api/prestamo/{prestamo}` | Consulta un préstamo. | 200 |
| PUT/PATCH | `/api/prestamo/{prestamo}` | Actualiza las fechas o el alumno del préstamo. | 200 |
| DELETE | `/api/prestamo/{prestamo}` | Elimina un préstamo y libera sus equipos pendientes. | 200 |
| POST | `/api/prestamo/{prestamo}/devolucion` | Registra la devolución y deja los equipos disponibles. | 200 |

Otros códigos que puede devolver la API:

- **422:** los datos enviados no son válidos (campos faltantes, matrícula o código repetidos, fechas incorrectas).
- **404:** el registro solicitado no existe.
- **409:** no se puede eliminar un alumno o un equipo con préstamos registrados, ni modificar un equipo que está prestado.

### Registrar una devolución

`POST /api/prestamo/{prestamo}/devolucion` acepta un cuerpo opcional. Sin cuerpo se devuelven todos los equipos pendientes del préstamo:

```json
{
    "equipos": [3],
    "observaciones": "Devuelto en buen estado"
}
```

## Ejemplos

Con los datos del seeder, los equipos con `id` 3 y 4 están disponibles y el alumno con `id` 1 existe.

### Registrar un préstamo (201)

`POST http://127.0.0.1:8000/api/prestamo`

```json
{
    "alumno_id": 1,
    "fecha_salida": "2026-10-11",
    "fecha_devolucion_esperada": "2026-10-15",
    "equipos": [3, 4]
}
```

Respuesta **201**:

```json
{
    "message": "Préstamo registrado correctamente",
    "statusCode": 201,
    "error": false,
    "data": {
        "id": 4,
        "alumno_id": 1,
        "alumno": {
            "id": 1,
            "matricula": "20240001",
            "nombre": "Ana Martínez Peña",
            "carrera": "Ingeniería en Sistemas",
            "created_at": "2026-10-11T12:09:20.000000Z",
            "updated_at": "2026-10-11T12:09:20.000000Z"
        },
        "fecha_salida": "2026-10-11",
        "fecha_devolucion_esperada": "2026-10-15",
        "detalles": [
            {
                "id": 5,
                "prestamo_id": 4,
                "equipo_id": 3,
                "equipo": {
                    "id": 3,
                    "codigo": "EQ-003",
                    "nombre": "Cámara Canon T7",
                    "tipo": "Cámara",
                    "estado": "prestado",
                    "created_at": "2026-10-11T12:09:20.000000Z",
                    "updated_at": "2026-10-11T12:10:53.000000Z"
                },
                "fecha_devolucion_real": null,
                "observaciones": null,
                "dias_retraso": 0
            },
            {
                "id": 6,
                "prestamo_id": 4,
                "equipo_id": 4,
                "equipo": {
                    "id": 4,
                    "codigo": "EQ-004",
                    "nombre": "Tableta Samsung Tab A8",
                    "tipo": "Tableta",
                    "estado": "prestado",
                    "created_at": "2026-10-11T12:09:20.000000Z",
                    "updated_at": "2026-10-11T12:10:53.000000Z"
                },
                "fecha_devolucion_real": null,
                "observaciones": null,
                "dias_retraso": 0
            }
        ],
        "created_at": "2026-10-11T12:10:53.000000Z",
        "updated_at": "2026-10-11T12:10:53.000000Z"
    }
}
```

### Repetir el mismo préstamo (422)

Si se envía otra vez el mismo `POST`, los equipos 3 y 4 ya están prestados y la API responde **422**:

```json
{
    "message": "Los datos enviados no son válidos",
    "statusCode": 422,
    "error": true,
    "data": {
        "equipos.0": [
            "El equipo seleccionado no existe o no está disponible."
        ],
        "equipos.1": [
            "El equipo seleccionado no existe o no está disponible."
        ]
    }
}
```

## Colección de Postman

La colección está en el repositorio, en la ruta:

```
postman/apiPrestamosEquipos.postman_collection.json
```

Para importarla:

1. Abre Postman.
2. Haz clic en **Import**.
3. Arrastra el archivo `apiPrestamosEquipos.postman_collection.json` o selecciónalo con **Files**.
4. Haz clic en **Import**.

La colección incluye todos los endpoints agrupados por recurso (Avisos, Alumnos, Equipos y Préstamos) y las dos variantes de devolución. Usa la variable `baseUrl`, que por defecto vale `http://127.0.0.1:8000/api`. Si cambias el puerto del servidor, edita esa variable en la pestaña **Variables** de la colección.
