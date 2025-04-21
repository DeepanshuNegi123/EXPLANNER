<?php
include 'db.php';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM experiments WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $delete_message = "Experiment deleted successfully!";
    } else {
        $delete_message = "Error deleting experiment: " . $stmt->error;
    }
    $stmt->close();
    
    header("Location: hlo.php?message=" . urlencode($delete_message));
    exit();
}

$sql = "SELECT * FROM experiments ORDER BY created_at DESC";
$result = $conn->query($sql);
if (!$result) {
    die("Database query failed: " . $conn->error);
}

function cleanText($text) {
    return preg_replace('/\s+/', ' ', htmlspecialchars($text));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Experiment Archive</title>
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
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .logo {
            font-size: 1.8rem;
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

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 2rem;
            color: var(--text);
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
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .experiments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .experiment-card {
            background: var(--card-bg);
            border-radius: 8px;
            padding: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border);
            overflow: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .experiment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .card-header {
            background: var(--secondary);
            padding: 1.2rem;
            position: relative;
            border-bottom: 1px solid var(--border);
        }

        .card-title {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }

        .card-date {
            position: absolute;
            top: 1.2rem;
            right: 1.2rem;
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-body p {
            margin-bottom: 1rem;
            color: var(--text-light);
        }

        .card-body strong {
            color: var(--text);
        }

        .card-actions {
            display: flex;
            gap: 0.8rem;
            margin-top: 1.5rem;
        }

        .action-btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            background: var(--input-bg);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .delete-btn {
            background: transparent;
            color: var(--error);
            border-color: var(--error);
        }

        .toggle-btn {
            background: transparent;
            color: var(--primary);
            border-color: var(--primary);
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .details {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0 15px;
            background: var(--secondary);
            border-radius: 4px;
            margin-top: 0;
        }

        .details.show {
            max-height: 2000px;
            padding: 15px;
            margin-top: 1rem;
            border: 1px solid var(--border);
        }

        .details h4 {
            color: var(--primary);
            margin: 1rem 0 0.5rem;
            font-size: 1rem;
        }

        .alert {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
            position: relative;
            transition: all 0.5s ease;
            border-left: 4px solid;
            background: var(--input-bg);
        }

        .alert-success {
            border-left-color: var(--success);
            color: var(--success);
        }

        .alert-error {
            border-left-color: var(--error);
            color: var(--error);
        }

        .alert-close {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            color: inherit;
        }

        .empty-state {
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 2rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .experiments-grid {
                grid-template-columns: 1fr;
            }
            
            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <span>🧪</span>
                <span>Experiment Archive</span>
            </div>
            <button class="theme-toggle" id="themeToggle">
                <span id="themeIcon">🌙</span>
                <span>Toggle Theme</span>
            </button>
        </header>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert <?= strpos($_GET['message'], 'Error') !== false ? 'alert-error' : 'alert-success' ?>" id="autoDismissAlert">
                <?= htmlspecialchars($_GET['message']) ?>
                <button class="alert-close" onclick="dismissAlert()">×</button>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <h1>Your Experiments</h1>
            <a href="test.php" class="btn btn-icon">
                <span>+</span>
                <span>New Experiment</span>
            </a>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <div class="experiments-grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="experiment-card">
                        <div class="card-header">
                            <h3 class="card-title"><?= htmlspecialchars($row['title']) ?></h3>
                            <span class="card-date"><?= date("Y-m-d H:i", strtotime($row['created_at'])) ?></span>
                        </div>
                        <div class="card-body">
                            <p><strong>Objective:</strong> <?= cleanText($row['objective']) ?></p>
                            
                            <div class="card-actions">
                                <button class="action-btn toggle-btn" onclick="toggleDetails(this, <?= $row['id'] ?>)">
                                    Toggle Details
                                </button>
                                <button class="action-btn delete-btn" onclick="confirmDelete(<?= $row['id'] ?>)">
                                    Delete
                                </button>
                            </div>
                            
                            <div class="details" id="details-<?= $row['id'] ?>">
                                <h4>Hypothesis</h4>
                                <p><?= cleanText($row['hypothesis']) ?></p>
                                
                                <h4>Materials</h4>
                                <p><?= cleanText($row['materials']) ?></p>
                                
                                <h4>Procedure</h4>
                                <p><?= cleanText($row['procedure']) ?></p>
                                
                                <?php if (!empty($row['safety'])): ?>
                                    <h4>Safety Precautions</h4>
                                    <p><?= cleanText($row['safety']) ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($row['results'])): ?>
                                    <h4>Expected Results</h4>
                                    <p><?= cleanText($row['results']) ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($row['conclusion'])): ?>
                                    <h4>Conclusion</h4>
                                    <p><?= cleanText($row['conclusion']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>No experiments found</p>
                <p>Create your first experiment to get started</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;
        
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

        function toggleDetails(button, id) {
            const details = document.getElementById(`details-${id}`);
            details.classList.toggle('show');
            button.textContent = details.classList.contains('show') ? 'Hide Details' : 'Show Details';
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this experiment? This action cannot be undone.')) {
                window.location.href = 'hlo.php?delete_id=' + id;
            }
        }

        // Auto-dismiss alert
        const alertTimer = setTimeout(() => {
            const alert = document.getElementById('autoDismissAlert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);

        function dismissAlert() {
            clearTimeout(alertTimer);
            const alert = document.getElementById('autoDismissAlert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>