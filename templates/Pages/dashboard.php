<!-- <div class="row">
    <!-- Patient List -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">Patients</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">John Doe</li>
                <li class="list-group-item">Jane Smith</li>
                <li class="list-group-item">Alice Johnson</li>
            </ul>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">AI Chat</div>
            <div class="card-body" id="chat-box" style="height:400px; overflow-y:auto; border:1px solid #ddd; padding:10px;">
                <div class="text-muted">Start your conversation with the bot...</div>
            </div>
            <div class="card-footer">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Type a message..." id="chat-input">
                    <button class="btn btn-success" id="send-btn">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const sendBtn = document.getElementById('send-btn');
    const chatInput = document.getElementById('chat-input');
    const chatBox = document.getElementById('chat-box');

    sendBtn.addEventListener('click', () => {
        const message = chatInput.value.trim();
        if(message){
            const msgEl = document.createElement('div');
            msgEl.classList.add('mb-2', 'text-end', 'fw-bold');
            msgEl.textContent = "You: " + message;
            chatBox.appendChild(msgEl);
            chatInput.value = '';
            chatBox.scrollTop = chatBox.scrollHeight;

            // Placeholder AI response
            setTimeout(() => {
                const botEl = document.createElement('div');
                botEl.classList.add('mb-2', 'text-start', 'text-primary');
                botEl.textContent = "Bot: Hello! This is a placeholder response.";
                chatBox.appendChild(botEl);
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 500);
        }
    });
</script> -->