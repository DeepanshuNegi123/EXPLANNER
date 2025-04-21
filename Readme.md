
# FLOW CHART 
<H2>


    A[Frontend] -->|HTTP Requests| B[Backend PHP]

    B -->|Database Operations| C[(MySQL Database)]

    C -->|Returns Data| B

    B -->|JSON Responses| A

    A -->|User Interactions| D[AI Integration]

    D -->|API Calls| E[OpenRouter AI]

</H2>









# save_experiment.php

##  🌔working
<h4>

1. Sets up to return JSON data

2. Connects to the database

3. Receives experiment data from a form

4. Validates the data

5. Saves to database

6. Returns success/error message

</h4>

<br>



``` php

// 1. Tell browser we're sending JSON
header('Content-Type: application/json');

// 2. Connect to database
include 'db.php';

// 3. Prepare response structure
$response = [
    'success' => false, 
    'message' => ''
];

try {
    // 4. Check if request is POST
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Only POST requests allowed");
    }

    // 5. Clean input data (security)
    $title = cleanInput($_POST['title'] ?? '');
    $objective = cleanInput($_POST['objective'] ?? '');
    // ... (other fields same way)

    // 6. Check required fields
    if (empty($title) || empty($objective) /* ... */) {
        throw new Exception("Missing required fields");
    }

    // 7. Save to database
    $stmt = $conn->prepare("INSERT INTO experiments (...) VALUES (...)");
    $stmt->bind_param("ssssssss", $title, $objective, /* ... */);

    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => "Saved successfully!",
            'id' => $stmt->insert_id // Get new record ID
        ];
    } else {
        throw new Exception("Database error: ".$stmt->error);
    }

} catch (Exception $e) {
    // 8. Handle errors
    http_response_code(400);
    $response['message'] = $e->getMessage();
}

// 9. Send response
echo json_encode($response);

// Helper function to clean input
function cleanInput($data) {
    global $conn;
    return $conn->real_escape_string($data);
}

```



# TEST.PHP




# 🧪 Experiment Planner Pro — JavaScript Breakdown

This script powers the front-end logic of the **Experiment Planner Pro** app. It includes theme toggling, tab switching, AI suggestions, chat functionality, and form handling.

---

## 🌗 Theme Toggle

Toggle between light and dark mode. Preference is saved in `localStorage`.

```js
const themeToggle = document.getElementById('themeToggle'); // Get the theme toggle button
const themeIcon = document.getElementById('themeIcon');     // Get the icon inside the button
const htmlElement = document.documentElement;               // Get the root HTML element

// Set theme from localStorage or system preference
const savedTheme = localStorage.getItem('theme') ||         // Check localStorage for a saved theme
                   (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'); // Fallback to system preference
htmlElement.setAttribute('data-theme', savedTheme);         // Set the data-theme attribute
updateIcon(savedTheme);                                     // Update the theme icon

// Toggle theme when button is clicked
themeToggle.addEventListener('click', () => {
    const currentTheme = htmlElement.getAttribute('data-theme'); // Get current theme
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark'; // Determine new theme
    htmlElement.setAttribute('data-theme', newTheme);            // Update data-theme
    localStorage.setItem('theme', newTheme);                     // Save to localStorage
    updateIcon(newTheme);                                        // Update icon
});

// Change icon based on current theme
function updateIcon(theme) {
    themeIcon.textContent = theme === 'dark' ? '☀️' : '🌙';
}
```

---

## 🤭 Navigation Button

Redirect to `hlo.php` when the "View" button is clicked:

```js
const select = document.getElementById('viewbtn'); // Get the view button
select.addEventListener('click', () => {
    window.location.href = 'hlo.php';             // Redirect to hlo.php
});
```

---

## 📂 Tab Switching

Switches between the AI Suggestions tab and the Chat tab:

