<x-layout>
    <section class="bg-gradient-to-br from-primary-50 to-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-3">Encuentra el atajo perfecto — rápido y sencillo</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Busca por tecla, descripción, aplicación o sistema. Aplica filtros por categoría, aplicación o sistema y obtén resultados relevantes al instante.</p>
            </div>

            <!-- Search / Filters -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col lg:flex-row gap-6 items-start">
                <!-- Big Search -->
                <div class="flex-1">
                    <label class="block text-sm text-gray-500 mb-2">Búsqueda global</label>
                    <div class="relative">
                        <input id="globalSearch" type="text" placeholder="ej. CTRL+C o copiar" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-150" />
                        <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
                    </div>

                    <div class="mt-4 flex items-center gap-2 text-sm text-gray-500">
                        <span class="font-medium text-gray-700">Resultados:</span>
                        <div id="resultStats">—</div>
                        <button id="clearAll" class="ml-auto text-xs bg-gray-100 px-3 py-1 rounded-md text-gray-700 hover:bg-gray-200">Limpiar filtros</button>
                    </div>

                    <!-- Results -->
                    <div id="results" class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
                </div>

                <!-- Sidebar Filters -->
                <aside class="w-full lg:w-80">
                    <div class="space-y-6">
                        <!-- Categories -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-semibold text-gray-700">Categorías</h3>
                                {{-- <span class="text-xs text-gray-500">Randomizadas</span> --}}
                            </div>
                            <div id="categories" class="flex flex-wrap gap-2"></div>
                        </div>

                        <!-- Applications Search + selected -->
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Aplicación</label>
                            <div class="relative">
                                <input id="appSearch" type="search" placeholder="Buscar aplicación (ej. VSCode)" class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <i class="fas fa-search absolute right-3 top-2.5 text-gray-400"></i>
                            </div>
                            <div id="appSuggestions" class="mt-2 bg-white border border-gray-200 rounded-md shadow-sm max-h-48 overflow-auto hidden"></div>

                            <div id="selectedApps" class="mt-3 flex flex-wrap gap-2"></div>
                        </div>

                        <!-- Systems -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Sistema</h3>
                            <div id="systems" class="flex gap-2"></div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <script>
        (function(){
            // --- State ---
            const state = {
                query: '',
                category_ids: [],
                application_ids: [],
                application_objects: [],
                system_id: null,
                limit: 12,
            };

            const $globalSearch = document.getElementById('globalSearch');
            const $results = document.getElementById('results');
            const $resultStats = document.getElementById('resultStats');
            const $categories = document.getElementById('categories');
            const $systems = document.getElementById('systems');
            const $clearAll = document.getElementById('clearAll');

            // helpers
            function el(html){ const wrap = document.createElement('div'); wrap.innerHTML = html; return wrap.firstElementChild; }
            function debounce(fn, wait) { let t; return function(...args){ clearTimeout(t); t = setTimeout(()=> fn.apply(this,args), wait); } }

            // --- fetch and render ---
            async function fetchRandomCategories(){
                const res = await fetch('/categories/random');
                const items = await res.json();
                renderCategories(items);
            }

            async function fetchSystems(){
                const res = await fetch('/systems');
                const items = await res.json();
                renderSystems(items);
            }

            async function search(){
                const qs = new URLSearchParams();
                if(state.query) qs.set('query', state.query);
                if(state.system_id) qs.set('system_id', state.system_id);
                if(state.application_ids.length) qs.set('application_id', state.application_ids[0]);
                if(state.category_ids.length) state.category_ids.forEach(id => qs.append('category_ids[]', id));
                qs.set('limit', state.limit);

                // If no filters -> hide results
                if(!state.query && !state.system_id && state.application_ids.length === 0 && state.category_ids.length === 0){
                    $results.innerHTML = `<div class="col-span-full px-6 py-8 text-center text-gray-500 border-dashed border-2 border-gray-200 rounded-lg">Escribe algo o aplica filtros para ver resultados.</div>`;
                    $resultStats.textContent = '—';
                    return;
                }

                const url = '/search?' + qs.toString();
                const res = await fetch(url);
                const data = await res.json();

                renderResults(data);
            }

            function renderCategories(items){
                $categories.innerHTML = '';
                items.forEach(cat => {
                    const tag = el(`<button data-id="${cat.id}" class="px-3 py-1 rounded-full border border-gray-200 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition">${cat.name}</button>`);
                    tag.addEventListener('click', () => toggleCategory(cat.id, tag));
                    $categories.appendChild(tag);
                })
            }

            function toggleCategory(id, elTag){
                const idx = state.category_ids.indexOf(id);
                if(idx === -1){
                    state.category_ids.push(id);
                    elTag.classList.add('bg-primary-500','text-white','border-transparent');
                } else {
                    state.category_ids.splice(idx,1);
                    elTag.classList.remove('bg-primary-500','text-white','border-transparent');
                }
                search();
            }

            function renderSystems(items){
                $systems.innerHTML = '';
                items.forEach(sys => {
                    const btn = el(`<button data-id="${sys.id}" class="px-3 py-1 rounded-lg text-sm border border-gray-200 text-gray-700 hover:bg-primary-50 transition">${sys.name}</button>`);
                    btn.addEventListener('click', () => selectSystem(sys.id, btn));
                    $systems.appendChild(btn);
                })
            }

            function selectSystem(id, elBtn){
                // if same id -> toggle off
                if(state.system_id === id){
                    state.system_id = null;
                    elBtn.classList.remove('bg-primary-500','text-white','border-transparent');
                } else {
                    state.system_id = id;
                    // remove active from others
                    $systems.querySelectorAll('button').forEach(b => b.classList.remove('bg-primary-500','text-white','border-transparent'));
                    elBtn.classList.add('bg-primary-500','text-white','border-transparent');
                }
                search();
            }

            function renderResults(items){
                $results.innerHTML = '';
                $resultStats.textContent = `${items.length} coincidencia${items.length === 1 ? '' : 's'}`;

                if(!items.length){
                    $results.innerHTML = `<div class="col-span-full px-6 py-8 text-center text-gray-500 border-dashed border-2 border-gray-200 rounded-lg">No se encontraron coincidencias.</div>`;
                    return;
                }

                items.forEach(item => {
                    const html = `
                    <a href="/shortcuts/${item.id}" class="block bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-150 group">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-sm text-gray-500 flex items-center gap-2">
                                <span class="px-2 py-1 rounded-md bg-gray-100 text-xs text-gray-700">${item.application?.name ?? '─'}</span>
                                <span class="px-2 py-1 rounded-md bg-gray-100 text-xs text-gray-700">${item.system?.name ?? '─'}</span>
                            </div>
                            <div class="text-xs text-gray-400 group-hover:text-primary-600">Ver</div>
                        </div>
                        <div class="text-gray-900 font-semibold mb-2 truncate">${escapeHtml(item.keys ?? item.description ?? 'Sin descripción')}</div>
                        <div class="text-sm text-gray-500" style="max-height:3rem; overflow:hidden">${escapeHtml(item.description ?? '')}</div>
                    </a>`;

                    $results.appendChild(el(html));
                })
            }

            // --- app search suggestions ---
            const $appSearch = document.getElementById('appSearch');
            const $appSuggestions = document.getElementById('appSuggestions');
            const $selectedApps = document.getElementById('selectedApps');

            async function fetchAppSuggestions(q){
                if(!q || q.length < 1){ $appSuggestions.classList.add('hidden'); return; }
                const res = await fetch(`/applications/search?q=${encodeURIComponent(q)}`);
                const items = await res.json();
                renderAppSuggestions(items);
            }

            function renderAppSuggestions(items){
                $appSuggestions.innerHTML = '';
                if(!items.length){ $appSuggestions.innerHTML = `<div class="p-2 text-sm text-gray-400">No hay resultados</div>`; $appSuggestions.classList.remove('hidden'); return; }
                items.forEach(app => {
                    const row = el(`<div class="px-3 py-2 hover:bg-gray-50 cursor-pointer text-sm text-gray-700 border-b last:border-b-0">${app.name}<div class="text-xs text-gray-400">${app.description ?? ''}</div></div>`);
                    row.addEventListener('click', () => selectApp(app));
                    $appSuggestions.appendChild(row);
                })
                $appSuggestions.classList.remove('hidden');
            }

            function selectApp(app){
                if(state.application_ids.includes(app.id)) return; // already selected
                state.application_ids.push(app.id);
                state.application_objects.push(app);
                renderSelectedApps();
                $appSuggestions.classList.add('hidden');
                $appSearch.value = '';
                search();
            }

            function renderSelectedApps(){
                $selectedApps.innerHTML = '';
                state.application_objects.forEach(app => {
                    const chip = el(`<div class="flex items-center gap-2 bg-primary-50 text-primary-700 px-3 py-1 rounded-full text-sm border border-transparent">${app.name} <button data-id="${app.id}" class="ml-2 text-xs bg-white rounded-full px-1 border text-gray-500 hover:bg-gray-100">✕</button></div>`);
                    chip.querySelector('button').addEventListener('click', () => removeApp(app.id));
                    $selectedApps.appendChild(chip);
                })
            }

            function removeApp(id){
                const idx = state.application_ids.indexOf(id);
                if(idx>-1){ state.application_ids.splice(idx,1); state.application_objects.splice(idx,1); renderSelectedApps(); search(); }
            }

            // --- utils ---
            function escapeHtml(str){ if(!str) return ''; return String(str).replace(/[&<>"'`]/g, (s) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;','`':'&#96;'})[s]); }

            // --- initial load & event wiring ---
            $globalSearch.addEventListener('input', debounce((e)=>{ state.query = e.target.value.trim(); search(); }, 300));
            $clearAll.addEventListener('click', () => { state.query=''; state.category_ids = []; state.application_ids=[]; state.application_objects=[]; state.system_id=null; $globalSearch.value=''; $appSearch.value=''; document.querySelectorAll('#categories button').forEach(b=>b.classList.remove('bg-primary-500','text-white','border-transparent')); document.querySelectorAll('#systems button').forEach(b=>b.classList.remove('bg-primary-500','text-white','border-transparent')); renderSelectedApps(); search(); });

            $appSearch.addEventListener('input', debounce((e)=> fetchAppSuggestions(e.target.value.trim()), 250));
            document.addEventListener('click', (ev)=>{ if(!$appSuggestions.contains(ev.target) && ev.target !== $appSearch) $appSuggestions.classList.add('hidden'); });

            // initial
            fetchRandomCategories();
            fetchSystems();
            search();
        })();
    </script>

</x-layout>
