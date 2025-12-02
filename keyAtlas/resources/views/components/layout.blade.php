<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KEYATLAS - Tu repositorio de atajos de teclado</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                        dark: {
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col">
    <!-- Header/Navigation -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center mr-4">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2" name="logo_encuadre">
                        <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-keyboard text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900">KEYATLAS</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    
                    <a href=/buscar class="text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200">
                        Buscar
                    </a>
                    <a href=/applications_view class="text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200">
                        Aplicaciones
                    </a>
                    <a href=/mis_shortcuts class="text-gray-700 hover:text-primary-600 font-medium transition-colors duration-200">
                        Mis atajos
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="hidden lg:block flex-1 max-w-md mx-8 relative">
                    <div class="relative">
                        <input 
                            id="globalQuickSearch"
                            aria-label="Buscar atajos y aplicaciones"
                            type="text" 
                            placeholder="Buscar atajos, programas, comandos..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                            autocomplete="off"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>

                    <!-- Quick search dropdown -->
                    <div id="globalQuickResults" class="absolute left-0 right-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden" style="max-height:420px; overflow:auto;">
                        <div class="p-3 border-b">
                            <span class="text-sm text-gray-600">Resultados rápidos</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                            <div class="p-3 border-r md:border-r border-gray-100">
                                <div class="text-xs font-semibold text-gray-500 mb-2">Atajos</div>
                                <div id="shortcutsPreview" class="space-y-2 text-sm text-gray-700 px-1"></div>
                            </div>
                            <div class="p-3">
                                <div class="text-xs font-semibold text-gray-500 mb-2">Aplicaciones</div>
                                <div id="appsPreview" class="space-y-2 text-sm text-gray-700 px-1"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Mobile Search Button -->
                    <button class="lg:hidden p-2 text-gray-600 hover:text-primary-600">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- User Actions -->
                    @auth
                        <div class="flex items-center space-x-3">
                            <a href="{{ url('/perfil') }}" class="flex items-center space-x-2 bg-primary-50 hover:bg-primary-100 px-3 py-2 rounded-lg transition-colors duration-200">
                                <i class="fas fa-user-circle text-primary-600"></i>
                                <span class="text-sm font-medium text-primary-700">Mi Espacio</span>
                            </a>
                        </div>
                    @else
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 font-medium">
                                Iniciar Sesión
                            </a>
                            <a href="{{ route('register') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                Registrarse
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Mobile Search -->
    <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 hidden" id="mobileSearch">
            <div class="relative">
            <input 
                id="mobileQuickSearch"
                type="text" 
                placeholder="Buscar atajos, programas, comandos..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                autocomplete="off"
            >
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>

            <!-- Mobile quick results (same structure) -->
            <div id="mobileQuickResults" class="absolute left-0 right-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden" style="max-height:320px; overflow:auto;">
                <div class="p-2 border-b text-sm text-gray-600">Resultados rápidos</div>
                <div class="p-3">
                    <div class="text-xs font-semibold text-gray-500 mb-2">Atajos</div>
                    <div id="shortcutsPreviewMobile" class="space-y-2 text-sm text-gray-700"></div>
                    <div class="mt-3 text-xs font-semibold text-gray-500 mb-2">Aplicaciones</div>
                    <div id="appsPreviewMobile" class="space-y-2 text-sm text-gray-700"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-dark-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-keyboard text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold">KEYATLAS</span>
                    </div>
                    <p class="text-gray-300 mb-4 max-w-md">
                        Tu repositorio centralizado de atajos de teclado y comandos. Mejora tu productividad aprendiendo y consultando combinaciones de teclas para tus programas favoritos.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-semibold mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href=/buscar class="text-gray-300 hover:text-white transition-colors duration-200">Buscar</a></li>
                        <li><a href=/applications_view class="text-gray-300 hover:text-white transition-colors duration-200">Programas</a></li>
                        <li><a href="{{ url('/categorias') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Categorías</a></li>
                        <li><a href="{{ url('/favoritos') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Mis Atajos</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="font-semibold mb-4">Soporte</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Centro de Ayuda</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Contacto</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Política de Privacidad</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Términos de Servicio</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} KEYATLAS. Todos los derechos reservados.
                </p>
                <p class="text-gray-400 text-sm mt-2 md:mt-0">
                    Hecho con <i class="fas fa-heart text-red-500"></i> para mejorar tu productividad
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript para funcionalidades básicas -->
    <script>
        // Toggle mobile search & global quick search
        document.addEventListener('DOMContentLoaded', function() {
            const searchButton = document.querySelector('button[class*="lg:hidden"]');
            const mobileSearch = document.getElementById('mobileSearch');
            
            if (searchButton && mobileSearch) {
                searchButton.addEventListener('click', function() {
                    mobileSearch.classList.toggle('hidden');
                });
            }

            // Quick search implementation
            const desktopInput = document.getElementById('globalQuickSearch');
            const desktopDropdown = document.getElementById('globalQuickResults');
            const shortcutsPreview = document.getElementById('shortcutsPreview');
            const appsPreview = document.getElementById('appsPreview');

            const mobileInput = document.getElementById('mobileQuickSearch');
            const mobileDropdown = document.getElementById('mobileQuickResults');
            const shortcutsPreviewMobile = document.getElementById('shortcutsPreviewMobile');
            const appsPreviewMobile = document.getElementById('appsPreviewMobile');

            function debounce(fn, wait){ let t; return function(...args){ clearTimeout(t); t = setTimeout(()=> fn.apply(this,args), wait); }}

            // Try multiple paths — helpful if API is namespaced with /api
            async function tryFetchJSON(paths){
                for(const p of paths){
                    try {
                        const r = await fetch(p);
                        if(!r.ok) continue;
                        const json = await r.json();
                        return json;
                    } catch(e){ /* try next */ }
                }
                return null;
            }

            function escapeHtml(s){ if(!s) return ''; return String(s).replace(/[&<>"'`]/g, (c)=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;','`':'&#96;'})[c]); }

            function renderShortcutRow(item){
                const keys = escapeHtml(item.keys || '');
                const desc = escapeHtml(item.description || '');
                // display keys first, small description underneath
                return `<a href="/shortcuts/${item.id}" class="block px-2 py-2 rounded hover:bg-gray-50 border border-transparent flex items-start gap-3 hover:border-gray-100">
                            <div class="flex-none px-2 py-1 rounded bg-gray-100 text-xs text-gray-700 font-semibold">${keys}</div>
                            <div class="flex-1 text-sm text-gray-700 truncate">${desc}</div>
                        </a>`;
            }

            function renderAppRow(item){
                const name = escapeHtml(item.name || '');
                const desc = escapeHtml(item.description || '');
                // link to programas page (friendly) — fallback to /applications/:id is fine when page exists
                const href = `/programas/${item.id}`;
                return `<a href="${href}" class="block px-2 py-2 rounded hover:bg-gray-50 border border-transparent hover:border-gray-100">
                            <div class="text-sm text-gray-800 font-semibold truncate">${name}</div>
                            ${desc ? `<div class="text-xs text-gray-400 truncate">${desc}</div>` : ''}
                        </a>`;
            }

            async function performQuickSearch(q){
                if(!q || q.length < 1) return {shortcuts: [], apps: []};

                // limit per section (3-5). We'll pick 4
                const perSection = 4;

                // Shortcuts API uses 'query' param in our SearchController
                const searchPaths = [
                    `/api/search?query=${encodeURIComponent(q)}&limit=${perSection}`,
                    `/search?query=${encodeURIComponent(q)}&limit=${perSection}`
                ];

                // Applications API expects 'q'
                const appsPaths = [
                    `/api/applications/search?q=${encodeURIComponent(q)}&limit=${perSection}`,
                    `/applications/search?q=${encodeURIComponent(q)}&limit=${perSection}`
                ];

                // run both in parallel and prefer first success
                const [shortcutsRes, appsRes] = await Promise.all([
                    tryFetchJSON(searchPaths),
                    tryFetchJSON(appsPaths)
                ]);

                return { shortcuts: shortcutsRes || [], apps: appsRes || [] };
            }

            async function showResultsFor(q, mobile=false){
                const desktopMode = !mobile;

                const shortcutsContainer = desktopMode ? shortcutsPreview : shortcutsPreviewMobile;
                const appsContainer = desktopMode ? appsPreview : appsPreviewMobile;
                const dropdown = desktopMode ? desktopDropdown : mobileDropdown;

                // minimal UI/Loading
                shortcutsContainer.innerHTML = `<div class="text-xs text-gray-400 px-2 py-2">Buscando atajos…</div>`;
                appsContainer.innerHTML = `<div class="text-xs text-gray-400 px-2 py-2">Buscando aplicaciones…</div>`;
                dropdown.classList.remove('hidden');

                const {shortcuts, apps} = await performQuickSearch(q);

                // limit ensures smaller lists
                const s = (shortcuts || []).slice(0,4);
                const a = (apps || []).slice(0,4);

                shortcutsContainer.innerHTML = s.length ? s.map(it => renderShortcutRow(it)).join('') : '<div class="text-xs text-gray-400 px-2 py-2">No hay atajos</div>';
                appsContainer.innerHTML = a.length ? a.map(it => renderAppRow(it)).join('') : '<div class="text-xs text-gray-400 px-2 py-2">No hay aplicaciones</div>';
            }

            const desktopHandler = debounce((e) => {
                const val = e.target.value.trim();
                if(!val) { desktopDropdown.classList.add('hidden'); return; }
                showResultsFor(val, false);
            }, 220);

            const mobileHandler = debounce((e) => {
                const val = e.target.value.trim();
                if(!val) { mobileDropdown.classList.add('hidden'); return; }
                showResultsFor(val, true);
            }, 220);

            if(desktopInput){
                desktopInput.addEventListener('input', desktopHandler);
                desktopInput.addEventListener('focus', (e)=>{ if(e.target.value.trim()) showResultsFor(e.target.value.trim(), false); });
            }

            if(mobileInput){
                mobileInput.addEventListener('input', mobileHandler);
                mobileInput.addEventListener('focus', (e)=>{ if(e.target.value.trim()) showResultsFor(e.target.value.trim(), true); });
            }

            // hide dropdown when clicking outside
            document.addEventListener('click', (ev)=>{
                if(desktopDropdown && !desktopDropdown.contains(ev.target) && ev.target !== desktopInput) desktopDropdown.classList.add('hidden');
                if(mobileDropdown && !mobileDropdown.contains(ev.target) && ev.target !== mobileInput) mobileDropdown.classList.add('hidden');
            });

            // close on Escape
            document.addEventListener('keydown', (ev)=>{
                if(ev.key === 'Escape'){
                    if(desktopDropdown) desktopDropdown.classList.add('hidden');
                    if(mobileDropdown) mobileDropdown.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>