class VegetableQuiz {
    constructor() {
        this.score = 0;
        this.questionCount = 0;
        this.usedItems = [];
        this.timeStarted = null;
        this.playerName = '';
        
        // DOM elements
        this.startScreen = document.getElementById('start-screen');
        this.gameScreen = document.getElementById('game-screen');
        this.resultScreen = document.getElementById('result-screen');
        this.playerForm = document.getElementById('player-form');
        this.scoreElement = document.getElementById('score');
        this.gameImage = document.getElementById('game-image');
        this.choicesContainer = document.getElementById('choices');
        this.finalScore = document.getElementById('final-score');
        this.completionTime = document.getElementById('completion-time');
        this.playAgainBtn = document.getElementById('play-again');
        this.highScoresTable = document.getElementById('high-scores-table').querySelector('tbody');
        
        // Bind event listeners
        this.playerForm.addEventListener('submit', this.startGame.bind(this));
        this.playAgainBtn.addEventListener('click', this.resetGame.bind(this));
    }
    
    async startGame(event) {
        event.preventDefault();
        this.playerName = document.getElementById('player-name').value;
        this.timeStarted = new Date().toISOString();
        this.score = 0;
        this.questionCount = 0;
        this.usedItems = [];
        
        this.startScreen.classList.add('hidden');
        this.gameScreen.classList.remove('hidden');
        this.resultScreen.classList.add('hidden');
        
        await this.loadQuestion();
    }
    
    async loadQuestion() {
        const formData = new FormData();
        formData.append('used_items', JSON.stringify(this.usedItems));
        
        const response = await fetch('api.php?action=get_question', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (!data.success || !data.data) {
            this.endGame();
            return;
        }
        
        const question = data.data;
        this.usedItems.push(question.id);
        
        this.gameImage.src = question.image_url;
        this.choicesContainer.innerHTML = '';
        
        question.choices.forEach(choice => {
            const button = document.createElement('button');
            button.className = 'choice-btn';
            button.textContent = choice;
            button.addEventListener('click', () => this.checkAnswer(choice, question.correct_answer));
            this.choicesContainer.appendChild(button);
        });
    }
    
    async checkAnswer(selected, correct) {
        if (selected === correct) {
            this.score++;
            this.scoreElement.textContent = this.score;
        }
        
        this.questionCount++;
        
        if (this.questionCount >= 10) {
            await this.endGame();
        } else {
            await this.loadQuestion();
        }
    }
    
    async endGame() {
        const duration = Math.round((new Date() - new Date(this.timeStarted)) / 1000);
        
        // Save result
        await fetch('api.php?action=save_result', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                player_name: this.playerName,
                score: this.score,
                time_started: this.timeStarted,
                duration: duration
            })
        });
        
        // Update UI
        this.gameScreen.classList.add('hidden');
        this.resultScreen.classList.remove('hidden');
        this.finalScore.textContent = `Final Score: ${this.score}/10`;
        this.completionTime.textContent = `Completion Time: ${duration} seconds`;
        
        // Load high scores
        await this.loadHighScores();
    }
    
    async loadHighScores() {
        const response = await fetch('api.php?action=get_high_scores');
        const data = await response.json();
        
        if (!data.success) return;
        
        this.highScoresTable.innerHTML = '';
        data.data.forEach(score => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${score.date_played}</td>
                <td>${score.player_name}</td>
                <td>${score.score}/10</td>
                <td>${score.duration_seconds} seconds</td>
            `;
            this.highScoresTable.appendChild(row);
        });
    }
    
    resetGame() {
        this.resultScreen.classList.add('hidden');
        this.startScreen.classList.remove('hidden');
        document.getElementById('player-name').value = '';
    }
}

// Initialize the game
document.addEventListener('DOMContentLoaded', () => {
    new VegetableQuiz();
});
