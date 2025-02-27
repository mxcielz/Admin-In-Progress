<?php
session_start();

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Verifica se o usuário existe no banco de dados
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM wblogin WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Se o usuário não existir, redireciona para a página de login
    header("Location: index.php");
    exit();
}

// Atualiza o tempo do último acesso (se ainda estiver dentro do limite)
$_SESSION['ultimo_acesso'] = time();
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<link href='https://unpkg.com/boxicons@2.1.4/dist/boxicons.js' rel='stylesheet'>

	<link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="../style.css">

	<title>WB - Admin</title>

</head>
<style>
/* Loader Styles */
#loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8); /* Fundo semitransparente */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999; /* Garantir que o loader fique acima de outros elementos */
    transition: opacity 0.5s ease, visibility 0s 0.5s; /* Transição suave para desaparecer */
}


/* Estilo do spinner */
.spinner {
    border: 4px solid #f3f3f3; /* Cor de fundo */
    border-top: 4px solid #3498db; /* Cor da animação */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

/* Animação do spinner */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

#content {
    display: none; /* Esconde o conteúdo do site até que o loader termine */
}

.text {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.money-container {
    display: flex;
    align-items: center;
    gap: 5px; /* Aumentei o espaço entre o valor e o olho */
}

/* Icone do toggle de visibilidade */
#toggle-icon {
    cursor: pointer;
    font-size: 16px; /* Reduzi de 20px para 16px */
    color: white;
    transition: color 0.3s ease-in-out;
}

#toggle-icon:hover {
    color: #1db954; /* Cor verde ao passar o mouse */
}

/* Controle da opacidade do valor */
#money-value {
    opacity: 0; /* Inicialmente escondido */
    transition: opacity 0.3s ease-in-out;
}

/* Modificação para modo escuro */
body.dark #loader {
    background: rgba(0, 0, 0, 0.9); /* Fundo mais escuro no modo escuro */
}


</style>
<body>
<div id="loader">
		<div class="spinner"></div>
	</div>

	<section id="sidebar">
	<a href="./admin.php" class="brand">
    <i class='bx bxs-message-dots bx-sm' ></i>
	<span class="text">WB Manutenção</span>
</a>

		<ul class="side-menu top">
			<li class="">
				<a href="../admin.php">
					<i class='bx bxs-dashboard bx-sm' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="#">

					<i class='bx bxs-shopping-bag-alt bx-sm' ></i>
					<span class="text">Curriculum</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class='bx bxs-doughnut-chart bx-sm' ></i>
					<span class="text">Analytics</span>
				</a>
			</li>
			<li class="active">
				<a href="#">
					<i class='bx bxs-message-dots bx-sm' ></i>
					<span class="text">Message</span>
				</a>
			</li>
			<li>
				<a href="../myteam/myteam.php">
					<i class='bx bxs-group bx-sm' ></i>
					<span class="text">Team</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu bottom">
			<li>
				<a href="#">
					<i class='bx bxs-cog bx-sm bx-spin-hover' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="../logout.php" class="logout">
					<i class='bx bx-power-off bx-sm bx-burst-hover' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>

	



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
<nav>
    <i class='bx bx-menu bx-sm' ></i>
    <a href="#" class="nav-link">Categories</a>
    <form action="#">
        <div class="form-input">
            <input type="search" placeholder="Search...">
            <button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
        </div>
    </form>
    <input type="checkbox" class="checkbox" id="switch-mode" hidden />
    <label class="swith-lm" for="switch-mode">
        <i class="bx bxs-moon"></i>
        <i class="bx bx-sun"></i>
        <div class="ball"></div>
    </label>

    <!-- Notification Bell -->
    <a href="#" class="notification" id="notificationIcon">
        <i class='bx bxs-bell bx-tada-hover' ></i>
        <span class="num">8</span>
    </a>
    <div class="notification-menu" id="notificationMenu">
        <ul>
            <li>New message from John</li>
            <li>Your order has been shipped</li>
            <li>New comment on your post</li>
            <li>Update available for your app</li>
            <li>Reminder: Meeting at 3PM</li>
        </ul>
    </div>

    <!-- Profile Menu -->
    <a href="#" class="profile" id="profileIcon">
        <img src="https://placehold.co/600x400/png" alt="Profile">
    </a>
    <div class="profile-menu" id="profileMenu">
        <ul>
            <li><a href="#">My Profile</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
    </div>
