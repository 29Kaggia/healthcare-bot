<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Settings - MediChat</title>
<style>
/* reuse your hospital theme variables */
*, *::before, *::after { box-sizing: border-box; }
:root {
  --bg: #f0f4f8; --surface: #fff; --border: #d1dce8;
  --accent: #1565c0; --accent-hover: #1976d2;
  --header-bg: #0a3d6b; --text: #1a2a3a;
  --text-muted: #607d8b; --text-dim: #90a4ae;
}
body { margin: 0; font-family: 'Segoe UI', sans-serif; background: var(--bg); display: flex; align-items: center; justify-content: center; min-height: 100vh; }
.settings-box { width: 460px; background: var(--surface); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(10,61,107,0.10); }
.settings-header { background: var(--header-bg); padding: 1.25rem 1.75rem; display: flex; align-items: center; gap: 0.75rem; }
.settings-header h2 { color: #fff; font-size: 1rem; font-weight: 600; margin: 0; }
.settings-header p { color: #90caf9; font-size: 0.68rem; margin: 2px 0 0; }
.settings-body { padding: 1.75rem 2rem 2rem; }
.field { margin-bottom: 1rem; }
.field label { display: block; font-size: 0.72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.3rem; }
.field input, .field select, .field textarea { width: 100%; padding: 0.65rem 0.875rem; border-radius: 7px; border: 1.5px solid var(--border); background: #f8fafc; color: var(--text); font-family: inherit; font-size: 0.875rem; transition: border-color 0.2s; }
.field input:focus, .field select:focus, .field textarea:focus { outline: none; border-color: var(--accent); background: #fff; box-shadow: 0 0 0 3px rgba(21,101,192,0.08); }
.field textarea { resize: vertical; min-height: 70px; }
.save-btn { width: 100%; padding: 0.7rem; background: var(--accent); color: #fff; border: none; border-radius: 7px; font-weight: 600; font-size: 0.9rem; cursor: pointer; font-family: inherit; transition: background 0.2s; margin-top: 0.5rem; }
.save-btn:hover { background: var(--accent-hover); }
.back-link { display: block; text-align: center; margin-top: 1rem; font-size: 0.82rem; color: var(--accent); cursor: pointer; text-decoration: none; }
.message.success { background: #f1f8e9; border: 1px solid #c5e1a5; border-left: 3px solid #43a047; border-radius: 7px; padding: 0.6rem 0.875rem; margin-bottom: 1rem; font-size: 0.82rem; color: #2e7d32; }
</style>
</head>
<body>
<div class="settings-box">
  <div class="settings-header">
    <div>
      <h2>Patient Profile</h2>
      <p>Used to personalize AI responses</p>
    </div>
  </div>
  <div class="settings-body">

    <?= $this->Flash->render() ?>

    <?= $this->Form->create(null, ['url' => ['controller' => 'Settings', 'action' => 'index']]) ?>

      <div class="field">
        <label>Age</label>
        <?= $this->Form->control('age', ['type' => 'number', 'label' => false, 'value' => $fullUser->age, 'placeholder' => 'e.g. 34']) ?>
      </div>

      <div class="field">
        <label>Gender</label>
        <?= $this->Form->select('gender', ['male' => 'Male', 'female' => 'Female', 'other' => 'Other', 'prefer_not' => 'Prefer not to say'], ['empty' => 'Select gender', 'value' => $fullUser->gender, 'label' => false]) ?>
      </div>

      <div class="field">
        <label>Known Conditions</label>
        <?= $this->Form->textarea('conditions', ['label' => false, 'value' => $fullUser->conditions, 'placeholder' => 'e.g. Diabetes, Hypertension, Asthma']) ?>
      </div>

      <div class="field">
        <label>Allergies</label>
        <?= $this->Form->textarea('allergies', ['label' => false, 'value' => $fullUser->allergies, 'placeholder' => 'e.g. Penicillin, Peanuts, Latex']) ?>
      </div>

      <button type="submit" class="save-btn">Save Profile</button>

    <?= $this->Form->end() ?>

    <a class="back-link" href="/dashboard">← Back to Dashboard</a>

  </div>
</div>
</body>
</html>