```js
const suggestionsTab = document.getElementById('suggestionsTab'); // Get the suggestions tab button
const chatTab = document.getElementById('chatTab');               // Get the chat tab button

// Event listener for suggestions tab
suggestionsTab.addEventListener('click', () => {
    toggleTab('suggestions');
});

// Event listener for chat tab
chatTab.addEventListener('click', () => {
    toggleTab('chat');
});

// Function to toggle between tabs
function toggleTab(tab) {
    const isSuggestions = tab === 'suggestions';
    document.getElementById('suggestionsContent').style.display = isSuggestions ? 'block' : 'none';
    document.getElementById('chatContent').style.display = isSuggestions ? 'none' : 'block';

    // Change tab styles
    suggestionsTab.style.backgroundColor = isSuggestions ? 'var(--primary)' : 'var(--secondary)';
    suggestionsTab.style.color = isSuggestions ? 'white' : 'var(--text)';
    chatTab.style.backgroundColor = isSuggestions ? 'var(--secondary)' : 'var(--primary)';
    chatTab.style.color = isSuggestions ? 'var(--text)' : 'white';
}
```

---

## 🤖 AI Suggestions with OpenRouter

Sends experiment title and objective to get suggestions.

```js
async function getAISuggestions() {
    const title = document.getElementById("title").value.trim();          // Get and trim title input
    const objective = document.getElementById("objective").value.trim();  // Get and trim objective input
    const aiResponse = document.getElementById("ai_response");            // Get the response container

    if (!title) return alert("Please enter an experiment title first.");

    aiResponse.innerHTML = '<div class="loading">Generating suggestions...</div>'; // Show loading
    aiResponse.classList.remove("error");

    try {
        const response = await fetch("https://openrouter.ai/api/v1/chat/completions", {
            method: "POST",
            headers: {
                "Authorization": "Bearer YOUR_API_KEY",     // Replace with real API key
                "HTTP-Referer": "http://localhost",
                "X-Title": "Experiment-Planner",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                model: "deepseek/deepseek-r1:free",
                messages: [
                    { role: "system", content: "You are a helpful assistant..." },
                    { role: "user", content: `Suggest details for: ${title}\nObjective: ${objective}` }
                ]
            })
        });

        const data = await response.json();                      // Parse response
        const content = data.choices[0].message.content;         // Get suggestion text
        const sections = parseResponse(content);                 // Parse sections
        displaySuggestions(sections);                            // Display on UI

    } catch (error) {
        aiResponse.classList.add("error");                      // Add error styling
        aiResponse.innerHTML = `Error: ${error.message}`;        // Show error message
    }
}
```

---

## 🔍 Response Parsing

Extract sections like Hypothesis, Materials, Procedure, and Safety:

```js
function parseResponse(content) {
    return {
        hypothesis: content.match(/HYPOTHESIS:\s*(.*?)(?=\n\d\.|$)/is)?.[1].trim() || '',
        materials: content.match(/MATERIALS:\s*(.*?)(?=\n\d\.|$)/is)?.[1].trim() || '',
        procedure: content.match(/PROCEDURE:\s*(.*?)(?=\n\d\.|$)/is)?.[1].trim() || '',
        safety: content.match(/SAFETY PRECAUTIONS:\s*(.*?)(?=\n\d\.|$)/is)?.[1].trim() || ''
    };
}
```

---

## 📌 Apply Suggestions to Form

```js
function applySuggestion(field) {
    const content = document.querySelector(`.response-title:contains(${field})`).nextElementSibling.textContent; // Get text below the section title
    document.getElementById(field).value = content; // Insert suggestion into the matching form field
}

function applyAllSuggestions() {
    ['hypothesis', 'materials', 'procedure', 'safety'].forEach(applySuggestion); // Apply all suggestions
}
```

---

## 💬 Chatbot (AI Conversation)

