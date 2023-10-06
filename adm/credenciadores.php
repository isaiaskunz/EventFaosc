<?php
    session_start();
    if ($_SESSION['admin'] != TRUE){
        echo "você não tem permissão para acessar este recurso";
        exit();
    }

?>

<?php 
    include "../db.php";
    $sql = "SELECT * FROM usuario as u  where credenciador = true;";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Credenciadores</title>
        </head>
    </head>
    <body>
      <div class="container">
        <h2>Cadastro de Credenciadores</h2>

        <p><a href="../registro.php">Cadastrar novo Credenciador</a></p>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Sobrenome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody> 
                <?php
                    if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?php echo $row['id_usuario']; ?></td>
                            <td><?php echo $row['nome']; ?></td>
                            <td><?php echo $row['sobrenome']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><a class="btn btn-info" href="visualise_user.php?id=<?php echo $row['id_usuario']; ?>">Visualisar</a>&nbsp;<a class="btn btn-danger" href="edit_user.php?id=<?php echo $row['id_usuario']; ?>">Editar</a></td>
                        </tr>                       

                <?php 
                    }
                }
                ?>                
            </tbody>
        </table>
      </div>
    </body>
</html>