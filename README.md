# Blog Semana de Sistemas 2026

Sistema web desarrollado con **Laravel, PHP y PostgreSQL** para la publicación y gestión de las actividades realizadas durante la Semana de Sistemas 2026.

El proyecto permite administrar categorías, publicaciones, fotografías, evidencias y usuarios mediante un panel administrativo, además de presentar el contenido en una interfaz pública.

## 1. Tecnologías utilizadas

- PHP y Laravel
- PostgreSQL 17
- Docker y Docker Compose
- Blade
- Bootstrap 5
- CSS y JavaScript
- Vite

## 2. Requisitos previos

Para ejecutar el proyecto se necesita:

- Git
- Docker y Docker Compose
- Un entorno compatible con Docker (WSL2 o Git Bash en Windows, Linux o macOS)
- Un navegador web

PHP, Composer, Node.js y las demás dependencias se ejecutan dentro del contenedor Docker del proyecto, por lo que no es necesario instalarlos directamente en la máquina anfitriona.

## 3. Clonar el repositorio

Ejecutar:

```bash
git clone https://github.com/JuliChav23011/Bog_SDS2026.git
```

Ingresar al repositorio:

```bash
cd Bog_SDS2026
```

El código actualizado se encuentra en la rama principal `main`.

## 4. Preparar el entorno Docker

Ubicarse en la carpeta raíz del repositorio que contiene el archivo `compose.yaml` (o `docker-compose.yml`).

Iniciar los contenedores:

```bash
docker compose up -d --build
```

Verificar que estén funcionando:

```bash
docker compose ps
```

En el entorno utilizado durante el desarrollo, los servicios principales son:

| Servicio | Nombre |
|---|---|
| Aplicación PHP/Apache | `servidor` |
| PostgreSQL | `db` |
| pgAdmin | `pgadmin` |

Los puertos configurados por defecto son:

| Servicio | Puerto local |
|---|---|
| Aplicación web | 8083 |
| PostgreSQL | 5433 |
| pgAdmin | 8087 |
| Vite | 5173 |

## 5. Preparar Laravel

La aplicación Laravel se encuentra en:

```text
www/blog-semana-sistemas/
```

Los siguientes comandos se ejecutan desde la carpeta raíz (`Bog_SDS2026/`) donde se encuentra el archivo de Docker Compose.

Crear el archivo `.env` a partir de `.env.example`:

```bash
cp www/blog-semana-sistemas/.env.example www/blog-semana-sistemas/.env
```

Instalar las dependencias PHP dentro del contenedor:

```bash
docker compose exec -w /var/www/html/blog-semana-sistemas servidor composer install
```

Generar una clave de aplicación para la nueva instalación:

```bash
docker compose exec -w /var/www/html/blog-semana-sistemas servidor php artisan key:generate
```

### Configuración de URL y PostgreSQL en `.env`

Abrir `www/blog-semana-sistemas/.env` y asegurarse de configurar las siguientes variables para que coincidan con los servicios de Docker:

```env
APP_URL=http://blog-semana-sistemas.localhost:8083

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=blog_sds2026
DB_USERNAME=postgres
DB_PASSWORD=root123
```

**Nota:** El puerto `5432` corresponde a la comunicación interna entre contenedores. El puerto `5433` es el expuesto hacia la computadora anfitriona.

## 6. Restaurar la base de datos

El respaldo de PostgreSQL se proporciona en el archivo:

`respaldo_blog_sds2026.sql`

Este archivo está incluido dentro del paquete de entrega `entrega_blog_sds2026.tar.gz`, el cual contiene la estructura de la base de datos y los registros de categorías, publicaciones, imágenes, usuarios y demás tablas del sistema.

### 6.1. Extraer y ubicar los archivos de entrega

Descomprimir el paquete `entrega_blog_sds2026.tar.gz` y colocar la carpeta resultante **`entrega_blog_sds2026/`** directamente en la raíz del proyecto (`Bog_SDS2026/`), al mismo nivel que `compose.yaml`:

```text
Bog_SDS2026/
├── compose.yaml
├── docker/
├── www/
└── entrega_blog_sds2026/
    ├── respaldo_blog_sds2026.sql
    └── imagenes_blog_sds2026.tar.gz
```

### 6.2. Crear la base de datos

Desde la carpeta raíz (`Bog_SDS2026/`), ejecutar:

```bash
docker compose exec -T db createdb -U postgres blog_sds2026
```

Si la base de datos ya existe, no es necesario volver a crearla.

### 6.3. Importar el respaldo

Desde la carpeta raíz (`Bog_SDS2026/`), ejecutar en **WSL2, Linux, macOS o Git Bash**:

