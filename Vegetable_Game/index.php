<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vegetable Quiz</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            background: linear-gradient(135deg, #e9ecef, #f8f9fa);
            color: #333;
        }
        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #007bff;
            text-shadow: 1px 1px 2px #aaa;
        }
        .game-container {
            margin-top: 20px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .game-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .choices-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        .choice-btn {
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .choice-btn:hover {
            background-color: #218838;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .score {
            font-size: 24px;
            margin: 20px 0;
        }
        #game-image {
            max-width: 300px;
            max-height: 300px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .high-scores {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Vegetable Quiz</h1>
    
    <div id="start-screen">
        <h2>Welcome to the Vegetable Quiz!</h2>
        <form id="player-form">
            <input type="text" id="player-name" placeholder="Enter your name" required>
            <button type="submit">Start Game</button>
        </form>
    </div>

    <div id="game-screen" class="hidden">
        <div class="score">Score: <span id="score">0</span>/10</div>
        <div class="game-container">
            <img id="game-image" src="" alt="Guess the vegetable">
            <div class="choices-container" id="choices">
                <!-- Choices will be inserted here -->
            </div>
        </div>
    </div>

    <div id="result-screen" class="hidden">
        <h2>Game Over!</h2>
        <div id="final-score"></div>
        <div id="completion-time"></div>
        <button id="play-again">Play Again</button>
        
        <div class="high-scores">
            <h3>High Scores</h3>
            <table id="high-scores-table">
                <thead>
                    <tr>
                        <th>Date Played</th>
                        <th>Username</th>
                        <th>Score</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- High scores will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="game.js"></script>
</body>
</html>
