<?php
function escreverLog($arquivo, $mensagem)
{    
    $dataHora = data("datahora"); #Data e Hora Padrão
    
    if(!$arquivo)
    {
        throw new Exception('Parâmetro com nome do arquivo não foi informado!');
    }
    else if(!file_exists($arquivo))
    {
        
            try { 
            echo "<br/><b>[Log]:</b> Criando Arquivo: ".$arquivo;       
            $arq  = fopen($arquivo, "w");
                    fwrite($arq, $dataHora.'-'.$mensagem."|\n");
                    fclose($arq);
            echo "<br/><b>[Log]:</b> Dados Inseridos: ".$mensagem."<br/>";   

            } catch (Exception $e){
                echo "[Atenção]: Impossivel Criar Arquivo {$arquivo} Erro:".$e->GetMessage();
            }    
        
    }
    else if(!$retorno = @file_get_contents($arquivo))
    {
            throw new Exception('Impossível ler o arquivo');
    }
    else 
    {
       try { 
            echo "<br/><b>[Log]:</b> Abrindo Arquivo: ".$arquivo;       
            $arq=fopen($arquivo, "a");
            fwrite($arq, $dataHora.'-'.$mensagem."|\n");
            fclose($arq);
            echo "<br/><b>[Log]:</b> Dados Inseridos: ".$mensagem."<br/><br/>";    
           
       } catch (Exception $e){
            echo "[Atenção]: Impossivel Abrir Arquivo {$arquivo} Erro:".$e->GetMessage();
       }         
    }  
    
}

function gerarLog($tipo, $mensagem)
{            
    

    
    if($tipo == "ok")
    {   
       $caminhoArq = 'logs/sucesso_'.data("arquivoLog"); #nome do arquivo com caminho        
       escreverLog($caminhoArq, $mensagem);        
    } 
    else 
    if($tipo == "falha")
    {        
       $caminhoArq = 'logs/falha_'.data("arquivoLog"); #nome do arquivo com caminho                   
       escreverLog($caminhoArq, $mensagem);         
    }

}

?>