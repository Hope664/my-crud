<?php

session_start();

$host     = "localhost";
$username = "root";
$password = "uMuHoZa@123hope";
$dbname   = "user_portal";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$action = isset($_GET['action']) ? $_GET['action'] : 'home';
if ($action === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

$login_error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, fname, lname, username, email, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $u);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($p, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['fname']    = $user['fname'];
            $_SESSION['lname']    = $user['lname'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email']    = $user['email'];
            header("Location: index.php?action=dashboard");
            exit();
        } else {
            $login_error = "Wrong password. Please try again.";
        }
    } else {
        $login_error = "Username not found. Please register first.";
    }
    $stmt->close();
}

$reg_error   = "";
$reg_success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $fname    = trim($_POST['fname']);
    $lname    = trim($_POST['lname']);
    $email    = trim($_POST['email']);
    $uname    = trim($_POST['username']);
    $pass     = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if (empty($fname) || empty($lname) || empty($email) || empty($uname) || empty($pass)) {
        $reg_error = "All fields are required.";

    } elseif ($pass !== $confirm) {
        $reg_error = "Passwords do not match.";

    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $check = $stmt->get_result();
        
        if ($check->num_rows > 0) {
            $reg_error = "Username already taken. Please choose another.";
        } else {
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $stmt2 = $conn->prepare("INSERT INTO users (fname, lname, email, username, password) VALUES (?, ?, ?, ?, ?)");
            $stmt2->bind_param("sssss", $fname, $lname, $email, $uname, $hashed_pass);

            if ($stmt2->execute()) {
                $reg_success = "Account created! You can now log in.";
            } else {
                $reg_error = "Something went wrong. Please try again.";
            }
            $stmt2->close();
        }
        $stmt->close();
    }
}

if ($action === 'dashboard' && !isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}

