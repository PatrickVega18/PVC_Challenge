# PVC Challenge Solutions

Este repositorio contiene las soluciones para los dos desafíos técnicos planteados, enfocándose estrictamente en el rendimiento, la limpieza del código y la escalabilidad.

## Estructura del Proyecto

- **`/PVC_Game01`**: Solución al Generador de Reportes Crediticios (Laravel).
- **`/PVC_Game02`**: Solución al Gilded Rose Kata (PHP Puro / Refactoring).

---

## Game 01: Generador de Reportes Crediticios

**Objetivo:** Generar reportes masivos en formato XLSX optimizando memoria y consultas.

### Estrategia Técnica Implementada

1.  **Arquitectura Asíncrona (Queues & Jobs):**
    - Se implementó un sistema de **Colas (Jobs)** para desacoplar la generación del reporte del ciclo de vida de la petición HTTP.
    - **Por qué:** Procesar millones de registros toma tiempo. Una descarga sincrónica causaría un **Timeout (Error 504)** en el servidor web. La solución asíncrona garantiza la estabilidad del servidor y mejora la UX mediante un sistema de "Solicitud -> Procesamiento -> Notificación".

2.  **Gestión de Memoria O(1) (Chunking):**
    - Se reemplazó el uso de cursores por `chunkById(1000)` combinado con `OpenSpout` y limpieza explícita de memoria (`unset`).
    - **Por qué:** Permite procesar datasets infinitos con un consumo de RAM plano y constante (aprox. 20MB), evitando errores de **Out of Memory (OOM)**. Se deshabilitó el `QueryLog` de Laravel para evitar fugas de memoria silenciosas.

3.  **Optimización SQL (Read Model Repository):**
    - Se evitó la hidratación de modelos Eloquent y el problema N+1. Se utilizó **Query Builder** con `UNION ALL` encapsulado en un Repositorio.
    - **Por qué:** Traslada la carga de procesamiento ("aplanar" tablas relacionales) al motor de Base de Datos, que es mucho más eficiente que PHP para estas tareas.

4.  **Clean Architecture & SOLID:**
    - Se aplicó una separación estricta de responsabilidades para evitar un "God Controller":
        - **Controller:** Solo gestiona flujo HTTP.
        - **Job:** Maneja la asincronía.
        - **Service:** Orquesta la lógica de negocio.
        - **Repository:** Abstrae la consulta compleja SQL.
        - **Transformer:** Mapea datos de DB a filas de Excel.
        - **Presenter:** Formatea datos para la vista (KB, Fechas).

### 🚀 Instrucciones de Ejecución (Importante)

Dado que el sistema utiliza procesamiento en segundo plano, es necesario tener activo un **Worker** para procesar las colas.

**En entorno local (Desarrollo):**
Abrir una terminal dedicada y ejecutar:
```bash
php artisan queue:work
```

*Este comando se quedará escuchando y procesará los reportes a medida que se soliciten en la web.*

**En entorno Productivo:**
No se debe usar `queue:work` manualmente. Se debe utilizar un gestor de procesos como **Supervisor** (Supervisord) para garantizar que el proceso del worker se mantenga siempre activo y se reinicie en caso de fallo.

---

## Game 02: Gilded Rose Refactoring Kata

**Objetivo:** Refactorizar código legado y añadir una nueva funcionalidad ("Conjured") sin romper la lógica existente.

### Estrategia Técnica Implementada

1.  **Safety Net (Red de Seguridad):**
    - Antes de tocar el código, se establecieron pruebas de **Snapshot (Golden Master)** usando `ApprovalTests`.
    - **Por qué:** Esto garantizó que el comportamiento original del sistema se mantuviera intacto bit a bit durante la refactorización.

2.  **Polimorfismo (Strategy Pattern):**
    - Se reemplazó la lógica condicional compleja (multiples `if/else` anidados) por un diseño orientado a objetos.
    - Se creó una interfaz `ItemUpdater` y clases específicas para cada tipo de ítem (`AgedBrieUpdater`, `BackstagePassUpdater`, etc.).
    - **Por qué:** Cumple con el principio de **Responsabilidad Única (SRP)**. Cada clase tiene una sola razón para cambiar. Si cambia la regla de los "Pases", solo se toca ese archivo.

3.  **Manejo de Casos Especiales (Sulfuras):**
    - Se creó un `SulfurasUpdater` con implementación vacía.
    - **Por qué:** Permite que el bucle principal trate a todos los ítems uniformemente sin introducir `if`s de exclusión ("Tell, Don't Ask").

4.  **Nueva Funcionalidad (Conjured):**
    - Gracias a la refactorización previa, añadir la categoría "Conjured" fue tan simple como crear una nueva clase `ConjuredUpdater` y registrarla, sin riesgo de efectos secundarios en otros ítems.