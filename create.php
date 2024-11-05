<?php
require 'banco.php';
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeErro = null;
    $enderecoErro = null;
    $emailErro = null;
    $idadeErro = null;
    $foneErro = null;
    $validacao = true;
 
    if (!empty($_POST)) {
        if (!empty($_POST['nome'])) {
            $nome = $_POST['nome'];
        } else {
            $nomeErro = 'Por favor digite o seu nome!';
            $validacao = false;
        }
 
        if (!empty($_POST['endereco'])) {
            $endereco = $_POST['endereco'];
        } else {
            $enderecoErro = 'Por favor digite o seu endereço!';
            $validacao = false;
        }
 
        if (!empty($_POST['email'])) {
            $email = $_POST['email'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErro = 'Por favor digite um endereço de email válido!';
                $validacao = false;
            }
        } else {
            $emailErro = 'Por favor digite um endereço de email!';
            $validacao = false;
        }
 
        if (!empty($_POST['idade'])) {
            $idade = $_POST['idade'];
        } else {
            $idadeErro = 'Por favor selecione um campo!';
            $validacao = false;
        }
 
        // Validando o fone (telefone)
        if (!empty($_POST['fone'])) {
            $fone = $_POST['fone'];
            // Adiciona uma validação simples para o fone
            if (!preg_match('/^\d{10,11}$/', $fone)) {
                $foneErro = 'Por favor digite um número de telefone válido (10 ou 11 dígitos)';
                $validacao = false;
            }
        } else {
            $foneErro = 'Por favor digite o seu telefone!';
            $validacao = false;
        }
    }
 
    if ($validacao) {
        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
        // Atualizando o SQL para usar o campo 'fone'
        $sql = "INSERT INTO tb_alunos (nome, endereco, email, idade, fone) VALUES (?, ?, ?, ?, ?)";
        $q = $pdo->prepare($sql);
 
        try {
            $q->execute(array($nome, $endereco, $email, $idade, $fone));
            Banco::desconectar();
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            echo "Erro ao inserir dados: " . $e->getMessage();
            Banco::desconectar();
            exit();
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Adicionar Contato</title>
</head>
<body>
<div class="container">
    <div class="span10 offset1">
        <div class="card">
            <div class="card-header">
                <h3 class="well">Adicionar Contato</h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="create.php" method="post">
                    <div class="control-group <?php echo !empty($nomeErro) ? 'error ' : ''; ?>">
                        <label class="control-label">Nome</label>
                        <div class="controls">
                            <input size="50" class="form-control" name="nome" type="text" placeholder="Nome" value="<?php echo !empty($nome) ? $nome : ''; ?>">
                            <?php if (!empty($nomeErro)): ?>
                                <span class="text-danger"><?php echo $nomeErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
 
                    <div class="control-group <?php echo !empty($enderecoErro) ? 'error ' : ''; ?>">
                        <label class="control-label">Endereço</label>
                        <div class="controls">
                            <input size="80" class="form-control" name="endereco" type="text" placeholder="Endereço" value="<?php echo !empty($endereco) ? $endereco : ''; ?>">
                            <?php if (!empty($enderecoErro)): ?>
                                <span class="text-danger"><?php echo $enderecoErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
 
                    <div class="control-group <?php echo !empty($emailErro) ? 'error ' : ''; ?>">
                        <label class="control-label">Email</label>
                        <div class="controls">
                            <input size="40" class="form-control" name="email" type="text" placeholder="Email" value="<?php echo !empty($email) ? $email : ''; ?>">
                            <?php if (!empty($emailErro)): ?>
                                <span class="text-danger"><?php echo $emailErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
 
                    <div class="control-group <?php echo !empty($idadeErro) ? 'error ' : ''; ?>">
                        <label class="control-label">Idade</label>
                        <div class="controls">
                            <input size="40" class="form-control" type="text" name="idade" id="idade" value="<?php echo !empty($idade) ? $idade : ''; ?>">
                            <?php if (!empty($idadeErro)): ?>
                                <span class="text-danger"><?php echo $idadeErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
 
                    <!-- Novo campo de fone -->
                    <div class="control-group <?php echo !empty($foneErro) ? 'error ' : ''; ?>">
                        <label class="control-label">Fone</label>
                        <div class="controls">
                            <input size="40" class="form-control" name="fone" type="text" placeholder="Fone (10 ou 11 dígitos)" value="<?php echo !empty($fone) ? $fone : ''; ?>">
                            <?php if (!empty($foneErro)): ?>
                                <span class="text-danger"><?php echo $foneErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
 
                    <div class="form-actions">
                        <br />
                        <button type="submit" class="btn btn-success">Adicionar</button>
                        <a href="index.php" class="btn btn-default">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
 
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>