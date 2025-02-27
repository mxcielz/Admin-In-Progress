<?php
session_start([
    'cookie_lifetime' => 0, // Cookie de sessão expira quando o navegador é fechado
]);

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

    <!-- My CSS -->
    <link rel="stylesheet" href="style.css">


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
    display: none; /* Oculto por padrão */
    position: absolute;
    top: 50px; /* Ajuste conforme necessário */
    right: 0;
    background: white;
    border: 1px solid #ccc;
    padding: 10px;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
    z-index: 1000;
}

.profile-menu.show {
    display: block; /* Torna visível quando a classe 'show' é adicionada */
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
    <i class='bx bxs-dashboard bx-sm' ></i>
    <span class="text">WB Manutenção</span>
</a>

        <ul class="side-menu top">
            <li class="active">
                <a href="#">
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
                <a href="./contact/message.php">
                    <i class='bx bxs-message-dots bx-sm' ></i>
                    <span class="text">Message</span>
                </a>
            </li>
            <li>
                <a href="./myteam/myteam.php">
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
                <a href="logout.php" class="logout">
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
        <i class='bx bx-menu bx-sm'></i>
        <a href="#" class="nav-link">Categories</a>
        <form action="#">
            <div class="form-input">
                <input type="search" placeholder="Search...">
                <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
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
            <i class='bx bxs-bell bx-tada-hover'></i>
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
    <img src="imagem/logo.png" alt="Profile">
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

<!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right' ></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>
                <a href="#" class="btn-download">
                    <i class='bx bxs-cloud-download bx-fade-down-hover' ></i>
                    <span class="text">Get PDF</span>
                </a>
            </div>

            <ul class="box-info">
                <li>
                    <i class='bx bxs-calendar-check' ></i>
                    <span class="text">
                        <h3>1020</h3>
                        <p>New Order</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-group' ></i>
                    <span class="text">
                        <h3>2834</h3>
                        <p>Visitors</p>
                    </span>
                </li>

                <li>
    <i class='bx bxs-dollar-circle'></i>
    <span class="text">
        <div class="money-container">
            <h3 id="money-value" style="opacity: 0;">N$2543.00</h3>
            <i id="toggle-icon" class='bx bx-show' onclick="toggleMoney()"></i>
        </div>
        <p>Total Sales</p>
    </span>
</li>

            </ul>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Recent Orders</h3>
                        <i class='bx bx-search' ></i>
                        <i class='bx bx-filter' ></i>
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
                            <tr>
                                <td>
                                    <img src="https://placehold.co/600x400/png">
                                    <p>Micheal John</p>
                                </td>
                                <td>18-10-2021</td>
                                <td><span class="status completed">Completed</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="https://placehold.co/600x400/png">
                                    <p>Ryan Doe</p>
                                </td>
                                <td>01-06-2022</td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="https://placehold.co/600x400/png">
                                    <p>Tarry White</p>
                                </td>
                                <td>14-10-2021</td>
                                <td><span class="status process">Process</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="https://placehold.co/600x400/png">
                                    <p>Selma</p>
                                </td>
                                <td>01-02-2023</td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="https://placehold.co/600x400/png">
                                    <p>Andreas Doe</p>
                                </td>
                                <td>31-10-2021</td>
                                <td><span class="status completed">Completed</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="todo">
                    <div class="head">
                        <h3>Todos</h3>
                        <i class='bx bx-plus icon'></i>
                        <i class='bx bx-filter' ></i>
    
                    </div>
                    <ul class="todo-list">
                        <li class="completed">
                            <p>Check Inventory</p>
                            <i class='bx bx-dots-vertical-rounded' ></i>
                        </li>
                        <li class="completed">
                            <p>Manage Delivery Team</p>
                            <i class='bx bx-dots-vertical-rounded' ></i>
                        </li>
                        <li class="not-completed">
                            <p>Contact Selma: Confirm Delivery</p>
                            <i class='bx bx-dots-vertical-rounded' ></i>
                        </li>
                        <li class="completed">
                            <p>Update Shop Catalogue</p>
                            <i class='bx bx-dots-vertical-rounded' ></i>
                        </li>
                        <li class="not-completed">
                            <p>Count Profit Analytics</p>
                            <i class='bx bx-dots-vertical-rounded' ></i>
                        </li>
                    </ul>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    

    <script src="script.js"></script>
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

document.addEventListener("DOMContentLoaded", function () {
    const profileIcon = document.getElementById("profileIcon");
    const profileMenu = document.getElementById("profileMenu");

    profileIcon.addEventListener("click", function (event) {
        event.preventDefault();
        profileMenu.style.display = (profileMenu.style.display === "block") ? "none" : "block";
    });

    // Fechar menu ao clicar fora dele
    document.addEventListener("click", function (event) {
        if (!profileIcon.contains(event.target) && !profileMenu.contains(event.target)) {
            profileMenu.style.display = "none";
        }
    });
});

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