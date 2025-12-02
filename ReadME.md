# Shortcut Explorer — Laravel Project

Este proyecto es una aplicación desarrollada en Laravel que permite explorar aplicaciones, ver sus atajos de teclado organizados por categorías y crear shortcuts personalizados mediante una interfaz intuitiva.

## 🚀 Funcionalidades principales

- **Buscador global** de aplicaciones y comandos.  
- **Vista detallada de aplicaciones**, mostrando:
  - Nombre  
  - Sistema al que pertenece  
  - Descripción  
  - Atajos agrupados por categoría  
- **Creación de atajos personalizados (Mis Shortcuts)**  
  - Selección de aplicación  
  - Selección de sistema  
  - Selección de categoría  
  - Constructor visual de combinaciones de teclas  
  - Validación y guardado vía API  
- **Interfaz responsiva** usando Blade + TailwindCSS.  

## 📂 Tecnologías utilizadas

- **Laravel 10**  
- **SQLite**  
- **Blade Templates**  
- **TailwindCSS**  
- **Fetch API** para comunicación con endpoints  
- **Seeders** para carga inicial de datos  

## ⚙️ Estructura general del proyecto

- `/resources/views` — Vistas Blade del buscador, detalles de aplicaciones y Mis Shortcuts  
- `/app/Http/Controllers` — Controladores de aplicaciones, shortcuts y búsquedas  
- `/routes/web.php` — Rutas de vistas y formularios  
- `/routes/api.php` — Endpoints JSON del proyecto  
- `/database/seeders` — Carga inicial de sistemas, aplicaciones, categorías y atajos  

## 🧪 Próximas mejoras

- CRUD completo para administrar aplicaciones, categorías y sistemas  
- Vista detallada por cada shortcut  
- Mejoras visuales y accesibilidad  
- Implementación de autenticación  
- Refinar API y optimizar búsqueda  

## 📜 Licencia

Proyecto académico creado para fines educativos. Libre para uso personal.
