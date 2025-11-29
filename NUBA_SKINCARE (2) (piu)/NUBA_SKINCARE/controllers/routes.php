//Ejemplo para router.php
$route->get('/chat', [ChatController::class, 'index']);
$route->post('/chat/sendMessage', [ChatController::class, 'sendMessage']);