```js
let conversationHistory = []; // Store message history between user and AI

async function sendChatMessage() {
    const input = document.getElementById('userInput').value.trim(); // Get chat input
    if (!input) return;

    conversationHistory.push({ role: 'user', content: input }); // Add user message to history

    const response = await fetch('https://openrouter.ai/api/v1/chat/completions', {
        method: 'POST',
        headers: {
            Authorization: 'Bearer YOUR_API_KEY', // API key required
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            model: 'deepseek/deepseek-r1:free',
            messages: conversationHistory
        })
    });

    const data = await response.json();
    const aiResponse = data.choices?.[0]?.message?.content || 'No response.'; // Extract AI reply
    conversationHistory.push({ role: 'assistant', content: aiResponse }); // Store AI reply

    document.getElementById('chatResponse').innerHTML += `
        <div><b>You:</b> ${input}</div>
        <div><b>AI:</b> ${aiResponse}</div>
    `; // Display messages

    document.getElementById('userInput').value = ''; // Clear input field
}

// Allow sending on Enter key
function handleChatKeyPress(e) {
    if (e.key === 'Enter') sendChatMessage();
}
```

---

## 📀 Form Submission with Loader & Feedback

```js
document.getElementById("experimentForm").addEventListener("submit", async function(e) {
    e.preventDefault(); // Prevent default form submission

    const saveButton = document.getElementById("save_button"); // Get save button
    const formData = new FormData(this);                       // Create form data object

    saveButton.disabled = true;                                // Disable button while processing
    saveButton.innerHTML = `⏳ Saving...`;                      // Show loading message

    try {
        const response = await fetch("save_experiment.php", {  // Send POST request
            method: "POST",
            body: formData
        });
        const result = await response.json();                  // Parse response

        if (!result.success) throw new Error(result.message || "Failed to save experiment");

        saveButton.innerHTML = `✅ Saved!`;                    // Show success
    } catch (error) {
        saveButton.innerHTML = `❌ ${error.message}`;          // Show error
    } finally {
        setTimeout(() => {
            saveButton.innerHTML = "Save Experiment";          // Reset button text
            saveButton.disabled = false;                       // Re-enable button
        }, 3000);
    }
});
```

---

## ✅ Summary

This script enables an interactive, AI-enhanced experience for planning and managing scientific experiments. It blends UI features (theme, tabs) with advanced functionality (AI, chatbot, saving).


<BR>
<BR>
<BR>
<BR>
<BR>





# PHP Script Breakdown: Experiment Archive Page (`hlo.php`)

This document explains each part of the `hlo.php` file, which is responsible for displaying and deleting experiments from a MySQL database in a styled web app.

---

## PHP Section

```php
include 'db.php';
```
Includes the database connection file to enable access to MySQL.

```php
if (isset($_GET['delete_id'])) {
```
Checks if a delete request has been made through the URL using a GET parameter.

```php
    $delete_id = $_GET['delete_id'];
```
Retrieves the ID of the experiment to be deleted.

```php
    $stmt = $conn->prepare("DELETE FROM experiments WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
```



Prepares a safe SQL statement using parameter binding to prevent SQL injection.


```php
    if ($stmt->execute()) {
        $delete_message = "Experiment deleted successfully!";
    } else {
        $delete_message = "Error deleting experiment: " . $stmt->error;
    }
```
Executes the delete operation and sets a success or error message.


```php

    $stmt->close();
```
Closes the statement.


```php
    header("Location: hlo.php?message=" . urlencode($delete_message));
    exit();
}
```
Redirects back to the same page with a success/error message.

```php

$sql = "SELECT * FROM experiments ORDER BY created_at DESC";
$result = $conn->query($sql);
if (!$result) {
    die("Database query failed: " . $conn->error);

}

```
Queries all experiments from the database, ordering by newest first. Stops the script if there's a query error.

---

## HTML Section

Contains the markup and layout for the experiment archive page.

### `<head>` and Styling

- Contains meta info and `<style>` tag defining theme variables and responsive design.
- Defines light and dark themes with CSS variables and custom animations for gradient background.

### `<body>` Structure

#### `.container`
The main wrapper for content, with inner layout sections:

- **Header**: Shows the logo and theme toggle button.
- **Alert Message**: Displays the delete confirmation message if redirected from delete.
- **Page Header**: Includes the title and a button to create a new experiment.
- **Experiments Grid**: Loops through experiments and creates a styled card for each.

