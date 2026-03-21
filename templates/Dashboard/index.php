<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AI Medical Chat</title>
<meta name="csrfToken" content="<?= $this->request->getAttribute('csrfToken') ?>">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #f0f4f8;
  --surface: #ffffff;
  --surface-2: #f0f4f8;
  --border: #d1dce8;
  --accent: #1565c0;
  --accent-light: #e3f0fa;
  --accent-hover: #eaf3fb;
  --text: #1a2a3a;
  --text-muted: #607d8b;
  --text-dim: #90a4ae;
  --user-bubble: #0a3d6b;
  --user-bubble-text: #e8f4ff;
  --user-label: #90caf9;
  --bot-bubble: #ffffff;
  --bot-label: #1565c0;
  --header-bg: #0a3d6b;
  --shadow: 0 1px 4px rgba(0,0,0,0.08);
}

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
  background: var(--bg);
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  color: var(--text);
  min-height: 100vh;
  padding: 2rem;
}

.layout {
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 1.25rem;
  max-width: 1100px;
  margin: 0 auto;
  height: calc(100vh - 4rem);
}

/* Panel shell */
.panel {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  box-shadow: var(--shadow);
}

.panel-header {
  background: var(--header-bg);
  padding: 1rem 1.25rem;
  border-bottom: none;
  display: flex;
  align-items: center;
  gap: 0.6rem;
  flex-shrink: 0;
}

.panel-header h2 {
  font-size: 0.95rem;
  font-weight: 600;
  letter-spacing: 0.02em;
  color: #ffffff;
}

/* Replace green dot with medical cross */
.header-dot {
  width: 20px;
  height: 20px;
  position: relative;
  flex-shrink: 0;
}

.header-dot::before,
.header-dot::after {
  content: '';
  position: absolute;
  background: #4fc3f7;
  border-radius: 2px;
}

.header-dot::before { width: 6px; height: 14px; left: 7px; top: 3px; }
.header-dot::after  { width: 14px; height: 6px; left: 3px; top: 7px; }

/* Status badge in chat header */
.chat-header-badge {
  margin-left: auto;
  background: rgba(79,195,247,0.15);
  border: 1px solid rgba(79,195,247,0.35);
  color: #4fc3f7;
  font-size: 0.65rem;
  padding: 2px 8px;
  border-radius: 20px;
  letter-spacing: 0.05em;
  font-weight: 700;
}

/* Patient list */
.patient-list {
  flex: 1;
  overflow-y: auto;
  padding: 0.5rem;
  scrollbar-width: thin;
  scrollbar-color: var(--border) transparent;
}

.patient-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.7rem 0.875rem;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.15s, border-color 0.15s;
  border: 1px solid transparent;
  border-left: 3px solid transparent;
  margin-bottom: 2px;
}

.patient-item:hover {
  background: var(--accent-hover);
  border-color: #b3d4ee;
  border-left-color: #b3d4ee;
}

.patient-item.active {
  background: var(--accent-light);
  border-color: #90caf9;
  border-left-color: var(--accent);
}

.patient-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: var(--accent-light);
  border: 1.5px solid #90caf9;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.7rem;
  font-weight: 700;
  color: var(--accent);
  flex-shrink: 0;
  letter-spacing: 0.02em;
}

.patient-info { flex: 1; min-width: 0; }

.patient-name {
  font-size: 0.82rem;
  font-weight: 600;
  color: var(--text);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.patient-tag {
  font-size: 0.68rem;
  color: var(--text-muted);
  margin-top: 1px;
}

/* Chat area */
.chat-panel { position: relative; }

.chat-box {
  flex: 1;
  overflow-y: auto;
  padding: 1.25rem;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  background: #f8fafc;
  scrollbar-width: thin;
  scrollbar-color: var(--border) transparent;
}

.chat-placeholder {
  margin: auto;
  text-align: center;
  color: var(--text-muted);
  font-size: 0.85rem;
}

.chat-placeholder .icon {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  opacity: 0.3;
}

/* Bubbles */
.msg {
  max-width: 72%;
  padding: 0.75rem 1rem;
  border-radius: 12px;
  font-size: 0.82rem;
  line-height: 1.55;
  animation: fadeUp 0.2s ease;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(5px); }
  to   { opacity: 1; transform: translateY(0); }
}

.msg-user {
  align-self: flex-end;
  background: var(--user-bubble);
  color: var(--user-bubble-text);
  border-bottom-right-radius: 3px;
}

