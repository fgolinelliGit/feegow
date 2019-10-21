<?php

require_once "funcoes.php";

require_once "cabecalho.php";


echo "Agendamento<br>";

$specialty_id = $_POST["especialidade_id"];
$profissional_id = $_POST["profissional_id"];
$name =  $_POST["txtNome"];
$cpf =  $_POST["numCPF"];
$source_id =  $_POST["slcComoConheceu"];
$birthdate =  date("Y-m-d",strtotime($_POST["dtDataNascimento"]));
$date_time = date("Y-m-d H:i:s");

InsereBD($specialty_id,$profissional_id,$name,$cpf,$source_id,$birthdate,$date_time);


require_once "rodape.php";







function InsereBD($specialty_id,$profissional_id,$name,$cpf,$source_id,$birthdate,$date_time){
	
$servername = "mysql.hostinger.com";
$database = "u139230181_evete";
$username = "u139230181_ujuze";
$password = "123456";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$strSQL = "INSERT INTO `TB_Agenda`
(`specialty_id`, `professional_id`, `name`, `cpf`, `source_id`, `birthdate`, `date_time`) 
VALUES (
$specialty_id,$profissional_id,'$name','$cpf',$source_id,'$birthdate','$date_time');";

if (mysqli_query($conn, $strSQL)) {
      echo "<hr>Seus dados foram salvos com sucesso e um integrante da nossa equipe entrará em contato para marcar seu agendamento.<br><br>";
		echo "Cód. Especialidade: ".$specialty_id ."<br>";
		echo "Cód. Profissional: ".$profissional_id ."<br>";
		echo "Nome: ".$name ."<br>";
		echo "CPF: ". $cpf ."<br>";
		echo "Cód. Como conheceu: ".$source_id ."<br>";
		echo "Data de nascimento: ".date("d/m/Y", strtotime($birthdate)) ."<br>";
} else {
      echo "Error: " . $strSQL . "<br>" . mysqli_error($conn);
}
mysqli_close($conn);

}

 ?>