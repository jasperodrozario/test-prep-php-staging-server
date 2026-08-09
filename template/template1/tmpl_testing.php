<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            position: relative;
        }

        /* Password Protection Styles */
        .password-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            padding: 20px;
            transition: all 0.8s ease;
        }

        .password-overlay.hidden {
            opacity: 0;
            pointer-events: none;
            transform: scale(1.1);
        }

        .password-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 50px;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.8s ease;
        }

        .password-card .lock-icon {
            font-size: 70px;
            color: #ffd93d;
            margin-bottom: 20px;
            animation: pulse 2s ease-in-out infinite;
        }

        .password-card h2 {
            color: #fff;
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .password-card p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
            font-size: 1rem;
            letter-spacing: 1px;
        }

        .password-input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .password-input-group i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
            font-size: 18px;
        }

        .password-input-group input {
            width: 100%;
            padding: 16px 20px 16px 50px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            color: #fff;
            font-size: 16px;
            transition: all 0.3s ease;
            outline: none;
        }

        .password-input-group input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .password-input-group input:focus {
            border-color: #ffd93d;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 20px rgba(255, 217, 61, 0.2);
        }

        .password-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #ffd93d, #ff6b6b);
            border: none;
            border-radius: 15px;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 2px;
        }

        .password-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.4);
        }

        .password-btn:active {
            transform: scale(0.98);
        }

        .password-error {
            color: #ff6b6b;
            background: rgba(255, 107, 107, 0.15);
            border: 1px solid rgba(255, 107, 107, 0.3);
            border-radius: 10px;
            padding: 12px;
            margin-top: 15px;
            font-size: 14px;
            display: none;
            animation: shake 0.5s ease;
        }

        .password-error.show {
            display: block;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .password-hint {
            color: rgba(255, 255, 255, 0.4);
            font-size: 12px;
            margin-top: 15px;
            letter-spacing: 1px;
        }

        /* Main Content Styles (Your existing styles) */
        .floating-hearts {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .heart-float {
            position: absolute;
            font-size: 20px;
            color: rgba(255, 255, 255, 0.3);
            animation: floatUp linear infinite;
        }

        @keyframes floatUp {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-10vh) rotate(720deg);
                opacity: 0;
            }
        }

        .love-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .header-section {
            text-align: center;
            margin-bottom: 50px;
            animation: fadeInDown 1.5s ease;
        }

        .big-heart {
            font-size: 80px;
            color: #ff6b6b;
            animation: pulse 1.5s ease-in-out infinite;
            display: inline-block;
            text-shadow: 0 0 30px rgba(255, 107, 107, 0.5);
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
        }

        .header-section h1 {
            font-size: 3.5rem;
            color: #fff;
            margin: 20px 0 10px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            font-weight: 700;
        }

        .header-section h1 span {
            color: #ffd93d;
        }

        .header-section p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.2rem;
            margin-top: 10px;
            letter-spacing: 2px;
        }

        .love-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .love-item {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 20px 35px;
            color: #fff;
            font-size: 1.4rem;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            cursor: default;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 1s ease forwards;
            opacity: 0;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }

        .love-item::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            transform: scale(0);
            transition: transform 0.5s ease;
        }

        .love-item:hover::before {
            transform: scale(1);
        }

        .love-item:hover {
            transform: translateY(-5px) scale(1.05);
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .love-item .heart-icon {
            color: #ff6b6b;
            font-size: 1.8rem;
            animation: pulse 2s ease-in-out infinite;
        }

        .love-item .noor-name {
            background: linear-gradient(45deg, #ffd93d, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        .love-item:nth-child(3n+1) {
            border-color: rgba(255, 107, 107, 0.5);
        }
        .love-item:nth-child(3n+2) {
            border-color: rgba(255, 217, 61, 0.5);
        }
        .love-item:nth-child(3n+3) {
            border-color: rgba(130, 204, 255, 0.5);
        }

        .love-item:nth-child(1) { animation-delay: 0.1s; }
        .love-item:nth-child(2) { animation-delay: 0.2s; }
        .love-item:nth-child(3) { animation-delay: 0.3s; }
        .love-item:nth-child(4) { animation-delay: 0.4s; }
        .love-item:nth-child(5) { animation-delay: 0.5s; }
        .love-item:nth-child(6) { animation-delay: 0.6s; }
        .love-item:nth-child(7) { animation-delay: 0.7s; }
        .love-item:nth-child(8) { animation-delay: 0.8s; }
        .love-item:nth-child(9) { animation-delay: 0.9s; }
        .love-item:nth-child(10) { animation-delay: 1.0s; }
        .love-item:nth-child(11) { animation-delay: 1.1s; }
        .love-item:nth-child(12) { animation-delay: 1.2s; }
        .love-item:nth-child(13) { animation-delay: 1.3s; }
        .love-item:nth-child(14) { animation-delay: 1.4s; }
        .love-item:nth-child(15) { animation-delay: 1.5s; }
        .love-item:nth-child(16) { animation-delay: 1.6s; }
        .love-item:nth-child(17) { animation-delay: 1.7s; }
        .love-item:nth-child(18) { animation-delay: 1.8s; }
        .love-item:nth-child(19) { animation-delay: 1.9s; }
        .love-item:nth-child(20) { animation-delay: 2.0s; }

        .footer-section {
            margin-top: 60px;
            text-align: center;
            animation: fadeInUp 2s ease forwards;
            opacity: 0;
            animation-delay: 2.2s;
        }

        .footer-section p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            letter-spacing: 1px;
        }

        .footer-section .heart-beat {
            display: inline-block;
            animation: pulse 1.5s ease-in-out infinite;
            color: #ff6b6b;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 2.5rem;
            }
            
            .love-item {
                padding: 15px 25px;
                font-size: 1.1rem;
            }
            
            .big-heart {
                font-size: 60px;
            }
            
            .love-grid {
                gap: 15px;
                padding: 10px;
            }

            .password-card {
                padding: 30px 25px;
            }

            .password-card h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .header-section h1 {
                font-size: 1.8rem;
            }
            
            .love-item {
                padding: 12px 20px;
                font-size: 0.95rem;
                gap: 8px;
            }
            
            .love-item .heart-icon {
                font-size: 1.2rem;
            }
            
            .big-heart {
                font-size: 45px;
            }

            .password-card {
                padding: 25px 20px;
            }

            .password-card .lock-icon {
                font-size: 50px;
            }
        }
