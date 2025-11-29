<?php
session_start();

class ChatController
{
    public function index()
    {
        // Verifica sesión y usuario
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol'])) {
            header('Location: /login');
            exit();
        }
        require_once __DIR__ . '/../views/chat.php';
    }

    public function sendMessage()
    {
        // Solo POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['message'])) {
            http_response_code(400);
            exit('Petición inválida');
        }

        // Verifica sesión
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['rol'])) {
            http_response_code(403);
            exit('Acceso denegado');
        }

        $role = $_SESSION['rol'];
        $message = substr(trim($_POST['message']), 0, 500);

        // Seguridad básica
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        if (preg_match('/(.)\\1{9,}/', $message)) {
            echo json_encode(['response' => 'Por favor, no envíes spam.']);
            exit;
        }

        // RESPUESTAS SEGÚN ROL
        $response = $this->generateResponse($message, $role);
        echo json_encode(['response' => $response]);
    }

    private function generateResponse($msg, $role)
    {
        switch ($role) {
            case 'admin':
                return $this->adminResponses($msg);
            case 'empleado':
                return $this->empleadoResponses($msg);
            case 'cliente':
                return $this->clienteResponses($msg);
            default:
                return "No se reconoció tu rol.";
        }
    }
    private function adminResponses($msg)
    {
        if (stripos($msg, 'ventas') !== false) return "Total de ventas este mes: [Simulado]";
        if (stripos($msg, 'usuarios') !== false) return "Usuarios registrados: [Simulado]";
        if (stripos($msg, 'configuración') !== false) return "Configuración actual: [Simulada]";
        return "¿Sobre qué reporte o configuración necesitas información?";
    }
    private function empleadoResponses($msg)
    {
        if (stripos($msg, 'inventario') !== false) return "Inventario disponible: [Simulado]";
        if (stripos($msg, 'logística') !== false) return "Logística: [Simulada]";
        if (stripos($msg, 'pendiente') !== false) return "Pedidos pendientes: [Simulados]";
        return "¿Sobre qué aspecto interno necesitas soporte?";
    }
    private function clienteResponses($msg)
    {
        if (stripos($msg, 'pedido') !== false) return "Para tu pedido, consulta: [Simulado]";
        if (stripos($msg, 'producto') !== false) return "Nuestros productos incluyen: [Simulado]";
        if (stripos($msg, 'recomendación') !== false) return "Cuéntame tu tipo de piel para recomendaciones.";
        if (stripos($msg, 'ayuda') !== false) return "¿Sobre qué necesitas ayuda en la tienda?";
        return "Estoy aquí para ayudarte con la tienda y el cuidado de tu piel.";
    }
}