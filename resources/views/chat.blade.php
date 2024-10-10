@extends('layouts.app')

@section('content')
<div class="chat-container">
    <div class="chat-header">
        <h2>Enviar Mensaje</h2>
        <meta http-equiv="refresh" content="10">
    </div>

    <div class="message-form">
        <form id="sendMessageForm">
            @csrf
            <div class="form-group">
                <label for="receiverEmail">Correo del destinatario:</label>
                <input type="email" id="receiverEmail" name="receiver_email" placeholder="Correo del destinatario" required>
            </div>

            <div class="form-group">
                <label for="messageInput">Mensaje:</label>
                <textarea id="messageInput" name="message" rows="4" placeholder="Escribe tu mensaje" required></textarea>
            </div>

            <button type="submit">Enviar</button>
        </form>
    </div>

    <div class="inbox">
        <h3>Buzón de Mensajes</h3>
        <div id="inboxMessages" class="messages">
            <!-- Mensajes del buzón cargados dinámicamente -->
            <p id="emptyInboxMessage">Cargando mensajes...</p>
        </div>
    </div>
</div>

<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<script src="{{ asset('js/app.js') }}" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    loadInboxMessages();

    document.getElementById('sendMessageForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const message = document.getElementById('messageInput').value;
        const receiverEmail = document.getElementById('receiverEmail').value;

        fetch('{{ url('/send-message') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                message: message,
                receiver_email: receiverEmail
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('messageInput').value = '';
            document.getElementById('receiverEmail').value = '';
            alert('Mensaje enviado exitosamente');
            loadInboxMessages(); // Reload inbox messages after sending
        });
    });

    function loadInboxMessages() {
        fetch('{{ url('/load-inbox-messages') }}')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar los mensajes');
            }
            return response.json();
        })
        .then(data => {
            const inboxMessagesDiv = document.getElementById('inboxMessages');
            inboxMessagesDiv.innerHTML = '';
            if (data.data.length > 0) {
                data.data.forEach(message => {
                    const messageDiv = document.createElement('div');
                    messageDiv.innerHTML = `
                        <strong>De ${message.sender_email}:</strong> <span id="message-${message.id}">${message.message}</span>
                        <button class="decrypt-button" onclick="decryptMessage(${message.id})">Desencriptar</button>
                        <button id="reEncryptButton-${message.id}" class="re-encrypt-button" style="display: none;" onclick="reEncryptMessage(${message.id})">Reencriptar</button>
                    `;
                    inboxMessagesDiv.appendChild(messageDiv);
                });
            } else {
                inboxMessagesDiv.innerHTML = '<p id="emptyInboxMessage">Buzón vacío</p>';
            }
        })
        .catch(error => {
            console.error('Error al cargar los mensajes:', error);
        });
    }

    // Define la función decryptMessage
    window.decryptMessage = function(messageId) {
        fetch(`/messages/decrypt/${messageId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al desencriptar el mensaje');
                }
                return response.json();
            })
            .then(data => {
                // Encuentra el span que contiene el mensaje encriptado y reemplaza su contenido
                const messageElement = document.getElementById(`message-${messageId}`);
                messageElement.innerHTML = data.data.message; // Sustituye el mensaje encriptado por el desencriptado
                
                // Muestra el botón para reencriptar
                const reEncryptButton = document.getElementById(`reEncryptButton-${messageId}`);
                reEncryptButton.style.display = 'inline-block'; // Muestra el botón
            })
            .catch(error => {
                console.error('Error al desencriptar el mensaje:', error);
            });
    };

    // Define la función reEncryptMessage
    window.reEncryptMessage = function(messageId) {
        const messageElement = document.getElementById(`message-${messageId}`);
        const originalMessage = messageElement.innerHTML; // Obtén el mensaje desencriptado

        fetch(`/messages/re-encrypt/${messageId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                message: originalMessage
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al reencriptar el mensaje');
            }
            return response.json();
        })
        .then(data => {
            // Actualiza el contenido del span con el mensaje reencriptado
            messageElement.innerHTML = data.data.message; // Supongamos que aquí se obtiene el mensaje reencriptado
            
            // Oculta el botón de reencriptar
            const reEncryptButton = document.getElementById(`reEncryptButton-${messageId}`);
            reEncryptButton.style.display = 'none'; // Oculta el botón
        })
        .catch(error => {
            console.error('Error al reencriptar el mensaje:', error);
        });
    };
});
</script>

<style>
.chat-container {
    width: 80%;
    margin: auto;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 20px;
    background-color: #f9f9f9;
}

.chat-header {
    text-align: center;
    margin-bottom: 20px;
}

.message-form {
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.message-form button {
    padding: 10px 15px;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 4px;
    cursor: pointer;
}

.inbox {
    margin-top: 30px;
}

.inbox h3 {
    text-align: center;
}

.messages {
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 4px;
    background-color: #fff;
    max-height: 150px;
    overflow-y: auto;
    text-align: center;
}

#emptyInboxMessage {
    color: #666;
}

/* Estilos para el botón de desencriptar */
.decrypt-button {
    padding: 5px 10px;
    border: none;
    background-color: #28a745; /* Color verde */
    color: white;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 10px;
}

.decrypt-button:hover {
    background-color: #218838; /* Color verde más oscuro al pasar el mouse */
}

/* Estilos para el botón de reencriptar */
.re-encrypt-button {
    padding: 5px 10px;
    border: none;
    background-color: #ffc107; /* Color amarillo */
    color: white;
    border-radius: 4px;
    cursor: pointer;
    margin-left: 10px;
}

.re-encrypt-button:hover {
    background-color: #e0a800; /* Color amarillo más oscuro al pasar el mouse */
}
</style>
@endsection
