<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PVC Game 01 - Reporte Crediticio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen font-sans py-10">

    <div class="w-full max-w-2xl space-y-8">

        <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-200">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-slate-800">Generador de Reportes</h1>
                <p class="text-slate-500 text-sm mt-1">Procesamiento Asíncrono (Queues)</p>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('report.export') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="start_date" class="block text-sm font-medium text-slate-700">Fecha Inicio</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="w-full px-4 py-2 border rounded-lg outline-none transition @error('start_date') border-red-500 focus:ring-red-500 @else border-slate-300 focus:ring-blue-500 focus:border-blue-500 @enderror">
                        @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="end_date" class="block text-sm font-medium text-slate-700">Fecha Fin</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-2 border rounded-lg outline-none transition @error('end_date') border-red-500 focus:ring-red-500 @else border-slate-300 focus:ring-blue-500 focus:border-blue-500 @enderror">
                        @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md transition duration-200 ease-in-out flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                        Solicitar Reporte
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">Reportes Disponibles</h2>
                <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:underline">Actualizar lista</a>
            </div>

            @if($files->isEmpty())
                <div class="text-center py-8 text-slate-400 text-sm">
                    No hay reportes generados aún.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-4 py-3">Archivo</th>
                                <th scope="col" class="px-4 py-3">Fecha</th>
                                <th scope="col" class="px-4 py-3 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $file)
                                <tr class="bg-white border-b hover:bg-slate-50">
                                    <td class="px-4 py-3 font-medium text-slate-900 whitespace-nowrap">
                                        {{ $file->name }}
                                        <div class="text-xs text-slate-400 font-normal">{{ $file->size }}</div>
                                    </td>
                                    <td class="px-4 py-3">{{ $file->date }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ $file->download_url }}" class="text-blue-600 hover:text-blue-900 font-medium">Descargar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</body>
</html>
