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
    --bg: #0f1117;
    --surface: #181c27;
    --surface-2: #1f2436;
    --border: rgba(255,255,255,0.07);
    --accent: #4fffb0;
    --accent-dim: rgba(79,255,176,0.12);
    --accent-dim2: rgba(79,255,176,0.06);
    --text: #e8eaf0;
    --text-muted: #6b7280;
    --text-dim: #9ca3af;
    --user-bubble: #1e2d3d;
    --bot-bubble: #1a2420;
    --shadow: 0 8px 32px rgba(0,0,0,0.4);
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    background: var(--bg);
    font-family: 'DM Sans', sans-serif;
    color: var(--text);
    min-height: 100vh;
    padding: 2rem;
    background-image:
      radial-gradient(ellipse 60% 40% at 80% 10%, rgba(79,255,176,0.04) 0%, transparent 60%),
      radial-gradient(ellipse 40% 30% at 10% 80%, rgba(79,255,176,0.03) 0%, transparent 50%);
  }

  .layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 1.25rem;
    max-width: 1100px;
    margin: 0 auto;
    height: calc(100vh - 4rem);
  }

  .panel {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow);
  }

  .panel-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 0.6rem;
    flex-shrink: 0;
  }

  .panel-header h2 {
    font-family: 'DM Serif Display', serif;
    font-size: 1rem;
    font-weight: 400;
    letter-spacing: 0.01em;
    color: var(--text);
  }

  .header-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--accent);
    box-shadow: 0 0 8px var(--accent);
    flex-shrink: 0;
  }

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
    padding: 0.75rem 0.875rem;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    border: 1px solid transparent;
    margin-bottom: 2px;
  }

  .patient-item:hover { background: var(--surface-2); border-color: var(--border); }
  .patient-item.active { background: var(--accent-dim2); border-color: rgba(79,255,176,0.2); }

  .patient-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: var(--surface-2);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--accent);
    flex-shrink: 0;
    letter-spacing: 0.02em;
  }

  .patient-info { flex: 1; min-width: 0; }

  .patient-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .patient-tag { font-size: 0.7rem; color: var(--text-muted); margin-top: 1px; }

  .chat-panel { position: relative; }

  .chat-box {
    flex: 1;
    overflow-y: auto;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    scrollbar-width: thin;
    scrollbar-color: var(--border) transparent;
  }

  .chat-placeholder {
    margin: auto;
    text-align: center;
    color: var(--text-muted);
    font-size: 0.85rem;
  }

  .chat-placeholder .icon { font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.4; }

  .msg {
    max-width: 72%;
    padding: 0.75rem 1rem;
    border-radius: 14px;
    font-size: 0.875rem;
    line-height: 1.55;
    animation: fadeUp 0.2s ease;
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .msg-user {
    align-self: flex-end;
    background: var(--user-bubble);
    border: 1px solid rgba(255,255,255,0.08);
    border-bottom-right-radius: 4px;
    color: var(--text);
  }

  .msg-user .msg-label {
    font-size: 0.7rem; font-weight: 600; color: var(--accent);
    margin-bottom: 0.3rem; letter-spacing: 0.05em; text-transform: uppercase;
  }

  .msg-bot {
    align-self: flex-start;
    background: var(--bot-bubble);
    border: 1px solid rgba(79,255,176,0.1);
    border-bottom-left-radius: 4px;
    color: var(--text);
  }

  .msg-bot .msg-label {
    font-size: 0.7rem; font-weight: 600; color: var(--text-muted);
    margin-bottom: 0.3rem; letter-spacing: 0.05em; text-transform: uppercase;
  }

  .typing {
    align-self: flex-start;
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 0.75rem 1rem;
    background: var(--bot-bubble);
    border: 1px solid rgba(79,255,176,0.1);
    border-radius: 14px;
    border-bottom-left-radius: 4px;
  }

  .typing span {
    width: 5px; height: 5px;
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
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 0.375rem 0.375rem 0.375rem 1rem;
    transition: border-color 0.2s;
  }

  .input-row:focus-within {
    border-color: rgba(79,255,176,0.35);
    box-shadow: 0 0 0 3px rgba(79,255,176,0.06);
  }

  #chat-input {
    flex: 1;
    background: transparent;
    border: none;
    outline: none;
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.875rem;
    line-height: 1.5;
  }

  #chat-input::placeholder { color: var(--text-muted); }

  #send-btn {
    background: var(--accent);
    color: #0a1a12;
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.15s, transform 0.1s;
    letter-spacing: 0.03em;
    flex-shrink: 0;
  }

  #send-btn:hover { opacity: 0.88; }
  #send-btn:active { transform: scale(0.97); }
  #send-btn:disabled { opacity: 0.5; cursor: not-allowed; }

  .hint { margin-top: 0.5rem; font-size: 0.7rem; color: var(--text-muted); text-align: center; }

  .msg-error {
    align-self: flex-start;
    background: rgba(255, 80, 80, 0.1);
    border: 1px solid rgba(255, 80, 80, 0.2);
    border-radius: 14px;
    padding: 0.75rem 1rem;
    font-size: 0.8rem;
    color: #ff8080;
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
      <div class="patient-item" onclick="window.location='/settings'">
        <div class="patient-avatar">⚙</div>
        <div class="patient-info"><div class="patient-name">Settings</div></div>
      </div>
      <div class="patient-item" onclick="window.location='/logout'">
        <div class="patient-avatar">⎋</div>
        <div class="patient-info"><div class="patient-name">Logout</div></div>
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
    label.textContent = role === 'user' ? 'You' : 'AI';
    const body = document.createElement('div');
    body.textContent = text;
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
</body>
</html>