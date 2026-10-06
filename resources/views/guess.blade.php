<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Who's That Pokémon?</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pokemon-ui.css') }}">
</head>
<body class="page-guess">

    <header class="nav"><div class="nav-in">
        <a href="/" class="brand"><span class="ball"></span>Pokédex</a>
        <nav class="nav-links"><a href="/">Pokédex</a><a href="/guess" class="on">Guess</a><a href="/battle">Battle</a></nav>
    </div></header>
    <div class="container"><div class="card">
        <h1 class="title">Who's that Pokémon?</h1>
        <div class="score-board">
            <div class="score-item">Score<span id="score">0</span></div>
            <div class="score-item">Best<span id="highscore">0</span></div>
            <div class="score-item">Streak<span id="streak">0</span></div>
        </div>

        <div class="timer-bar-bg">
            <div class="timer-bar-fill" id="timer-bar" style="width:100%"></div>
        </div>

        <div class="image-container">
            <div class="loader abs" id="loader">Loading...</div>
            <img id="poke-img" class="pokemon-img" src="" alt="Mystery Pokemon" style="display: none;">
        </div>

        <div class="choices" id="choices-container">
            <!-- Buttons injected by JS -->
        </div>

        <div class="result-msg" id="result-msg"></div>
        <button class="next-btn" id="next-btn" onclick="startRound()">Next Pokémon →</button>
    </div></div>

    <script>
        let score = 0;
        let highScore = parseInt(localStorage.getItem('poke-highscore') || '0');
        let streak = 0;
        let correctPokemon = null;
        let isRevealed = false;
        let timerInterval = null;
        let timeLeft = 10;
        const TIMER_SECONDS = 10;
        const MAX_ID = 1025; // full National Dex, same as Pokédex & Battle
        let roundId = 0;
        const sleep = ms => new Promise(r => setTimeout(r, ms));
        
        const imgEl = document.getElementById('poke-img');
        const loader = document.getElementById('loader');
        const choicesContainer = document.getElementById('choices-container');
        const resultMsg = document.getElementById('result-msg');
        const nextBtn = document.getElementById('next-btn');
        const scoreEl = document.getElementById('score');
        const highscoreEl = document.getElementById('highscore');
        const streakEl = document.getElementById('streak');
        const timerBar = document.getElementById('timer-bar');

        // Init high score display
        highscoreEl.innerText = highScore;

        async function fetchRandomPokemon() {
            const id = Math.floor(Math.random() * MAX_ID) + 1;
            try {
                const res = await fetch(`https://pokeapi.co/api/v2/pokemon/${id}`);
                if (!res.ok) return null;
                return await res.json();
            } catch(e) { return null; }
        }

        function startTimer() {
            clearInterval(timerInterval);
            timeLeft = TIMER_SECONDS;
            timerBar.style.width = '100%';
            timerBar.style.backgroundColor = '#2ecc71';

            timerInterval = setInterval(() => {
                timeLeft--;
                const pct = (timeLeft / TIMER_SECONDS) * 100;
                timerBar.style.width = pct + '%';
                if (pct < 30) timerBar.style.backgroundColor = '#e74c3c';
                else if (pct < 60) timerBar.style.backgroundColor = '#f39c12';

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    // Time's up - treat as wrong answer
                    if (!isRevealed) {
                        timeUp();
                    }
                }
            }, 1000);
        }

        function timeUp() {
            isRevealed = true;
            clearInterval(timerInterval);
            timerBar.style.width = '0%';

            const buttons = document.querySelectorAll('.choice-btn');
            buttons.forEach(b => {
                b.disabled = true;
                if (b.dataset.name === correctPokemon.name) b.classList.add('correct');
            });

            streak = 0;
            streakEl.innerText = streak;
            score = Math.max(0, score - 5); // Penalty for timeout
            scoreEl.innerText = score;

            resultMsg.innerText = `⏰ Time's up! It was ${correctPokemon.name.toUpperCase()}!`;
            resultMsg.style.color = '#f39c12';
            resultMsg.classList.add('show');
            nextBtn.style.display = 'block';

            imgEl.classList.add('revealed');
            if (correctPokemon.cries && correctPokemon.cries.latest) {
                new Audio(correctPokemon.cries.latest).play().catch(()=>{});
            }
        }

        function failRound(msg) {
            loader.innerText = msg;
            loader.style.display = 'block';
            nextBtn.innerText = 'Retry';
            nextBtn.style.display = 'block';
        }

        async function startRound(attempt = 0) {
            const myRound = ++roundId;
            isRevealed = false;
            clearInterval(timerInterval);
            imgEl.style.display = 'none';
            imgEl.classList.remove('revealed');
            loader.innerText = 'Loading...';
            loader.style.display = 'block';
            choicesContainer.innerHTML = '';
            resultMsg.classList.remove('show');
            nextBtn.style.display = 'none';
            nextBtn.innerText = 'Next Pokémon →';
            timerBar.style.width = '100%';
            timerBar.style.backgroundColor = '#2ecc71';

            // Bounded retry with backoff (no more infinite loop when offline)
            const retry = async (msg) => {
                if (attempt >= 3) return failRound(msg);
                await sleep(800 * (attempt + 1));
                if (myRound === roundId) startRound(attempt + 1);
            };

            const correctData = await fetchRandomPokemon();
            if (myRound !== roundId) return;
            if (!correctData) return retry('Failed to load. Check your connection.');
            correctPokemon = correctData;

            // Collect 3 distinct wrong names; refetch failures instead of using fake "unknown-N" names
            const wrongNames = new Set();
            for (let tries = 0; wrongNames.size < 3 && tries < 3; tries++) {
                const ids = new Set();
                while (ids.size < 3 - wrongNames.size) {
                    const id = Math.floor(Math.random() * MAX_ID) + 1;
                    if (id !== correctData.id) ids.add(id);
                }
                const results = await Promise.all([...ids].map(id =>
                    fetch(`https://pokeapi.co/api/v2/pokemon/${id}`).then(r => r.ok ? r.json() : null).catch(() => null)));
                results.forEach(d => { if (d && d.name !== correctData.name) wrongNames.add(d.name); });
            }
            if (myRound !== roundId) return;
            if (wrongNames.size < 3) return retry('Failed to load. Check your connection.');

            const options = [correctData.name, ...wrongNames].sort(() => Math.random() - 0.5);

            imgEl.onerror = () => { if (myRound === roundId) retry('Image failed to load.'); };
            // Choices + timer only appear once the silhouette is visible
            imgEl.onload = () => {
                if (myRound !== roundId) return;
                loader.style.display = 'none';
                imgEl.style.display = 'block';
                options.forEach(opt => {
                    const btn = document.createElement('button');
                    btn.className = 'choice-btn';
                    btn.dataset.name = opt;
                    btn.innerText = opt;
                    btn.onclick = () => checkAnswer(opt, btn);
                    choicesContainer.appendChild(btn);
                });
                startTimer();
            };
            imgEl.src = correctData.sprites.other['official-artwork'].front_default || correctData.sprites.front_default;
        }

        function checkAnswer(selectedName, btn) {
            if (isRevealed) return;
            isRevealed = true;
            clearInterval(timerInterval);

            const buttons = document.querySelectorAll('.choice-btn');
            buttons.forEach(b => {
                b.disabled = true;
                if (b.dataset.name === correctPokemon.name) {
                    b.classList.add('correct');
                }
            });

            if (selectedName === correctPokemon.name) {
                btn.classList.add('correct');
                // Bonus points for time remaining
                const timeBonus = timeLeft * 2;
                const points = 10 + timeBonus;
                score += points;
                streak++;
                resultMsg.innerText = `✔️ Correct! +${points} pts (${timeBonus} time bonus!)`;
                resultMsg.style.color = '#4ADE80';
            } else {
                btn.classList.add('wrong');
                streak = 0;
                score = Math.max(0, score - 5); // same penalty as a timeout
                resultMsg.innerText = "Wrong! It's " + correctPokemon.name.toUpperCase() + "!";
                resultMsg.style.color = '#ef5350';
            }

            // Update and save high score
            if (score > highScore) {
                highScore = score;
                localStorage.setItem('poke-highscore', highScore);
                highscoreEl.innerText = highScore;
            }

            scoreEl.innerText = score;
            streakEl.innerText = streak;
            imgEl.classList.add('revealed');
            resultMsg.classList.add('show');
            nextBtn.style.display = 'block';

            // Play Cry
            if (correctPokemon.cries && correctPokemon.cries.latest) {
                const audio = new Audio(correctPokemon.cries.latest);
                audio.volume = 0.5;
                audio.play().catch(e=>console.log(e));
            }
        }

        // Start first round
        startRound();
    </script>

</body>
</html>