$user = null;
if ($action === 'dashboard' && isset($_SESSION['user_id'])) {
    $id   = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT id, fname, lname, username, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PUROVUE</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-glow"></div>

<?php

if ($action === 'dashboard' && isset($_SESSION['user_id'])): ?>

<nav class="navbar">
  <div class="nav-logo">
    <div class="logo-box"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></div>
    <span class="logo-name">NexaPortal</span>
  </div>
  <a href="index.php?action=logout" class="nav-btn">Log out</a>
</nav>

<div class="page">
  <div class="dash-wrap">

    <div class="welcome-card">
      <div class="wc-time"><?php
        $h = (int)date('H');
        echo ($h<12 ? "Good morning" : ($h<17 ? "Good afternoon" : "Good evening"));
        echo " &nbsp;·&nbsp; " . date('l, j F Y');
      ?></div>
      <h2>Hello, <em><?php echo htmlspecialchars($user['fname']); ?></em> 👋</h2>
      <p>Welcome back. Your account is active and ready.</p>
    </div>

    <div class="detail-card">
      <div class="dc-head">
        <h3>Your Account</h3>
        <span class="badge-on">● Active</span>
      </div>
      <div class="d-row">
        <span class="d-label">Full Name</span>
        <span class="d-value"><?php echo htmlspecialchars($user['fname'].' '.$user['lname']); ?></span>
      </div>
      <div class="d-row">
        <span class="d-label">Username</span>
        <span class="d-value"><?php echo htmlspecialchars($user['username']); ?></span>
      </div>
      <div class="d-row">
        <span class="d-label">Email</span>
        <span class="d-value"><?php echo htmlspecialchars($user['email']); ?></span>
      </div>
      <div class="d-row">
        <span class="d-label">User ID</span>
        <span class="d-value">#<?php echo $user['id']; ?></span>
      </div>
    </div>

    <a href="index.php?action=logout" class="btn-out">← Log out</a>

  </div>
</div>

<?php

elseif ($action === 'login'): ?>

<nav class="navbar">
  <div class="nav-logo">
    <div class="logo-box"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></div>
    <span class="logo-name">NexaPortal</span>
  </div>
  <a href="index.php" class="nav-btn">← Home</a>
</nav>

<div class="page">
  <div class="form-wrap">
    <div class="form-card">

      <div class="tab-row">
        <a href="index.php?action=login"    class="tab-btn active">Log in</a>
        <a href="index.php?action=register" class="tab-btn">Register</a>
      </div>

      <h2>Welcome back</h2>
      <p class="form-sub">Sign in to access your dashboard</p>

      <?php if ($login_error): ?>
        <div class="alert err">⚠ <?php echo htmlspecialchars($login_error); ?></div>
      <?php endif; ?>

      <form method="POST" action="index.php?action=login">
        <div class="field">
          <label>Username</label>
          <input type="text" name="username" placeholder="Enter your username" required autocomplete="off">
        </div>
        <div class="field">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" name="login" class="btn-submit">Sign in</button>
      </form>

      <p class="switch-hint">
        No account? <a href="index.php?action=register">Create one →</a>
      </p>

    </div>
  </div>
</div>

<?php

elseif ($action === 'register'): ?>

<nav class="navbar">
  <div class="nav-logo">
    <div class="logo-box"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></div>
    <span class="logo-name">NexaPortal</span>
  </div>
  <a href="index.php" class="nav-btn">← Home</a>
</nav>

<div class="page">
  <div class="form-wrap">
    <div class="form-card">

  
      <div class="tab-row">
        <a href="index.php?action=login"    class="tab-btn">Log in</a>
        <a href="index.php?action=register" class="tab-btn active">Register</a>
      </div>

      <h2>Create account</h2>
      <p class="form-sub">Fill in your details to get started</p>

      <?php if ($reg_error): ?>
        <div class="alert err">⚠ <?php echo htmlspecialchars($reg_error); ?></div>
      <?php endif; ?>

      <?php if ($reg_success): ?>
        <div class="alert ok">✓ <?php echo htmlspecialchars($reg_success); ?>
          &nbsp;<a href="index.php?action=login" style="color:#4ade80;text-decoration:underline">Log in now →</a>
        </div>
      <?php endif; ?>

      <form method="POST" action="index.php?action=register">

        <div class="field-row">
          <div class="field">
            <label>First Name</label>
            <input type="text" name="fname" placeholder="Alex" required>
          </div>
          <div class="field">
            <label>Last Name</label>
            <input type="text" name="lname" placeholder="Doe" required>
          </div>
        </div>

        <div class="field">
          <label>Email</label>
          <input type="email" name="email" placeholder="alex@example.com" required>
        </div>

        <div class="field">
          <label>Username</label>
          <input type="text" name="username" placeholder="No spaces e.g. alex_doe" required autocomplete="off">
        </div>

        <div class="field">
          <label>Password</label>
          <input type="password" name="password" placeholder="Choose a password" required>
        </div>

        <div class="field">
          <label>Confirm Password</label>
          <input type="password" name="confirm" placeholder="Repeat your password" required>
        </div>

        <button type="submit" name="register" class="btn-submit">Create account</button>

      </form>

      <p class="switch-hint">
        Already have an account? <a href="index.php?action=login">Log in →</a>
      </p>

    </div>
  </div>
</div>

<?php
else: ?>

<nav class="navbar">
  <div class="nav-logo">
    <div class="logo-box"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></div>
    <span class="logo-name">NexaPortal</span>
  </div>
  <div class="nav-right">
    <a href="index.php?action=login"    class="nav-btn">Log in</a>
    <a href="index.php?action=register" class="nav-btn solid">Register</a>
  </div>
</nav>

<div class="page">
  <section class="landing">
    <div class="pill"><span class="pill-dot"></span>Now live &amp; ready</div>
    <h1>Your workspace,<br><em>simplified.</em></h1>
    <p class="sub">One place to manage your team, track your work, and stay on top of everything that matters.</p>
    <a href="index.php?action=register" class="btn-cta">
      Get started <span class="arr">→</span>
    </a>
    <div class="stats-row">
      <div class="stat"><div class="stat-n">2,400+</div><div class="stat-l">Active users</div></div>
      <div class="stat"><div class="stat-n">99.9%</div><div class="stat-l">Uptime</div></div>
      <div class="stat"><div class="stat-n">Fast</div><div class="stat-l">MySQL powered</div></div>
    </div>
  </section>
</div>

<footer class="footer">
  &copy; <?php echo date('Y'); ?> NexaPortal &nbsp;·&nbsp; Built with PHP &amp; MySQL
</footer>

<?php endif; ?>

</body>
</html>