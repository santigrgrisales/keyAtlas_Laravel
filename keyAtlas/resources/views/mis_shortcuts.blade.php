<x-layout>
  <section class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Agregar atajo (Mis Shortcuts)</h1>
        <p class="text-sm text-gray-500 mb-6">Crea un nuevo atajo para tu aplicación favorita. Solo completa la descripción, elige la aplicación y categoría, arma la combinación de teclas y guarda.</p>

        <form id="shortcutForm" class="space-y-6" action="/mis-shortcuts" method="post">
          <!-- NOTE: route is not protected by CSRF per routes/web.php; using fetch so we won't include token here -->

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Application selector (typeahead) -->
            <div>
              <label class="text-sm font-semibold text-gray-700">Aplicación</label>
              <div class="relative mt-2">
                <input id="appSearchInput" type="search" placeholder="Buscar aplicación (ej. VSCode)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500" autocomplete="off">
                <div id="appSuggestions" class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-md shadow-sm z-50 hidden max-h-48 overflow-auto"></div>
              </div>

              <div id="selectedApp" class="mt-3"></div>
            </div>

            <!-- Category select -->
            <div>
              <label class="text-sm font-semibold text-gray-700">Categoría</label>
              <select id="categorySelect" class="w-full mt-2 px-3 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-primary-500">
                <option value="">-- Selecciona una categoría --</option>
              </select>
            </div>
          </div>

          <!-- System select and optional meta -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
            <div>
              <label class="text-sm font-semibold text-gray-700">Sistema</label>
              <select id="systemSelect" class="w-full mt-2 px-3 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-primary-500">
                <option value="">-- Selecciona un sistema --</option>
              </select>
              <p class="text-xs text-gray-400 mt-1">Puedes seleccionar manualmente o dejar que la aplicación determine el sistema.</p>
            </div>

            <div class="text-right">
              <label class="text-sm font-semibold text-gray-700 invisible">placeholder</label>
              <div class="mt-2 text-sm text-gray-600">Teclas mínimas 2 — puedes agregar más</div>
            </div>
          </div>

          <!-- Key builder -->
          <div class="bg-gray-50 border border-gray-100 p-4 rounded-lg">
            <label class="text-sm font-semibold text-gray-700">Armar combinación de teclas</label>

            <div class="mt-3 space-y-3">
              <div class="flex gap-2 items-center">
                <div class="inline-flex gap-2 bg-white border border-gray-200 rounded px-2 py-2 items-center" id="keyTokens">
                  <!-- token pills inserted here -->
                </div>

                <div class="flex items-center gap-2">
                  <button type="button" data-key="Ctrl" class="addKeyBtn inline-flex items-center px-3 py-1 rounded bg-primary-50 text-primary-700 border border-primary-100 text-sm">Ctrl</button>
                  <button type="button" data-key="Shift" class="addKeyBtn inline-flex items-center px-3 py-1 rounded bg-primary-50 text-primary-700 border border-primary-100 text-sm">Shift</button>
                  <button type="button" data-key="Alt" class="addKeyBtn inline-flex items-center px-3 py-1 rounded bg-primary-50 text-primary-700 border border-primary-100 text-sm">Alt</button>
                  <button type="button" data-key="Cmd" class="addKeyBtn inline-flex items-center px-3 py-1 rounded bg-primary-50 text-primary-700 border border-primary-100 text-sm">Cmd</button>
                </div>
              </div>

              <div class="flex gap-2 items-center">
                <input id="customKeyInput" type="text" placeholder="Escribe una tecla (ej. T, F5)" class="px-3 py-2 border border-gray-300 rounded-lg flex-1">
                <button id="addCustomKey" type="button" class="px-4 py-2 rounded bg-primary-500 text-white hover:bg-primary-600">Añadir</button>
                <button id="removeLastKey" type="button" class="px-4 py-2 rounded bg-red-50 text-red-700 border border-red-100 hover:bg-red-100">Quitar</button>
                <button id="clearKeys" type="button" class="px-4 py-2 rounded bg-gray-50 text-gray-700 border border-gray-100 hover:bg-gray-100">Limpiar</button>
              </div>

              <div class="text-xs text-gray-400">Ejemplo guardado: <em id="previewKeys">—</em></div>

            </div>
          </div>

          <!-- Description (only user input field) -->
          <div>
            <label class="text-sm font-semibold text-gray-700">Descripción</label>
            <textarea id="descriptionInput" rows="3" class="w-full mt-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500" placeholder="Describe qué hace este atajo (ej. Copiar al portapapeles)"></textarea>
          </div>

          <!-- Result / submit -->
          <div class="flex items-center justify-between gap-3">
            <div id="formMessage" class="text-sm text-gray-500"></div>
            <div class="flex items-center gap-3">
              <button id="previewBtn" type="button" class="px-4 py-2 rounded bg-white text-gray-700 border border-gray-200 hover:bg-gray-50">Vista previa</button>
              <button id="submitBtn" type="button" class="px-4 py-2 rounded bg-primary-600 hover:bg-primary-700 text-white">Guardar atajo</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </section>

  <script>
    (function(){
      // elements
      const appSearch = document.getElementById('appSearchInput');
      const appSuggestions = document.getElementById('appSuggestions');
      const selectedApp = document.getElementById('selectedApp');
      const categorySelect = document.getElementById('categorySelect');
      const systemSelect = document.getElementById('systemSelect');

      const keyTokens = document.getElementById('keyTokens');
      const customKeyInput = document.getElementById('customKeyInput');
      const addCustomKey = document.getElementById('addCustomKey');
      const removeLastKey = document.getElementById('removeLastKey');
      const clearKeys = document.getElementById('clearKeys');
      const previewKeys = document.getElementById('previewKeys');

      const descriptionInput = document.getElementById('descriptionInput');
      const submitBtn = document.getElementById('submitBtn');
      const formMessage = document.getElementById('formMessage');

      // state
      let appObj = null; // { id, name, description }
      let keys = []; // array of strings

      // utils
      const debounce = (fn, wait=250)=>{ let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn.apply(this,a), wait);} };
      const el = (html) => { const d=document.createElement('div'); d.innerHTML=html; return d.firstElementChild };
      const escapeHtml = (s)=> s ? String(s).replace(/[&<>"'`]/g, (c)=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;','`':'&#96;'})[c]) : '';

      // fetch categories & systems
      async function loadMeta(){
        try{
          const cRes = await fetch('/categories');
          if(cRes.ok){
            const cats = await cRes.json();
            cats.forEach(c=>{ const o = document.createElement('option'); o.value=c.id; o.textContent=c.name; categorySelect.appendChild(o); });
          }
        }catch(e){ console.error('categories',e); }

        try{
          const sRes = await fetch('/systems');
          if(sRes.ok){
            const ss = await sRes.json();
            ss.forEach(s=>{ const o = document.createElement('option'); o.value=s.id; o.textContent=s.name; systemSelect.appendChild(o); });
          }
        }catch(e){ console.error('systems',e); }
      }

      // app typeahead
      async function fetchAppSuggestions(q){
        if(!q || q.length<1){ appSuggestions.classList.add('hidden'); return; }
        try{
          const res = await fetch(`/applications/search?q=${encodeURIComponent(q)}`);
          if(!res.ok) { appSuggestions.classList.remove('hidden'); appSuggestions.innerHTML = '<div class="p-2 text-sm text-gray-400">Error</div>'; return; }
          const list = await res.json();
          appSuggestions.innerHTML = '';
          if(!list.length) appSuggestions.innerHTML = '<div class="p-2 text-sm text-gray-400">No hay resultados</div>';
          list.forEach(app=>{
            const row = el(`<div class=\"px-3 py-2 hover:bg-gray-50 cursor-pointer\"><div class=\"text-sm font-medium\">${escapeHtml(app.name)}</div><div class=\"text-xs text-gray-400\">${escapeHtml(app.description||'')}</div></div>`);
            row.addEventListener('click', ()=> selectApp(app));
            appSuggestions.appendChild(row);
          });
          appSuggestions.classList.remove('hidden');
        }catch(e){ console.error(e); }
      }

      function selectApp(app){
        appObj = app;
        appSuggestions.classList.add('hidden');
        appSearch.value = '';
        selectedApp.innerHTML = `<div class=\"flex items-center gap-3 bg-primary-50 border border-primary-100 px-3 py-2 rounded-md\"><div class=\"w-8 h-8 rounded bg-primary-100 flex items-center justify-center text-primary-700 font-semibold\">${escapeHtml((app.name||'')[0]||'?')}</div><div class=\"text-sm\"><div class=\"font-semibold\">${escapeHtml(app.name)}</div><div class=\"text-xs text-gray-400\">${escapeHtml(app.description||'')}</div></div><button id=\"deselectApp\" class=\"ml-3 text-xs px-2 py-1 rounded bg-white border border-gray-200 text-gray-700\">Quitar</button></div>`;
        const btn = document.getElementById('deselectApp'); btn.addEventListener('click', ()=>{ appObj=null; selectedApp.innerHTML=''; });

        // try to set system automatically if app includes system
        if(app.system_id){ systemSelect.value = app.system_id; }
      }

      // keys handling
      function renderTokens(){
        keyTokens.innerHTML = '';
        keys.forEach((k, i)=>{
          const pill = el(`<span class=\"inline-flex items-center gap-2 bg-gray-100 px-3 py-1 rounded-full text-sm text-gray-800 mr-1\">${escapeHtml(k)} <button data-index=\"${i}\" class=\"ml-1 text-xs text-gray-400 hover:text-red-600\">✕</button></span>`);
          pill.querySelector('button').addEventListener('click', ()=>{ keys.splice(i,1); updatePreview(); renderTokens(); });
          keyTokens.appendChild(pill);
        })
        updatePreview();
      }

      function updatePreview(){
        previewKeys.textContent = keys.length ? keys.join(' + ') : '—';
      }

      // quick modifiers
      document.querySelectorAll('.addKeyBtn').forEach(b=> b.addEventListener('click', ()=>{ keys.push(b.dataset.key); renderTokens(); }));
      addCustomKey.addEventListener('click', ()=>{ const v = customKeyInput.value.trim(); if(!v) return; keys.push(v); customKeyInput.value=''; renderTokens(); });
      removeLastKey.addEventListener('click', ()=>{ keys.pop(); renderTokens(); });
      clearKeys.addEventListener('click', ()=>{ keys = []; renderTokens(); });

      // preview button just shows collected data small modal / message
      document.getElementById('previewBtn').addEventListener('click', ()=>{
        if(!appObj){ formMessage.innerHTML = '<span class="text-sm text-red-500">Selecciona una aplicación primero.</span>'; return; }
        if(!categorySelect.value){ formMessage.innerHTML = '<span class="text-sm text-red-500">Selecciona una categoría.</span>'; return; }
        if(keys.length < 1){ formMessage.innerHTML = '<span class="text-sm text-red-500">Agrega al menos 1 tecla.</span>'; return; }
        formMessage.innerHTML = `<div class=\"text-sm text-gray-700\"><strong>Aplicación:</strong> ${escapeHtml(appObj.name)} &nbsp; <strong>Keys:</strong> ${escapeHtml(keys.join(' + '))} &nbsp; <strong>Desc:</strong> ${escapeHtml(descriptionInput.value)}</div>`;
      });

      // submit
      submitBtn.addEventListener('click', async ()=>{
        formMessage.innerHTML = '';

        if(!appObj){ formMessage.innerHTML = '<span class="text-sm text-red-500">Selecciona una aplicación.</span>'; return; }
        if(!categorySelect.value){ formMessage.innerHTML = '<span class="text-sm text-red-500">Selecciona una categoría.</span>'; return; }
        if(!systemSelect.value){ formMessage.innerHTML = '<span class="text-sm text-red-500">Selecciona el sistema.</span>'; return; }
        const keysStr = keys.join(' + ');
        if(!keysStr){ formMessage.innerHTML = '<span class="text-sm text-red-500">La combinación de teclas no puede estar vacía.</span>'; return; }
        if(!descriptionInput.value.trim()){ formMessage.innerHTML = '<span class="text-sm text-red-500">La descripción es obligatoria.</span>'; return; }

        submitBtn.disabled = true; submitBtn.textContent = 'Guardando...';

        try{
          const payload = {
            application_id: appObj.id,
            category_id: categorySelect.value,
            system_id: systemSelect.value,
            keys: keysStr,
            description: descriptionInput.value.trim()
          };

          const res = await fetch('/mis-shortcuts', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json'   // <= importante: permite que Laravel devuelva JSON de errores/validación
            },
            body: JSON.stringify(payload)
          });

          // tratar la respuesta con más robustez
          let json = null;
          const text = await res.text();
          try { json = text ? JSON.parse(text) : null; } catch(e) { json = null; }

          if(res.ok && json && json.success){
            formMessage.innerHTML = '<span class="text-sm text-green-600">Atajo guardado correctamente.</span>';
            // reset
            appObj=null; selectedApp.innerHTML=''; categorySelect.value=''; systemSelect.value=''; keys = []; renderTokens(); descriptionInput.value='';
          }else{
            // intentar mostrar errores detallados
            let err = 'Error guardando';
            if(json){
              if(json.errors){
                err = Object.values(json.errors).flat().join('; ');
              } else if(json.message){
                err = json.message;
              } else if(json.error){
                err = json.error;
              }
            } else {
              // si no hay JSON, usar status
              err = `Error del servidor (status ${res.status})`;
            }
            formMessage.innerHTML = `<span class="text-sm text-red-500">${escapeHtml(err)}</span>`;
          }

        }catch(e){
          console.error(e);
          formMessage.innerHTML = '<span class="text-sm text-red-500">Error al conectarse al servidor.</span>';
        }finally{ submitBtn.disabled=false; submitBtn.textContent='Guardar atajo'; }

      });

      // attach input handlers
      appSearch.addEventListener('input', debounce(e=>fetchAppSuggestions(e.target.value), 200));
      document.addEventListener('click', (ev)=>{ if(!appSuggestions.contains(ev.target) && ev.target !== appSearch) appSuggestions.classList.add('hidden'); });

      // init
      loadMeta(); renderTokens();

    })();
  </script>

</x-layout>
