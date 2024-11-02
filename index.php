<?php

const DIV_END = '</div>';
const TD_OPEN = '<td>';
const TD_END = '</td>';

define('SERVER_NAME', 'mysql');
define('USERNAME', 'root');
define('PASSWORD', 'pwd010203');
define('DATABASE', 'mydb');

function connectDb()
{
    $link = new mysqli(SERVER_NAME, USERNAME, PASSWORD, DATABASE);
    if ($link->connect_errno) {
        printf("Connect failed: %s\n", $link->connect_error);
        exit();
    }
    return $link;
}

function clearData($link)
{
    $link->query("DELETE FROM dados");
    echo '<div><strong>Status:</strong> Todos os registros foram deletados</div>';
}

function addData($link)
{
    $rand_value = rand(1, 999);
    $rand_text = strtoupper(substr(bin2hex(random_bytes(4)), 1));
    $host_name = gethostname();
    $query = "INSERT INTO dados (AlunoID, Nome, Sobrenome, Endereco, Cidade, Host) VALUES ('$rand_value', '$rand_text', '$rand_text', '$rand_text', '$rand_text', '$host_name')";

    if ($link->query($query) === true) {
        echo '<div><strong>Status:</strong> New record created successfully</div>';
    } else {
        echo '<div><strong>Error:</strong> ' . $link->error . DIV_END;
    }

    return $rand_value;
}

function getPhpVersionInfo()
{
    return '<div><strong>Versão Atual do PHP:</strong> ' . phpversion() . DIV_END;
}

function getCurrentTimeInfo()
{
    return '<div><strong>Hora Atual:</strong> ' . date('Y-m-d H:i:s') . DIV_END;
}

function getHostnameInfo()
{
    return '<div><strong>Hostname:</strong> ' . gethostname() . DIV_END;
}

function displayServerInfo()
{
    echo '<div class="info">';
    echo getPhpVersionInfo();
    echo getCurrentTimeInfo();
    echo getHostnameInfo();
    echo DIV_END;
}

// Chamar a função refatorada
displayServerInfo();

function fetchAndDisplayData($link, $new_id = null)
{
    $result = $link->query("SELECT * FROM dados");
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>AlunoID</th><th>Nome</th><th>Sobrenome</th><th>Endereco</th><th>Cidade</th><th>Host</th><th>Status</th></tr>';
        while ($row = $result->fetch_assoc()) {
            $highlight = ($row['AlunoID'] == $new_id) ? ' class="highlight"' : '';
            echo '<tr' . $highlight . '>';
            echo TD_OPEN . $row['AlunoID'] . TD_END;
            echo TD_OPEN . $row['Nome'] . TD_END;
            echo TD_OPEN . $row['Sobrenome'] . TD_END;
            echo TD_OPEN . $row['Endereco'] . TD_END;
            echo TD_OPEN . $row['Cidade'] . TD_END;
            echo TD_OPEN . $row['Host'] . TD_END;
            echo TD_OPEN . ($row['AlunoID'] == $new_id ? 'Recém Criado' : '') . TD_END;
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<div class="no-data">SEM DADOS</div>';
    }
}

ini_set("display_errors", 1);
$link = connectDb();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['clear'])) {
        clearData($link);
    } elseif (isset($_POST['add'])) {
        $new_id = addData($link);
    }
}

display_info();
fetchAndDisplayData($link, isset($new_id) ? $new_id : null);
?>