### PHP Inside HTML
```php

<?php while ($row = $result->fetch_assoc()): ?>
```
Loops through each experiment in the database.

Each experiment card includes:
- Title, creation date
- Objective (always shown)
- Toggleable details (hypothesis, materials, procedure, safety, results, conclusion)
- Buttons for delete and details

---

## JavaScript Section

Handles client-side interactivity.

### Theme Toggle
```js
const themeToggle = document.getElementById('themeToggle');
```
Sets up event listener to toggle between light and dark themes.

```js
const savedTheme = localStorage.getItem('theme') || 
                  (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
```
Detects user's preferred theme or uses saved theme from `localStorage`.

```js
function updateIcon(theme) {
    themeIcon.textContent = theme === 'dark' ? '☀️' : '🌙';
}
```
Updates the icon on the theme toggle button.

### Details Toggle
```js
function toggleDetails(button, id) {
    const details = document.getElementById(`details-${id}`);
    details.classList.toggle('show');
    button.textContent = details.classList.contains('show') ? 'Hide Details' : 'Show Details';
}
```
Shows or hides the detailed experiment content.

### Delete Confirmation
```js
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this experiment?')) {
        window.location.href = 'hlo.php?delete_id=' + id;
    }
}
```
Confirms with the user before deleting the experiment.

### Auto-Dismiss Alert
```js
const alertTimer = setTimeout(() => {
    const alert = document.getElementById('autoDismissAlert');
    if (alert) {
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }
}, 3000);
```
Automatically dismisses alert messages after 3 seconds.

---

## Final PHP Close
```php
$conn->close();
```
Closes the MySQL connection.

---

## Summary
This script is a full-featured experiment archive interface:
- Displays experiments from a database
- Allows toggling dark/light themes
- Enables deleting experiments with confirmation
- Displays detailed sections of each experiment
- Provides alert messages and auto-dismisses them for user feedback


<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
<BR>





# PHP Script Breakdown: Database Connection (`db.php`)

This document explains each line of the `db.php` file, which is responsible for establishing a connection to a MySQL database using PHP and XAMPP.

---

## PHP Script

```php
<?php
$host = "localhost";
$user = "root"; // Default XAMPP MySQL user
$password = ""; // Default XAMPP MySQL password (leave empty)
$database = "experiment_db"; // Your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

---

## Explanation Line-by-Line

### `<?php`
Starts the PHP code block. Everything within this block is interpreted as PHP.

---

### `$host = "localhost";`
Defines the hostname for the MySQL server. `localhost` means the MySQL server is running on the same machine as the PHP code (common with XAMPP setups).

---

### `$user = "root";`
Defines the MySQL username. `root` is the default admin account in XAMPP.

---

### `$password = "";`
Defines the password for the MySQL account. In XAMPP, the root user does not have a password by default.

---

### `$database = "experiment_db";`
Sets the name of the database you want to connect to. Replace `experiment_db` with your own database name if needed.

---

### `$conn = new mysqli($host, $user, $password, $database);`
Creates a new MySQL connection using the `mysqli` class, passing all the required credentials (host, user, password, database).

---

### `if ($conn->connect_error) {`
Checks if the connection failed. If there's an error, it proceeds inside the block.

---

### `die("Connection failed: " . $conn->connect_error);`
Stops the script and shows an error message if the connection wasn't successful. `die()` halts execution and prints the message.

---

### `?>`
Ends the PHP code block. This is optional if the file only contains PHP code.

---

## Summary
- This script connects your PHP application to a MySQL database.
- It uses the default XAMPP settings: `localhost`, user `root`, and no password.
- It includes a simple error handler to stop the script if the connection fails.

---

## ✅ Save As:
Save this code in a file named `db.php` and place it in the root folder of your project. Include it in other files using:

```php
include 'db.php';
```

This allows you to reuse the same connection across multiple PHP pages easily.











