<?php
require 'banco.php';
 
$id = 0;
if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}
 
if (!empty($_POST)) {
    $id = $_POST['codigo'];
 
    try {
        $pdo = Banco::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
        $sql = "DELETE FROM tb_alunos WHERE codigo = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
 
        if ($q->rowCount() > 0) {
            Banco::desconectar();
            header("Location: index.php");
            exit();
        } else {
            echo "<div class='alert alert-warning'>Nenhum registro encontrado para excluir.</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erro ao excluir dados: " . $e->getMessage() . "</div>";
    }
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Excluir Contato</title>
</head>
<body>
    <div class="container">
        <div class="span10 offset1">
            <div class="row">
                <h3 class="well">Excluir Contato</h3>
            </div>
            <form class="form-horizontal" action="delete.php" method="POST">
                <input type="hidden" name="codigo" value="<?php echo $id; ?>" />
                <div class="alert alert-danger">Deseja excluir o contato?</div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-danger">Sim</button>
                    <a href="index.php" class="btn btn-default">Não</a>
                </div>
            </form>        
        </div>  
    </div>
</body>
</html>