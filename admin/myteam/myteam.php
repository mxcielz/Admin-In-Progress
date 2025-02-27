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
    header("Location: ../index.php");
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
    header("Location: ../index.php");
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

	<!-- My CSS -->
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

.spinner {
    border: 4px solid #f3f3f3; /* Cor de fundo */
    border-top: 4px solid #3498db; /* Cor da animação */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
#content {
	display: none; /* Esconde o conteúdo do site até que o loader termine */
}


/* Modificação para modo escuro */
body.dark #loader {
    background: rgba(0, 0, 0, 0.9); /* Fundo mais escuro no modo escuro */
}

a {
        color: inherit; /* Herda a cor do elemento pai, que pode ser a cor padrão do texto */
        text-decoration: none; /* Remove o sublinhado padrão do link */
    }

	.hover-effect {
        display: inline-flex;  /* Usa flexbox para alinhar conteúdo */
        justify-content: center;  /* Centraliza horizontalmente */
        align-items: center;  /* Centraliza verticalmente */
        padding: 5px 10px;  /* Ajuste o padding para ter o tamanho certo */
        text-decoration: none;
        border-radius: 500px; /* Bordas arredondadas para o fundo */
        transition: background-color 0.3s ease, transform 0.3s ease;
        font-size: 14px;  /* Ajusta o tamanho do texto */
        color: #333; /* Cor padrão para o texto */
    }

    .hover-effect:hover {
        background-color: rgba(211, 211, 211, 0.5); /* Cor de fundo cinza mais visível ao passar o mouse */
        transform: scale(1.05); /* Aumenta o efeito de escala ao passar o mouse */
    }

    .icon {
        font-size: 20px; /* Tamanho do ícone */
        color: #555; /* Cor padrão do ícone */
    }
	.profile {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.profile img {
    width: 40px; /* Ajuste o tamanho da imagem */
    height: 40px;
    border-radius: 50%; /* Torna a imagem redonda */
    border: 2px solid #ddd; /* Borda sutil */
    transition: border-color 0.3s ease-in-out;
}

.profile img:hover {
    border-color: #3498db; /* Borda azul ao passar o mouse */
}

.profile-menu {
    display: none;
    position: absolute;
    top: 50px; /* Ajuste conforme necessário */
    right: 10px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    width: 150px;
    z-index: 1000;
}

.profile-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.profile-menu ul li {
    padding: 10px;
    text-align: center;
}

.profile-menu ul li a {
    text-decoration: none;
    color: #333;
    display: block;
    transition: background 0.3s ease-in-out;
}

.profile-menu ul li a:hover {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

</style>
<body>
<div id="loader">
		<div class="spinner"></div>
	</div>

	<section id="sidebar">
		<a href="./admin.php" class="brand">
		<i class='bx bxs-group bx-sm' ></i>
		<span class="text">WB Manutenção</span>
		</a>
		<ul class="side-menu top">
			<li>
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
			<li>
				<a href="../contact/message.php">
					<i class='bx bxs-message-dots bx-sm' ></i>
					<span class="text">Message</span>
				</a>
			</li>
			<li class="active">
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
    <img src="../imagem/logo.png" alt="Profile">
    </a>
    <div class="profile-menu" id="profileMenu">
        <ul>
            <li><a href="#">My Profile</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="../logout.php">Log Out</a></li>
        </ul>
    </div>
</nav>
<!-- NAVBAR -->


		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Team</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
			</div>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Recent Orders</h3>
                    <i class='bx bx-search'></i>
					<a href="addteam.php" class="hover-effect">
    <i class='bx bx-plus icon'></i>
</a>
                    <i class='bx bx-filter'></i>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Date Order</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Conexão com o banco de dados
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

                        // Carrega os dados do banco de dados
                        $sql = "SELECT user, date_order, status, image FROM team";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>
                                            <img src='" . $row['image'] . "' alt='User Image'>
                                            <p>" . $row['user'] . "</p>
                                        </td>
                                        <td>" . $row['date_order'] . "</td>
                                        <td><span class='status " . $row['status'] . "'>" . $row['status'] . "</span></td>
                                      </tr>";
                            }
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
				
			</div>
		</div>

		
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

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