<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Battle Arena</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ui-bg: rgba(0, 0, 0, 0.75);
            --dex-red: #e3350d;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Press Start 2P', monospace;
            background: #222;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #fff;
        }

        .container {
            width: 100%;
            max-width: 900px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            position: relative;
            color: #333;
        }

        .nav-link {
            color: white;
            background: rgba(0,0,0,0.5);
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.55rem;
            font-family: 'Press Start 2P', monospace;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .nav-link:hover { background: rgba(0,0,0,0.85); }

        /* --- Selection Screen --- */
        #selection-screen {
            padding: 40px;
            text-align: center;
            background: #f2f2f2;
            min-height: 600px;
        }

        .title { color: var(--dex-red); font-size: 2rem; margin-bottom: 20px; text-shadow: 2px 2px 0px #ccc; }
        
        .mode-select { margin-bottom: 30px; }
        .mode-select select {
            padding: 10px;
            font-family: 'Press Start 2P', monospace;
            font-size: 0.8rem;
            border: 2px solid #333;
        }

        .players-selection {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            gap: 20px;
        }

        .player-box {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            border: 4px solid #ccc;
            flex: 1;
            box-shadow: 0 4px 0 #ccc;
        }

        .player-box h3 { margin-bottom: 15px; font-size: 1rem; color: #555; }
        .player-box input {
            width: 100%;
            padding: 10px;
            font-family: 'Press Start 2P', monospace;
            font-size: 0.7rem;
            margin-bottom: 15px;
            border: 2px solid #ccc;
            text-align: center;
        }

        .preview-img { width: 150px; height: 150px; object-fit: contain; background: #f9f9f9; border-radius: 50%; margin-bottom: 15px; border: 2px solid #eee; }
        .preview-name { font-size: 0.9rem; margin-bottom: 15px; color: #333; }

        .btn-random { background: #30a7d7; color: white; border: none; padding: 10px; cursor: pointer; font-family: 'Press Start 2P', monospace; font-size: 0.6rem; border-radius: 5px; margin-bottom: 5px;}
        .btn-confirm { background: #4ADE80; color: #333; border: none; padding: 10px; cursor: pointer; font-family: 'Press Start 2P', monospace; font-size: 0.6rem; border-radius: 5px;}
        
        .start-btn {
            background: var(--dex-red);
            color: white;
            padding: 15px 30px;
            font-size: 1.2rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-family: 'Press Start 2P', monospace;
            box-shadow: 0 5px 0 #a02005;
            transition: transform 0.1s;
        }
        .start-btn:active { transform: translateY(5px); box-shadow: 0 0 0; }
        .start-btn:disabled { background: #ccc; box-shadow: 0 5px 0 #999; cursor: not-allowed; }

        /* --- Battle Arena --- */
        #battle-arena {
            display: none;
            background: url('https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/environments/grass.png') no-repeat center bottom;
            background-size: cover;
            background-color: #87CEEB;
            height: 600px;
            flex-direction: column;
            color: #333;
        }

        .battle-field { flex: 1; position: relative; }

        .enemy { position: absolute; top: 40px; right: 50px; text-align: right; }
        .enemy img { width: 150px; filter: drop-shadow(0 10px 5px rgba(0,0,0,0.3)); }
        
        .hp-box {
            background: #F8F8F8;
            border: 4px solid #333;
            border-radius: 10px;
            padding: 10px;
            width: 280px;
            position: absolute;
            box-shadow: 3px 3px 0 rgba(0,0,0,0.2);
        }
        .hp-box.enemy-hp { top: 40px; left: 30px; }
        .hp-box.player-hp { bottom: 30px; right: 30px; } 

        .hp-info { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 0.8rem; }
        .status-badge { font-size: 0.6rem; padding: 2px 5px; border-radius: 3px; display: none; margin-top: 5px; }
        .status-psn { background: #A33EA1; color: white; }
        .status-cnf { background: #F95587; color: white; }

        .hp-bar-bg { background: #555; height: 10px; border-radius: 5px; border: 2px solid #222; overflow: hidden; }
        .hp-bar-fill { height: 100%; background: #4ADE80; transition: width 0.5s, background-color 0.5s; width: 100%; }

        .player { position: absolute; bottom: 0px; left: 50px; }
        .player img { width: 180px; filter: drop-shadow(0 10px 5px rgba(0,0,0,0.3)); } 

        .ui-panel {
            height: 180px;
            background: var(--ui-bg);
            border-top: 4px solid #fff;
            display: flex;
            padding: 15px;
            gap: 15px;
        }

        .dialog-box {
            flex: 1;
            border: 4px solid #fff;
            border-radius: 10px;
            padding: 15px;
            font-size: 0.8rem;
            line-height: 1.8;
            background: #fff;
            color: #333;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .log-entry {
            border-bottom: 1px dotted #ccc;
            padding-bottom: 5px;
        }

        .moves-box {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .moves-box button {
            background: #f8f8f8;
            border: 4px solid #333;
            border-radius: 10px;
            font-family: 'Press Start 2P', monospace;
            font-size: 0.7rem;
            cursor: pointer;
            box-shadow: 2px 2px 0 rgba(0,0,0,0.2);
        }
        .moves-box button:hover { background: #e0e0e0; }
        .moves-box button:disabled { opacity: 0.5; cursor: not-allowed; }

        /* Status Colors for moves */
        .move-type { font-size: 0.5rem; margin-top: 5px; color: #666; display:block; }

        @keyframes tackle-player { 0% { transform: translateX(0); } 50% { transform: translateX(50px) translateY(-20px); } 100% { transform: translateX(0); } }
        @keyframes tackle-enemy { 0% { transform: translateX(0); } 50% { transform: translateX(-50px) translateY(20px); } 100% { transform: translateX(0); } }
        @keyframes damage-blink { 0%, 40%, 80% { opacity: 1; } 20%, 60% { opacity: 0; } 100% { opacity: 1; } }

        /* Weather Effects */
        .weather-overlay {
            position: absolute; top:0; left:0; right:0; bottom:0;
            pointer-events: none;
            z-index: 10;
            opacity: 0.3;
            transition: all 1s;
        }
        .weather-rain {
            background: linear-gradient(to bottom, transparent, rgba(0, 100, 255, 0.4));
            animation: rain 0.5s infinite linear;
        }
        .weather-sun {
            background: radial-gradient(circle at top right, rgba(255, 200, 0, 0.5), transparent);
            opacity: 0.5;
        }
        .weather-sandstorm {
            background: rgba(194, 178, 128, 0.4);
            animation: sandstorm 2s infinite linear;
        }

        @keyframes rain {
            0% { background-position: 0 0; }
            100% { background-position: -20px 100px; }
        }
        @keyframes sandstorm {
            0% { background-position: 0 0; }
            100% { background-position: 100px 0; }
        }

        /* Floating Damage Numbers */
        @keyframes floatUp {
            0%   { opacity: 1; transform: translateY(0) scale(1); }
            100% { opacity: 0; transform: translateY(-60px) scale(1.4); }
        }
        .float-dmg {
            position: absolute;
            font-family: 'Press Start 2P', monospace;
            font-size: 1rem;
            font-weight: bold;
            pointer-events: none;
            animation: floatUp 1s ease forwards;
            z-index: 50;
            text-shadow: 2px 2px 0 rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/" class="nav-link">⬅ Pokédex</a>

        <!-- Selection Screen -->
        <div id="selection-screen">
            <h1 class="title">BATTLE ARENA</h1>
            
            <div class="mode-select">
                <select id="game-mode" onchange="updateMode()">
                    <option value="cpu">Player 1 vs CPU</option>
                    <option value="p2">Player 1 vs Player 2</option>
                </select>
            </div>

            <div class="players-selection">
                <!-- Player 1 -->
                <div class="player-box">
                    <h3>Player 1</h3>
                    <img id="p1-img" class="preview-img" src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/poke-ball.png">
                    <div id="p1-name" class="preview-name">Select Pokémon</div>
                    <input type="text" id="p1-input" placeholder="Name or ID">
                    <button class="btn-confirm" onclick="selectPokemon('p1')">Search</button>
                    <button class="btn-random" onclick="randomPokemon('p1')">Random</button>
                </div>
                
                <!-- Player 2 / CPU -->
                <div class="player-box">
                    <h3 id="p2-title">CPU</h3>
                    <img id="p2-img" class="preview-img" src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/poke-ball.png">
                    <div id="p2-name" class="preview-name">Select Pokémon</div>
                    <input type="text" id="p2-input" placeholder="Name or ID">
                    <button class="btn-confirm" onclick="selectPokemon('p2')">Search</button>
                    <button class="btn-random" onclick="randomPokemon('p2')">Random</button>
                </div>
            </div>

            <button id="start-btn" class="start-btn" onclick="startBattle()" disabled>START BATTLE</button>
        </div>

        <!-- Battle Arena -->
        <div id="battle-arena">
            <div class="battle-field">
                <div id="weather-overlay" class="weather-overlay"></div>
                <!-- Enemy (P2/CPU) -->
                <div class="hp-box enemy-hp" id="enemy-ui">
                    <div class="hp-info">
                        <span id="enemy-name">???</span>
                        <span>Lv50</span>
                    </div>
                    <div class="hp-bar-bg"><div class="hp-bar-fill" id="enemy-hp-bar"></div></div>
                    <div style="text-align: right; font-size: 0.6rem; margin-top: 5px;" id="enemy-hp-text">100/100</div>
                    <span id="enemy-status-psn" class="status-badge status-psn">PSN</span>
                    <span id="enemy-status-cnf" class="status-badge status-cnf">CNF</span>
                </div>
                <div class="enemy" id="enemy-sprite-container">
                    <img id="enemy-sprite" src="">
                </div>

                <!-- Player (P1) -->
                <div class="hp-box player-hp" id="player-ui">
                    <div class="hp-info">
                        <span id="player-name">???</span>
                        <span>Lv50</span>
                    </div>
                    <div class="hp-bar-bg"><div class="hp-bar-fill" id="player-hp-bar"></div></div>
                    <div style="text-align: right; font-size: 0.6rem; margin-top: 5px;" id="player-hp-text">100/100</div>
                    <span id="player-status-psn" class="status-badge status-psn">PSN</span>
                    <span id="player-status-cnf" class="status-badge status-cnf">CNF</span>
                </div>
                <div class="player" id="player-sprite-container">
                    <img id="player-sprite" src="">
                </div>
            </div>

            <div class="ui-panel">
                <div class="dialog-box" id="dialog">...</div>
                <div class="moves-box" id="moves-container" style="display: none;"></div>
            </div>
        </div>
    </div>

    <!-- Background Music -->
    <audio id="bgm" src="/music/battle.mp3" loop preload="auto"></audio>

    <script>
        // --- Retro Sound Engine ---
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const SFX = {
            hit: () => {
                if(audioCtx.state === 'suspended') audioCtx.resume();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'square';
                osc.frequency.setValueAtTime(150, audioCtx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(40, audioCtx.currentTime + 0.15);
                gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.15);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.15);
            },
            superHit: () => {
                if(audioCtx.state === 'suspended') audioCtx.resume();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'square';
                osc.frequency.setValueAtTime(600, audioCtx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(300, audioCtx.currentTime + 0.2);
                gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.2);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.2);
            },
            weakHit: () => {
                if(audioCtx.state === 'suspended') audioCtx.resume();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(100, audioCtx.currentTime);
                osc.frequency.linearRampToValueAtTime(50, audioCtx.currentTime + 0.1);
                gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.1);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.1);
            },
            faint: () => {
                if(audioCtx.state === 'suspended') audioCtx.resume();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(400, audioCtx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(50, audioCtx.currentTime + 0.6);
                gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.6);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.6);
            },
            victory: () => {
                if(audioCtx.state === 'suspended') audioCtx.resume();
                const notes = [ {f: 440, d: 0.15}, {f: 440, d: 0.15}, {f: 440, d: 0.15}, {f: 587.33, d: 0.4} ];
                let startTime = audioCtx.currentTime;
                notes.forEach(note => {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = 'square';
                    osc.frequency.setValueAtTime(note.f, startTime);
                    gain.gain.setValueAtTime(0.1, startTime);
                    gain.gain.linearRampToValueAtTime(0, startTime + note.d);
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.start(startTime);
                    osc.stop(startTime + note.d);
                    startTime += note.d + 0.05;
                });
            }
        };

        // --- Selection Logic ---
        let selectedP1 = null;
        let selectedP2 = null;
        let gameMode = 'cpu';

        function updateMode() {
            gameMode = document.getElementById('game-mode').value;
            document.getElementById('p2-title').innerText = gameMode === 'cpu' ? 'CPU' : 'Player 2';
        }

        async function fetchPokeData(query) {
            try {
                const res = await fetch(`https://pokeapi.co/api/v2/pokemon/${query.toString().toLowerCase().trim()}`);
                if (!res.ok) return null;
                return await res.json();
            } catch (e) { return null; }
        }

        async function selectPokemon(playerCode) {
            const val = document.getElementById(`${playerCode}-input`).value;
            if(!val) return;
            document.getElementById(`${playerCode}-name`).innerText = "Searching...";
            const data = await fetchPokeData(val);
            if(data) setSelection(playerCode, data);
            else document.getElementById(`${playerCode}-name`).innerText = "Not found!";
        }

        async function randomPokemon(playerCode) {
            document.getElementById(`${playerCode}-name`).innerText = "Rolling...";
            // BUG FIX #5: Extended range to 1025 (includes Gen 9 Pokémon)
            const data = await fetchPokeData(Math.floor(Math.random() * 1025) + 1);
            if (data) setSelection(playerCode, data);
            else document.getElementById(`${playerCode}-name`).innerText = 'Not found!';
        }

        function setSelection(playerCode, data) {
            document.getElementById(`${playerCode}-img`).src = data.sprites.other['official-artwork'].front_default || data.sprites.front_default;
            document.getElementById(`${playerCode}-name`).innerText = data.name.toUpperCase();
            if(playerCode === 'p1') selectedP1 = data;
            if(playerCode === 'p2') selectedP2 = data;
            checkReady();
        }

        function checkReady() {
            document.getElementById('start-btn').disabled = !(selectedP1 && selectedP2);
        }

        // --- Battle Logic ---
        let p1State, p2State;
        let battleOver = false;
        let p1MoveQueue = null;
        let p2MoveQueue = null;
        let currentWeather = 'none'; // none, rain, sun, sandstorm

        // BUG FIX #3: Fetch moves in two passes — first try to get 20 from the end of the list
        // (later moves tend to be learnable at higher levels = more powerful & representative)
        // then fetch details in parallel for speed.
        async function fetchMoves(movesList) {
            setDialog('Loading moves data...');
            // Sort by level learned descending to prefer stronger, signature moves
            const sorted = [...movesList].sort((a, b) => {
                const lvA = a.version_group_details?.[0]?.level_learned_at ?? 0;
                const lvB = b.version_group_details?.[0]?.level_learned_at ?? 0;
                return lvB - lvA;
            }).slice(0, 30);

            const details = await Promise.all(
                sorted.map(m => fetch(m.move.url).then(r => r.json()).catch(() => null))
            );

            // Only keep moves that actually deal damage
            const damaging = details.filter(m => m && m.power !== null && m.power > 0);

            // If Pokémon has fewer than 4 damaging moves, fallback to Tackle
            while (damaging.length < 4) {
                damaging.push({ name: 'tackle', power: 40, type: { name: 'normal' }, damage_class: { name: 'physical' }, pp: 35 });
            }

            // Pick 4 with most power for variety
            damaging.sort((a, b) => b.power - a.power);
            return damaging.slice(0, 4).map(m => ({
                name: m.name,
                power: m.power,
                type: m.type.name,
                damageClass: m.damage_class ? m.damage_class.name : 'physical',
                pp: m.pp || 10,
                maxPp: m.pp || 10
            }));
        }

        function calcStats(base) {
            const hp = Math.floor((2 * base[0].base_stat) * 50 / 100) + 60;
            const atk = Math.floor((2 * base[1].base_stat) * 50 / 100) + 5;
            const def = Math.floor((2 * base[2].base_stat) * 50 / 100) + 5;
            const spAtk = Math.floor((2 * (base[3] ? base[3].base_stat : base[1].base_stat)) * 50 / 100) + 5;
            const spDef = Math.floor((2 * (base[4] ? base[4].base_stat : base[2].base_stat)) * 50 / 100) + 5;
            const spd = Math.floor((2 * base[5].base_stat) * 50 / 100) + 5;
            return { hp, atk, def, spAtk, spDef, spd };
        }

        async function startBattle() {
            document.getElementById('selection-screen').style.display = 'none';
            document.getElementById('battle-arena').style.display = 'flex';
            battleOver = false;
            setDialog("Setting up the arena...");

            const p1S = calcStats(selectedP1.stats);
            const p2S = calcStats(selectedP2.stats);

            p1State = {
                id: 'player', name: selectedP1.name.toUpperCase(), originalData: selectedP1,
                hp: p1S.hp, maxHp: p1S.hp, attack: p1S.atk, defense: p1S.def, spAtk: p1S.spAtk, spDef: p1S.spDef, speed: p1S.spd,
                moves: await fetchMoves(selectedP1.moves),
                status: null, statusTurns: 0,
                types: selectedP1.types.map(t => t.type.name)
            };

            p2State = {
                id: 'enemy', name: selectedP2.name.toUpperCase(), originalData: selectedP2,
                hp: p2S.hp, maxHp: p2S.hp, attack: p2S.atk, defense: p2S.def, spAtk: p2S.spAtk, spDef: p2S.spDef, speed: p2S.spd,
                moves: await fetchMoves(selectedP2.moves),
                status: null, statusTurns: 0,
                types: selectedP2.types.map(t => t.type.name)
            };

            // Setup UI Sprites
            const sp1 = selectedP1.sprites.back_default || selectedP1.sprites.front_default;
            const playerSprite = document.getElementById('player-sprite');
            playerSprite.src = sp1;
            playerSprite.style.opacity = '1';
            if(!selectedP1.sprites.back_default) playerSprite.style.transform = 'scaleX(-1)';
            else playerSprite.style.transform = 'none';

            const enemySprite = document.getElementById('enemy-sprite');
            enemySprite.src = selectedP2.sprites.front_default;
            enemySprite.style.opacity = '1';

            document.getElementById('player-name').innerText = p1State.name;
            document.getElementById('enemy-name').innerText = p2State.name;
            
            // Clear previous log
            document.getElementById('dialog').innerHTML = '';
            
            // Weather System
            const weathers = ['none', 'rain', 'sun', 'sandstorm'];
            currentWeather = weathers[Math.floor(Math.random() * weathers.length)];
            const weatherOverlay = document.getElementById('weather-overlay');
            weatherOverlay.className = 'weather-overlay'; // reset
            if (currentWeather !== 'none') {
                weatherOverlay.classList.add('weather-' + currentWeather);
            }
            
            updateHPUI();
            setDialog(`Battle Start! ${p1State.name} vs ${p2State.name}!`);

            // Start BGM
            const bgm = document.getElementById('bgm');
            bgm.volume = 0.3; // Lower volume so it doesn't overpower SFX
            bgm.play().catch(e => console.log('BGM Autoplay blocked', e));

            if (currentWeather === 'rain') setDialog(`It started to rain!`);
            if (currentWeather === 'sun') setDialog(`The sunlight is harsh!`);
            if (currentWeather === 'sandstorm') setDialog(`A sandstorm kicked up!`);
            
            // Play cries if available
            if (selectedP1.cries && selectedP1.cries.latest) {
                let a1 = new Audio(selectedP1.cries.latest);
                a1.volume = 0.5;
                a1.play().catch(e=>console.log(e));
            }
            setTimeout(() => {
                if (selectedP2.cries && selectedP2.cries.latest) {
                    let a2 = new Audio(selectedP2.cries.latest);
                    a2.volume = 0.5;
                    a2.play().catch(e=>console.log(e));
                }
            }, 800);

            await sleep(1500);
            startTurn();
        }

        function updateHPUI() {
            [p1State, p2State].forEach(p => {
                const pct = Math.max(0, (p.hp / p.maxHp) * 100);
                document.getElementById(`${p.id}-hp-bar`).style.width = pct + '%';
                document.getElementById(`${p.id}-hp-bar`).style.backgroundColor = pct < 20 ? '#ef5350' : pct < 50 ? '#FACC15' : '#4ADE80';
                document.getElementById(`${p.id}-hp-text`).innerText = `${Math.floor(p.hp)}/${p.maxHp}`;
                
                // Status Badges
                document.getElementById(`${p.id}-status-psn`).style.display = p.status === 'poison' ? 'inline-block' : 'none';
                document.getElementById(`${p.id}-status-cnf`).style.display = p.status === 'confusion' ? 'inline-block' : 'none';
            });
        }

        function setDialog(text) {
            const dialog = document.getElementById('dialog');
            const entry = document.createElement('div');
            entry.className = 'log-entry';
            entry.innerHTML = text;
            dialog.appendChild(entry);
            dialog.scrollTop = dialog.scrollHeight;
        }

        function sleep(ms) { return new Promise(resolve => setTimeout(resolve, ms)); }

        function setTurnIndicator(text) {
            // BUG FIX #6: Visual turn indicator
            const existing = document.getElementById('turn-indicator');
            if (existing) existing.remove();
            const ind = document.createElement('div');
            ind.id = 'turn-indicator';
            ind.style.cssText = 'position:absolute;top:10px;left:50%;transform:translateX(-50%);background:rgba(0,0,0,0.7);color:#f1c40f;font-size:0.55rem;padding:5px 12px;border-radius:20px;z-index:20;white-space:nowrap;';
            ind.innerText = text;
            document.querySelector('.battle-field').appendChild(ind);
        }

        function startTurn() {
            if(battleOver) return;
            p1MoveQueue = null;
            p2MoveQueue = null;
            promptPlayer1();
        }

        function promptPlayer1() {
            // PP: If all P1 moves are depleted, force Struggle
            const hasUsableMoves = p1State.moves.some(m => m.pp > 0);
            if (!hasUsableMoves) {
                p1MoveQueue = getStruggle();
                document.getElementById('moves-container').style.display = 'none';
                setDialog(`${p1State.name} has no PP left! It must use Struggle!`);
                setTimeout(() => {
                    if(gameMode === 'cpu') {
                        p2MoveQueue = getCpuMove(p2State);
                        executeTurn();
                    } else {
                        promptPlayer2();
                    }
                }, 1500);
                return;
            }

            setTurnIndicator('🎮 YOUR TURN');
            setDialog(`Player 1 (${p1State.name}), choose a move!`);
            renderMoves(p1State, (move) => {
                p1MoveQueue = move;
                document.getElementById('moves-container').style.display = 'none';
                if(gameMode === 'cpu') {
                    setTurnIndicator('🤖 CPU THINKING...');
                    p2MoveQueue = getCpuMove(p2State);
                    executeTurn();
                } else {
                    promptPlayer2();
                }
            });
        }

        function promptPlayer2() {
            const hasUsableMoves = p2State.moves.some(m => m.pp > 0);
            if (!hasUsableMoves) {
                p2MoveQueue = getStruggle();
                document.getElementById('moves-container').style.display = 'none';
                setDialog(`${p2State.name} has no PP left! It must use Struggle!`);
                setTimeout(() => executeTurn(), 1500);
                return;
            }

            setTurnIndicator('🎮 PLAYER 2 TURN');
            setDialog(`Player 2 (${p2State.name}), choose a move!`);
            renderMoves(p2State, (move) => {
                p2MoveQueue = move;
                document.getElementById('moves-container').style.display = 'none';
                executeTurn();
            });
        }

        // PP: Struggle move — used when all PP are exhausted, deals normal dmg and hurts user 1/4 maxHP
        function getStruggle() {
            return { name: 'struggle', power: 50, type: 'normal', damageClass: 'physical', pp: 1, maxPp: 1, isStruggle: true };
        }

        // PP: CPU picks a random move that still has PP remaining
        function getCpuMove(state) {
            const available = state.moves.filter(m => m.pp > 0);
            if (available.length === 0) return getStruggle();
            return available[Math.floor(Math.random() * available.length)];
        }

        function renderMoves(playerState, callback) {
            const container = document.getElementById('moves-container');
            container.innerHTML = '';
            playerState.moves.forEach(move => {
                const btn = document.createElement('button');
                const ppColor = move.pp === 0 ? '#ef5350' : move.pp <= move.maxPp / 4 ? '#FACC15' : '#333';
                const classBadge = move.damageClass === 'special' ? ' [Sp]' : ' [Ph]';
                btn.innerHTML = `
                    <span style="font-size:0.65rem">${move.name.replace(/-/g,' ').toUpperCase()}</span>
                    <span class="move-type" style="color:${ppColor}">${move.type}${classBadge} | Pwr:${move.power} | PP:${move.pp}/${move.maxPp}</span>
                `;
                // PP: Disable button if this move has no PP left
                if (move.pp <= 0) {
                    btn.disabled = true;
                    btn.style.opacity = '0.4';
                    btn.style.cursor = 'not-allowed';
                } else {
                    btn.onclick = () => callback(move);
                }
                container.appendChild(btn);
            });
            container.style.display = 'grid';
        }

        // =============================================
        // FULL POKEMON TYPE CHART (Gen VI+ — 18 Types)
        // Chart[attackType][defenderType] = multiplier
        // =============================================
        const TYPE_CHART = {
            normal:   { rock: 0.5, ghost: 0, steel: 0.5 },
            fire:     { fire: 0.5, water: 0.5, grass: 2, ice: 2, bug: 2, rock: 0.5, dragon: 0.5, steel: 2 },
            water:    { fire: 2, water: 0.5, grass: 0.5, ground: 2, rock: 2, dragon: 0.5 },
            electric: { water: 2, electric: 0.5, grass: 0.5, ground: 0, flying: 2, dragon: 0.5 },
            grass:    { fire: 0.5, water: 2, grass: 0.5, poison: 0.5, ground: 2, flying: 0.5, bug: 0.5, rock: 2, dragon: 0.5, steel: 0.5 },
            ice:      { fire: 0.5, water: 0.5, grass: 2, ice: 0.5, ground: 2, flying: 2, dragon: 2, steel: 0.5 },
            fighting: { normal: 2, ice: 2, poison: 0.5, flying: 0.5, psychic: 0.5, bug: 0.5, rock: 2, ghost: 0, dark: 2, steel: 2, fairy: 0.5 },
            poison:   { grass: 2, poison: 0.5, ground: 0.5, rock: 0.5, ghost: 0.5, steel: 0, fairy: 2 },
            ground:   { fire: 2, electric: 2, grass: 0.5, poison: 2, flying: 0, bug: 0.5, rock: 2, steel: 2 },
            flying:   { electric: 0.5, grass: 2, fighting: 2, bug: 2, rock: 0.5, steel: 0.5 },
            psychic:  { fighting: 2, poison: 2, psychic: 0.5, dark: 0, steel: 0.5 },
            bug:      { fire: 0.5, grass: 2, fighting: 0.5, flying: 0.5, psychic: 2, ghost: 0.5, dark: 2, steel: 0.5, fairy: 0.5 },
            rock:     { fire: 2, ice: 2, fighting: 0.5, ground: 0.5, flying: 2, bug: 2, steel: 0.5 },
            ghost:    { normal: 0, psychic: 2, ghost: 2, dark: 0.5 },
            dragon:   { dragon: 2, steel: 0.5, fairy: 0 },
            dark:     { fighting: 0.5, psychic: 2, ghost: 2, dark: 0.5, fairy: 0.5 },
            steel:    { fire: 0.5, water: 0.5, electric: 0.5, ice: 2, rock: 2, steel: 0.5, fairy: 2 },
            fairy:    { fire: 0.5, fighting: 2, poison: 0.5, dragon: 2, dark: 2, steel: 0.5 },
            stellar:  {},
            unknown:  {}
        };

        function getTypeMultiplier(moveType, defenderTypes) {
            let multiplier = 1;
            const chart = TYPE_CHART[moveType] || {};
            defenderTypes.forEach(defType => {
                multiplier *= (chart[defType] !== undefined ? chart[defType] : 1);
            });
            return multiplier;
        }

        function calcDamage(power, atk, def, multiplier = 1, moveType = 'normal') {
            let weatherMod = 1;
            if (currentWeather === 'rain') {
                if (moveType === 'water') weatherMod = 1.5;
                if (moveType === 'fire') weatherMod = 0.5;
            } else if (currentWeather === 'sun') {
                if (moveType === 'fire') weatherMod = 1.5;
                if (moveType === 'water') weatherMod = 0.5;
            }

            const base = (((2 * 50 / 5 + 2) * power * (atk / def)) / 50) + 2;
            return Math.floor(base * multiplier * weatherMod * (Math.random() * (1 - 0.85) + 0.85));
        }

        async function processAttack(attacker, defender, move) {
            if(attacker.hp <= 0) return;  // dead Pokémon cannot attack

            // Confusion with recovery timer (2-5 turns)
            if(attacker.status === 'confusion') {
                attacker.statusTurns--;
                if(attacker.statusTurns <= 0) {
                    attacker.status = null;
                    attacker.statusTurns = 0;
                    setDialog(`${attacker.name} snapped out of confusion!`);
                    await sleep(1200);
                } else {
                    setDialog(`${attacker.name} is confused!`);
                    await sleep(1000);
                    if(Math.random() < 0.33) {
                        setDialog(`It hurt itself in its confusion!`);
                        await sleep(800);
                        const selfDmg = calcDamage(40, attacker.attack, attacker.defense);
                        attacker.hp = Math.max(0, attacker.hp - selfDmg);
                        SFX.hit();
                        await animateDamage(attacker.id);
                        updateHPUI();
                        await sleep(800);
                        return;  // Skip normal attack this turn
                    }
                }
            }

            setDialog(`${attacker.name} used ${move.name.replace(/-/g,' ').toUpperCase()}!`);

            // Deduct 1 PP when move is used (not for Struggle)
            if (!move.isStruggle) {
                move.pp = Math.max(0, move.pp - 1);
            }

            await animateAttack(attacker.id);
            await sleep(300);

            // --- Physical vs Special Attack/Defense Stats + STAB bonus ---
            const atkStat = move.damageClass === 'special' ? attacker.spAtk : attacker.attack;
            const defStat = move.damageClass === 'special' ? defender.spDef : defender.defense;
            const stab = attacker.types.includes(move.type) ? 1.5 : 1;
            const multiplier = getTypeMultiplier(move.type, defender.types);
            const dmg = calcDamage(move.power, atkStat, defStat, multiplier * stab, move.type);
            defender.hp = Math.max(0, defender.hp - dmg);
            showFloatingDamage(defender.id, dmg, multiplier);
            await animateDamage(defender.id);
            updateHPUI();
            await sleep(600);

            // Show effectiveness message
            if (multiplier === 0) {
                setDialog(`It has no effect on ${defender.name}...`);
                await sleep(1200);
            } else if (multiplier >= 4) {
                SFX.superHit();
                setDialog(`⚡⚡ It's super effective!! (4×)`);
                await sleep(1200);
            } else if (multiplier >= 2) {
                SFX.superHit();
                setDialog(`⚡ It's super effective!`);
                await sleep(1200);
            } else if (multiplier <= 0.5) {
                SFX.weakHit();
                setDialog(`It's not very effective...`);
                await sleep(1200);
            } else {
                SFX.hit();
            }

            // PLOT HOLE FIX: Struggle Recoil Damage to Attacker (1/4 of maxHP)
            if (move.isStruggle && attacker.hp > 0) {
                const recoil = Math.max(1, Math.floor(attacker.maxHp / 4));
                attacker.hp = Math.max(0, attacker.hp - recoil);
                setDialog(`${attacker.name} is hit with recoil!`);
                SFX.hit();
                await animateDamage(attacker.id);
                updateHPUI();
                await sleep(1000);
            }

            if(defender.hp > 0 && !defender.status) {
                // Apply Status Effects conditionally based on move type
                if(move.type === 'poison' && Math.random() < 0.3) {
                    defender.status = 'poison';
                    setDialog(`${defender.name} was poisoned! 🟣`);
                    updateHPUI();
                    await sleep(1200);
                } else if ((move.type === 'psychic' || move.type === 'ghost') && Math.random() < 0.3) {
                    defender.status = 'confusion';
                    defender.statusTurns = Math.floor(Math.random() * 4) + 2;
                    setDialog(`${defender.name} became confused! 💫`);
                    updateHPUI();
                    await sleep(1200);
                }
            }
        }

        async function executeTurn() {
            const p1First = p1State.speed > p2State.speed ||
                            (p1State.speed === p2State.speed && Math.random() < 0.5);
            
            const first  = p1First ? {atk: p1State, def: p2State, m: p1MoveQueue} : {atk: p2State, def: p1State, m: p2MoveQueue};
            const second = p1First ? {atk: p2State, def: p1State, m: p2MoveQueue} : {atk: p1State, def: p2State, m: p1MoveQueue};

            // First Attack
            await processAttack(first.atk, first.def, first.m);
            if(checkFaint()) return;

            // Second Attack (only if first attacker didn't already KO the defender and second attacker didn't faint from struggle recoil)
            await processAttack(second.atk, second.def, second.m);
            if(checkFaint()) return;

            // End-of-turn: Poison damage (1/8 max HP per turn)
            for(let p of [p1State, p2State]) {
                if(p.status === 'poison' && p.hp > 0) {
                    setDialog(`${p.name} is hurt by poison! 🟣`);
                    await sleep(800);
                    p.hp = Math.max(0, p.hp - Math.floor(p.maxHp / 8));
                    SFX.weakHit();
                    await animateDamage(p.id);
                    updateHPUI();
                    await sleep(800);
                    if(checkFaint()) return;
                }
            }

            // End-of-turn: Sandstorm damage (1/16 max HP for non-Rock/Ground/Steel)
            if (currentWeather === 'sandstorm') {
                for(let p of [p1State, p2State]) {
                    if (p.hp > 0 && !p.types.includes('rock') && !p.types.includes('ground') && !p.types.includes('steel')) {
                        setDialog(`${p.name} is buffeted by the sandstorm! 🌪️`);
                        await sleep(800);
                        p.hp = Math.max(0, p.hp - Math.floor(p.maxHp / 16));
                        SFX.weakHit();
                        await animateDamage(p.id);
                        updateHPUI();
                        await sleep(800);
                        if(checkFaint()) return;
                    }
                }
            }

            startTurn();
        }

        async function animateAttack(id) {
            const el = document.getElementById(`${id}-sprite`);
            el.style.animation = `tackle-${id} 0.5s`;
            await sleep(500);
            el.style.animation = '';
        }
        async function animateDamage(id) {
            const el = document.getElementById(`${id}-sprite`);
            el.style.animation = `damage-blink 0.5s`;
            await sleep(500);
            el.style.animation = '';
        }

        function showFloatingDamage(id, dmg, multiplier) {
            const spriteEl = document.getElementById(`${id}-sprite`);
            if (!spriteEl) return;
            const rect = spriteEl.getBoundingClientRect();
            const field = document.querySelector('.battle-field');
            const fieldRect = field.getBoundingClientRect();

            const el = document.createElement('div');
            el.className = 'float-dmg';
            el.innerText = `-${dmg}`;

            // Color based on effectiveness
            if (multiplier >= 2) el.style.color = '#f1c40f';
            else if (multiplier === 0) el.style.color = '#aaa';
            else if (multiplier <= 0.5) el.style.color = '#90caf9';
            else el.style.color = '#ef5350';

            el.style.left = (rect.left - fieldRect.left + rect.width / 2 - 20) + 'px';
            el.style.top  = (rect.top  - fieldRect.top  + rect.height / 3) + 'px';
            field.appendChild(el);

            // Remove after animation
            el.addEventListener('animationend', () => el.remove());
        }

        function checkFaint() {
            if(p1State.hp <= 0) { endGame(p2State, p1State); return true; }
            if(p2State.hp <= 0) { endGame(p1State, p2State); return true; }
            return false;
        }

        function endGame(winner, loser) {
            battleOver = true;
            loser.hp = 0;
            
            // Stop BGM
            const bgm = document.getElementById('bgm');
            bgm.pause();
            bgm.currentTime = 0;

            updateHPUI();
            setDialog(`💥 ${loser.name} fainted! 🏆 ${winner.name} wins!`);
            document.getElementById(`${loser.id}-sprite`).style.opacity = '0';
            
            SFX.faint();
            setTimeout(() => {
                SFX.victory();
            }, 1000);

            // Show interactive end game options
            const movesBox = document.getElementById('moves-container');
            movesBox.innerHTML = `
                <button onclick="rematch()" style="background:#4ADE80; color:#111; padding:10px; font-size:0.65rem;">🔄 Rematch</button>
                <button onclick="resetToSelection()" style="background:#30a7d7; color:#fff; padding:10px; font-size:0.65rem;">👥 Change Pokémon</button>
                <button onclick="window.location.href='/'" style="background:#e3350d; color:#fff; grid-column:span 2; padding:10px; font-size:0.65rem;">📖 Back to Pokédex</button>
            `;
            movesBox.style.display = 'grid';
        }

        function rematch() {
            // Restore HP, PP, status
            p1State.hp = p1State.maxHp;
            p1State.status = null;
            p1State.statusTurns = 0;
            p1State.moves.forEach(m => m.pp = m.maxPp);

            p2State.hp = p2State.maxHp;
            p2State.status = null;
            p2State.statusTurns = 0;
            p2State.moves.forEach(m => m.pp = m.maxPp);

            document.getElementById('player-sprite').style.opacity = '1';
            document.getElementById('enemy-sprite').style.opacity = '1';

            battleOver = false;
            updateHPUI();
            document.getElementById('dialog').innerHTML = '';

            // BUG FIX #1 & #2: Re-roll weather AND restart BGM on Rematch
            const weathers = ['none', 'rain', 'sun', 'sandstorm'];
            currentWeather = weathers[Math.floor(Math.random() * weathers.length)];
            const weatherOverlay = document.getElementById('weather-overlay');
            weatherOverlay.className = 'weather-overlay';
            if (currentWeather !== 'none') {
                weatherOverlay.classList.add('weather-' + currentWeather);
            }

            setDialog(`Rematch! ${p1State.name} vs ${p2State.name}!`);
            if (currentWeather === 'rain') setDialog(`It started to rain!`);
            if (currentWeather === 'sun') setDialog(`The sunlight is harsh!`);
            if (currentWeather === 'sandstorm') setDialog(`A sandstorm kicked up!`);

            // BUG FIX #1: Restart BGM
            const bgm = document.getElementById('bgm');
            bgm.currentTime = 0;
            bgm.play().catch(e => console.log('BGM:', e));

            // Play cries again
            if (selectedP1.cries && selectedP1.cries.latest) {
                let a1 = new Audio(selectedP1.cries.latest);
                a1.volume = 0.5;
                a1.play().catch(e=>console.log(e));
            }

            setTimeout(() => startTurn(), 1500);
        }

        function resetToSelection() {
            // Stop BGM
            const bgm = document.getElementById('bgm');
            bgm.pause();
            bgm.currentTime = 0;

            document.getElementById('battle-arena').style.display = 'none';
            document.getElementById('selection-screen').style.display = 'block';
            document.getElementById('moves-container').style.display = 'none';
            battleOver = false;
        }

        // Auto load Pokémon from URL Query parameter if provided (e.g. /battle?p1=pikachu)
        window.addEventListener('DOMContentLoaded', async () => {
            const urlParams = new URLSearchParams(window.location.search);
            const p1Param = urlParams.get('p1');
            const p2Param = urlParams.get('p2');

            if (p1Param) {
                document.getElementById('p1-input').value = p1Param;
                await selectPokemon('p1');
            }
            if (p2Param) {
                document.getElementById('p2-input').value = p2Param;
                await selectPokemon('p2');
            } else if (p1Param) {
                // Auto roll a random opponent for CPU mode convenience
                await randomPokemon('p2');
            }
        });
    </script>
</body>
</html>
