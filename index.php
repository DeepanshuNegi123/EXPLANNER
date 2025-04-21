<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Experiment Planner Pro</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            background: linear-gradient(-45deg, var(--body-bg), var(--primary), 
            var(--body-bg), var(--primary));
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        :root {
            --primary: #1a73e8;
            --secondary: #f1f3f4;
            --text: #202124;
            --card-bg: rgba(255, 255, 255, 0.9);
            --body-bg: #f8f9fa;
        }
        
        [data-theme="dark"] {
            --primary: rgb(109, 147, 209);
            --secondary: rgb(51, 54, 54);
            --text: #e8eaed;
            --card-bg: rgba(255, 255, 255, 0.06);
            --body-bg: rgb(19, 19, 20);
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            transition: all 0.3s ease;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }
        
        .logo {
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .logo-icon {
            font-size: 2.5rem;
        }
        
        .theme-toggle {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: grid;
            place-items: center;
        }
        
        .hero {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(90deg, var(--primary), #34a853);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .subtitle {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            color: var(--text);
            opacity: 0.9;
        }
        
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 3rem;
        }
        
        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-secondary {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }
        
        .feature-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            backdrop-filter: blur(5px);
        }
        
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .feature-title {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }

        /* New About & Contact Section */
        .about-contact {
            margin-top: 4rem;
            padding: 3rem 0;
            border-top: 1px solid rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .info-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .info-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color:#34a853;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-list {
            list-style: none;
        }

        .info-list li {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-list a {
            color: var(--text);
            text-decoration: none;
            transition: color 0.2s;
        }

        .info-list a:hover {
            color: var(--primary);
            text-decoration: underline;
        }
        
        footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <span class="logo-icon">🧪</span>
                <span>Experiment Planner Pro</span>
            </div>
            <button class="theme-toggle" id="themeToggle">
                <span id="themeIcon">🌙</span>
            </button>
        </header>
        
        <main>
            <section class="hero">
                <h1>Streamline Your Scientific Research</h1>
                <p class="subtitle">
                    Design, document, and optimize your experiments with our all-in-one platform. 
                    Perfect for researchers, students, and science enthusiasts.
                </p>
                
                <div class="cta-buttons">
                    <a href="middle.php" class="btn btn-primary">
                        <span>🚀</span>
                        Start Planning
                    </a>
                    <a href="#features" class="btn btn-secondary">
                        <span>🔍</span>
                        Learn More
                    </a>
                </div>
            </section>
            
            <section id="features" class="features">
                <div class="feature-card">
                    <div class="feature-icon">📝</div>
                    <h3 class="feature-title">Manual Entry</h3>
                    <p>Complete control with detailed forms for objectives, materials, procedures, and more.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🤖</div>
                    <h3 class="feature-title">AI Suggestions</h3>
                    <p>Get intelligent recommendations for hypotheses, materials lists, and procedures.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">💾</div>
                    <h3 class="feature-title">Save & Organize</h3>
                    <p>Store all your experiments securely and access them anytime.</p>
                </div>
            </section>

            <!-- New About & Contact Section -->
            <section class="about-contact">
                <h2 style="text-align: center; margin-bottom: 1rem;">About & Contact</h2>
                <p style="text-align: center; max-width: 700px; margin: 0 auto 2rem; color: var(--text); opacity: 0.8;">
                    Connect with our team and learn more about the project
                </p>
                
                <div class="info-grid">
                    <div class="info-card">
                        <h3 class="info-title">📧 Contact Us</h3>
                        <ul class="info-list">
                            <li>📩 <a href="form.php">Email Support</a></li>
                            <li>📞 <a href="tel:+1234567890">+1 (234) 567-890</a></li>
                            <li>🏢 Science Park, Research City</li>
                        </ul>
                    </div>
                    
                    <div class="info-card">
                        <h3 class="info-title">👨‍🔬 Development Team</h3>
                        <ul class="info-list">
                            <li>👨‍💻 <a href="#">Lead Developer: Deepanshu </a></li>
                            <li>👩‍🔬 <a href="#">Research Lead</a></li>
                            <li>🎨 <a href="#">UI Designer</a></li>
                        </ul>
                    </div>
                    
                    <div class="info-card">
                        <h3 class="info-title">🤝 Contributors</h3>
                        <ul class="info-list">
                            <li>🌟 <a href="#">Open Source Community</a></li>
                            <li>🔬 <a href="#">Scientific Advisors</a></li>
                            <li>📊 <a href="#">Data Specialists</a></li>
                        </ul>
                    </div>
                    
                   
                </div>
            </section>
        </main>
        
        <footer>
            <p>© 2025 Experiment Planner Pro | Designed for Scientists</p>
        </footer>
    </div>

    <script>
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;
        
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme') || 
                         (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        
        // Apply the saved theme
        htmlElement.setAttribute('data-theme', savedTheme);
        updateIcon(savedTheme);
        
        // Toggle theme on button click
        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcon(newTheme);
        });
        
        function updateIcon(theme) {
            themeIcon.textContent = theme === 'dark' ? '☀️' : '🌙';
        }
    </script>
</body>
</html>