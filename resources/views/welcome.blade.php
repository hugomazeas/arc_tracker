<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Archery Tracker') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #0f172a;
            padding: 10px;
            height: 100vh;
            overflow: hidden;
            color: #e2e8f0;
        }

        .container {
            max-width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            flex: 1;
            overflow: hidden;
        }

        .panel {
            background: #1e293b;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid #334155;
        }

        h2 {
            font-size: 1.2rem;
            color: #f1f5f9;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #334155;
            flex-shrink: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .manage-link {
            font-size: 0.85rem;
            color: #2196F3;
            text-decoration: none;
            font-weight: normal;
        }

        .manage-link:hover {
            text-decoration: underline;
        }

        .player-select {
            margin-bottom: 10px;
            flex-shrink: 0;
        }

        label {
            display: block;
            margin-bottom: 4px;
            font-weight: 600;
            color: #cbd5e1;
            font-size: 0.9rem;
        }

        select, input {
            width: 100%;
            padding: 8px;
            border: 2px solid #334155;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: border-color 0.3s;
            background: #0f172a;
            color: #e2e8f0;
        }

        select:focus, input:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .target-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            min-height: 0;
        }

        #target {
            cursor: crosshair;
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
        }

        .arrow-marker {
            fill: #ff0000;
            stroke: #8b0000;
            stroke-width: 2;
        }

        .score-display {
            text-align: center;
            margin: 8px 0;
            flex-shrink: 0;
        }

        .score-value {
            font-size: 2rem;
            font-weight: bold;
            color: #4CAF50;
        }

        .score-label {
            color: #94a3b8;
            margin-top: 2px;
            font-size: 0.85rem;
        }

        .arrows-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin: 8px 0;
            flex-shrink: 0;
        }

        .arrow-score {
            background: #0f172a;
            padding: 8px;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
            font-size: 1rem;
            border: 1px solid #334155;
            color: #64748b;
        }

        .arrow-score.hit {
            background: #064e3b;
            color: #6ee7b7;
            border-color: #10b981;
        }

        .arrow-score.miss {
            background: #7f1d1d;
            color: #fca5a5;
            border-color: #dc2626;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            flex-shrink: 0;
        }

        button:hover:not(:disabled) {
            background: #45a049;
        }

        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .reset-btn {
            background: #f44336;
            margin-top: 6px;
        }

        .reset-btn:hover:not(:disabled) {
            background: #da190b;
        }

        .week-selector {
            display: flex;
            gap: 6px;
            margin-bottom: 10px;
            align-items: center;
            flex-shrink: 0;
        }

        .week-selector button {
            width: auto;
            padding: 6px 12px;
            background: #2196F3;
            font-size: 0.85rem;
        }

        .week-selector button:hover {
            background: #0b7dda;
        }

        .week-selector span {
            flex: 1;
            text-align: center;
            font-weight: 600;
            color: #e2e8f0;
            font-size: 0.85rem;
        }

        .leaderboard-wrapper {
            flex: 1;
            overflow-y: auto;
            min-height: 0;
        }

        .leaderboard-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .leaderboard-table th {
            background: #0f172a;
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            color: #cbd5e1;
            border-bottom: 2px solid #334155;
            position: sticky;
            top: 0;
        }

        .leaderboard-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #334155;
            color: #e2e8f0;
        }

        .leaderboard-table tr:hover {
            background: #334155;
        }

        .rank {
            font-weight: bold;
            color: #4CAF50;
        }

        .bonuses-display {
            margin: 8px 0;
            padding: 10px;
            background: #422006;
            border-radius: 4px;
            border-left: 4px solid #fbbf24;
            flex-shrink: 0;
        }

        .bonuses-display h3 {
            font-size: 0.95rem;
            margin-bottom: 6px;
            color: #fbbf24;
        }

        .bonus-item {
            margin: 3px 0;
            color: #fde68a;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .bonuses-display p {
            color: #fde68a;
        }

        .info-section {
            margin: 8px 0;
            padding: 10px;
            background: #0c4a6e;
            border-radius: 4px;
            border-left: 4px solid #38bdf8;
            flex-shrink: 0;
            max-height: 150px;
            overflow-y: auto;
        }

        .info-section h3 {
            margin-bottom: 6px;
            color: #7dd3fc;
            font-size: 0.95rem;
        }

        .info-section p {
            margin: 3px 0;
            color: #bae6fd;
            font-size: 0.8rem;
        }

        .button-group {
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <!-- Game Panel -->
            <div class="panel">
                <h2>
                    <span>New Game</span>
                    <a href="/manage/players" class="manage-link">Manage Players →</a>
                </h2>

                <div class="player-select">
                    <label for="playerSelect">Select Player:</label>
                    <select id="playerSelect">
                        <option value="">-- Select a player --</option>
                    </select>
                </div>

                <div class="target-container">
                    <svg id="target" width="500" height="500" viewBox="-250 -250 500 500">
                        <!-- Target rings -->
                        <circle cx="0" cy="0" r="250" fill="#fafafa" stroke="#666" stroke-width="2"/>
                        <circle cx="0" cy="0" r="200" fill="#1a1a1a" stroke="#666" stroke-width="2"/>
                        <circle cx="0" cy="0" r="150" fill="#2563eb" stroke="#666" stroke-width="2"/>
                        <circle cx="0" cy="0" r="100" fill="#dc2626" stroke="#666" stroke-width="2"/>
                        <circle cx="0" cy="0" r="50" fill="#fbbf24" stroke="#666" stroke-width="2"/>

                        <!-- Crosshair -->
                        <line x1="-250" y1="0" x2="250" y2="0" stroke="#999" stroke-width="1" stroke-dasharray="5,5"/>
                        <line x1="0" y1="-250" x2="0" y2="250" stroke="#999" stroke-width="1" stroke-dasharray="5,5"/>

                        <!-- Score labels -->
                        <text x="0" y="-230" text-anchor="middle" font-size="20" fill="#1a1a1a" font-weight="bold">6</text>
                        <text x="0" y="-180" text-anchor="middle" font-size="20" fill="#fafafa" font-weight="bold">7</text>
                        <text x="0" y="-130" text-anchor="middle" font-size="20" fill="#fafafa" font-weight="bold">8</text>
                        <text x="0" y="-80" text-anchor="middle" font-size="20" fill="#fafafa" font-weight="bold">9</text>
                        <text x="0" y="-30" text-anchor="middle" font-size="20" fill="#1a1a1a" font-weight="bold">10</text>

                        <!-- Arrow markers will be added here -->
                        <g id="arrowMarkers"></g>
                    </svg>
                </div>

                <div class="score-display">
                    <div class="score-value" id="currentScore">0</div>
                    <div class="score-label">Current Score (Arrow <span id="arrowCount">0</span>/4)</div>
                </div>

                <div class="arrows-list" id="arrowsList">
                    <div class="arrow-score">-</div>
                    <div class="arrow-score">-</div>
                    <div class="arrow-score">-</div>
                    <div class="arrow-score">-</div>
                </div>

                <div id="bonusesDisplay"></div>
            </div>

            <!-- Leaderboard Panel -->
            <div class="panel">
                <h2>Weekly Leaderboard</h2>

                <div class="button-group">
                    <button id="submitBtn" disabled>Submit Game</button>
                    <button class="reset-btn" id="resetBtn">Reset</button>
                </div>

                <div class="info-section" id="bonusInfo">
                    <h3>Available Bonuses</h3>
                    <div id="bonusList"></div>
                </div>

                <div class="week-selector">
                    <button id="prevWeek">&larr; Previous</button>
                    <span id="weekDisplay">Loading...</span>
                    <button id="nextWeek">Next &rarr;</button>
                </div>

                <div class="leaderboard-wrapper">
                    <table class="leaderboard-table">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Player</th>
                                <th>Games</th>
                                <th>Total</th>
                                <th>Avg</th>
                                <th>Best</th>
                            </tr>
                        </thead>
                        <tbody id="leaderboardBody">
                            <tr>
                                <td colspan="6" style="text-align: center;">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '/api';
        let currentArrows = [];
        let currentPlayer = null;
        let currentWeek = null;
        let currentYear = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadPlayers();
            loadBonuses();
            initializeWeek();
            setupEventListeners();
        });

        function setupEventListeners() {
            document.getElementById('target').addEventListener('click', handleTargetClick);
            document.getElementById('playerSelect').addEventListener('change', handlePlayerChange);
            document.getElementById('submitBtn').addEventListener('click', submitGame);
            document.getElementById('resetBtn').addEventListener('click', resetGame);
            document.getElementById('prevWeek').addEventListener('click', () => changeWeek(-1));
            document.getElementById('nextWeek').addEventListener('click', () => changeWeek(1));
        }

        async function loadPlayers() {
            try {
                const response = await fetch(`${API_BASE}/players`);
                const players = await response.json();
                const select = document.getElementById('playerSelect');
                players.forEach(player => {
                    const option = document.createElement('option');
                    option.value = player.id;
                    option.textContent = player.name;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading players:', error);
            }
        }

        async function loadBonuses() {
            try {
                const response = await fetch(`${API_BASE}/bonuses`);
                const bonuses = await response.json();
                const bonusList = document.getElementById('bonusList');
                bonusList.innerHTML = bonuses.map(bonus =>
                    `<p><strong>[+${bonus.points} pts]</strong> ${bonus.name}: ${bonus.description}</p>`
                ).join('');
            } catch (error) {
                console.error('Error loading bonuses:', error);
            }
        }

        function initializeWeek() {
            const now = new Date();
            currentYear = now.getFullYear();
            currentWeek = getWeekNumber(now);
            loadLeaderboard();
        }

        function getWeekNumber(date) {
            const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
            const dayNum = d.getUTCDay() || 7;
            d.setUTCDate(d.getUTCDate() + 4 - dayNum);
            const yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
            return Math.ceil((((d - yearStart) / 86400000) + 1)/7);
        }

        async function loadLeaderboard() {
            try {
                const response = await fetch(`${API_BASE}/leaderboard/weekly?year=${currentYear}&week=${currentWeek}`);
                const data = await response.json();

                document.getElementById('weekDisplay').textContent =
                    `Week ${data.week}, ${data.year} (${data.start_date} to ${data.end_date})`;

                const tbody = document.getElementById('leaderboardBody');
                if (data.leaderboard.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">No games this week</td></tr>';
                } else {
                    tbody.innerHTML = data.leaderboard.map((entry, index) => `
                        <tr>
                            <td class="rank">#${index + 1}</td>
                            <td>${entry.player_name}</td>
                            <td>${entry.games_played}</td>
                            <td><strong>${entry.total_score}</strong></td>
                            <td>${entry.avg_score}</td>
                            <td>${entry.best_game}</td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error loading leaderboard:', error);
            }
        }

        function changeWeek(delta) {
            currentWeek += delta;
            if (currentWeek < 1) {
                currentWeek = 52;
                currentYear--;
            } else if (currentWeek > 52) {
                currentWeek = 1;
                currentYear++;
            }
            loadLeaderboard();
        }

        function handlePlayerChange(e) {
            currentPlayer = e.target.value;
            updateSubmitButton();
        }

        function handleTargetClick(e) {
            if (currentArrows.length >= 4 || !currentPlayer) return;

            const svg = e.currentTarget;
            const rect = svg.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            // Convert to SVG coordinates
            const svgX = (x / rect.width) * 500;
            const svgY = (y / rect.height) * 500;

            addArrow(svgX, svgY);
        }

        function addArrow(x, y) {
            currentArrows.push({ x, y });

            // Draw arrow marker
            const marker = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            marker.setAttribute('cx', x);
            marker.setAttribute('cy', y);
            marker.setAttribute('r', 8);
            marker.setAttribute('class', 'arrow-marker');
            document.getElementById('arrowMarkers').appendChild(marker);

            // Calculate score
            const distance = Math.sqrt(x * x + y * y);
            let score = 0;
            if (distance <= 50) score = 10;
            else if (distance <= 100) score = 9;
            else if (distance <= 150) score = 8;
            else if (distance <= 200) score = 7;
            else if (distance <= 250) score = 6;
            // else score remains 0 (miss)

            // Update display
            updateScoreDisplay(score);
            updateSubmitButton();
        }

        function updateScoreDisplay(arrowScore) {
            const arrowsList = document.getElementById('arrowsList').children;
            const index = currentArrows.length - 1;

            if (arrowScore === 0) {
                arrowsList[index].textContent = 'MISS';
                arrowsList[index].classList.add('miss');
            } else {
                arrowsList[index].textContent = arrowScore;
                arrowsList[index].classList.add('hit');
            }

            // Calculate total (only count numeric scores)
            const total = Array.from(arrowsList)
                .slice(0, currentArrows.length)
                .reduce((sum, el) => {
                    const text = el.textContent;
                    return sum + (text === 'MISS' ? 0 : parseInt(text));
                }, 0);

            document.getElementById('currentScore').textContent = total;
            document.getElementById('arrowCount').textContent = currentArrows.length;
        }

        function updateSubmitButton() {
            document.getElementById('submitBtn').disabled = currentArrows.length !== 4 || !currentPlayer;
        }

        async function submitGame() {
            try {
                const response = await fetch(`${API_BASE}/games`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        player_id: currentPlayer,
                        arrows: currentArrows
                    })
                });

                const game = await response.json();

                // Show result
                const bonusesDisplay = document.getElementById('bonusesDisplay');
                if (game.bonuses_applied && game.bonuses_applied.length > 0) {
                    bonusesDisplay.innerHTML = `
                        <div class="bonuses-display">
                            <h3>🎉 Bonuses Earned!</h3>
                            ${game.bonuses_applied.map(b =>
                                `<div class="bonus-item">+${b.points} pts: ${b.name}</div>`
                            ).join('')}
                            <p style="margin-top: 10px;"><strong>Final Score: ${game.total_score}</strong></p>
                        </div>
                    `;
                } else {
                    bonusesDisplay.innerHTML = `
                        <div class="bonuses-display">
                            <h3>✓ Game Submitted!</h3>
                            <p style="margin-top: 10px;"><strong>Final Score: ${game.total_score}</strong></p>
                        </div>
                    `;
                }

                loadLeaderboard();
                setTimeout(resetGame, 30000);
            } catch (error) {
                console.error('Error submitting game:', error);
                alert('Error submitting game. Please try again.');
            }
        }

        function resetGame() {
            currentArrows = [];
            document.getElementById('arrowMarkers').innerHTML = '';
            document.getElementById('currentScore').textContent = '0';
            document.getElementById('arrowCount').textContent = '0';
            document.getElementById('bonusesDisplay').innerHTML = '';

            const arrowsList = document.getElementById('arrowsList').children;
            Array.from(arrowsList).forEach(el => {
                el.textContent = '-';
                el.classList.remove('hit', 'miss');
            });

            updateSubmitButton();
        }
    </script>
</body>
</html>
