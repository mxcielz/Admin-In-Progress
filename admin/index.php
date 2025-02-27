<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WB - Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins');

        /* BASIC */
        html {
            background-color: #56baed;
        }

        body {
            font-family: "Poppins", sans-serif;
            height: 100vh;
        }

        a {
            color: #92badd;
            display:inline-block;
            text-decoration: none;
            font-weight: 400;
        }

        h2 {
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            display:inline-block;
            margin: 40px 8px 10px 8px;
            color: #cccccc;
        }

        /* STRUCTURE */
        .wrapper {
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            width: 100%;
            min-height: 100%;
            padding: 20px;
        }

        #formContent {
            -webkit-border-radius: 10px 10px 10px 10px;
            border-radius: 10px 10px 10px 10px;
            background: #fff;
            padding: 30px;
            width: 90%;
            max-width: 450px;
            position: relative;
            padding: 0px;
            -webkit-box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
            box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
            text-align: center;
        }

        #formFooter {
            background-color: #f6f6f6;
            border-top: 1px solid #dce8f1;
            padding: 25px;
            text-align: center;
            -webkit-border-radius: 0 0 10px 10px;
            border-radius: 0 0 10px 10px;
        }

        /* FORM TYPOGRAPHY */
        input[type=button], input[type=submit], input[type=reset] {
            background-color: #56baed;
            border: none;
            color: white;
            padding: 15px 80px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            text-transform: uppercase;
            font-size: 13px;
            -webkit-box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
            box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
            -webkit-border-radius: 5px 5px 5px 5px;
            border-radius: 5px 5px 5px 5px;
            margin: 5px 20px 40px 20px;
            -webkit-transition: all 0.3s ease-in-out;
            -moz-transition: all 0.3s ease-in-out;
            -ms-transition: all 0.3s ease-in-out;
            -o-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }

        input[type=button]:hover, input[type=submit]:hover, input[type=reset]:hover {
            background-color: #39ace7;
        }

        input[type=text] {
            background-color: #f6f6f6;
            border: none;
            color: #0d0d0d;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 5px;
            width: 85%;
            border: 2px solid #f6f6f6;
            -webkit-transition: all 0.5s ease-in-out;
            -moz-transition: all 0.5s ease-in-out;
            -ms-transition: all 0.5s ease-in-out;
            -o-transition: all 0.5s ease-in-out;
            transition: all 0.5s ease-in-out;
            -webkit-border-radius: 5px 5px 5px 5px;
            border-radius: 5px 5px 5px 5px;
        }

        input[type=text]:focus {
            background-color: #fff;
            border-bottom: 2px solid #5fbae9;
        }

        /* ANIMATIONS */
        .fadeInDown {
            -webkit-animation-name: fadeInDown;
            animation-name: fadeInDown;
            -webkit-animation-duration: 1s;
            animation-duration: 1s;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                -webkit-transform: translate3d(0, -100%, 0);
                transform: translate3d(0, -100%, 0);
            }
            100% {
                opacity: 1;
                -webkit-transform: none;
                transform: none;
            }
        }

        * {
            box-sizing: border-box;
        }

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

        /* Error message */
        .error-box {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .logo {
    width: 150px; /* Ajuste o tamanho da logo */
    height: auto;
    display: block;
    margin: 5px auto; /* Centraliza a imagem */
}

    </style>
</head>
<body>
    <div id="loader">
        <div class="spinner"></div>
    </div>
    <div class="wrapper fadeInDown">
    <div id="formContent">
        <!-- Adicionando a logo -->
        <img src="./imagem/logo.png" alt="Logo" class="logo">
        
        <h2 class="active">Logar Usuário</h2>
        
        <!-- Formulário de login -->
        <form action="loginprocess.php" method="POST">
            <input type="text" id="username" name="username" class="fadeIn second" placeholder="Usuário" required>
            <input type="text" id="password" name="password" class="fadeIn third" placeholder="Senha" required>
            <input type="submit" class="fadeIn fourth" value="Entrar">
            
            <?php
            if (isset($_GET['erro']) && $_GET['erro'] == 'senha_incorreta') {
                echo '<div class="error-box">Senha ou Usuário incorreta! Tente novamente.</div>';
            }
            ?>
        </form>
    </div>
</div>


    <script>
        window.addEventListener('load', () => {
            setTimeout(() => {
                const loader = document.getElementById('loader');
                loader.style.opacity = '0';
                loader.style.visibility = 'hidden';
            }, 1500); 
        });
    </script>
</body>
</html>
