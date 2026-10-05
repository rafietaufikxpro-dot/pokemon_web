<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Who's That Pokémon?</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #2c3e50;
            background-image: radial-gradient(circle, #34495e 0%, #2c3e50 100%);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            backdrop-filter: blur(10px);
            position: relative;
        }

        .nav-link {
            position: absolute;
            top: 20px; left: 20px;
            color: white;
            background: rgba(0,0,0,0.5);
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.8rem;
            font-family: 'Press Start 2P', monospace;
            transition: 0.2s;
        }
        .nav-link:hover { background: rgba(0,0,0,0.8); }

        .title {
            font-family: 'Press Start 2P', monospace;
            color: #f1c40f;
            text-shadow: 3px 3px 0 #d35400;
            font-size: 1.5rem;
            margin-bottom: 10px;
            margin-top: 40px;
        }

        .score-board {
            display: flex;
            justify-content: center;
            gap: 30px;
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #ecf0f1;
        }

        .score-item {
            background: rgba(0,0,0,0.3);
            padding: 8px 18px;
            border-radius: 10px;
            text-align: center;
        }

        .score-item span {
            display: block;
            font-size: 1.4rem;
            color: #f1c40f;
        }

        /* Timer Bar */
        .timer-bar-bg {
            width: 100%;
            height: 10px;
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .timer-bar-fill {
            height: 100%;
            background: #2ecc71;
            border-radius: 5px;
            transition: width 1s linear, background-color 0.5s;
        }

        .image-container {
            width: 250px;
            height: 250px;
            margin: 0 auto 30px auto;
            background: url('https://www.pokemon.com/static/all/img/bg-repeating-content.png');
            border-radius: 50%;
            border: 5px solid #f1c40f;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.5), 0 0 15px rgba(241, 196, 15, 0.5);
            position: relative;
            overflow: hidden;
        }

        .pokemon-img {
            width: 80%;
            height: 80%;
            object-fit: contain;
            filter: brightness(0); /* Silhouette effect */
            transition: filter 0.5s ease-in-out, transform 0.3s;
        }
        
        .pokemon-img.revealed {
            filter: brightness(1);
            transform: scale(1.1);
        }

        .choices {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .choice-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            text-transform: capitalize;
            box-shadow: 0 4px 0 #2980b9;
            transition: all 0.1s;
        }
        
        .choice-btn:hover { background: #2980b9; }
        .choice-btn:active { transform: translateY(4px); box-shadow: 0 0 0; }
        .choice-btn:disabled { cursor: not-allowed; opacity: 0.8; }

        .choice-btn.correct { background: #2ecc71; box-shadow: 0 4px 0 #27ae60; }
        .choice-btn.wrong { background: #e74c3c; box-shadow: 0 4px 0 #c0392b; }

        .result-msg {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 20px;
            min-height: 35px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .result-msg.show { opacity: 1; }

        .next-btn {
            display: none;
            background: #f1c40f;
            color: #333;
            border: none;
            padding: 12px 25px;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 25px;
            cursor: pointer;
            margin: 20px auto 0 auto;
            box-shadow: 0 4px 0 #d35400;
        }
        .next-btn:active { transform: translateY(4px); box-shadow: 0 0 0; }

        .loader {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            font-size: 1.2rem;
            color: #333;
            display: none;
        }
    </style>
</head>
<body>

    <div class="container">
        <a href="/" class="nav-link">⬅ Back</a>
        
        <h1 class="title">Who's That Pokémon?</h1>
        <div class="score-board">
            <div class="score-item">Score<span id="score">0</span></div>
            <div class="score-item">🏆 Best<span id="highscore">0</span></div>
            <div class="score-item">Streak<span id="streak">0</span></div>
        </div>

        <div class="timer-bar-bg">
            <div class="timer-bar-fill" id="timer-bar" style="width:100%"></div>
        </div>

        <div class="image-container">
            <div class="loader" id="loader">Loading...</div>
            <img id="poke-img" class="pokemon-img" src="" alt="Mystery Pokemon" style="display: none;">
        </div>

        <div class="choices" id="choices-container">
            <!-- Buttons injected by JS -->
        </div>

        <div class="result-msg" id="result-msg"></div>
        <button class="next-btn" id="next-btn" onclick="startRound()">Next Pokémon ➡</button>
    </div>

    <script>
        let score = 0;
        let highScore = parseInt(localStorage.getItem('poke-highscore') || '0');
        let streak = 0;
        let correctPokemon = null;
        let isRevealed = false;
        let timerInterval = null;
        let timeLeft = 10;
        const TIMER_SECONDS = 10;
        
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
            const id = Math.floor(Math.random() * 898) + 1; // Gen 1-8 to ensure good artwork
            try {
                const res = await fetch(`https://pokeapi.co/api/v2/pokemon/${id}`);
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
                if (b.innerText === correctPokemon.name) b.classList.add('correct');
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

        async function startRound() {
            isRevealed = false;
            imgEl.style.display = 'none';
            imgEl.classList.remove('revealed');
            loader.style.display = 'block';
            choicesContainer.innerHTML = '';
            resultMsg.classList.remove('show');
            nextBtn.style.display = 'none';

            // Fetch correct pokemon + 3 random wrong IDs simultaneously (BUG FIX #4: parallel fetching)
            const correctData = await fetchRandomPokemon();
            if (!correctData) { startRound(); return; }
            correctPokemon = correctData;

            // Generate 3 unique random IDs that differ from the correct one
            const wrongIds = new Set();
            while (wrongIds.size < 3) {
                const id = Math.floor(Math.random() * 898) + 1;
                if (id !== correctData.id) wrongIds.add(id);
            }

            // Fetch all 3 wrong pokemon in parallel
            const wrongResults = await Promise.all(
                [...wrongIds].map(id =>
                    fetch(`https://pokeapi.co/api/v2/pokemon/${id}`)
                        .then(r => r.json())
                        .catch(() => null)
                )
            );

            const wrongNames = wrongResults
                .filter(d => d !== null)
                .map(d => d.name);

            // Ensure we have 3 wrong names; if any failed, pad with generic names
            while (wrongNames.length < 3) {
                wrongNames.push('unknown-' + wrongNames.length);
            }

            const options = [correctData.name, ...wrongNames];
            options.sort(() => Math.random() - 0.5); // Shuffle

            // Set Image
            imgEl.src = correctData.sprites.other['official-artwork'].front_default || correctData.sprites.front_default;
            imgEl.onload = () => {
                loader.style.display = 'none';
                imgEl.style.display = 'block';
            };

            // Set Buttons
            options.forEach(opt => {
                const btn = document.createElement('button');
                btn.className = 'choice-btn';
                btn.innerText = opt;
                btn.onclick = () => checkAnswer(opt, btn);
                choicesContainer.appendChild(btn);
            });

            // Start timer after image loads
            imgEl.onload = () => {
                loader.style.display = 'none';
                imgEl.style.display = 'block';
                startTimer();
            };
        }

        function checkAnswer(selectedName, btn) {
            if (isRevealed) return;
            isRevealed = true;
            clearInterval(timerInterval);

            const buttons = document.querySelectorAll('.choice-btn');
            buttons.forEach(b => {
                b.disabled = true;
                if (b.innerText === correctPokemon.name) {
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
                score = Math.max(0, score);
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
