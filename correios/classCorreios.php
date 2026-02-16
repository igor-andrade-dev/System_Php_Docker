<?php 

class ClassCorreios{

    public $Retorno;

    #Pesquisa de preço e prazo de encomendas do correio
    public function pesquisaPrecoPrazo($CepOrigem,$CepDestino,$Peso,$Formato,$Comprimento,$Altura,$Largura,$MaoPropria,$ValorDeclarado,$AvisoRecebimento,$Codigo,$Diametro)
    {
        $CepOrigem = str_replace('-', '', $CepOrigem);
        $CepDestino = str_replace('-', '', $CepDestino);

        $Url="https://www.cepcerto.com/ws/xml-frete/$CepOrigem/$CepDestino/$Peso/$Altura/$Largura/$Comprimento/17aa5f8571df033dbbf292886bf7f0f7aacd8ce2";
        $this->Retorno=@simplexml_load_string(@file_get_contents(@$Url));

if($Codigo == 41106){
	if(@$this->Retorno->prazopac == ""){
		 echo "CEP Não Encontrado!";
		exit();
	} 
        echo "<span><small class='text-info'>Prazo: ".$this->Retorno->prazopac . "</small></span>";

        echo "<span class='mr-2 ' style='color:green;'><small>Valor: ".$this->Retorno->valorpac." </small></span>";
}else if ($Codigo == 40010){
	if(@$this->Retorno->prazosedex == ""){
		 echo "CEP Não Encontrado!";
		exit();
	} 
	
    echo "<span><small class='text-info'>Prazo: ".$this->Retorno->prazosedex . "</small></span>";

    echo "<span class='mr-2 ' style='color:green;'><small>Valor: ".$this->Retorno->valorsedex." </small></span>";
}
        
        
        
    }
}

 ?>