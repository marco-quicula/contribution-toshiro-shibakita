<!DOCTYPE html>
<html lang="pt_br">

<head>
    <title>Exemplo PHP</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .info {
            margin-bottom: 20px;
        }
        .info div {
            margin-bottom: 5px;
        }
        .no-data {
            color: red;
            font-weight: bold;
        }
        .button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px 5px;
            cursor: pointer;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #ff1a1a;
        }
    </style>
</head>
<body>

<form method="post">
    <button type="submit" name="clear" class="button">Limpar Dados</button>
    <button type="submit" name="add" class="button">Adicionar Novo Registro</button>
</form>

<?php
ini_set("display_errors", 1);

echo '<div class="info">';
echo '<div><strong>Versão Atual do PHP:</strong> ' . phpversion() . '</div>';

$servername = $_ENV["SERVER_NAME"];
$username = $_ENV["MYSQL_USER"];
$password = $_ENV["MYSQL_PASSWORD"];
$database = $_ENV["MYSQL_DATABASE"];

// Criar conexão
$link = new mysqli($servername, $username, $password, $database);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

// Verificar se o botão de limpar foi clicado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clear'])) {
    $link->query("DELETE FROM dados");
    echo '<div><strong>Status:</strong> Todos os registros foram deletados</div>';
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $valor_rand1 =  rand(1, 999);
    $valor_rand2 = strtoupper(substr(bin2hex(random_bytes(4)), 1));
    $host_name = gethostname();

    $query = "INSERT INTO dados (AlunoID, Nome, Sobrenome, Endereco, Cidade, Host) VALUES ('$valor_rand1' , '$valor_rand2', '$valor_rand2', '$valor_rand2', '$valor_rand2','$host_name')";

    if ($link->query($query) === true) {
        echo '<div><strong>Status:</strong> Novo registro criado com sucesso.</div>';
    } else {
        echo '<div><strong>Error:</strong> ' . $link->error . '</div>';
    }
}
echo '<div><strong>Hora Atual:</strong> ' . date('Y-m-d H:i:s') . '</div>';
echo '<div><strong>Hostname:</strong> ' . gethostname() . '</div>';

echo '</div>';

// Recuperar todos os registros da tabela
$result = $link->query("SELECT * FROM dados");

if ($result->num_rows > 0) {
    echo '<table>';
    echo '<tr><th>AlunoID</th><th>Nome</th><th>Sobrenome</th><th>Endereco</th><th>Cidade</th><th>Host</th><th>Status</th></tr>';
    while($row = $result->fetch_assoc()) {
        $highlight = ($row['AlunoID'] == $valor_rand1) ? ' class="highlight"' : '';
        echo '<tr' . $highlight . '>';
        echo '<td>' . $row['AlunoID'] . '</td>';
        echo '<td>' . $row['Nome'] . '</td>';
        echo '<td>' . $row['Sobrenome'] . '</td>';
        echo '<td>' . $row['Endereco'] . '</td>';
        echo '<td>' . $row['Cidade'] . '</td>';
        echo '<td>' . $row['Host'] . '</td>';
        echo '<td>' . ($row['AlunoID'] == $valor_rand1 ? 'Recém Criado' : '') . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<div class="no-data">SEM DADOS</div>';
}

?>

</body>
</html>
