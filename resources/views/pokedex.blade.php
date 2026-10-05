<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex - Official Style</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #313131;
            --card-bg: #f2f2f2;
            --text-dark: #313131;
            --text-light: #919191;
            --nav-bg: #e3350d;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #fff;
            background-image: url('https://www.pokemon.com/static/all/img/bg-repeating-content.png');
            color: #333;
        }

        /* Navbar */
        .navbar {
            background-color: var(--nav-bg);
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar h1 {
            color: white;
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .battle-btn {
            background-color: #313131;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .battle-btn:hover { background-color: #000; transform: translateY(-2px); }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            background: white;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .page-title {
            color: var(--text-dark);
            font-size: 2.2rem;
            font-weight: 700;
        }

        .pokemon-count {
            color: var(--text-light);
            font-weight: 500;
        }

        /* Filter Section */
        .filter-section {
            background-color: var(--bg-dark);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .search-container {
            margin-bottom: 15px;
            display: flex;
        }

        .search-container input {
            flex: 1;
            padding: 14px 20px;
            font-size: 1rem;
            border: 2px solid #555;
            border-radius: 8px;
            background: #fff;
            outline: none;
            transition: border-color 0.2s;
        }
        .search-container input:focus {
            border-color: #30a7d7;
        }

        /* Type Filters */
        .type-filter-title {
            color: #ccc;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .type-pill-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .type-pill {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
            user-select: none;
            background: #444;
            color: #ddd;
        }
        .type-pill:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }
        .type-pill.active {
            border-color: #fff;
            box-shadow: 0 0 10px rgba(255,255,255,0.4);
            transform: scale(1.05);
        }

        /* Grid */
        .pokemon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .pokemon-card {
            background: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .pokemon-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        }

        .img-container {
            background-color: #f8f8f8;
            text-align: center;
            padding: 25px 20px 15px 20px;
            position: relative;
        }

        .img-container img {
            width: 100%;
            height: 180px;
            object-fit: contain;
            transition: transform 0.3s;
        }
        .pokemon-card:hover .img-container img {
            transform: scale(1.08);
        }

        .card-battle-quick-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(227, 53, 13, 0.9);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 4px 10px;
            font-size: 0.7rem;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            opacity: 0;
            transition: opacity 0.2s, transform 0.2s;
            z-index: 2;
        }
        .pokemon-card:hover .card-battle-quick-btn {
            opacity: 1;
        }
        .card-battle-quick-btn:hover {
            background: #b82806;
            transform: scale(1.05);
        }

        .card-info {
            padding: 15px 20px 20px 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .poke-id {
            color: var(--text-light);
            font-weight: bold;
            font-size: 0.85rem;
        }

        .poke-name {
            color: var(--text-dark);
            font-size: 1.3rem;
            margin: 4px 0 12px 0;
            text-transform: capitalize;
            font-weight: 700;
        }

        .types {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: auto;
        }

        .type-badge {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: capitalize;
            text-align: center;
            min-width: 70px;
        }

        /* Load More */
        .load-more-container {
            text-align: center;
            margin: 20px 0 40px 0;
        }

        .load-more-btn {
            background-color: #30a7d7;
            color: white;
            border: none;
            padding: 14px 35px;
            font-size: 1.05rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s, transform 0.2s;
        }
        .load-more-btn:hover { background-color: #1b82b1; transform: translateY(-2px); }
        .load-more-btn:disabled { background-color: #ccc; cursor: not-allowed; }

        /* Loader */
        .loader {
            display: none;
            text-align: center;
            margin: 20px 0;
            font-size: 1.1rem;
            color: #666;
            font-weight: 500;
        }
        
        /* Modal Styles */
        .modal-backdrop {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.75);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            padding: 20px;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: #fff;
            max-width: 520px;
            width: 100%;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.35);
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
            animation: popIn 0.3s ease;
        }

        @keyframes popIn {
            from { transform: scale(0.85); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0,0,0,0.1);
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            z-index: 10;
        }
        .modal-close:hover { background: rgba(0,0,0,0.25); }

        .modal-header {
            background: #f8f8f8;
            text-align: center;
            padding: 30px 20px 10px 20px;
        }

        .modal-header img {
            width: 180px;
            height: 180px;
            object-fit: contain;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .modal-title-row h2 {
            font-size: 1.8rem;
            text-transform: capitalize;
            color: var(--text-dark);
        }

        .modal-id {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--text-light);
        }

        .modal-meta-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            background: #f4f4f4;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin: 15px 0;
            font-size: 0.9rem;
        }

        .stat-row {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 0.85rem;
        }

        .stat-label {
            width: 90px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .stat-val {
            width: 40px;
            text-align: right;
            font-weight: bold;
            margin-right: 10px;
        }

        .stat-bar-bg {
            flex: 1;
            height: 8px;
            background: #e5e5e5;
            border-radius: 4px;
            overflow: hidden;
        }

        .stat-bar-fill {
            height: 100%;
            background: #30a7d7;
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        .modal-actions {
            margin-top: 25px;
            display: flex;
            gap: 10px;
        }

        .modal-battle-btn {
            flex: 1;
            background: var(--nav-bg);
            color: white;
            padding: 14px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 1rem;
            box-shadow: 0 4px 10px rgba(227, 53, 13, 0.3);
            transition: all 0.3s;
        }
        .modal-battle-btn:hover { background: #b82806; transform: translateY(-2px); }

        /* Type Colors */
        .bg-normal { background: #A8A77A; color: #fff; } 
        .bg-fire { background: #EE8130; color: white; } 
        .bg-water { background: #6390F0; color: white; } 
        .bg-electric { background: #F7D02C; color: #222; }
        .bg-grass { background: #7AC74C; color: white; } 
        .bg-ice { background: #96D9D6; color: #222; }
        .bg-fighting { background: #C22E28; color: white; } 
        .bg-poison { background: #A33EA1; color: white; }
        .bg-ground { background: #E2BF65; color: #222; } 
        .bg-flying { background: #A98FF3; color: white; }
        .bg-psychic { background: #F95587; color: white; } 
        .bg-bug { background: #A6B91A; color: white; }
        .bg-rock { background: #B6A136; color: white; } 
        .bg-ghost { background: #735797; color: white; }
        .bg-dragon { background: #6F35FC; color: white; } 
        .bg-dark { background: #705848; color: white; }
        .bg-steel { background: #B7B7CE; color: #222; } 
        .bg-fairy { background: #D685AD; color: white; }
        .bg-stellar { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border: 1px solid #ff00ff; }
        .bg-unknown { background: #68A090; color: white; }
    </style>
</head>
<body>

    <div class="navbar">
        <h1><span>⚡</span> Pokédex</h1>
        <div>
            <a href="/guess" class="battle-btn" style="background:#f1c40f; color:#333; margin-right:10px;">❓ GUESS POKÉMON</a>
            <a href="/battle" class="battle-btn">⚔️ BATTLE ARENA</a>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h2 class="page-title">National Pokédex</h2>
            <span class="pokemon-count" id="countDisplay">Loaded: 0 Pokémon</span>
        </div>

        <div class="filter-section">
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search Pokémon by Name or Number (e.g. charizard, 6)...">
                <button id="randomBtn" onclick="openRandom()" style="background:#f95587; color:white; border:none; padding:14px 20px; font-size:1rem; border-radius:8px; cursor:pointer; font-weight:bold; margin-left:10px;">🎲 Surprise Me!</button>
            </div>

            <div class="type-filter-title">Filter by Element Type:</div>
            <div class="type-pill-container" id="typeContainer">
                <div class="type-pill active" data-type="all" onclick="filterByType('all')">All Types</div>
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
                <div class="type-pill bg-stellar" data-type="stellar" onclick="filterByType('stellar')">Stellar</div>
                <div class="type-pill bg-unknown" data-type="unknown" onclick="filterByType('unknown')">Unknown</div>
            </div>
        </div>

        <div class="pokemon-grid" id="pokemonGrid">
            <!-- Cards injected by JS -->
        </div>

        <div class="loader" id="loader">Loading Pokémon...</div>

        <div class="load-more-container">
            <button class="load-more-btn" id="loadMoreBtn" onclick="loadMore()">Load More Pokémon</button>
        </div>
    </div>

    <!-- Quick Detail Modal -->
    <div class="modal-backdrop" id="pokeModal" onclick="closeModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeModalDirect()">✕</button>
            <div class="modal-header">
                <img id="mImg" src="" alt="">
                <button onclick="playCry()" style="position:absolute; top:15px; left:15px; background:rgba(0,0,0,0.1); border:none; width:35px; height:35px; border-radius:50%; font-size:1.2rem; cursor:pointer; z-index:10;" title="Play Cry">🔊</button>
            </div>
            <div class="modal-body">
                <div class="modal-title-row">
                    <h2 id="mName">Pokemon</h2>
                    <span class="modal-id" id="mId">#0001</span>
                </div>
                <div class="types" id="mTypes" style="margin-bottom: 15px;"></div>
                
                <div class="modal-meta-grid">
                    <div><strong>Height:</strong><br><span id="mHeight">0.7 m</span></div>
                    <div><strong>Weight:</strong><br><span id="mWeight">6.9 kg</span></div>
                    <div><strong>Base Exp:</strong><br><span id="mExp">64</span></div>
                </div>

                <div style="margin-top: 15px;">
                    <div style="font-weight: bold; margin-bottom: 10px; color: var(--text-dark);">Base Stats:</div>
                    <div id="mStats"></div>
                </div>

                <div class="modal-actions">
                    <a id="mBattleBtn" href="/battle" class="modal-battle-btn">⚔️ BATTLE WITH THIS POKÉMON</a>
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
        const LIMIT = 12;

        const grid = document.getElementById('pokemonGrid');
        const loader = document.getElementById('loader');
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        const searchInput = document.getElementById('searchInput');
        const countDisplay = document.getElementById('countDisplay');
        const modal = document.getElementById('pokeModal');
        let currentCryUrl = null;

        // Init
        async function init() {
            try {
                loader.style.display = 'block';
                const res = await fetch('https://pokeapi.co/api/v2/pokemon?limit=1025');
                const data = await res.json();
                
                allPokemon = data.results.map((p, index) => {
                    const idMatch = p.url.match(/\/pokemon\/(\d+)\//);
                    const id = idMatch ? parseInt(idMatch[1]) : index + 1;
                    return {
                        id: id,
                        name: p.name,
                        url: p.url
                    };
                });

                currentPool = [...allPokemon];
                applyFilterAndSearch();
            } catch (err) {
                loader.innerText = 'Failed to load Pokémon. Please check your internet connection.';
            }
        }

        async function filterByType(type) {
            selectedType = type;
            
            // Update UI pills
            document.querySelectorAll('.type-pill').forEach(pill => {
                if (pill.dataset.type === type) {
                    pill.classList.add('active');
                } else {
                    pill.classList.remove('active');
                }
            });

            loader.style.display = 'block';
            loadMoreBtn.style.display = 'none';

            if (type === 'all') {
                currentPool = [...allPokemon];
                applyFilterAndSearch();
            } else {
                try {
                    const res = await fetch(`https://pokeapi.co/api/v2/type/${type}`);
                    const data = await res.json();
                    currentPool = data.pokemon.map(p => {
                        const idMatch = p.pokemon.url.match(/\/pokemon\/(\d+)\//);
                        const id = idMatch ? parseInt(idMatch[1]) : 0;
                        return {
                            id: id,
                            name: p.pokemon.name,
                            url: p.pokemon.url
                        };
                    }).filter(p => p.id > 0 && p.id <= 1025);

                    applyFilterAndSearch();
                } catch (err) {
                    loader.innerText = 'Failed to filter by type.';
                }
            }
        }

        function applyFilterAndSearch() {
            const query = searchInput.value.toLowerCase().trim();
            searchToken++;
            grid.innerHTML = '';
            currentIndex = 0;

            if (query === '') {
                filteredPokemon = [...currentPool];
            } else {
                filteredPokemon = currentPool.filter(p => {
                    return p.name.includes(query) || p.id.toString() === query;
                });
            }

            countDisplay.innerText = `Showing ${filteredPokemon.length} Pokémon`;
            loadMore();
        }

        async function fetchDetails(url) {
            const res = await fetch(url);
            return res.json();
        }

        async function loadMore() {
            const currentToken = searchToken;
            loadMoreBtn.style.display = 'none';
            loader.style.display = 'block';

            const toLoad = filteredPokemon.slice(currentIndex, currentIndex + LIMIT);
            if (toLoad.length === 0) {
                loader.style.display = 'none';
                if (filteredPokemon.length === 0) {
                    grid.innerHTML = '<p style="grid-column: 1/-1; text-align:center; color:#999; padding: 40px 0;">No Pokémon found matching the selected filters.</p>';
                }
                return;
            }

            try {
                const promises = toLoad.map(p => fetchDetails(p.url));
                const results = await Promise.all(promises);

                if (currentToken !== searchToken) return;

                results.forEach(data => {
                    const card = document.createElement('div');
                    card.className = 'pokemon-card';
                    card.onclick = () => openModal(data);
                    
                    const idFormatted = data.id.toString().padStart(4, '0');
                    const imgUrl = data.sprites.other['official-artwork'].front_default || data.sprites.front_default;
                    
                    let typesHtml = '';
                    data.types.forEach(t => {
                        typesHtml += `<div class="type-badge bg-${t.type.name}">${t.type.name}</div>`;
                    });

                    card.innerHTML = `
                        <button class="card-battle-quick-btn" onclick="event.stopPropagation(); window.location.href='/battle?p1=${data.id}'">
                            ⚔️ Battle
                        </button>
                        <div class="img-container">
                            <img src="${imgUrl}" alt="${data.name}" loading="lazy">
                        </div>
                        <div class="card-info">
                            <p class="poke-id">#${idFormatted}</p>
                            <h3 class="poke-name">${data.name}</h3>
                            <div class="types">
                                ${typesHtml}
                            </div>
                        </div>
                    `;
                    grid.appendChild(card);
                });

                currentIndex += LIMIT;
                loader.style.display = 'none';

                if (currentIndex < filteredPokemon.length) {
                    loadMoreBtn.style.display = 'inline-block';
                }
            } catch (err) {
                if (currentToken === searchToken) {
                    loader.innerText = 'Error loading some Pokémon details.';
                }
            }
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
                const barColor = val >= 100 ? '#4ADE80' : val >= 60 ? '#30a7d7' : '#FACC15';

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
