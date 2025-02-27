<?php
// Iniciar a sessão
session_start();

// Verificar se os campos foram preenchidos
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    header("Location: login.php?erro=campos");
    exit;
}

// Obter os valores de username e senha do método POST
$username = $_POST['username'];
$password = $_POST['password'];

// Estabelecer conexão com o banco de dados MySQL
$conexao = new mysqli("localhost", "root", "", "wb");

// Verificar conexão
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Usar prepared statements para evitar SQL Injection
$sql = "SELECT id, username, password FROM wblogin WHERE username=? AND password=?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar se a consulta retornou algum resultado
if ($resultado->num_rows > 0) {
    // Se o login for bem-sucedido, armazenar os dados do usuário na sessão
    $dados_tratados = $resultado->fetch_assoc();
    $_SESSION['user_id'] = $dados_tratados['id'];
    $_SESSION['username'] = $dados_tratados['username'];

    // Redirecionar para a página 'admin.php'
    header("Location: admin.php");
    exit;
} else {
    // Se o login falhar, redirecionar para login.php com uma mensagem de erro
    header("Location: index.php?erro=senha_incorreta");
    exit;
}

// Fechar conexão
$stmt->close();
$conexao->close();
?>