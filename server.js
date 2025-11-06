const express = require('express');
const http = require('http');
const socketIo = require('socket.io');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

app.use(express.static('public'));  // Para servir arquivos estáticos (HTML, CSS, JS)

io.on('connection', (socket) => {
  console.log('Um jogador se conectou');

  // Exemplo de evento para enviar mensagens aos clientes
  socket.on('chatMessage', (msg) => {
    io.emit('chatMessage', msg);  // Envia para todos os jogadores
  });

  socket.on('disconnect', () => {
    console.log('Jogador desconectado');
  });
});

server.listen(3000, () => {
  console.log('Servidor rodando na porta 3000');
});
