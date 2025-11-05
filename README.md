# 🎬 Letterboxd UAM - API de Reseñas de Películas

Una API REST para una plataforma de reseñas de películas construida con Laravel 11, inspirada en Letterboxd. Esta API proporciona gestión completa de películas, reseñas de usuarios, calificaciones y capacidades de búsqueda avanzada.

## 🚀 Características

- **Gestión Completa de Películas**: Operaciones CRUD para películas con metadatos ricos
- **Autenticación de Usuarios**: Autenticación segura basada en JWT con Laravel Sanctum
- **Búsqueda Avanzada**: Búsqueda multi-criterio por título, director, actor, género y fecha de lanzamiento
- **Sistema de Reseñas**: Reseñas de usuarios con calificaciones de 1-5 estrellas y comentarios
- **Gestión de Reparto**: Relaciones actor-película con nombres de personajes
- **Clasificación por Géneros**: Sistema de categorización de películas
- **Estadísticas y Analytics**: Calificaciones de películas, conteos de reseñas y métricas de engagement
- **Contenido Popular**: Películas trending basadas en reseñas y calificaciones
- **Documentación API**: Documentación completa Swagger/OpenAPI
- **Acceso Basado en Roles**: Permisos de administrador y usuario

## 🛠️ Tecnologías

- **Framework**: Laravel 11
- **Autenticación**: Laravel Sanctum
- **Base de Datos**: PostgreSQL (con soporte de migración para otras bases de datos)
- **Documentación API**: Swagger/OpenAPI (L5-Swagger)
- **Validación**: Laravel Form Requests
- **Gestión de Recursos**: Laravel API Resources
- **Testing**: PHPUnit

## 📚 Documentación API

Accede a la documentación interactiva de la API en: `http://localhost:8000/api/documentation`

### Autenticación

Todos los endpoints protegidos requieren autenticación Bearer token:
```
Authorization: Bearer {your-token}
```

### Resumen de Endpoints API

#### 🔐 Autenticación
- `POST /api/register` - Registro de usuario
- `POST /api/login` - Inicio de sesión
- `POST /api/logout` - Cierre de sesión (autenticado)
- `GET /api/user` - Obtener información del usuario actual (autenticado)
- `PUT /api/profile` - Actualizar perfil de usuario (autenticado)
- `PUT /api/change-password` - Cambiar contraseña (autenticado)

#### 🎭 Géneros (Público)
- `GET /api/genres` - Listar todos los géneros
- `GET /api/genres/{id}` - Obtener género específico

#### 🎬 Actores (Público)
- `GET /api/actors` - Listar todos los actores
- `GET /api/actors/{id}` - Obtener actor específico

#### 🎥 Directores (Público)
- `GET /api/directors` - Listar todos los directores
- `GET /api/directors/{id}` - Obtener director específico

#### 🎪 Películas
- `GET /api/movies` - Listar películas (paginado)
- `GET /api/movies/{id}` - Obtener detalles de película (con relaciones opcionales)
- `GET /api/movies/search` - Búsqueda avanzada de películas
- `GET /api/movies/popular` - Obtener películas populares
- `GET /api/movies/{id}/statistics` - Obtener estadísticas de película
- `POST /api/movies` - Crear película con subida de póster (solo admin)
- `PUT /api/movies/{id}` - Actualizar película con subida de póster (solo admin)
- `DELETE /api/movies/{id}` - Eliminar película (solo admin)

#### 🎭 Reparto de Películas
- `GET /api/movie-casts` - Listar todas las entradas de reparto
- `GET /api/movie-casts/{id}` - Obtener entrada específica de reparto
- `POST /api/movie-casts` - Crear entrada de reparto (solo admin)
- `PUT /api/movie-casts/{id}` - Actualizar entrada de reparto (solo admin)
- `DELETE /api/movie-casts/{id}` - Eliminar entrada de reparto (solo admin)

#### ⭐ Reseñas
- `GET /api/reviews` - Listar todas las reseñas (autenticado)
- `GET /api/reviews/{id}` - Obtener reseña específica (autenticado)
- `GET /api/reviews/my-reviews` - Obtener reseñas propias del usuario (autenticado)
- `GET /api/movies/{movieId}/reviews` - Obtener reseñas de película específica
- `POST /api/reviews` - Crear reseña (autenticado)
- `PUT /api/reviews/{id}` - Actualizar reseña (solo propietario)
- `DELETE /api/reviews/{id}` - Eliminar reseña (solo propietario)

#### 👑 Endpoints Solo Admin
- `POST /api/genres` - Crear género
- `PUT /api/genres/{id}` - Actualizar género
- `DELETE /api/genres/{id}` - Eliminar género
- `POST /api/actors` - Crear actor
- `DELETE /api/actors/{id}` - Eliminar actor
- `POST /api/directors` - Crear director
- `DELETE /api/directors/{id}` - Eliminar director

## 🔍 Ejemplos de Búsqueda Avanzada

### Buscar por múltiples criterios:
```
GET /api/movies/search?title=Matrix&genre_id=1&release_date=1999-03-31
```

### Encontrar películas con actor específico:
```
GET /api/movies/search?actor_id=5
```

### Buscar por director y género:
```
GET /api/movies/search?director_id=2&genre_id=3
```

## 📊 Ejemplos de Respuestas

### Película con Relaciones:
```json
{
  "id": 1,
  "title": "The Matrix",
  "release_date": "1999-03-31",
  "director": {
    "id": 1,
    "name": "Lana Wachowski"
  },
  "cast": [
    {
      "id": 1,
      "name": "Keanu Reeves",
      "character_name": "Neo"
    }
  ],
  "reviews": {
    "count": 150,
    "average_rating": 4.2,
    "data": [...]
  }
}
```

### Estadísticas de Película:
```json
{
  "movie_id": 1,
  "title": "The Matrix",
  "statistics": {
    "total_reviews": 150,
    "average_rating": 4.2,
    "rating_distribution": {
      "1": 5,
      "2": 10,
      "3": 15,
      "4": 40,
      "5": 80
    },
    "recent_reviews_count": 25,
    "last_review_date": "2024-11-01T10:30:00Z"
  }
}
```

## 🗂️ Esquema de Base de Datos

La aplicación utiliza las siguientes entidades principales:
- **Users**: Cuentas de usuario con roles
- **Movies**: Catálogo de películas con metadatos
- **Actors**: Información de actores
- **Directors**: Información de directores
- **Genres**: Géneros de películas
- **Movie Cast**: Relación muchos-a-muchos entre películas y actores
- **Reviews**: Reseñas de usuarios con calificaciones y comentarios
```
