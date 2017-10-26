<?php
/*
            BackuPHP -> SCRIPT PARA AUTOMATIZAR BACKUP DIÁRIO
            OBJETIVO: SCRIPT PARA BACKUP DE DIRETÓRIOS E BANCO DE DADOS;
*/


include_once("copiar.func.php");
include_once("log.func.php");


define('DIR_LOG',  'logs');
define('DIR_DADOS','dados');
define('DIR_ORIGEM', './DIRETÓRIO QUE VOCE DESEJA COPIAR'); #Deve ser alterado para diretório específico.


function data($tipo){
    
    $dia = date('d');
    $mes = date('m');
    $ano = date('Y');  
    
    switch($tipo){
        case "data":
            $data = $dia.'_'.$mes.'_'.$ano;    
        break;    
        
        case "datahora":
            $hor = date('H');
            $min = date('i');
            $seg = date('s');
            $data = $hor.':'.$min.':'.$seg;            
        break;
            
        case "arquivoLog":    
            $data = $dia.'_'.$mes.'_'.$ano.'.log';    
        break;
                
            
        default:
        echo "Tipo Data Não Definida!";                                                    
    }
 
    return $data;
}
 


function verificarDiretorio($diretorio){

    if(!file_exists($diretorio)){
        
        try {
            mkdir($diretorio, 0777); 
            echo "<b>[Inicialização]</b>: Criando Diretório - ".$diretorio."<br/>";
        }
        catch (Exception $erro) {
            echo "Erro Criar Diretório: ".$erro->getMessage();
            gerarLog("falha", "[Inicialização]: Erro ao Criar {$diretorio}");
        }        
    } else {
            echo "<b>[Inicialização]</b>: Diretório OK - ".$diretorio."/<br/>";
    } 
}

function verificarArquivoDiario($arquivo){

    if(file_exists($arquivo)){
        try {                  
            $arq      = fopen($arquivo, "r");
            $conteudo = fgets($arq, 16);
            
                    if($conteudo != "OK")
                    {
                        fclose($arq);
                        unlink($arquivo);
                        
                    } else {
                        fclose($arq);
                        exit("<br/><b>[Status Backup]</b>: Já foi realizado com sucesso hoje :)");
                    }
            
        }
        catch (Exception $erro)
        {
        echo $erro->getFile().': [Erro verificarArquivoDiario]: '.$erro->getMessage();
        gerarLog("falha", "[Inicialização DAT]: Erro ao Acessar {$arquivo}");    
        }
            
    } else {
            echo "<b>[Inicialização]</b>: Criando Arquivo Status - ".$arquivo."<br/>";
    } 
}


function dumpSQL(){
    
    $hostname_conexao = "localhost"; 
    $database_conexao = "seu Banco de Dados";
    $username_conexao = "seu Usuário no SGBD";      
    $password_conexao = "sua Senha"; 
    
    try {  
        #Path deve ser alterado...
        $path= "C:/wamp_server/bin/mysql/mysql5.7.14/bin/mysqldump.exe";
        
        $cmd = $path." -h ".$hostname_conexao." -u ".$username_conexao." ".$database_conexao." > ".DIR_DADOS."/".data("data")."/SQL/dump.sql -p".$password_conexao;
        
        system($cmd);     
        gerarLog("ok", "[Backup SQL]: Sucesso no DumpSQL");    
    } 
    catch (Exception $erro)
    {
        echo $erro->getFile().': [Erro DumpSQL]: '.$erro->getMessage();
        gerarLog("falha", "[Backup SQL]: Falha no DumpSQL"); 
        die;
    }
}

try {      
            #Verificar existência de Diretórios            
            verificarDiretorio(DIR_LOG);
            verificarDiretorio(DIR_DADOS);
            verificarDiretorio(DIR_DADOS."/".data("data"));
            verificarDiretorio(DIR_DADOS."/".data("data")."/SQL");                        
            verificarArquivoDiario(DIR_DADOS."/".data("data")."/status.dat");
    
            #Deve ser alterado o DIR_ORIGEM no topo do documento.
            copiar_diretorio(DIR_ORIGEM, "./".DIR_DADOS."/".data('data')."/", true);
            copiar_diretorio(DIR_ORIGEM, "./".DIR_DADOS."/".data('data')."/", true);                       
    
            dumpSQL();
    
            $arq      = fopen(DIR_DADOS."/".data("data")."/status.dat", "w");
            $conteudo = fwrite($arq, "OK");
            fclose($arq);
            
    } 
    catch (Exception $erro)
    {
        echo $erro->getFile().': #'.$erro->getLine().' - '.$erro->getMessage();
        
            gerarLog("falha", "[Inicialização]: Falha Detectada:".$erro->getMessage()); 
        
            $arq      = fopen(DIR_DADOS."/".data("data")."/status.dat", "w");
            $conteudo = fwrite($arq, "NÃO_OK");        
            fclose($arq);
    }

echo "\nFinalizando aplicação..\n";
die;
?>
