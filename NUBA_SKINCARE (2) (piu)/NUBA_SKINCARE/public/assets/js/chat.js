let chatForm = document.getElementById('chat-form');
let chatInput = document.getElementById('chat-input');
let chatMessages = document.getElementById('chat-messages');

chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    let msg = chatInput.value.trim();
    if (!msg) return;
    if (msg.length > 500) {
        alert("El mensaje es demasiado largo.");
        return;
    }

    renderMessage('Tú', msg);

    fetch('/chat/sendMessage', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'message=' + encodeURIComponent(msg)
    })
    .then(r => r.json())
    .then(d => {
        renderMessage('Chatbot', d.response);
    });
    chatInput.value = '';
});

function renderMessage(sender, text) {
    let div = document.createElement('div');
    div.className = 'chat-message ' + (sender === 'Tú' ? 'user' : 'bot');
    div.innerText = sender + ': ' + text;
    chatMessages.appendChild(div);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}