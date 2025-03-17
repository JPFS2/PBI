<?php
session_start();

// Credenciais de conexão com o banco de dados
$servername = "localhost";
$dbname = "thewaysi_pbi";
$dbusername = "thewaysi_pbi";
$dbpassword = "J@hn9230";

// Conectar ao banco de dados
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Buscar usuário no banco de dados
    $sql = "SELECT password, profile FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        $profile = $row['profile'];

        // Verificar senha
        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true); // Segurança contra sequestro de sessão
            $_SESSION['username'] = $username;
            $_SESSION['profile'] = $profile;

            // Redirecionamento baseado no tipo de usuário
            switch ($profile) {
                case "DIRETORIA":
                    header("Location: home.html");
                    break;
                case "VENDAS":
                    header("Location: vendas.html");
                    break;
                case "VENDASJOCEILDO":
                    header("Location: vendasjoceildo.html");
                    break;
                case "VENDASRODRIGO":
                    header("Location: vendasrodrigo.html");
                    break;
                default:
                    header("Location: index.html?erro=perfil_invalido");
                    exit;
            }
            exit;
        }
    }

    // Erro de login genérico para não revelar informações
    header("Location: index.html?erro=1");
    exit;
}

$conn->close();
?>
