<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Auth - MediChat</title>
<?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')) ?>
<style>
*, *::before, *::after { box-sizing: border-box; }

:root {
  --bg: #f0f4f8;
  --surface: #ffffff;
  --border: #d1dce8;
  --accent: #1565c0;
  --accent-hover: #1976d2;
  --accent-light: #e3f0fa;
  --header-bg: #0a3d6b;
  --text: #1a2a3a;
  --text-muted: #607d8b;
  --text-dim: #90a4ae;
  --shadow: 0 4px 24px rgba(10,61,107,0.10);
}

body {
  margin: 0;
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  background: var(--bg);
  color: var(--text);
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background-image:
    radial-gradient(ellipse 60% 40% at 80% 10%, rgba(21,101,192,0.06) 0%, transparent 60%),
    radial-gradient(ellipse 40% 30% at 10% 80%, rgba(21,101,192,0.04) 0%, transparent 50%);
}

.auth-box {
  width: 400px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: var(--shadow);
}

.auth-header {
  background: var(--header-bg);
  padding: 1.25rem 1.75rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.auth-cross {
  width: 24px;
  height: 24px;
  position: relative;
  flex-shrink: 0;
}

.auth-cross::before,
.auth-cross::after {
  content: '';
  position: absolute;
  background: #4fc3f7;
  border-radius: 2px;
}

.auth-cross::before { width: 7px; height: 17px; left: 8.5px; top: 3.5px; }
.auth-cross::after  { width: 17px; height: 7px; left: 3.5px; top: 8.5px; }

.auth-header-text h2 {
  color: #ffffff;
  font-size: 1rem;
  font-weight: 600;
  letter-spacing: 0.02em;
  margin: 0;
}

.auth-header-sub {
  color: #90caf9;
  font-size: 0.68rem;
  margin-top: 2px;
  letter-spacing: 0.03em;
}

.auth-body {
  padding: 1.75rem 2rem 2rem;
}

.auth-body h2 {
  font-size: 1rem;
  font-weight: 600;
  color: var(--text);
  margin: 0 0 1.25rem;
  text-align: left;
}

.input {
  margin-bottom: 1rem;
}

.input label {
  display: block;
  font-size: 0.72rem;
  font-weight: 600;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 0.3rem;
}

.input input {
  width: 100%;
  padding: 0.65rem 0.875rem;
  border-radius: 7px;
  border: 1.5px solid var(--border);
  background: #f8fafc;
  color: var(--text);
  font-family: inherit;
  font-size: 0.875rem;
  transition: border-color 0.2s, background 0.2s;
}

.input input:focus {
  outline: none;
  border-color: var(--accent);
  background: #ffffff;
  box-shadow: 0 0 0 3px rgba(21,101,192,0.08);
}

.input input::placeholder { color: var(--text-dim); }

.submit input[type="submit"],
button[type="submit"] {
  width: 100%;
  padding: 0.7rem;
  background: var(--accent);
  color: #ffffff;
  border: none;
  border-radius: 7px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  font-family: inherit;
  letter-spacing: 0.02em;
  transition: background 0.2s, transform 0.1s;
  margin-top: 0.25rem;
}

.submit input[type="submit"]:hover,
button[type="submit"]:hover { background: var(--accent-hover); }

.submit input[type="submit"]:active,
button[type="submit"]:active { transform: scale(0.98); }

.switch {
  text-align: center;
  margin-top: 1rem;
  font-size: 0.82rem;
  color: var(--text-muted);
  cursor: pointer;
  user-select: none;
}

.switch span {
  color: var(--accent);
  font-weight: 600;
}

.switch span:hover { text-decoration: underline; }

.form-section { display: none; }
.form-section.active { display: block; }

.message.error {
  background: #fff5f5;
  border: 1px solid #ffcdd2;
  border-left: 3px solid #e53935;
  border-radius: 7px;
  padding: 0.6rem 0.875rem;
  margin-bottom: 1rem;
  font-size: 0.82rem;
  color: #c62828;
}

.message.success {
  background: #f1f8e9;
  border: 1px solid #c5e1a5;
  border-left: 3px solid #43a047;
  border-radius: 7px;
  padding: 0.6rem 0.875rem;
  margin-bottom: 1rem;
  font-size: 0.82rem;
  color: #2e7d32;
}

.auth-disclaimer {
  text-align: center;
  font-size: 0.65rem;
  color: var(--text-dim);
  margin-top: 1.25rem;
  padding-top: 1rem;
  border-top: 1px solid var(--border);
}
</style>
</head>

<body>

<div class="auth-box">

  <!-- Header -->
  <div class="auth-header">
    <div class="auth-cross"></div>
    <div class="auth-header-text">
      <h2>MediChat Portal</h2>
      <div class="auth-header-sub">Clinical Decision Support System</div>
    </div>
  </div>

  <div class="auth-body">

    <?= $this->Flash->render() ?>

    <!-- LOGIN FORM -->
    <div class="form-section active" id="login-section">
      <h2>Sign in to your account</h2>

      <?= $this->Form->create(null, [
          'url' => ['controller' => 'Users', 'action' => 'login'],
      ]) ?>

        <?= $this->Form->control('email', [
            'type'        => 'email',
            'placeholder' => 'doctor@hospital.com',
            'label'       => 'Email Address',
            'required'    => true,
        ]) ?>

        <?= $this->Form->control('password', [
            'type'        => 'password',
            'placeholder' => '••••••••',
            'label'       => 'Password',
            'required'    => true,
        ]) ?>

        <?= $this->Form->submit('Sign In') ?>

      <?= $this->Form->end() ?>

      <div class="switch" onclick="showRegister()">
        Don't have an account? <span>Register here</span>
      </div>

      <div class="auth-disclaimer">
        Authorized personnel only — all sessions are monitored
      </div>
    </div>

    <!-- REGISTER FORM -->
    <div class="form-section" id="register-section">
      <h2>Create your account</h2>

      <?= $this->Form->create(null, [
          'url' => ['controller' => 'Users', 'action' => 'register'],
      ]) ?>

        <?= $this->Form->control('name', [
            'type'        => 'text',
            'placeholder' => 'Full name',
            'label'       => 'Full Name',
            'required'    => true,
        ]) ?>

        <?= $this->Form->control('email', [
            'type'        => 'email',
            'placeholder' => 'doctor@hospital.com',
            'label'       => 'Email Address',
            'required'    => true,
        ]) ?>

        <?= $this->Form->control('password', [
            'type'        => 'password',
            'placeholder' => '••••••••',
            'label'       => 'Password',
            'required'    => true,
        ]) ?>

        <?= $this->Form->submit('Create Account') ?>

      <?= $this->Form->end() ?>

      <div class="switch" onclick="showLogin()">
        Already have an account? <span>Sign in here</span>
      </div>

      <div class="auth-disclaimer">
        Authorized personnel only — all sessions are monitored
      </div>
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

  <?php if (isset($showRegister) && $showRegister): ?>
    showRegister();
  <?php endif; ?>
</script>

</body>
</html>