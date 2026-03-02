<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kalkulator materiałów – Estimo</title>
    @livewireStyles
    <style>
        body { font-family: system-ui, sans-serif; margin: 0; padding: 1.5rem; min-height: 100vh; background: #fafaf9; color: #1c1917; }
        .wrap { max-width: 36rem; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="wrap">
        <p class="mb-4 flex flex-wrap items-center gap-2">
            <a href="{{ route('home') }}" class="text-xl font-bold text-stone-800 hover:text-orange-600">Estimo</a>
            <a href="{{ route('materials.summary') }}" class="text-sm text-stone-500 hover:text-orange-600 ml-2">Lista zakupów</a>
        </p>
        @livewire('kalkulator-wizard')
    </div>
    @livewireScripts
</body>
</html>
