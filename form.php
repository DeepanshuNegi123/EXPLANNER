<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Experiment Planner Pro</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            color: #202124;
            line-height: 1.6;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #1a73e8;
            text-align: center;
        }

        .contact-form {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            box-sizing: border-box;
        }

        textarea {
            min-height: 150px;
            resize: vertical;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            font-size: 1rem;
            width: 100%;
            background: #1a73e8;
            color: white;
        }

        .contact-info {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
            justify-content: center;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            flex: 1;
            min-width: 200px;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <span>🧪</span>
                <span>Experiment Planner Pro</span>
            </div>
        </header>
        
        <h1>Contact Our Team</h1>
        
        <?php
        $name = $email = $subject = $message = '';
        $error = '';
        $success = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize and validate inputs
            $name = trim(htmlspecialchars($_POST['name'] ?? ''));
            $email = trim(htmlspecialchars($_POST['email'] ?? ''));
            $subject = trim(htmlspecialchars($_POST['subject'] ?? 'general'));
            $message = trim(htmlspecialchars($_POST['message'] ?? ''));
            
            // Validate inputs
            if (empty($name)) {
                $error = "Please enter your name";
            } elseif (empty($email)) {
                $error = "Please enter your email address";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Please enter a valid email address";
            } 

            if (empty($error)) {
                // Configure email
                $to = "deepanshu9760873328@hgmail.com"; 
                $email_subject = "Contact Form: $subject";
                $email_body = "Name: $name\n";
                $email_body .= "Email: $email\n";
                $email_body .= "Subject: $subject\n\n";
                $email_body .= "Message:\n$message";
                
                $headers = "From: $email\r\n";
                $headers .= "Reply-To: $email\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();
                
                // Send email
                if (mail($to, $email_subject, $email_body, $headers)) {
                    $success = "Thank you! Your message has been sent successfully.";
                    // Clear form fields
                    $name = $email = $message = '';
                    $subject = 'general';
                } else {
                    $error = "Sorry, there was an error sending your message. Please try again later.";
                }
            }
        }
        ?>
        
        <?php if (!empty($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="contact-form">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="name">Your Name *</label>
                    <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <select id="subject" name="subject">
                        <option value="general" <?php echo ($subject == 'general') ? 'selected' : ''; ?>>General Inquiry</option>
                        <option value="support" <?php echo ($subject == 'support') ? 'selected' : ''; ?>>Technical Support</option>
                        <option value="feedback" <?php echo ($subject == 'feedback') ? 'selected' : ''; ?>>Feedback/Suggestions</option>
                        <option value="business" <?php echo ($subject == 'business') ? 'selected' : ''; ?>>Business Inquiry</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">Your Message *</label>
                    <textarea id="message" name="message" ><?php echo $message; ?></textarea>
                </div>
                
                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
        
        <!-- [Rest of your HTML remains the same...] -->
    </div>
</body>
</html>