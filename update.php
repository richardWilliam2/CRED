<?php 
require 'banco.php';

$id = null;
if (!empty($_GET['codigo'])) { $codigo = $_REQUEST['codigo'];

}
if (null == $codigo) {
header("Location: index.php");

}
if (!empty($_POST)) {
$nomeErro = null;
$enderecoErro = null;
$telefoneErro = null;
$emailErro = null;
$idadeErro = null;
$nome = $_POST['nome'];
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$idade = $_POST['idade'];

}

?>
