<x-layout>
	<section class="py-12">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
				<div class="w-full lg:max-w-2xl">
					<label class="text-sm text-gray-600">Buscar aplicaciones</label>
					<input id="appsPageSearch" type="search" placeholder="Escribe un nombre o descripción..." class="w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
				</div>

				<div class="w-full lg:w-56">
					<label class="text-sm text-gray-600">Filtrar por sistema</label>
					<select id="appsSystemFilter" class="w-full mt-2 px-3 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-primary-500">
						<option value="">Todos los sistemas</option>
					</select>
				</div>
			</div>

			<div id="appsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
				<!-- tarjetas cargando -->
			</div>

			<div id="appsPagination" class="mt-8 flex items-center justify-center space-x-3"></div>
		</div>
	</section>

	<script>
		(function(){
			const $search = document.getElementById('appsPageSearch');
			const $system = document.getElementById('appsSystemFilter');
			const $grid = document.getElementById('appsGrid');
			const $pagination = document.getElementById('appsPagination');

			let state = {
				q: '',
				system_id: '',
				page: 1
			};

			function debounce(fn, wait){ let t; return function(...args){ clearTimeout(t); t = setTimeout(()=> fn.apply(this,args), wait); }}

			function el(html){ const wrap = document.createElement('div'); wrap.innerHTML = html; return wrap.firstElementChild; }

			function escapeHtml(s){ if(!s) return ''; return String(s).replace(/[&<>"'`]/g, (c)=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;','`':'&#96;'})[c]); }

			async function fetchSystems(){
				try{
					const res = await fetch('/systems');
					if(!res.ok) return;
					const items = await res.json();
					items.forEach(sys => {
						const opt = document.createElement('option');
						opt.value = sys.id;
						opt.textContent = sys.name;
						$system.appendChild(opt);
					});
				}catch(e){ console.warn('No se pudieron cargar sistemas', e); }
			}

			async function fetchApplications(){
				$grid.innerHTML = `<div class="col-span-full p-6 text-center text-gray-500">Cargando aplicaciones…</div>`;
				$pagination.innerHTML = '';

				const params = new URLSearchParams();
				if(state.q) params.set('q', state.q);
				if(state.system_id) params.set('system_id', state.system_id);
				if(state.page) params.set('page', state.page);

				// endpoint defined in routes: /applications-list
				const url = '/applications-list?' + params.toString();
				try{
					const res = await fetch(url);
					if(!res.ok) throw new Error('Network');
					const data = await res.json();
					renderGrid(data.data || data);
					renderPagination(data);
				}catch(e){
					$grid.innerHTML = `<div class="col-span-full p-6 text-center text-red-500">Error cargando aplicaciones.</div>`;
					console.error(e);
				}
			}

			function renderGrid(items){
				if(!items || !items.length){
					$grid.innerHTML = `<div class="col-span-full p-6 text-center text-gray-500">No se encontraron aplicaciones.</div>`;
					return;
				}

				$grid.innerHTML = '';
				items.forEach(app => {
					const initials = escapeHtml((app.name || '').charAt(0).toUpperCase());
					const systemName = app.system && app.system.name ? escapeHtml(app.system.name) : '';
					const categories = app.categories || [];

					const card = el(`
						<a href="/programas/${app.id}" class="group block bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
							<div class="flex items-start gap-3 mb-3">
								<div class="w-12 h-12 rounded-md bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center text-primary-700 font-semibold text-lg shadow-sm">${initials}</div>

								<div class="flex-1 min-w-0">
									<div class="flex items-center justify-between gap-3">
										<div class="text-sm font-semibold text-gray-800 truncate">${escapeHtml(app.name)}</div>
										${systemName ? `<div class="text-xs text-gray-500">${systemName}</div>` : ''}
									</div>

									<div class="mt-2 text-sm text-gray-600" style="display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;">
										${escapeHtml(app.description || '')}
									</div>

									<div class="mt-3 flex items-center gap-2 flex-wrap">
										${categories.length ? categories.map(c=> `<span class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded">${escapeHtml(c.name)}</span>`).join('') : ''}
									</div>
								</div>
							</div>

							<div class="mt-4 flex items-center justify-between">
								<div class="inline-flex items-center gap-2 text-xs text-gray-500">
									<span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 rounded text-gray-600">
										<i class="fas fa-keyboard text-[10px]"></i>
									</span>
									<span>Atajos</span>
								</div>

								<div>
									<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-primary-700 bg-primary-50 border border-primary-100 hover:bg-primary-100">Abrir</span>
								</div>
							</div>
						</a>
					`);

					$grid.appendChild(card);
				});
			}

			function renderPagination(paginator){
				// paginator expected to have: current_page, last_page, prev_page_url, next_page_url
				if(!paginator || !paginator.current_page) return;

				const current = paginator.current_page;
				const last = paginator.last_page || 1;

				$pagination.innerHTML = '';

				const makeBtn = (text, disabled, onClick) => {
					const btn = el(`<button class="px-3 py-1 rounded-md text-sm ${disabled? 'text-gray-400 bg-gray-100':'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'}">${text}</button>`);
					if(!disabled) btn.addEventListener('click', onClick);
					return btn;
				};

				// Prev
				$pagination.appendChild(makeBtn('Anterior', current <=1, ()=>{ state.page = Math.max(1, current-1); fetchApplications(); }));

				// pages: show a small window around current
				const windowSize = 5;
				let start = Math.max(1, current - Math.floor(windowSize/2));
				let end = Math.min(last, start + windowSize -1);
				if(end - start < windowSize -1) start = Math.max(1, end - windowSize +1);

				for(let p = start; p <= end; p++){
					const active = p === current;
					const btn = el(`<button class="px-3 py-1 rounded-md text-sm ${active? 'bg-primary-500 text-white':'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'}">${p}</button>`);
					if(!active) btn.addEventListener('click', ()=>{ state.page = p; fetchApplications(); });
					$pagination.appendChild(btn);
				}

				// Next
				$pagination.appendChild(makeBtn('Siguiente', current >= last, ()=>{ state.page = Math.min(last, current+1); fetchApplications(); }));
			}

			// wire events
			$search.addEventListener('input', debounce((e)=>{ state.q = e.target.value.trim(); state.page = 1; fetchApplications(); }, 300));
			$system.addEventListener('change', (e)=>{ state.system_id = e.target.value; state.page = 1; fetchApplications(); });

			// initial load
			fetchSystems().then(()=> fetchApplications());
		})();
	</script>
</x-layout>
