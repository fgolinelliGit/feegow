<?php

require_once "funcoes.php";

require_once "cabecalho.php";

$especialidade_id = $_POST["especialidade_id"];
$profissional_id = $_POST["profissional_id"];

echo "Preencha com seus dados<br>";

MontaForm($especialidade_id,$profissional_id);

//MontaDisponibilidadeAgenda();

//MontaProfissionalEspecialista($_POST["profissional_id"]);

require_once "rodape.php";




function MontaForm($especialidade_id,$profissional_id){
	
?>
<div class='w3-half w3-margin'>
<form action='agendar.php' method='post' enctype='multipart/form-data'>
<input type='hidden' name='especialidade_id' value='<?=$especialidade_id?>'>
<input type='hidden' name='profissional_id' value='<?=$profissional_id?>'>
Nome<br>
<input type="text" name="txtNome"><br>
Data Nascimento<br>
<input type="date" name="dtDataNascimento"><br>
Como conheceu<br>
<select name="slcComoConheceu">
<?=BuscaComoConheceu();?>
</select><br>
CPF<br>
<input type="number" name="numCPF" min="1" max="99999999999"><br>
<input type="submit" value="Salvar">
</form>
</div>
<?php
	
}




function BuscaComoConheceu(){

	//Define os dados de cabeçalho da requisição
	$cabecalho = array(
	'Content-Type: application/json',
	'x-access-token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJmZWVnb3ciLCJhdWQiOiJwdWJsaWNhcGkiLCJpYXQiOiIxNy0wOC0yMDE4IiwibGljZW5zZUlEIjoiMTA1In0.UnUQPWYchqzASfDpVUVyQY0BBW50tSQQfVilVuvFG38'
	);
	 
	// URL para onde será enviada a requisição GET
	$url_feed = "https://api.feegow.com.br/api/patient/list-sources";
	 
	// Inicia a sessão cURL
	$ch = curl_init();

	// Informa a URL onde será enviada a requisição
	curl_setopt($ch, CURLOPT_URL, $url_feed);

	// Informa cabecalho
	curl_setopt($ch, CURLOPT_HTTPHEADER, $cabecalho);
	
	// Se true retorna o conteúdo em forma de string para uma variável
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Envia a requisição
	$result = curl_exec($ch);
	 
	// Finaliza a sessão
	curl_close($ch);
	
	//echo substr($result,0, 50000)."<br><br>";

	// Separar área de sucesso do restante
	$array = explode('content":',$result);
	
	// Se o retorno do API for com sucesso
	if (strstr($array[0], 'true', true)){
		
		// Separação grande
		$array2 = explode('},{',$array[1]);
		
		foreach ($array2 as $value2) {
			
			//echo $value2."<br><br><hr>";
			
			// Separa itens
			$array3 = explode(',',$value2);
			
				$array4 = explode(':',$array3[0]);
				
				$origem_id = $array4[1];
				
				$array5 = explode(':',$array3[1]);
				
				$nome_origem = $array5[1];
			
				echo "<option value=$origem_id>";
				echo "$nome_origem</option>";
			
		}
	}
}





function MontaDisponibilidadeAgenda($profissional_id){

	//Define os dados de cabeçalho da requisição
	$cabecalho = array(
	'Content-Type: application/json',
	'x-access-token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJmZWVnb3ciLCJhdWQiOiJwdWJsaWNhcGkiLCJpYXQiOiIxNy0wOC0yMDE4IiwibGljZW5zZUlEIjoiMTA1In0.UnUQPWYchqzASfDpVUVyQY0BBW50tSQQfVilVuvFG38'
	);
	 
	// URL para onde será enviada a requisição GET
	$url_feed = "https://api.feegow.com.br/api/appoints/available-schedule?tipo=E&espacialidade_id=&profissional_id=$profissional_id";
	 
	// Inicia a sessão cURL
	$ch = curl_init();

	// Informa a URL onde será enviada a requisição
	curl_setopt($ch, CURLOPT_URL, $url_feed);

	// Informa cabecalho
	curl_setopt($ch, CURLOPT_HTTPHEADER, $cabecalho);
	
	// Se true retorna o conteúdo em forma de string para uma variável
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Envia a requisição
	$result = curl_exec($ch);
	 
	// Finaliza a sessão
	curl_close($ch);
	
	echo substr($result,0, 50000)."<br><br>";

	// Separar área de sucesso do restante
	$array = explode('content":',$result);
	
	// Se o retorno do API for com sucesso
	if (strstr($array[0], 'true', true)){
		
		// Separa por médico e sua especialidade
		$array2 = explode(']},{',$array[1]);
		
		//echo $array2[0]."<br><br><hr>";
		//echo $array2[1]."<br><br><hr>";
		
		foreach ($array2 as $value2) {
			
			//echo $value2."<br><br><hr>";
			
			// Separa o médico da especialidade
			$array3 = explode('especialidades":[',$value2);
			
			if ( !strpos($array3[0],'"nome":null,') && !strpos($array3[1],'especialidade_id":null')){	
			
				//echo $array3[0]."<br><br><hr>";
				//echo $array3[1]."<br><br><hr>";
				
				// Separa campos da área da especialidade
				$array4 = explode(',',$array3[1]);
				
				// Separa id especialidade
				$array5 = explode(':',$array4[0]);
				
				// Apenas as especialidade recebidas
				if ( $array5[1] == $especialidade_id ){
					
					// Separa dados do especialista
					$array6 = explode(',',$array3[0]);
					
					foreach ($array6 as $value6) {
						
						$array7 = explode(':',$value6);
						
						//echo "".$value6."<br><br><hr>";

						if( $array7[0] == '"nome"' ){
							
							$nome = $array7[1];
							$codEspecialidade = $array5[1];
						 
						}
						if( $array7[0] == '"foto"' ){
							
							$array8 = explode('/',$array7[2]);
							
							//echo count($array8);
							
							$foto = str_replace('"','',$array8[count($array8)-1]);
						 
						}						
						if( $array7[0] == '"conselho"' ){
						
							$tipoDeConselho = $array7[1];
						 
						}
						if( $array7[0] == '"documento_conselho"' ){
						
							$numeroRegistro = $array7[1];
							
						}
						
					}
					
					if ( isset($nome) ){
						
						echo "<div class='w3-half w3-margin'>";
						echo "<form action='agendamento.php' method='post' enctype='multipart/form-data'>";
						echo "<input type='hidden' name='especialidade_id' value='".$_POST["especialidade_id"]."'>";
						echo "<table class='w3-container w3-card-4' width=400 border=0>";
						echo "<tr><td><img class='w3-circle' src='https://clinic7.feegow.com.br/uploads/105/Perfil/$foto' width='150' height='50'></td></tr>";
						echo "<tr><td>Nome: $nome</td></tr>";
						echo "<tr><td>$codEspecialidade</td></tr>";
						echo "<tr><td>$tipoDeConselho : $numeroRegistro</td></tr>";
						echo "<tr><td><input type=submit value=Agendar></td></tr></table>";
						echo "</form>";
						echo "</div>";
						
					}
				}
			}
		}
	}
}