</nav>
<!-- NAVBAR -->


<main>
    <div class="head-title">
        <div class="left">
            <h1>Dashboard</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="#">Message</a>
                </li>
                <li>
                    <i class='bx bx-chevron-right'></i>
                </li>
                <li>
                    <a href="#" class="active">Home</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="messages">
        <h2>Mensagens Recebidas</h2>
        <ul class="message-list">
            <?php
            // Consulta para recuperar as mensagens do banco de dados
            $sql = "SELECT company_name, contact_name, email, phone, message FROM contact_messages ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Exibe cada mensagem como um item de lista
                while ($row = $result->fetch_assoc()) {
                    echo "<li class='message-item'>";
                    echo "<div class='message-header' onclick='toggleMessageDetails(this)'>";
                    echo "<strong>Empresa:</strong> " . htmlspecialchars($row['company_name']);
                    echo "</div>";
                    echo "<div class='message-body' style='display: none;'>";
                    echo "<strong>Contato:</strong> " . htmlspecialchars($row['contact_name']) . "<br>";
                    echo "<strong>Email:</strong> " . htmlspecialchars($row['email']) . "<br>";
                    echo "<strong>Telefone:</strong> " . htmlspecialchars($row['phone']) . "<br>";
                    echo "<strong>Mensagem:</strong> " . nl2br(htmlspecialchars($row['message'])) . "<br>";
                    echo "</div>";
                    echo "</li>";
                }
            } else {
                echo "<li>Nenhuma mensagem encontrada.</li>";
            }
            ?>
        </ul>
    </div>
</main>
	<script src="../script.js"></script>
	<script>
		// Função para ocultar o loader após 3 segundos e mostrar o conteúdo
		window.addEventListener('load', () => {
			setTimeout(() => {
				const loader = document.getElementById('loader');
				const content = document.getElementById('content');

				// Esconde o loader e mostra o conteúdo do site
				loader.style.opacity = '0'; // Fade out
				loader.style.visibility = 'hidden'; // Esconde completamente após o fade
				content.style.display = 'block'; // Torna o conteúdo visível
			}, 1300); // 1300 milissegundos = 1,3 segundos
		});

		function toggleMoney() {
    let moneyValue = document.getElementById('money-value');
    let toggleIcon = document.getElementById('toggle-icon');

    if (moneyValue.style.opacity == "0" || moneyValue.style.opacity === '') {
        moneyValue.style.opacity = "1"; // Mostra o dinheiro
        toggleIcon.classList.replace('bx-show', 'bx-hide'); // Troca para ícone de olho fechado
    } else {
        moneyValue.style.opacity = "0"; // Oculta o dinheiro
        toggleIcon.classList.replace('bx-hide', 'bx-show'); // Troca para ícone de olho aberto
    }
}
function toggleMessageDetails(element) {
    const messageBody = element.nextElementSibling;
    if (messageBody.style.display === "none" || messageBody.style.display === "") {
        messageBody.style.display = "block";
    } else {
        messageBody.style.display = "none";
    }
}

	</script>
<script>
    let tempoExpiracao = 15 * 60 * 1000; // 15 minutos em milissegundos
    let timeout;

    function resetTimer() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            window.location.href = "index.php"; // Redireciona para a tela de login
        }, tempoExpiracao);
    }

    // Reinicia o tempo sempre que o usuário interagir com a página
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeydown = resetTimer;
    document.onclick = resetTimer;
</script>

</body>
</html>