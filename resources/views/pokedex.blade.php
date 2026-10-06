<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pokemon-ui.css') }}">
</head>
<body class="page-dex">

    <header class="nav"><div class="nav-in">
        <a href="/" class="brand"><span class="ball"></span>Pokédex</a>
        <nav class="nav-links"><a href="/" class="on">Pokédex</a><a href="/guess">Guess</a><a href="/battle">Battle</a></nav>
    </div></header>

    <div class="container">
        <div class="page-header">
            <h2 class="page-title">National Pokédex</h2>
            <span class="pokemon-count" id="countDisplay">Loaded: 0 Pokémon</span>
        </div>

        <div class="filter-section">
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search by name or number — e.g. charizard, 6">
                <button id="randomBtn" class="btn" onclick="openRandom()">🎲 Surprise me</button>
            </div>

                        <div class="type-pill-container" id="typeContainer">
                <div class="type-pill active" data-type="all" onclick="filterByType('all')">All</div>
                <div class="type-pill bg-normal" data-type="normal" onclick="filterByType('normal')">Normal</div>
                <div class="type-pill bg-fire" data-type="fire" onclick="filterByType('fire')">Fire</div>
                <div class="type-pill bg-water" data-type="water" onclick="filterByType('water')">Water</div>
                <div class="type-pill bg-electric" data-type="electric" onclick="filterByType('electric')">Electric</div>
                <div class="type-pill bg-grass" data-type="grass" onclick="filterByType('grass')">Grass</div>
                <div class="type-pill bg-ice" data-type="ice" onclick="filterByType('ice')">Ice</div>
                <div class="type-pill bg-fighting" data-type="fighting" onclick="filterByType('fighting')">Fighting</div>
                <div class="type-pill bg-poison" data-type="poison" onclick="filterByType('poison')">Poison</div>
                <div class="type-pill bg-ground" data-type="ground" onclick="filterByType('ground')">Ground</div>
                <div class="type-pill bg-flying" data-type="flying" onclick="filterByType('flying')">Flying</div>
                <div class="type-pill bg-psychic" data-type="psychic" onclick="filterByType('psychic')">Psychic</div>
                <div class="type-pill bg-bug" data-type="bug" onclick="filterByType('bug')">Bug</div>
                <div class="type-pill bg-rock" data-type="rock" onclick="filterByType('rock')">Rock</div>
                <div class="type-pill bg-ghost" data-type="ghost" onclick="filterByType('ghost')">Ghost</div>
                <div class="type-pill bg-dragon" data-type="dragon" onclick="filterByType('dragon')">Dragon</div>
                <div class="type-pill bg-dark" data-type="dark" onclick="filterByType('dark')">Dark</div>
                <div class="type-pill bg-steel" data-type="steel" onclick="filterByType('steel')">Steel</div>
                <div class="type-pill bg-fairy" data-type="fairy" onclick="filterByType('fairy')">Fairy</div>
            </div>
        </div>

        <div class="pokemon-grid" id="pokemonGrid">
            <!-- Cards injected by JS -->
        </div>

        <div class="loader" id="loader">Loading Pokémon...</div>

        <div class="load-more-container">
            <button class="load-more-btn" id="loadMoreBtn" onclick="loadMore()">Load more</button>
        </div>
    </div>

    <!-- Quick Detail Modal -->
    <div class="modal-backdrop" id="pokeModal" onclick="closeModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeModalDirect()">✕</button>
            <div class="modal-header">
                <img id="mImg" src="" alt="">
                <button class="icon-btn" onclick="playCry()" title="Play cry">🔊</button>
            </div>
            <div class="modal-body">
                <div class="modal-title-row">
                    <h2 id="mName">Pokemon</h2>
                    <span class="modal-id" id="mId">#0001</span>
                </div>
                <div class="types" id="mTypes" style="margin-bottom: 15px;"></div>
                
                <div class="modal-meta-grid">
                    <div><strong>Height</strong><span id="mHeight">0.7 m</span></div>
                    <div><strong>Weight</strong><span id="mWeight">6.9 kg</span></div>
                    <div><strong>Base EXP</strong><span id="mExp">64</span></div>
                </div>

                <div style="margin-top: 15px;">
                    <div style="font-weight:600; margin-bottom:10px;">Base stats</div>
                    <div id="mStats"></div>
                </div>

                <div class="modal-actions">
                    <a id="mBattleBtn" href="/battle" class="modal-battle-btn">⚔️ Battle with this Pokémon</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let allPokemon = [];
        let typeFilteredPokemon = null; // null means 'all'
        let currentPool = [];
        let filteredPokemon = [];
        let currentIndex = 0;
        let searchToken = 0;
        let searchDebounceTimer = null;
        let selectedType = 'all';
        const detailCache = new Map();
        const LIMIT = 24;

        const grid = document.getElementById('pokemonGrid');
        const loader = document.getElementById('loader');
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        const searchInput = document.getElementById('searchInput');
        const countDisplay = document.getElementById('countDisplay');
        const modal = document.getElementById('pokeModal');
        let currentCryUrl = null;

        // Init
        const TYPES = ['normal','fire','water','electric','grass','ice','fighting','poison','ground','flying','psychic','bug','rock','ghost','dragon','steel','dark','fairy'];
        const typeMap = new Map(); // id -> type names, built once from the 18 type endpoints
        const ART_URL = id => `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${id}.png`;
        const SPRITE_URL = id => `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${id}.png`;
        const idFromUrl = url => { const m = url.match(/\/pokemon\/(\d+)\//); return m ? parseInt(m[1]) : 0; };
        const typesOf = id => (typeMap.get(id) || []).filter(Boolean);

        // Init: 1 list request + 18 small type requests. Cards then render instantly with no per-card downloads;
        // the full Pokémon data is only fetched when a card is opened.
        async function init() {
            loader.innerText = 'Loading Pokémon...';
            loader.style.display = 'block';
            try {
                const [listRes, ...typeRes] = await Promise.all([
                    fetch('https://pokeapi.co/api/v2/pokemon?limit=1025'),
                    ...TYPES.map(t => fetch(`https://pokeapi.co/api/v2/type/${t}`).then(r => r.ok ? r.json() : null).catch(() => null))
                ]);
                if (!listRes.ok) throw new Error('bad response');
                const data = await listRes.json();
                allPokemon = data.results.map((p, i) => ({ id: idFromUrl(p.url) || i + 1, name: p.name, url: p.url }));
                typeRes.forEach((t, i) => {
                    if (!t) return;
                    t.pokemon.forEach(e => {
                        const id = idFromUrl(e.pokemon.url);
                        if (id > 0 && id <= 1025) {
                            const arr = typeMap.get(id) || [];
                            arr[e.slot - 1] = TYPES[i];
                            typeMap.set(id, arr);
                        }
                    });
                });
                currentPool = [...allPokemon];
                applyFilterAndSearch();
            } catch (err) {
                loader.innerText = 'Failed to load Pokémon. Please check your internet connection.';
            }
        }

        function setActivePill(type) {
            document.querySelectorAll('.type-pill').forEach(pill => pill.classList.toggle('active', pill.dataset.type === type));
        }

        // Filtering is now local (no requests), so there are no stale-response races.
        function filterByType(type) {
            selectedType = type;
            setActivePill(type);
            currentPool = type === 'all' ? [...allPokemon] : allPokemon.filter(p => typesOf(p.id).includes(type));
            applyFilterAndSearch();
        }

        function applyFilterAndSearch() {
            const query = searchInput.value.toLowerCase().trim().replace(/^#/, '').replace(/\s+/g, '-');
            grid.innerHTML = '';
            currentIndex = 0;
            filteredPokemon = query === '' ? [...currentPool]
                : currentPool.filter(p => p.name.includes(query) || p.id.toString() === query);
            countDisplay.innerText = `Showing ${filteredPokemon.length} Pokémon`;
            loadMore();
        }

        async function fetchDetails(url) {
            if (!detailCache.has(url)) {
                const req = fetch(url).then(r => { if (!r.ok) throw new Error('bad response'); return r.json(); });
                detailCache.set(url, req);
                req.catch(() => detailCache.delete(url));
            }
            return detailCache.get(url);
        }

        function loadMore() {
            loader.style.display = 'none';
            const batch = filteredPokemon.slice(currentIndex, currentIndex + LIMIT);
            if (batch.length === 0) {
                loadMoreBtn.style.display = 'none';
                if (filteredPokemon.length === 0) {
                    grid.innerHTML = '<p style="grid-column: 1/-1; text-align:center; color:var(--text-2); padding: 40px 0;">No Pokémon found matching the selected filters.</p>';
                }
                return;
            }
            batch.forEach(p => {
                const card = document.createElement('div');
                card.className = 'pokemon-card';
                card.tabIndex = 0;
                card.setAttribute('role', 'button');
                const open = async () => {
                    try { openModal(await fetchDetails(p.url)); }
                    catch (e) { countDisplay.innerText = 'Could not load details. Try again.'; }
                };
                card.onclick = open;
                card.onkeydown = e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); } };
                const typesHtml = typesOf(p.id).map(t => `<div class="type-badge bg-${t}">${t}</div>`).join('');
                card.innerHTML = `
                    <button class="card-battle-quick-btn" onclick="event.stopPropagation(); window.location.href='/battle?p1=${p.id}'">⚔️ Battle</button>
                    <div class="img-container">
                        <img src="${ART_URL(p.id)}" alt="${p.name}" loading="lazy"
                             onerror="this.onerror=null;this.src='${SPRITE_URL(p.id)}'">
                    </div>
                    <div class="card-info">
                        <p class="poke-id">#${p.id.toString().padStart(4, '0')}</p>
                        <h3 class="poke-name">${p.name.replace(/-/g, ' ')}</h3>
                        <div class="types">${typesHtml}</div>
                    </div>`;
                grid.appendChild(card);
            });
            currentIndex += LIMIT;
            loadMoreBtn.style.display = currentIndex < filteredPokemon.length ? 'inline-block' : 'none';
        }

        // Live Search with Debounce
        searchInput.addEventListener('input', () => {
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(() => {
                applyFilterAndSearch();
            }, 300);
        });

        // Detail Modal Logic
        function openModal(data) {
            document.getElementById('mName').innerText = data.name;
            document.getElementById('mId').innerText = '#' + data.id.toString().padStart(4, '0');
            document.getElementById('mImg').src = data.sprites.other['official-artwork'].front_default || data.sprites.front_default;
            document.getElementById('mHeight').innerText = (data.height / 10) + ' m';
            document.getElementById('mWeight').innerText = (data.weight / 10) + ' kg';
            document.getElementById('mExp').innerText = data.base_experience || '-';
            document.getElementById('mBattleBtn').href = `/battle?p1=${data.id}`;
            
            if (data.cries && data.cries.latest) {
                currentCryUrl = data.cries.latest;
            } else {
                currentCryUrl = null;
            }

            // Types
            let typesHtml = '';
            data.types.forEach(t => {
                typesHtml += `<div class="type-badge bg-${t.type.name}">${t.type.name}</div>`;
            });
            document.getElementById('mTypes').innerHTML = typesHtml;

            // Stats
            const statLabels = {
                'hp': 'HP',
                'attack': 'Attack',
                'defense': 'Defense',
                'special-attack': 'Sp. Atk',
                'special-defense': 'Sp. Def',
                'speed': 'Speed'
            };

            let statsHtml = '';
            data.stats.forEach(s => {
                const name = statLabels[s.stat.name] || s.stat.name;
                const val = s.base_stat;
                const pct = Math.min(100, Math.floor((val / 200) * 100));
                const barColor = val >= 100 ? 'var(--accent)' : 'var(--text)';

                statsHtml += `
                    <div class="stat-row">
                        <span class="stat-label">${name}</span>
                        <span class="stat-val">${val}</span>
                        <div class="stat-bar-bg">
                            <div class="stat-bar-fill" style="width: ${pct}%; background-color: ${barColor};"></div>
                        </div>
                    </div>
                `;
            });
            document.getElementById('mStats').innerHTML = statsHtml;

            modal.style.display = 'flex';
        }

        function closeModal(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        }

        function closeModalDirect() {
            modal.style.display = 'none';
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeModalDirect();
            }
        });

        function playCry() {
            if (currentCryUrl) {
                const audio = new Audio(currentCryUrl);
                audio.volume = 0.5;
                audio.play();
            }
        }

        async function openRandom() {
            if (allPokemon.length === 0) return;
            const randIndex = Math.floor(Math.random() * allPokemon.length);
            const poke = allPokemon[randIndex];
            try {
                const data = await fetchDetails(poke.url);
                openModal(data);
                playCry(); // Auto play cry on random load for fun
            } catch (err) {
                console.error("Failed to fetch random pokemon", err);
            }
        }

        // Initialize
        init();
    </script>
</body>
</html>
