<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Auth - AI Healthcare</title>

<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*, *::before, *::after {
  box-sizing: border-box;
}

:root {
  --bg: #0f1117;
  --surface: #181c27;
  --border: rgba(255,255,255,0.07);
  --accent: #4fffb0;
  --text: #e8eaf0;
  --text-muted: #6b7280;
}

body {
  margin: 0;
  font-family: 'DM Sans', sans-serif;
  background: var(--bg);
  color: var(--text);
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}

.auth-box {
  width: 380px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 8px 32px rgba(0,0,0,0.4);
}

h2 {
  font-family: 'DM Serif Display', serif;
  text-align: center;
  margin: 0 0 1.5rem;
  font-size: 1.8rem;
}

/* Override CakePHP's .input wrapper div */
.input {
  margin-bottom: 1rem;
}

.input input {
  width: 100%;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  border: 1px solid var(--border);
  background: transparent;
  color: var(--text);
  font-family: 'DM Sans', sans-serif;
  font-size: 0.95rem;
  transition: border-color 0.2s;
}

.input input:focus {
  outline: none;
  border-color: var(--accent);
}

/* Hide CakePHP's auto-generated labels */
.input label {
  display: none;
}

/* CakePHP wraps submit in .submit div */
.submit input[type="submit"],
button[type="submit"] {
  width: 100%;
  padding: 0.75rem;
  background: var(--accent);
  color: #0f1117;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  font-family: 'DM Sans', sans-serif;
  transition: opacity 0.2s;
}

.submit input[type="submit"]:hover,
button[type="submit"]:hover {
  opacity: 0.85;
}

.switch {
  text-align: center;
  margin-top: 1rem;
  font-size: 0.85rem;
  color: var(--text-muted);
  cursor: pointer;
  user-select: none;
}

.switch span {
  color: var(--accent);
  font-weight: 500;
}

.form-section {
  display: none;
}

.form-section.active {
  display: block;
}

/* Flash messages */
.message.error {
  background: rgba(255, 80, 80, 0.1);
  border: 1px solid rgba(255, 80, 80, 0.3);
  border-radius: 8px;
  padding: 0.6rem 1rem;
  margin-bottom: 1rem;
  font-size: 0.85rem;
  color: #ff6b6b;
}
</style>
</head>

<body>

<div class="auth-box">

  <?= $this->Flash->render() ?>

  <!-- LOGIN FORM -->
  <div class="form-section active" id="login-section">
    <h2>Login</h2>

    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'login'],
    ]) ?>

      <?= $this->Form->control('email', [
          'type'        => 'email',
          'placeholder' => 'Email address',
          'label'       => false,
          'required'    => true,
      ]) ?>

      <?= $this->Form->control('password', [
          'type'        => 'password',
          'placeholder' => 'Password',
          'label'       => false,
          'required'    => true,
      ]) ?>

      <?= $this->Form->submit('Continue') ?>

    <?= $this->Form->end() ?>

    <div class="switch" onclick="showRegister()">
      Don't have an account? <span>Register</span>
    </div>
  </div>

  <!-- REGISTER FORM -->
  <div class="form-section" id="register-section">
    <h2>Register</h2>

    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'register'],
    ]) ?>

      <?= $this->Form->control('name', [
          'type'        => 'text',
          'placeholder' => 'Full name',
          'label'       => false,
          'required'    => true,
      ]) ?>

      <?= $this->Form->control('email', [
          'type'        => 'email',
          'placeholder' => 'Email address',
          'label'       => false,
          'required'    => true,
      ]) ?>

      <?= $this->Form->control('password', [
          'type'        => 'password',
          'placeholder' => 'Password',
          'label'       => false,
          'required'    => true,
      ]) ?>

      <?= $this->Form->submit('Create Account') ?>

    <?= $this->Form->end() ?>

    <div class="switch" onclick="showLogin()">
      Already have an account? <span>Login</span>
    </div>
  </div>

</div>

<script>
  function showRegister() {
    document.getElementById('login-section').classList.remove('active');
    document.getElementById('register-section').classList.add('active');
  }

  function showLogin() {
    document.getElementById('register-section').classList.remove('active');
    document.getElementById('login-section').classList.add('active');
  }

  // If redirected back after failed register attempt, show register form
  <?php if (isset($showRegister) && $showRegister): ?>
    showRegister();
  <?php endif; ?>
</script>

</body>
</html>