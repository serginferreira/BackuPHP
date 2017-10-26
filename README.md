# BackuPHP
BackuPHP -> Script para automatizar o Backup de Arquivos físicos de uma Aplicação Web, bem como criar um arquivo Dump com backup da estrutura de dados do BD. (Aconselhável MySQL).

# Configurações Iniciais (Index.php);

1) DIRETÓRIO QUE VOCE DESEJA COPIAR:
Deve ser setado manualmente respeitando o ./ se for raiz, se estiver em um nível hierárquico abaixo basta usar o ../

2) CONFIGURAÇÃO DE ACESSO AO SGBD 
Deve-se modificar as variáveis $hostname_conexao, $database_conexao,  $username_conexao, $password_conexao setando os valores corretos para acesso ao SGBD.

3) VERIFICAR O $path do MySQLDump
Deve ser colocado de acordo com o caminho de seu servidor.
Se estiver instalado em uma máquina linux basta mudar o comando para apenas "mysqldump"


# Planejamento:

Executar o script duas vezes (crontab) em horários na madrugada, deste modo se no primeiro horário acontecer alguma falha será possível no segundo horário uma nova tentativa.
Em caso de backups realizados com êxito o script simplesmente não executa mais de uma vez no mesmo dia.
