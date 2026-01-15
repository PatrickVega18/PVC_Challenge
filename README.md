# PVC Challenge Solutions

Este repositorio contiene las soluciones para los dos desafíos técnicos planteados, enfocándose estrictamente en el rendimiento, la limpieza del código y la escalabilidad.

## Estructura del Proyecto

- **`/PVC_Game01`**: Solución al Generador de Reportes Crediticios (Laravel).
- **`/PVC_Game02`**: Solución al Gilded Rose Kata (PHP Puro / Refactoring).

---

## Game 01: Generador de Reportes Crediticios

**Objetivo:** Generar reportes masivos en formato XLSX optimizando memoria y consultas.

### Estrategia Técnica Implementada

1.  **Streaming vs Buffering:**
    - Se utilizó la librería **OpenSpout** para escribir el archivo Excel línea por línea directamente al flujo de salida (output stream).
    - **Por qué:** Evita cargar todo el archivo en la memoria RAM (algo común en librerías como PhpSpreadsheet). Esto permite exportar millones de registros con un consumo de memoria constante y bajo.

2.  **SQL Optimization:**
    - En lugar de usar Eloquent con relaciones (`with()`), que hidrataría miles de objetos PHP pesados, se utilizó **Query Builder** con `UNION ALL`.
    - **Por qué:** La base de datos relacional es jerárquica (Reporte -> Deudas), pero el Excel es plano. Trasladar la lógica de "aplanado" a SQL mediante `UNION ALL` es  más rápido que procesarlo en PHP, y evita el problema de consultas.

3.  **Database Cursors:**
    - Se utilizó el método `cursor()` de Laravel.
    - **Por qué:** Mantiene solo un registro de la base de datos en memoria a la vez mientras se itera, siendo perfecto para la estrategia de streaming del Excel.

4.  **Arquitectura:**
    - Se separó la lógica en un **Service Pattern** (`CreditReportService`) para no saturar el controlador y permitir futura reutilización (ej. comandos de consola).

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