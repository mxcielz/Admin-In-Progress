<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wb";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['user'];
    $date_order = $_POST['date_order'];
    $status = $_POST['status'];
    $image = $_FILES['image'];

    // Verifica se o upload foi bem-sucedido
    if ($image['error'] == 0) {
        $imagePath = 'uploads/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);

        // Insere os dados no banco de dados
        $sql = "INSERT INTO team (user, date_order, status, image) VALUES ('$user', '$date_order', '$status', '$imagePath')";
        if ($conn->query($sql) === TRUE) {
            // Redireciona para evitar reenvio do formulário
            header("Location: myteam.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Erro ao fazer upload da imagem.";
    }
}

$conn->close();
?>