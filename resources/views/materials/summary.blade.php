<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista zakupów – Estimo</title>
    @livewireStyles
    <style>
        body { font-family: system-ui, sans-serif; margin: 0; padding: 1.5rem; min-height: 100vh; background: #fafaf9; color: #1c1917; }
        .wrap { max-width: 42rem; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="wrap">
        <p class="mb-4">
            <a href="{{ route('home') }}" class="text-xl font-bold text-stone-800 hover:text-orange-600">Estimo</a>
            <span class="text-stone-400 mx-2">/</span>
            <a href="{{ route('materials.app') }}" class="text-stone-600 hover:text-stone-800">Kalkulator materiałów</a>
        </p>
        @livewire('quote-summary')
    </div>
    @livewireScripts
</body>
</html>
