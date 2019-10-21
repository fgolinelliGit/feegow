<?php

require_once "funcoes.php";

require_once "cabecalho.php";

$especialidade_id = $_POST["especialidade_id"];

echo "Agende com um profissional<br>";

MontaProfissionalEspecialista($_POST["especialidade_id"]);

require_once "rodape.php";






function MontaProfissionalEspecialista($especialidade_id){

	//Define os dados de cabeçalho da requisição
	$cabecalho = array(
	'Content-Type: application/json',
	'x-access-token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJmZWVnb3ciLCJhdWQiOiJwdWJsaWNhcGkiLCJpYXQiOiIxNy0wOC0yMDE4IiwibGljZW5zZUlEIjoiMTA1In0.UnUQPWYchqzASfDpVUVyQY0BBW50tSQQfVilVuvFG38'
	);
	 
	// URL para onde será enviada a requisição GET
	$url_feed = "https://api.feegow.com.br/api/professional/list?especialidade_id=$especialidade_id";
	 
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
						if( $array7[0] == '"profissional_id"' ){
							
							$profissional_id = $array7[1];
						 
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
					
					// Se cadastro com id 
					if ( isset($profissional_id) ){
						
						echo "<form action='agendamento.php' method='post' enctype='multipart/form-data'>";
						echo "<input type='hidden' name='especialidade_id' value='$especialidade_id'>";
						echo "<input type='hidden' name='profissional_id' value='$profissional_id'>";
						echo "<div class='w3-third w3-margin'>";
						echo "<table class='w3-container w3-card-4' width=400 border=0>";
						echo "<tr><td><img class='w3-circle' src='https://clinic7.feegow.com.br/uploads/105/Perfil/$foto' width='150' height='50'></td></tr>";
						echo "<tr><td>Nome: $nome ($profissional_id / $especialidade_id)</td></tr>";
						echo "<tr><td>$codEspecialidade</td></tr>";
						echo "<tr><td>$tipoDeConselho : $numeroRegistro</td></tr>";
						echo "<tr><td><input type=submit value=Agendar></td></tr></table>";
						echo "</div>";
						echo "</form>";
						
					}
				}
			}
		}
	}
}



 ?>