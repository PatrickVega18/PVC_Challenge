<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PVC Game 01 - Reporte Crediticio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center h-screen font-sans">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border border-slate-200">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Generador de Reportes</h1>
            <p class="text-slate-500 text-sm mt-1">Exportación optimizada XLSX</p>
        </div>

        <form action="/export-report" method="GET" class="space-y-6">

            <div class="space-y-2">
                <label for="start_date" class="block text-sm font-medium text-slate-700">Fecha Inicio</label>
                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    value="{{ old('start_date') }}"
                    class="w-full px-4 py-2 border rounded-lg outline-none transition @error('start_date') border-red-500 focus:ring-red-500 @else border-slate-300 focus:ring-blue-500 focus:border-blue-500 @enderror"
                    required
                >
                @error('start_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="end_date" class="block text-sm font-medium text-slate-700">Fecha Fin</label>
                <input
                    type="date"
                    id="end_date"
                    name="end_date"
                    value="{{ old('end_date') }}"
                    class="w-full px-4 py-2 border rounded-lg outline-none transition @error('end_date') border-red-500 focus:ring-red-500 @else border-slate-300 focus:ring-blue-500 focus:border-blue-500 @enderror"
                    required
                >
                @error('end_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md transition duration-200 ease-in-out flex items-center justify-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Descargar Excel
                </button>
            </div>

        </form>
    </div>

</body>
</html>