</style>

    <!-- Password Protection Overlay -->
    <div class="password-overlay" id="passwordOverlay">
        <div class="password-card">
            <div class="lock-icon">🔐</div>
            <h2>Secret Letter</h2>
            <p>Enter the secret password to unlock</p>
            
            <div class="password-input-group">
                <i class="fas fa-key"></i>
                <input 
                    type="password" 
                    id="passwordInput" 
                    placeholder="Enter password..."
                    autofocus
                >
            </div>
            
            <button class="password-btn" onclick="checkPassword()">
                <i class="fas fa-unlock-alt" style="margin-right: 10px;"></i>
                Unlock
            </button>
            
            <div class="password-error" id="passwordError">
                <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
                Wrong password! Try again
            </div>
            
            <div class="password-hint">Hint: 143</div>
        </div>
    </div>

    <!-- Floating hearts background -->
    <div class="floating-hearts" id="floatingHearts"></div>

    <!-- Main content (initially hidden) -->
    <div class="love-container" id="mainContent" style="display: none;">
        <!-- Header -->
        <div class="header-section">
            <div class="big-heart">❤️</div>
            <h1>
                I <span>❤️</span> You 
                <span class="noor-name">Noor</span>
            </h1>
            <p>Every beat of my heart whispers your name</p>
        </div>

        <!-- Love messages grid -->
        <div class="love-grid" id="loveGrid">
            <!-- Messages will be inserted here by JavaScript -->
        </div>

        <!-- Footer -->
        <div class="footer-section">
            <p>
                Made with <span class="heart-beat">❤️</span> 
                for the most beautiful soul in the universe
            </p>
            <p style="margin-top: 10px; font-size: 0.9rem; opacity: 0.7;">
                Forever & Always, My Noor ✨
            </p>
        </div>
    </div>

    <script>
        // ===== PASSWORD PROTECTION =====
        // 🔑 CHANGE THIS PASSWORD TO YOUR SECRET ONE
        const CORRECT_PASSWORD = "noorihateyou"; // Change this to your special date/number
        
        function checkPassword() {
            const input = document.getElementById('passwordInput');
            const error = document.getElementById('passwordError');
            const password = input.value.trim();
            
            if (password === CORRECT_PASSWORD) {
                // Correct password - show content
                error.classList.remove('show');
                document.getElementById('passwordOverlay').classList.add('hidden');
                document.getElementById('mainContent').style.display = 'flex';
                
                // Initialize the love content
                createFloatingHearts();
                generateLoveMessages();
                
                // Play a success animation
                document.querySelector('.password-card').style.transform = 'scale(0.95)';
                setTimeout(() => {
                    document.querySelector('.password-card').style.transform = 'scale(1)';
                }, 200);
                
                // Clear input
                input.value = '';
            } else {
                // Wrong password
                error.classList.add('show');
                input.style.borderColor = '#ff6b6b';
                input.value = '';
                input.focus();
                
                // Shake animation
                document.querySelector('.password-card').style.animation = 'shake 0.5s ease';
                setTimeout(() => {
                    document.querySelector('.password-card').style.animation = '';
                }, 500);
            }
        }

        // Allow Enter key to submit
        document.getElementById('passwordInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                checkPassword();
            }
        });

        // Focus on the input when page loads
        window.addEventListener('load', function() {
            document.getElementById('passwordInput').focus();
        });

        // ===== FLOATING HEARTS AND MESSAGES =====
        function createFloatingHearts() {
            const container = document.getElementById('floatingHearts');
            const heartSymbols = ['❤️', '💕', '💗', '💖', '✨', '💝', '💓'];
            
            for (let i = 0; i < 25; i++) {
                const heart = document.createElement('div');
                heart.className = 'heart-float';
                heart.textContent = heartSymbols[Math.floor(Math.random() * heartSymbols.length)];
                heart.style.left = Math.random() * 100 + '%';
                heart.style.fontSize = (Math.random() * 20 + 15) + 'px';
                heart.style.animationDuration = (Math.random() * 15 + 10) + 's';
                heart.style.animationDelay = (Math.random() * 10) + 's';
                heart.style.opacity = Math.random() * 0.5 + 0.2;
                container.appendChild(heart);
            }
        }

        function generateLoveMessages() {
            const messages = [
                "I Love You, Noor ❤️",
                "You're My Everything, Bomai",
                "My Heart Belongs to You, Jaan",
                "You're My Sunshine ☀️",
                "I Adore You, Sweetheart",
                "You Make Me Complete, Babe",
                "You're My Whole World 🌍",
                "Forever Yours, Jaan Pakhi",
                "My Love for You is Endless 💖",
                "You're My Dream Come True",
                "I Cherish You, Noor",
                "You Are My Happiness 😊",
                "My One and Only, Jaaneman",
                "You're Perfect Just the Way You Are",
                "I'm Crazy About You, Angry Bird 😘",
                "My Beautiful Queen 👑",
                "Every Day I Fall for You Again",
                "You Mean Everything to Me",
                "My Forever Love, Babe",
                "My Heart Beats Only for You ❤️",
                "You're My Angel 😇",
                "I'm So Lucky to Have You",
                "You're My Paradise 🌸",
                "My Love for You is Infinite ♾️",
                "You Are My Destiny",
                "Good Morning, Sweetheart ☀️",
                "I Miss You, Jaan",
                "Come Here, Bomai 🤗",
                "You're My Favorite Person",
                "Life Feels Better With You",
                "I Can't Stop Thinking About You",
                "My Cutest Angry Bird 🐦❤️",
                "You Make My Heart Smile",
                "I'm Proud to Call You Mine",
                "My Precious Jaan Pakhi 🕊️",
                "You Are My Safe Place",
                "You're My Sweetest Addiction",
                "Sending You a Big Hug, Babe 🤍",
                "You Complete My Soul",
                "Love You More Every Single Day 💕"
            ];

            const grid = document.getElementById('loveGrid');
            
            let allMessages = [];
            while (allMessages.length < 40) {
                allMessages = allMessages.concat(messages);
            }
            
            const shuffled = allMessages.sort(() => Math.random() - 0.5);
            const selectedMessages = shuffled.slice(0, 40);
            
            selectedMessages.forEach((msg, index) => {
                const item = document.createElement('div');
                item.className = 'love-item';
                
                const hearts = ['❤️', '💕', '💗', '💖', '💝', '💓', '💘'];
                const heart = hearts[index % hearts.length];
                
                item.innerHTML = `
                    <span class="heart-icon">${heart}</span>
                    ${msg.replace('Noor', '<span class="noor-name">Noor</span>')}
                    <span class="heart-icon">${heart}</span>
                `;
                
                grid.appendChild(item);
            });
        }

        // Click effect for love items
        document.addEventListener('click', function(e) {
            const item = e.target.closest('.love-item');
            if (item) {
                item.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    item.style.transform = '';
                }, 300);
                
                for (let i = 0; i < 10; i++) {
                    const miniHeart = document.createElement('div');
                    miniHeart.textContent = '❤️';
                    miniHeart.style.position = 'fixed';
                    miniHeart.style.left = e.clientX + (Math.random() - 0.5) * 100 + 'px';
                    miniHeart.style.top = e.clientY + (Math.random() - 0.5) * 100 + 'px';
                    miniHeart.style.fontSize = (Math.random() * 20 + 10) + 'px';
                    miniHeart.style.pointerEvents = 'none';
                    miniHeart.style.zIndex = '9999';
                    miniHeart.style.transition = 'all 1s ease';
                    miniHeart.style.opacity = '1';
                    document.body.appendChild(miniHeart);
                    
                    setTimeout(() => {
                        miniHeart.style.transform = `translate(${(Math.random() - 0.5) * 200}px, ${(Math.random() - 0.5) * 200}px) scale(0)`;
                        miniHeart.style.opacity = '0';
                    }, 50);
                    
                    setTimeout(() => {
                        miniHeart.remove();
                    }, 1050);
                }
            }
        });

        console.log('💖 Made with love for Noor 💖');
        console.log('🔐 Password protected page');
    </script>
