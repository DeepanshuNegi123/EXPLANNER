<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Experiment Planner Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script> 
    <style>
        /* Light Theme */
        :root {
            --primary: #1a73e8;
            --secondary: #f1f3f4;
            --text: #202124;
            --text-light: #5f6368;
            --border: #dadce0;
            --success: #34a853;
            --error: #d93025;
            --card-bg: rgba(255, 255, 255, 0.9);
            --body-bg: #f8f9fa;
            --input-bg: #ffffff;
        }

        /* Dark Theme */
        [data-theme="dark"] {
            --primary: #8ab4f8;
            --secondary: #303134;
            --text: #e8eaed;
            --text-light: #9aa0a6;
            --border: #5f6368;
            --card-bg: #2d2e30;
            --body-bg: #202124;
            --input-bg: #2d2f33;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        }
        
        body {
            font-family: 'Roboto', 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: var(--text);
            min-height: 100vh;
            padding: 20px;
            background: linear-gradient(-45deg, var(--body-bg), var(--primary), var(--body-bg), var(--primary));
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            margin-bottom: 1.5rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .theme-toggle {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .app-container {
            display: flex;
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .panel {
            flex: 1;
            background: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            padding: 24px;
            min-height: 80vh;
            backdrop-filter: blur(5px);
        }
        
        .panel-header {
            font-size: 18px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        label {
            display: block;
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 8px;
        }
        
        input, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 14px;
            transition: border 0.2s;
            background-color: var(--input-bg);
            color: var(--text);
        }
        
        input:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(138, 180, 248, 0.2);
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .btn {
            padding: 10px 24px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* New form layout styles */
        .form-section {
            margin-bottom: 24px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 16px;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .form-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            cursor: pointer;
        }

        .form-section-title {
            font-weight: 500;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-section-content {
            display: block;
        }

        .form-section.collapsed .form-section-content {
            display: none;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        /* Required field indicator */
        .required-field::after {
            content: " *";
            color: var(--error);
        }
        
        /* Chatbot specific styles */
        .chat-container {
            margin-top: 20px;
        }

        .chat-input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        #chatResponse {
            padding: 15px;
            min-height: 100px;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid var(--border);
            border-radius: 5px;
            background-color: var(--secondary);
            scrollbar-width: thin;
            scrollbar-color: var(--primary) var(--secondary);
        }

        /* For Webkit browsers */
        #chatResponse::-webkit-scrollbar {
            width: 8px;
        }

        #chatResponse::-webkit-scrollbar-track {
            background: var(--secondary);
        }

        #chatResponse::-webkit-scrollbar-thumb {
            background-color: var(--primary);
            border-radius: 4px;
        }

        .chat-message {
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 5px;
            line-height: 1.5;
        }

        .user-message {
            background-color: rgba(26, 115, 232, 0.1);
            border-left: 3px solid var(--primary);
        }

        .ai-message {
            background-color: var(--card-bg);
            border-left: 3px solid var(--success);
        }

        .chat-message strong {
            display: block;
            margin-bottom: 5px;
            color: var(--primary);
        }

        .ai-message strong {
            color: var(--success);
        }

        .loading-spinner {
            border: 4px solid rgba(0,0,0,0.1);
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-radius: 4px;
            overflow: hidden;
        }

        .tab-btn {
            flex: 1;
            padding: 10px;
            text-align: center;
            background: var(--secondary);
            border: none;
            cursor: pointer;
            color: var(--text);
        }

        .tab-btn.active {
            background: var(--primary);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
        
        @media (max-width: 768px) {
            .app-container {
                flex-direction: column;
            }
            
            .panel {
                min-height: auto;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Consistent Header -->
    <header class="app-header">
        <div class="logo">
            <span class="logo-icon">🧪</span>
            <span>Experiment Planner Pro</span>
        </div>
        <button class="theme-toggle" id="themeToggle">
            <span id="themeIcon">🌙</span>
            <span>Toggle Theme</span>
        </button>
    </header>

    <div class="app-container">
        <!-- Manual Entry Panel -->
        <div class="panel">
            <div class="panel-header">
                <span>📝</span>
                <span>Experiment Details</span>
            </div>
            
            <form id="experimentForm">
                <!-- Basic Information Section -->
                <div class="form-section">
                    <div class="form-section-header" onclick="toggleSection(this)">
                        <div class="form-section-title">
                            <span>🔍</span>
                            <span>Basic Information</span>
                        </div>
                        <span class="toggle-icon">▼</span>
                    </div>
                    <div class="form-section-content">
                        <div class="form-group">
                            <label for="title" class="required-field">Title</label>
                            <input type="text" id="title" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="objective" class="required-field">Objective</label>
                            <textarea id="objective" name="objective" required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Experiment Design Section -->
                <div class="form-section">
                    <div class="form-section-header" onclick="toggleSection(this)">
                        <div class="form-section-title">
                            <span>🧪</span>
                            <span>Experiment Design</span>
                        </div>
                        <span class="toggle-icon">▼</span>
                    </div>
                    <div class="form-section-content">
                        <div class="form-group">
                            <label for="hypothesis" class="required-field">Hypothesis</label>
                            <textarea id="hypothesis" name="hypothesis" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="materials" class="required-field">Materials</label>
                            <textarea id="materials" name="materials" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="procedure" class="required-field">Procedure</label>
                            <textarea id="procedure" name="procedure" required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Safety Section -->
                <div class="form-section">
                    <div class="form-section-header" onclick="toggleSection(this)">
                        <div class="form-section-title">
                            <span>⚠️</span>
                            <span>Safety & Precautions</span>
                        </div>
                        <span class="toggle-icon">▼</span>
                    </div>
                    <div class="form-section-content">
                        <div class="form-group">
                            <label for="safety">Safety Precautions</label>
                            <textarea id="safety" name="safety" placeholder="List any safety measures needed..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Results & Conclusion Section -->
                <div class="form-section">
                    <div class="form-section-header" onclick="toggleSection(this)">
                        <div class="form-section-title">
                            <span>📊</span>
                            <span>Results & Conclusion</span>
                        </div>
                        <span class="toggle-icon">▼</span>
                    </div>
                    <div class="form-section-content">
                        <div class="form-group">
                            <label for="results">Expected Results</label>
                            <textarea id="results" name="results" placeholder="What results do you expect to see?"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="conclusion">Conclusion</label>
                            <textarea id="conclusion" name="conclusion" placeholder="What conclusions might you draw?"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-icon" id="save_button">
                        <span>💾</span>
                        <span>Save Experiment</span>
                    </button>
                    <button type="button" class="btn btn-icon" id="viewbtn">
                        <span>👁️</span>
                        <span>View Experiments</span>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- AI Suggestions Panel -->
        <div class="panel">
            <div class="panel-header">
                <span>🤖</span>
                <span>AI Assistant</span>
            </div>
            
            <div class="tabs">
                <button id="suggestionsTab" class="tab-btn active">💡 Suggestions</button>
                <button id="chatTab" class="tab-btn">💬 Chat</button>
            </div>
            
            <!-- Suggestions Content -->
            <div id="suggestionsContent" class="tab-content active">
                <button onclick="getAISuggestions()" class="btn btn-icon">
                    <span>🔍</span>
                    <span>Analyze Experiment</span>
                </button>
                
                <div id="ai_response">
                    <p>Enter your experiment details and click the button above to get AI-generated suggestions.</p>
                </div>
            </div>
            
            <!-- Chat Content -->
            <div id="chatContent" class="tab-content">
                <div class="chat-container">
                    <div class="chat-input-group">
                        <input type="text" id="userInput" class="chat-input" placeholder="Ask anything about your experiment..." onkeypress="handleChatKeyPress(event)">
                        <button class="btn chat-btn" onclick="sendChatMessage()">Ask</button>
                    </div>
                    <div id="chatResponse"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Theme Toggle Functionality
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;
        const viewBtn = document.getElementById('viewbtn');
        
        viewBtn.addEventListener('click', () => {
            window.location.href = 'store.php';
        });

        // Check for saved theme preference or use preferred color scheme
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
            themeToggle.querySelector('span:last-child').textContent = theme === 'dark' ? ' Light Mode' : ' Dark Mode';
        }

        // Section Toggle Functionality
        function toggleSection(header) {
            const section = header.parentElement;
            section.classList.toggle('collapsed');
            const icon = header.querySelector('.toggle-icon');
            icon.textContent = section.classList.contains('collapsed') ? '▶' : '▼';
        }

        // Initialize sections as expanded by default
        document.querySelectorAll('.form-section').forEach(section => {
            section.classList.add('collapsed');
            toggleSection(section.querySelector('.form-section-header'));
        });

        // Tab Switching
        document.getElementById('suggestionsTab').addEventListener('click', function() {
            document.getElementById('suggestionsContent').classList.add('active');
            document.getElementById('chatContent').classList.remove('active');
            this.classList.add('active');
            document.getElementById('chatTab').classList.remove('active');
        });

        document.getElementById('chatTab').addEventListener('click', function() {
            document.getElementById('suggestionsContent').classList.remove('active');
            document.getElementById('chatContent').classList.add('active');
            this.classList.add('active');
            document.getElementById('suggestionsTab').classList.remove('active');
        });

        // Enhanced AI Suggestions Function
        async function getAISuggestions() {
            const title = document.getElementById("title").value.trim();
            const objective = document.getElementById("objective").value.trim();
            
            if (!title) {
                alert("Please enter an experiment title first.");
                return;
            }

            const aiResponse = document.getElementById("ai_response");
            aiResponse.innerHTML = '<div class="loading">Generating suggestions... This may take 10-20 seconds.</div>';
            aiResponse.classList.remove("error");

            try {
                const response = await fetch("https://openrouter.ai/api/v1/chat/completions", {
                    method: "POST",
                    headers: {
                        "Authorization": "Bearer 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",
                        "HTTP-Referer": "http://localhost",
                        "X-Title": "Experiment-Planner",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        model: "deepseek/deepseek-r1:free",
                        messages: [
                            {
                                role: "system",
                                content: "You are a helpful scientific research assistant. Provide experiment planning suggestions with these exact section headers: " +
                                        "1. HYPOTHESIS\n2. MATERIALS\n3. PROCEDURE\n4. SAFETY PRECAUTIONS\n" +
                                        "Format each section clearly with the header in all caps followed by a colon."
                            },
                            {
                                role: "user",
                                content: `Suggest details for an experiment titled: "${title}".\n\n` +
                                         `Objective: ${objective || 'Not specified'}\n\n` +
                                         `Provide suggestions for hypothesis, materials, procedure, and safety precautions.`
                            }
                        ],
                        temperature: 0.7
                    })
                });

                if (!response.ok) {
                    throw new Error(`API request failed with status ${response.status}`);
                }

                const data = await response.json();
                const content = data.choices[0].message.content;

                // Parse the response into sections
                const sections = parseResponse(content);
                
                let html = `
                    <div class="response-section">
                        <div class="response-title">Hypothesis</div>
                        <div>${sections.hypothesis || 'No hypothesis generated'}</div>
                    </div>
                    <div class="response-section">
                        <div class="response-title">Materials</div>
                        <div>${sections.materials || 'No materials listed'}</div>
                    </div>
                    <div class="response-section">
                        <div class="response-title">Procedure</div>
                        <div>${sections.procedure || 'No procedure provided'}</div>
                    </div>
                `;

                if (sections.safety) {
                    html += `
                        <div class="response-section">
                            <div class="response-title">Safety Precautions</div>
                            <div>${sections.safety}</div>
                        </div>
                    `;
                }

                // Add apply buttons
                html += `
                    <div class="action-buttons" style="margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                        <button onclick="applySuggestion('hypothesis')" class="btn">Apply Hypothesis</button>
                        <button onclick="applySuggestion('materials')" class="btn">Apply Materials</button>
                        <button onclick="applySuggestion('procedure')" class="btn">Apply Procedure</button>
                        ${sections.safety ? '<button onclick="applySuggestion(\'safety\')" class="btn">Apply Safety</button>' : ''}
                        <button onclick="applyAllSuggestions()" class="btn" style="background: var(--success);">Apply All</button>
                    </div>
                `;

                aiResponse.innerHTML = html;
            } catch (error) {
                aiResponse.classList.add("error");
                aiResponse.innerHTML = `Error: ${error.message}`;
                console.error("Error details:", error);
            }
        }

        function parseResponse(content) {
            const sections = {
                hypothesis: '',
                materials: '',
                procedure: '',
                safety: ''
            };
            
            // Split by section headers
            const hypothesisMatch = content.match(/HYPOTHESIS:\s*(.*?)(?=\n\d\.|$)/is);
            const materialsMatch = content.match(/MATERIALS:\s*(.*?)(?=\n\d\.|$)/is);
            const procedureMatch = content.match(/PROCEDURE:\s*(.*?)(?=\n\d\.|$)/is);
            const safetyMatch = content.match(/SAFETY PRECAUTIONS:\s*(.*?)(?=\n\d\.|$)/is);
            
            if (hypothesisMatch) sections.hypothesis = hypothesisMatch[1].trim();
            if (materialsMatch) sections.materials = materialsMatch[1].trim();
            if (procedureMatch) sections.procedure = procedureMatch[1].trim();
            if (safetyMatch) sections.safety = safetyMatch[1].trim();
            
            return sections;
        }

        function applySuggestion(field) {
            const aiResponse = document.getElementById("ai_response");
            const section = Array.from(aiResponse.querySelectorAll('.response-title'))
                                .find(el => el.textContent.toLowerCase().includes(field));
            
            if (section) {
                const content = section.nextElementSibling.textContent;
                document.getElementById(field).value = content;
                
                // Show confirmation
                const btn = document.createElement('div');
                btn.textContent = '✓ Applied';
                btn.style.color = 'var(--success)';
                btn.style.marginTop = '5px';
                btn.style.fontWeight = 'bold';
                section.parentNode.appendChild(btn);
                
                setTimeout(() => btn.remove(), 2000);
            }
        }

        function applyAllSuggestions() {
            applySuggestion('hypothesis');
            applySuggestion('materials');
            applySuggestion('procedure');
            applySuggestion('safety');
        }

        let conversationHistory = [];
        
        // Chatbot Functions
        async function sendChatMessage() {
            const input = document.getElementById('userInput').value.trim();
            const responseDiv = document.getElementById('chatResponse');
            
            if (!input) {
                responseDiv.innerHTML = '<div class="alert alert-warning">Please enter a message.</div>';
                return;
            }
            
            // Add user message to conversation history
            conversationHistory.push({ role: 'user', content: input });
            
            // Show loading spinner and user message
            responseDiv.innerHTML += `
                <div class="chat-message user-message">
                    <strong>You:</strong> ${input}
                </div>
                <div class="loading-spinner"></div>
            `;
            responseDiv.scrollTop = responseDiv.scrollHeight;
            
            try {
                const response = await fetch(
                    'https://openrouter.ai/api/v1/chat/completions',
                    {
                        method: 'POST',
                        headers: {
                            Authorization: 'Bearer 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',
                            'HTTP-Referer': 'http://localhost',
                            'X-Title': 'Experiment-Planner',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            model: 'deepseek/deepseek-r1:free',
                            messages: conversationHistory,
                        }),
                    }
                );
                
                if (!response.ok) {
                    throw new Error(`API request failed with status ${response.status}`);
                }
                
                const data = await response.json();
                const aiResponse = data.choices?.[0]?.message?.content || 'No response received.';
                
                // Add AI response to conversation history
                conversationHistory.push({ role: 'assistant', content: aiResponse });
                
                // Remove loading spinner and add AI response
                const loadingSpinner = responseDiv.querySelector('.loading-spinner');
                if (loadingSpinner) loadingSpinner.remove();
                
                responseDiv.innerHTML += `
                    <div class="chat-message ai-message">
                        <strong>AI:</strong> ${marked.parse(aiResponse)}
                    </div>
                `;
                
                // Clear input field
                document.getElementById('userInput').value = '';
                
                // Scroll to bottom
                responseDiv.scrollTop = responseDiv.scrollHeight;
                
            } catch (error) {
                responseDiv.innerHTML += `<div class="alert alert-danger">Error: ${error.message}</div>`;
                console.error('Error:', error);
            }
        }
        
        function handleChatKeyPress(event) {
            if (event.key === 'Enter') {
                sendChatMessage();
            }
        }

        // Form submission handler
        document.getElementById("experimentForm").addEventListener("submit", async function(e) {
            e.preventDefault();
            
            const saveButton = document.getElementById("save_button");
            const originalHTML = saveButton.innerHTML;
            
            saveButton.disabled = true;
            saveButton.innerHTML = `<span>⏳</span><span>Saving...</span>`;
            
            try {
                const formData = new FormData(this);
                const response = await fetch("save_experiment.php", {
                    method: "POST",
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`Server error: ${response.status}`);
                }

                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.message || "Failed to save experiment");
                }
                
                saveButton.innerHTML = `<span>✅</span><span>Saved Successfully!</span>`;
                saveButton.style.backgroundColor = "var(--success)";
                
            } catch (error) {
                console.error("Save error:", error);
                const errorMsg = error.message.length > 30 ? "Save Failed" : error.message;
                saveButton.innerHTML = `<span>❌</span><span>${errorMsg}</span>`;
                saveButton.style.backgroundColor = "var(--error)";
            } finally {
                setTimeout(() => {
                    saveButton.innerHTML = originalHTML;
                    saveButton.style.backgroundColor = "var(--primary)";
                    saveButton.disabled = false;
                }, 3000);
            }
        });
    </script>
</body>
</html>