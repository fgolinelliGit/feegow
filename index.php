<?php

// Funções usadas em vários arquivos
require_once "funcoes.php";

require_once "cabecalho.php";


echo "<form action='medico.php' method='post' enctype='multipart/form-data'>";
echo "<label>Especielidade<br>";
echo "<select name='especialidade_id'>";
MontaSelectEspecialidade();
echo "</select>";
echo "</label>";
echo "<input type=submit value=Selecionar>";
echo "</form>";


require_once "rodape.php";






function MontaSelectEspecialidade(){

	//Define os dados de cabeçalho da requisição
	$cabecalho = array(
	'Content-Type: application/json',
	'x-access-token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJmZWVnb3ciLCJhdWQiOiJwdWJsaWNhcGkiLCJpYXQiOiIxNy0wOC0yMDE4IiwibGljZW5zZUlEIjoiMTA1In0.UnUQPWYchqzASfDpVUVyQY0BBW50tSQQfVilVuvFG38'
	);
	 
	// URL para onde será enviada a requisição GET
	$url_feed = "https://api.feegow.com.br/api/specialties/list";
	 
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
	
	//echo $result."<br>";
	
	//exit();

	// Separar área de sucesso das especialidades
	$array = explode(':[',$result);
	
	// Se o retorno do API for com sucesso
	if (strstr($array[0], 'true', true)){
		
		// Limpa caracteres superfluos
		
		$array[1] = str_replace('[{','',$array[1]);
		
		$array[1] = str_replace(']}','',$array[1]);

		//$array[1] = str_replace('{','',$array[1]);

		//$array[1] = str_replace('}','',$array[1]);

		$array[1] = str_replace('"','',$array[1]);
		
		// Separar grupos
		$array2 = explode('},{',$array[1]);
		
		foreach ($array2 as $value) {
			
			$temp = "<option value='";
			
			// Separar grupo de especialidades
			$array3 = explode(',',$value);
			
			foreach($array3 as $value1){
				
				// Separar descrição do valor
				$array4 = explode(':',$value1);
				
				if ($array4[0] == "especialidade_id"){
				
					$codigo = $array4[1];
					
				}elseif ($array4[0] == "nome"){
				
					$descricao = AjustaTexto($array4[1]);
				}
				
			}
			
			$temp = $temp.$codigo."'>".$descricao."</option>";
			
			echo $temp;
			
			$temp = "";
			
		}
	}

}



 ?>