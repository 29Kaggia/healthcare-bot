<div class="auth-box">
  <div class="form-section active" id="register-section">
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
function showLogin() {
  // Redirect to login page
  window.location.href = '<?= $this->Url->build(['controller' => 'Users', 'action' => 'login']) ?>';
}
</script>

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

.input label {
  display: none;
}

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