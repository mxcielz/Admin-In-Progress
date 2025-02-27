<!-- filepath: /c:/xampp/htdocs/SITE WB/contact.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Contato</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="contact-form-container">
        <h2>Entre em Contato</h2>
        <form action="process_contact.php" method="POST">
            <div class="form-group">
                <label for="company_name">Nome da Empresa:</label>
                <input type="text" id="company_name" name="company_name" required>
            </div>
            <div class="form-group">
                <label for="contact_name">Nome do Contato:</label>
                <input type="text" id="contact_name" name="contact_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Telefone:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="message">Mensagem:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>