.msg-user .msg-label {
  font-size: 0.65rem;
  font-weight: 700;
  color: var(--user-label);
  margin-bottom: 0.3rem;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

.msg-bot {
  align-self: flex-start;
  background: var(--bot-bubble);
  border: 1px solid var(--border);
  color: var(--text);
  border-bottom-left-radius: 3px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}

.msg-bot .msg-label {
  font-size: 0.65rem;
  font-weight: 700;
  color: var(--bot-label);
  margin-bottom: 0.3rem;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

/* Typing indicator */
.typing {
  align-self: flex-start;
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 0.75rem 1rem;
  background: var(--bot-bubble);
  border: 1px solid var(--border);
  border-radius: 12px;
  border-bottom-left-radius: 3px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}

.typing span {
  width: 5px;
  height: 5px;
  border-radius: 50%;
  background: var(--accent);
  opacity: 0.4;
  animation: bounce 1.2s infinite;
}

.typing span:nth-child(2) { animation-delay: 0.2s; }
.typing span:nth-child(3) { animation-delay: 0.4s; }

@keyframes bounce {
  0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
  30% { transform: translateY(-4px); opacity: 1; }
}

/* Footer / input */
.chat-footer {
  padding: 0.875rem 1rem;
  border-top: 1px solid var(--border);
  background: var(--surface);
  flex-shrink: 0;
}

.input-row {
  display: flex;
  gap: 0.5rem;
  align-items: center;
  background: var(--surface-2);
  border: 1.5px solid #b3cde0;
  border-radius: 8px;
  padding: 0.35rem 0.35rem 0.35rem 0.875rem;
  transition: border-color 0.2s, background 0.2s;
}

.input-row:focus-within {
  border-color: var(--accent);
  background: #ffffff;
  box-shadow: 0 0 0 3px rgba(21,101,192,0.08);
}

#chat-input {
  flex: 1;
  background: transparent;
  border: none;
  outline: none;
  color: var(--text);
  font-family: inherit;
  font-size: 0.85rem;
  line-height: 1.5;
}

#chat-input::placeholder { color: var(--text-muted); }

#send-btn {
  background: var(--accent);
  color: #ffffff;
  border: none;
  border-radius: 6px;
  padding: 0.5rem 1.1rem;
  font-family: inherit;
  font-size: 0.8rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.15s, transform 0.1s;
  letter-spacing: 0.03em;
  flex-shrink: 0;
}

#send-btn:hover { background: #1976d2; }
#send-btn:active { transform: scale(0.97); }
#send-btn:disabled { background: #90a4ae; cursor: not-allowed; }

.hint {
  margin-top: 0.45rem;
  font-size: 0.65rem;
  color: var(--text-dim);
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
}

.msg-error {
  align-self: flex-start;
  background: #fff5f5;
  border: 1px solid #ffcdd2;
  border-radius: 10px;
  padding: 0.75rem 1rem;
  font-size: 0.8rem;
  color: #c62828;
}

/* Section divider in sidebar */
.list-divider {
  font-size: 0.65rem;
  color: var(--text-dim);
  text-transform: uppercase;
  letter-spacing: 0.07em;
  padding: 0.5rem 0.875rem 0.25rem;
  font-weight: 600;
}
.msg-body p { margin-bottom: 0.5rem; }
.msg-body p:last-child { margin-bottom: 0; }

.msg-body strong { font-weight: 600; color: inherit; }

.msg-body ul, .msg-body ol {
  margin: 0.4rem 0 0.5rem 1.2rem;
  padding: 0;
}

.msg-body li { margin-bottom: 0.25rem; line-height: 1.5; }

.msg-body h1, .msg-body h2, .msg-body h3 {
  font-size: 0.85rem;
  font-weight: 700;
  margin: 0.6rem 0 0.3rem;
  color: #1565c0;
}

.msg-body code {
  background: #f0f4f8;
  border: 1px solid #d1dce8;
  border-radius: 4px;
  padding: 1px 5px;
  font-size: 0.78rem;
  font-family: monospace;
}

.msg-body hr {
  border: none;
  border-top: 1px solid #d1dce8;
  margin: 0.5rem 0;
}
</style>
</head>
<body>

<div class="layout">

  <!-- Sidebar -->
  <div class="panel">
    <div class="panel-header">
      <span class="header-dot"></span>
      <h2>Dashboard</h2>
    </div>
    <div class="patient-list">

      <div class="patient-item active" id="new-chat-btn">
        <div class="patient-avatar">+</div>
        <div class="patient-info">
          <div class="patient-name">New Chat</div>
          <div class="patient-tag">Start a conversation</div>
        </div>
      </div>

      <?php foreach ($conversations as $conv): ?>
      <div class="patient-item conversation-item" data-id="<?= $conv->id ?>">
        <div class="patient-avatar"><?= strtoupper(substr($conv->title, 0, 2)) ?></div>
        <div class="patient-info">
          <div class="patient-name"><?= h($conv->title) ?></div>
          <div class="patient-tag"><?= $conv->created->timeAgoInWords() ?></div>
        </div>
      </div>
      <?php endforeach; ?>

    </div>

    <div style="padding: 1rem; border-top: 1px solid var(--border);">
      <div class="patient-item">
  <div class="patient-avatar">⚙</div>
  <div class="patient-info">
    <div class="patient-name">Settings</div>
  </div>
