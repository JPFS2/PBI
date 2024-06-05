<?php
// Credenciais de conexão com o banco de dados
$servername = "localhost";
$dbname = "pbi";
$dbusername = "root";
$dbpassword = "";

// Conectar ao banco de dados
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Preparar e executar a consulta SQL
    $sql = "SELECT * FROM usuarios WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar se o usuário existe
    if ($result->num_rows > 0) {
        echo "Login bem-sucedido!";
        // Aqui você pode redirecionar o usuário para outra página
        header("Location: index.html");
    } else {
        echo "Nome de usuário ou senha incorretos.";
    }

    $stmt->close();
}

$conn->close();
?>