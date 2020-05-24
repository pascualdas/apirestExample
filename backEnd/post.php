<?php
// VALIDACION DEL METODO QUE SE UTILIZA PARA EL HTTP REQUEST
// $metodoRequestsHTTP = $_SERVER['REQUEST_METHOD'];
// switch ($metodoRequestsHTTP) {
// case 'POST':
//     echo 'REQUEST DE TIPO POST';
//     break;
// case 'GET':
//     echo 'REQUEST DE TIPO GET';
//     break;
// case 'PUT':
//     echo 'REQUEST DE TIPO PUT';
//     break;
// case 'DELETE':
//     echo 'REQUEST DE TIPO DELETE';
//     break;
// default:
//     echo 'REQUEST DESCONOCIDA';
// }
// DATOS DE CONEXION A BD
include "config.php";
// FUNCIONALIDADES PARA CRUD EN BD CON PDO MYSQL
include "utils.php";


$dbConn =  connect($db);
// METODO PARA MOSTRAR DATOS DE UN POST O DE TODOS LOS POST
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['id']) && $_GET['id']!='')
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * FROM posts where id=:id");
      $sql->bindValue(':id', $_GET['id']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
    else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM posts");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
  }


  // METODO PARA GUARDAR UN NUEVO POST
}else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $input = $_POST;
    $sql = "INSERT INTO posts
          (title, status, content, user_id)
          VALUES
          (:title, :status, :content, :user_id)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    if($postId)
    {
      $input['id'] = $postId;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
   }

   // MODIFICAR GET
}else if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $input = $_GET;
    $postId = $input['id'];
    $fields = getParams($input);

    $sql = "
          UPDATE posts
          SET $fields
          WHERE id='$postId'
           ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("HTTP/1.1 200 OK");
    echo "<p align='center'> ULTIMO REGISTRO ACTUALIZADO: ".$postId.".</p>";
    exit();




  // METODO PARA BORRAR DE BD
}else if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){

  $id = $_GET["id"];
  $statement = $dbConn->prepare("DELETE FROM posts where id=:id");
  $statement->bindValue(':id', $id);
  $statement->execute();
  header("HTTP/1.1 200 OK");
  
  echo "<p align='center'> ULTIMO REGISTRO ELIMINADO: ".$id.".</p>";
  //echo json_encode(array("id"=>$id, "estado"=>'deleted'));
	exit();
}else{
  //En caso de que ninguna de las opciones anteriores se haya ejecutado
  header("HTTP/1.1 400 Bad Request");
  //var_dump($_SERVER['REQUEST_METHOD'] );
}




?>