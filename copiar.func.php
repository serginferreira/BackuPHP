<?php
   function copiar_diretorio($diretorio, $destino, $ver_acao){
       
      if ($destino{strlen($destino) - 1} == '/'){
         $destino = substr($destino, 0, -1);
        }
      if (!is_dir($destino)){
         if ($ver_acao){
            echo "<hr/>"; 
            echo "<b>[Diretório Destino]</b>: Criando ".$destino."<br/>"; 
            }
         mkdir($destino, 0755);
      }
       
     try {    
          $folder = opendir($diretorio);  
          echo "<hr/>";                

          echo "<table border='1 style='width:30%'>
                <tr>    
                    <th colspan='2'>Copiando {$diretorio} ...</th>    
                </tr>
                <tr>    
                    <th>Arquivo</th>
                    <th>Destino</th>     
                </tr>";

          while ($item = readdir($folder)){

             if ($item == '.' || $item == '..'){
                continue;
                }
                if (!is_dir("{$diretorio}/{$item}"))
                {             

                if ($ver_acao){
                   echo"
                    <tr>    
                    <th>{$item}</th>
                    <th>{$destino}</th>     
                    </tr>";
                }

                copy("{$diretorio}/{$item}", "{$destino}/{$item}");
                }else{
                copiar_diretorio("{$diretorio}/{$item}", "{$destino}/{$item}", $ver_acao);
                }     
              
          }
          echo "</table>";
          gerarLog("ok", "[Backup Fisico]: Dados copiados em {$destino} com sucesso"); 
         
     } catch (Exception $erro) {         
         
          echo "Erro na Cópia dos Arquivos";
          gerarLog("falha", "[Backup Fisico]: Erro na cópia de dados em {$destino}");    
     }     
    

   }

?>