</div>
      <div class="patient-item">
  <div class="patient-item" onclick="window.location='<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout']) ?>'">
  <div class="patient-avatar">⎋</div>
  <div class="patient-info">
    <div class="patient-name">Logout</div>
  </div>
</div>
</div>
    </div>
  </div>

  <!-- Chat Panel -->
  <div class="panel chat-panel">
    <div class="panel-header">
      <span class="header-dot"></span>
      <h2>AI Assistant</h2>
    </div>

    <div class="chat-box" id="chat-box">
      <div class="chat-placeholder" id="placeholder">
        <div class="icon">✦</div>
        Start your conversation with the AI assistant
      </div>
    </div>

    <div class="chat-footer">
      <div class="input-row">
        <input type="text" id="chat-input" placeholder="Ask your health question…" autocomplete="off">
        <button id="send-btn">Send</button>
      </div>
      <p class="hint">AI responses are for clinical decision support only.</p>
    </div>
  </div>

</div>

<script>
  const sendBtn     = document.getElementById('send-btn');
  const chatInput   = document.getElementById('chat-input');
  const chatBox     = document.getElementById('chat-box');
  const placeholder = document.getElementById('placeholder');

  // Read CSRF token from meta tag injected by CakePHP
  const csrfToken = document.querySelector('meta[name="csrfToken"]')?.content || '';

  let currentConversationId = null;

  function removePlaceholder() {
    const el = document.getElementById('placeholder');
    if (el) el.remove();
  }

 function appendMessage(text, role) {
  removePlaceholder();
  const wrap = document.createElement('div');
  wrap.classList.add('msg', role === 'user' ? 'msg-user' : 'msg-bot');

  const label = document.createElement('div');
  label.classList.add('msg-label');
  label.textContent = role === 'user' ? 'You' : 'AI Assistant';

  const body = document.createElement('div');
  body.classList.add('msg-body');

  if (role === 'bot') {
    body.innerHTML = marked.parse(text); // render markdown for AI
  } else {
    body.textContent = text; // plain text for user
  }

  wrap.appendChild(label);
  wrap.appendChild(body);
  chatBox.appendChild(wrap);
  chatBox.scrollTop = chatBox.scrollHeight;
}

  function appendError(text) {
    removePlaceholder();
    const el = document.createElement('div');
    el.classList.add('msg-error');
    el.textContent = '⚠ ' + text;
    chatBox.appendChild(el);
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function showTyping() {
    const el = document.createElement('div');
    el.classList.add('typing');
    el.id = 'typing-indicator';
    el.innerHTML = '<span></span><span></span><span></span>';
    chatBox.appendChild(el);
    chatBox.scrollTop = chatBox.scrollHeight;
    return el;
  }

  function send() {
    const message = chatInput.value.trim();
    if (!message) return;

    appendMessage(message, 'user');
    chatInput.value = '';
    sendBtn.disabled = true;

    const typing = showTyping();

    fetch('/dashboard/sendMessage', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrfToken
      },
      body: JSON.stringify({
        message: message,
        conversation_id: currentConversationId
      })
    })
    .then(res => {
      if (!res.ok) throw new Error('Server error: ' + res.status);
      return res.json();
    })
    .then(data => {
      typing.remove();
      if (data.success) {
        appendMessage(data.reply, 'bot');
        currentConversationId = data.conversation_id;
      } else {
        appendError(data.reply || 'No response from AI.');
      }
    })
    .catch(err => {
      typing.remove();
      appendError('Could not reach server. Check your connection.');
      console.error('Fetch error:', err);
    })
    .finally(() => {
      sendBtn.disabled = false;
      chatInput.focus();
    });
  }

  sendBtn.addEventListener('click', send);
  chatInput.addEventListener('keydown', e => { if (e.key === 'Enter') send(); });

  // New chat button
  document.getElementById('new-chat-btn').addEventListener('click', () => {
    currentConversationId = null;
    chatBox.innerHTML = '<div class="chat-placeholder" id="placeholder"><div class="icon">✦</div>Start your conversation with the AI assistant</div>';
    document.querySelectorAll('.patient-item').forEach(i => i.classList.remove('active'));
    document.getElementById('new-chat-btn').classList.add('active');
  });

  // Load existing conversation
  document.querySelectorAll('.conversation-item').forEach(item => {
    item.addEventListener('click', () => {
      const id = item.dataset.id;
      document.querySelectorAll('.patient-item').forEach(i => i.classList.remove('active'));
      item.classList.add('active');

      fetch('/dashboard/getMessages/' + id)
        .then(res => res.json())
        .then(data => {
          chatBox.innerHTML = '';
          if (data.messages && data.messages.length) {
            data.messages.forEach(msg => appendMessage(msg.content, msg.role));
          } else {
            chatBox.innerHTML = '<div class="chat-placeholder" id="placeholder"><div class="icon">✦</div>No messages yet</div>';
          }
          currentConversationId = id;
        })
        .catch(err => appendError('Could not load conversation.'));
    });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
</body>
</html>