<?php include "db.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Auth System</title>
    <!-- SweetAlert2 for Modern Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        /* (Keep the same CSS from the previous response here) */
        :root { --primary: #6366f1; --primary-hover: #4f46e5; --bg-gradient: linear-gradient(-45deg, #0f172a, #1e1b4b, #312e81, #1e3a8a); --glass: rgba(255, 255, 255, 0.05); --glass-border: rgba(255, 255, 255, 0.1); }
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { margin: 0; height: 100vh; display: flex; justify-content: center; align-items: center; background: var(--bg-gradient); background-size: 400% 400%; animation: gradientBG 15s ease infinite; overflow: hidden; }
        @keyframes gradientBG { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .container { position: relative; width: 900px; height: 550px; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px); border: 1px solid var(--glass-border); border-radius: 24px; display: flex; overflow: hidden; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3); }
        .left { width: 40%; background: rgba(99, 102, 241, 0.15); color: white; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px; text-align: center; border-right: 1px solid var(--glass-border); z-index: 2; }
        .right { width: 60%; padding: 50px; display: flex; flex-direction: column; justify-content: center; background: rgba(255, 255, 255, 0.03); }
        h2.form-title { color: white; font-size: 2rem; margin-bottom: 25px; }
        input { width: 100%; padding: 14px 16px; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); border-radius: 12px; color: white; outline: none; margin-bottom: 20px; transition: 0.3s; }
        input:focus { border-color: var(--primary); box-shadow: 0 0 15px rgba(99, 102, 241, 0.3); }
        button { width: 100%; padding: 14px; background: var(--primary); color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; transition: 0.3s; }
        button:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6); }
        .switch { margin-top: 20px; color: rgba(255, 255, 255, 0.6); cursor: pointer; text-align: center; font-size: 14px; }
        .switch span { color: var(--primary); font-weight: 600; }
        .hidden { display: none; }
        .fade-in { animation: fadeIn 0.5s forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
    </style>
</head>

<body>

<div class="container">
    <div class="left">
        <h2 id="title">Welcome</h2>
        <p id="desc">Unlock a world of premium products with just one click.</p>
    </div>

    <div class="right">
        <!-- LOGIN FORM -->
        <div id="loginBox" class="fade-in">
            <h2 class="form-title">Login</h2>
            <form method="post" action="login.php">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button name="login">Sign In</button>
            </form>
            <p class="switch" onclick="toggle()">Don't have an account? <span>Register</span></p>
        </div>

        <!-- REGISTER FORM -->
        <div id="registerBox" class="hidden">
            <h2 class="form-title">Create Account</h2>
            <form method="post" action="register.php">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button name="register">Get Started</button>
            </form>
            <p class="switch" onclick="toggle()">Already a member? <span>Login</span></p>
        </div>
    </div>
</div>

<script>
    function toggle(){
        const login = document.getElementById("loginBox");
        const reg = document.getElementById("registerBox");
        const title = document.getElementById("title");
        const desc = document.getElementById("desc");

        if(login.classList.contains("hidden")){
            login.classList.remove("hidden");
            login.classList.add("fade-in");
            reg.classList.add("hidden");
            title.innerText = "Welcome";
            desc.innerText = "Unlock a world of premium products with just one click.";
        } else {
            login.classList.add("hidden");
            reg.classList.remove("hidden");
            reg.classList.add("fade-in");
            title.innerText = "Join Us";
            desc.innerText = "Create a free account and start your shopping journey today.";
        }
    }

    // CHECK FOR URL PARAMETERS TO SHOW ALERTS
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    const type = urlParams.get('type');

    if (msg) {
        let title = type === 'success' ? 'Success!' : 'Oops...';
        let text = '';

        // Map messages to user-friendly text
        const messages = {
            'empty_fields': 'Please fill in all fields.',
            'invalid_email': 'The email format is incorrect.',
            'user_exists': 'Username or Email is already taken.',
            'registered': 'Account created successfully! You can now login.',
            'wrong_pass': 'Incorrect password. Try again.',
            'no_user': 'No account found with that username.'
        };

        text = messages[msg] || 'Something went wrong.';

        Swal.fire({
            icon: type,
            title: title,
            text: text,
            confirmButtonColor: '#6366f1',
            background: '#1e293b',
            color: '#fff'
        }).then(() => {
            // Clean the URL so the alert doesn't pop up again on refresh
            window.history.replaceState({}, document.title, window.location.pathname);
        });
    }
</script>

</body>
</html>