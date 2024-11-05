<?php
require 'banco.php';
 
$codigo = null;
 
if (!empty($_GET['id'])) {
    $codigo = $_GET['id'];
}
 
if ($codigo === null) {
    header("Location: index.php");
    exit;
}
 
if (!empty($_POST)) {
    $nomeErro = $enderecoErro = $emailErro = $idadeErro = $foneErro = null;
   
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $email = $_POST['email'];
    $idade = $_POST['idade'];
    $fone = $_POST['fone'];
    $validacao = true;
 
    if (empty($nome)) {
        $nomeErro = 'Por favor, digite o nome!';
        $validacao = false;
    }
 
    if (empty($email)) {
        $emailErro = 'Por favor, digite o email!';
        $validacao = false;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErro = 'Por favor, digite um email válido!';
        $validacao = false;
    }
 
    if (empty($endereco)) {
        $enderecoErro = 'Por favor digite o endereço!';
        $validacao = false;
    }
 
    if (empty($idade)) {
        $idadeErro = 'Por favor preencha o campo!';
        $validacao = false;
    }
 
    // Validar fone (telefone)
    if (empty($fone)) {
        $foneErro = 'Por favor digite o telefone!';
        $validacao = false;
    } else if (!preg_match('/^\d{10,11}$/', $fone)) {
        $foneErro = 'Por favor digite um número de telefone válido (10 ou 11 dígitos)';
        $validacao = false;
    }
 
    // Update data
    if ($validacao) {
        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE tb_alunos SET nome = ?, endereco = ?, fone = ?, email = ?, idade = ? WHERE codigo = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($nome, $endereco, $fone, $email, $idade, $codigo));
        Banco::desconectar();
        header("Location: index.php");
    }
} else {
    $pdo = Banco::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM tb_alunos WHERE codigo = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($codigo));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    $nome = $data['nome'];
    $endereco = $data['endereco'];
    $email = $data['email'];
    $idade = $data['idade'];
    $fone = $data['fone']; // Recuperando o telefone
    Banco::desconectar();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Atualizar Contato</title>
</head>
<body>
<div class="container">
    <div class="span10 offset1">
        <div class="card">
            <div class="card-header">
                <h3 class="well">Atualizar Contato</h3>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="update.php?id=<?php echo $codigo; ?>" method="post">
                    <div class="control-group <?php echo !empty($nomeErro) ? 'error' : ''; ?>">
                        <label class="control-label">Nome</label>
                        <div class="controls">
                            <input name="nome" class="form-control" size="50" type="text" placeholder="Nome" value="<?php echo !empty($nome) ? $nome : ''; ?>">
                            <?php if (!empty($nomeErro)): ?>
                                <span class="text-danger"><?php echo $nomeErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
 
                    <div class="control-group <?php echo !empty($enderecoErro) ? 'error' : ''; ?>">
                        <label class="control-label">Endereço</label>
                        <div class="controls">
                            <input name="endereco" class="form-control" size="80" type="text" placeholder="Endereço" value="<?php echo !empty($endereco) ? $endereco : ''; ?>">
                            <?php if (!empty($enderecoErro)): ?>
                                <span class="text-danger"><?php echo $enderecoErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
 
                    <div class="control-group <?php echo !empty($emailErro) ? 'error' : ''; ?>">
                        <label class="control-label">Email</label>
                        <div class="controls">
                            <input name="email" class="form-control" size="40" type="text" placeholder="Email" value="<?php echo !empty($email) ? $email : ''; ?>">
                            <?php if (!empty($emailErro)): ?>
                                <span class="text-danger"><?php echo $emailErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
 
                    <div class="control-group <?php echo !empty($idadeErro) ? 'error' : ''; ?>">
                        <label class="control-label">Idade</label>
                        <div class="controls">
                            <input name="idade" class="form-control" size="80" type="text" placeholder="Idade" value="<?php echo !empty($idade) ? $idade : ''; ?>">
                            <?php if (!empty($idadeErro)): ?>
                                <span class="text-danger"><?php echo $idadeErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
 
                    <!-- Novo campo de fone -->
                    <div class="control-group <?php echo !empty($foneErro) ? 'error' : ''; ?>">
                        <label class="control-label">Telefone</label>
                        <div class="controls">
                            <input name="fone" class="form-control" size="40" type="text" placeholder="Telefone (10 ou 11 dígitos)" value="<?php echo !empty($fone) ? $fone : ''; ?>">
                            <?php if (!empty($foneErro)): ?>
                                <span class="text-danger"><?php echo $foneErro; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
 
                    <br />
                    <div class="form-actions">
                        <button type="submit" class="btn btn-warning">Atualizar</button>
                        <a href="index.php" class="btn btn-default">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
 