function MontaProfissionalEspecialista($profissional_id){

	//Define os dados de cabeçalho da requisição
	$cabecalho = array(
	'Content-Type: application/json',
	'x-access-token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJmZWVnb3ciLCJhdWQiOiJwdWJsaWNhcGkiLCJpYXQiOiIxNy0wOC0yMDE4IiwibGljZW5zZUlEIjoiMTA1In0.UnUQPWYchqzASfDpVUVyQY0BBW50tSQQfVilVuvFG38'
	);
	 
	// URL para onde será enviada a requisição GET
	$url_feed = "https://api.feegow.com.br/api/professional/search?profissional_id=$profissional_id";
	 
	// Inicia a sessão cURL
	$ch = curl_init();

	// Informa a URL onde será enviada a requisição
	curl_setopt($ch, CURLOPT_URL, $url_feed);

	// Informa cabecalho
	curl_setopt($ch, CURLOPT_HTTPHEADER, $cabecalho);
	
	// Se true retorna o conteúdo em forma de string para uma variável
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Envia a requisição
	$result = curl_exec($ch);
	 
	// Finaliza a sessão
	curl_close($ch);
	
	echo substr($result,0, 50000)."<br><br>";

	// Separar área de sucesso do restante
	$array = explode('content":',$result);
	
	// Se o retorno do API for com sucesso
	if (strstr($array[0], 'true', true)){
		
		// Separa por médico e sua especialidade
		$array2 = explode(']},{',$array[1]);
		
		//echo $array2[0]."<br><br><hr>";
		//echo $array2[1]."<br><br><hr>";
		
		foreach ($array2 as $value2) {
			
			//echo $value2."<br><br><hr>";
			
			// Separa o médico da especialidade
			$array3 = explode('especialidades":[',$value2);
			
			if ( !strpos($array3[0],'"nome":null,') && !strpos($array3[1],'especialidade_id":null')){	
			
				//echo $array3[0]."<br><br><hr>";
				//echo $array3[1]."<br><br><hr>";
				
				// Separa campos da área da especialidade
				$array4 = explode(',',$array3[1]);
				
				// Separa id especialidade
				$array5 = explode(':',$array4[0]);
				
				// Apenas as especialidade recebidas
				if ( $array5[1] == $especialidade_id ){
					
					// Separa dados do especialista
					$array6 = explode(',',$array3[0]);
					
					foreach ($array6 as $value6) {
						
						$array7 = explode(':',$value6);
						
						//echo "".$value6."<br><br><hr>";

						if( $array7[0] == '"nome"' ){
							
							$nome = $array7[1];
							$codEspecialidade = $array5[1];
						 
						}
						if( $array7[0] == '"foto"' ){
							
							$array8 = explode('/',$array7[2]);
							
							//echo count($array8);
							
							$foto = str_replace('"','',$array8[count($array8)-1]);
						 
						}						
						if( $array7[0] == '"conselho"' ){
						
							$tipoDeConselho = $array7[1];
						 
						}
						if( $array7[0] == '"documento_conselho"' ){
						
							$numeroRegistro = $array7[1];
							
						}
						
					}
					
					if ( isset($nome) ){
						
						echo "<div class='w3-half w3-margin'>";
						echo "<form action='agendamento.php' method='post' enctype='multipart/form-data'>";
						echo "<input type='hidden' name='especialidade_id' value='".$_POST["especialidade_id"]."'>";
						echo "<table class='w3-container w3-card-4' width=400 border=0>";
						echo "<tr><td><img class='w3-circle' src='https://clinic7.feegow.com.br/uploads/105/Perfil/$foto' width='150' height='50'></td></tr>";
						echo "<tr><td>Nome: $nome</td></tr>";
						echo "<tr><td>$codEspecialidade</td></tr>";
						echo "<tr><td>$tipoDeConselho : $numeroRegistro</td></tr>";
						echo "<tr><td><input type=submit value=Agendar></td></tr></table>";
						echo "</form>";
						echo "</div>";
						
					}
				}
			}
		}
	}
}



 ?>