```bash
docker compose exec -T db psql -X -v ON_ERROR_STOP=1 -U postgres -d blog_sds2026 < entrega_blog_sds2026/respaldo_blog_sds2026.sql
```

Si se ejecuta desde **Windows PowerShell**, usar la siguiente variante (ya que PowerShell no admite el operador de redirección de entrada de forma nativa):

```powershell
cmd /c "docker compose exec -T db psql -X -v ON_ERROR_STOP=1 -U postgres -d blog_sds2026 < entrega_blog_sds2026\respaldo_blog_sds2026.sql"
```

La importación restaurará las tablas y los registros incluidos en el respaldo.

**Importante:** El respaldo debe importarse en una base de datos vacía. No ejecutar `php artisan migrate:fresh` después de importar el respaldo, ya que eliminaría las tablas y los registros restaurados.

## 7. Restaurar las imágenes de las publicaciones

Las fotografías cargadas desde el panel administrativo se entregan en:

`imagenes_blog_sds2026.tar.gz`

Desde la carpeta raíz (`Bog_SDS2026/`), asegurarse de que exista el directorio de destino y extraerlas en el almacenamiento público de Laravel:

```bash
mkdir -p www/blog-semana-sistemas/storage/app/public
tar -xzf entrega_blog_sds2026/imagenes_blog_sds2026.tar.gz -C www/blog-semana-sistemas/storage/app/public/
```

La estructura resultante debe ser:

```text
www/blog-semana-sistemas/
└── storage/
    └── app/
        └── public/
            └── publicaciones/
                └── archivos de imágenes
```

Es importante conservar los nombres originales de los archivos, ya que las rutas se encuentran registradas en la base de datos.

### 7.1. Crear el enlace de almacenamiento y ajustar permisos

Ejecutar:

```bash
docker compose exec -w /var/www/html/blog-semana-sistemas servidor php artisan storage:link
docker compose exec -w /var/www/html/blog-semana-sistemas servidor chown -R www-data:www-data storage bootstrap/cache
```

Las imágenes ubicadas directamente en `public/img/` ya forman parte del repositorio y no necesitan restaurarse desde este paquete.

## 8. Instalar los recursos del frontend

Desde la carpeta raíz (`Bog_SDS2026/`), ejecutar:

```bash
docker compose exec -w /var/www/html/blog-semana-sistemas servidor npm install
docker compose exec -w /var/www/html/blog-semana-sistemas servidor npm run build
```

Este paso compila los estilos y scripts administrados mediante Vite.

## 9. Limpiar la configuración de Laravel

Una vez configurada la base de datos e importado el respaldo, ejecutar:

```bash
docker compose exec -w /var/www/html/blog-semana-sistemas servidor php artisan optimize:clear
```

Esto limpia la caché y garantiza que Laravel utilice la conexión a `blog_sds2026`.

## 10. Acceder al sistema

Con los contenedores iniciados y el entorno configurado, acceder desde el navegador a:

- **Inicio:** `http://blog-semana-sistemas.localhost:8083`
- **Publicaciones:** `http://blog-semana-sistemas.localhost:8083/publicaciones`

El acceso al contenido y al panel administrativo depende de la autenticación y del rol asignado a cada usuario.

## 11. Verificación de la base de datos

Para comprobar que los registros fueron restaurados correctamente, ejecutar:

```bash
docker compose exec -T db psql -U postgres -d blog_sds2026 -c "
SELECT 'categorias' AS tabla, COUNT(*) FROM categorias
UNION ALL
SELECT 'publicaciones', COUNT(*) FROM publicaciones
UNION ALL
SELECT 'imagenes_publicaciones', COUNT(*) FROM imagenes_publicaciones
UNION ALL
SELECT 'usuarios', COUNT(*) FROM usuarios;
"
```

El respaldo contiene:

| Tabla | Registros |
|---|---:|
| Categorías | 8 |
| Publicaciones | 21 |
| Imágenes de publicaciones | 67 |
| Usuarios | 3 |

## 12. Consideraciones importantes

- El repositorio en GitHub contiene el código fuente del proyecto.
- El respaldo SQL contiene la estructura y los datos almacenados en PostgreSQL.
- El archivo comprimido de imágenes contiene las fotografías cargadas mediante el panel administrativo.
- Los tres componentes son necesarios para recuperar el contenido completo del blog.
- No es necesario ejecutar los seeders ni migraciones después de importar el respaldo.
- El respaldo de usuarios contiene información de autenticación y debe distribuirse de manera privada.

## 13. Repositorio

- **GitHub:** `https://github.com/JuliChav23011/Bog_SDS2026.git`
- **Rama principal:** `main`
- **Proyecto:** Blog Semana de Sistemas 2026
