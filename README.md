# 📌 ERP

Breve descripción de lo que hace tu sistema (ejemplo: “Sistema de gestión de bancos y movimientos financieros desarrollado en Laravel con PostgreSQL”).

---

## 🚀 Tecnologías utilizadas
- **Laravel 12** – Framework principal.  
- **PostgreSQL** – Base de datos.  
- **Barryvdh/Laravel-Dompdf** – Generación de reportes PDF.  
- **Laravel Breeze** – Autenticación y scaffolding simple.  
- **Laravel Sail** – Entorno de desarrollo con Docker (opcional).  
- **Laravel Pint** – Formateo de código.  
- **PHPUnit / Mockery / Faker** – Pruebas unitarias y datos de prueba.  
- **Node.js + Vite** – Compilación de assets frontend.  

---

## ⚙️ Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/ballsfox14/omni_erp.git
cd mi-proyecto-laravel
```

### 2. Instalar dependencias PHP
```bash
composer install
```

### 3. Instalar dependencias JS
```bash
npm install
```

### 4. Configurar entorno
```bash
cp .env.example .env
php artisan key:generate
```
Editar `.env` con tus credenciales de base de datos.

### 5. Migrar base de datos
```bash
php artisan migrate
```

### 6. Compilar assets
```bash
npm run build
```

---

## 🧪 Scripts útiles
- **Configurar todo de golpe**:  
  ```bash
  composer run setup
  ```
- **Modo desarrollo**:  
  ```bash
  composer run dev
  ```
- **Ejecutar pruebas**:  
  ```bash
  composer run test
  ```

---

## 📂 Estructura principal
- `app/` → Controladores, modelos, lógica.  
- `resources/views/` → Vistas Blade.  
- `database/migrations/` → Migraciones.  
- `routes/` → Rutas web y API.  
- `tests/` → Pruebas unitarias.  

---

## 📜 Licencia
Este proyecto está bajo la licencia MIT.  

---