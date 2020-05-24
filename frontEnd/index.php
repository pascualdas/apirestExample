<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*  
    ######################################################################################################################################
    CONCEPTOS:
    ######################################################################################################################################
    - API: APLICATION PROGRAMING INTERFACE      -> 
    - REST : REPRESENTATION STATE TRANSFERER    -> Representacion de transferencia de estado.
    - Repuestas en XML, TXT O JSON (Javascript object notation). Ejemplo JSON:

        [
            {
                "id"    : 1,
                "name"  : "PascualDas"
            }
        ]

    ######################################################################################################################################
    ######################################################################################################################################
    - TIPOS DE APIS:
    ######################################################################################################################################
        - LOCALES Y REMOTAS:
        * LOCALES: Servicios que se comunican internamente con el entorno, eje: solicitud para enviar una notificación dentro de una app.
        * REMOTAS: Servicios externos a los que se accede para extraer datos de BD o solicitar hacer una acción. Usan webservices x http.
            - SOAP: modelo viejito.
            - REST: arquitectura mas utilizada actualmente. (RESTFULL).
    
    ######################################################################################################################################
    ######################################################################################################################################
    - API REST: 
    ######################################################################################################################################
    Basicamente es un modulo del lado del servidor que permite conectar al cliente web mediante ajax con la BD. 
    El cliente web AJAX hace una peticion al backend controller y este responde con datos JSON traidos de la BD o realiza 
    una accion requerida. Es un WEBSERVICE que permite acceder al mismo desde cualquier tecnologia que pueda utilizar el protocolo HTTP. (Apps, desktop, web etc).

        - FRONTEND -> AJAX -> SOLICITA AL-> BACKEND PHP

        - ACCESO: 
            * PUBLICO:  Disponibles sin autenticación.
            * PRIVADO:  Se requiere de un logueo y esta genera un token, cuando se hace una peticion se verifica el token y se procede
                        a dar respuesta a la petición. El formato de los Tokens en API REST es JWT.
        
        - URI (): Cada recurso (servicio) tiene un identificador unico de acceso: http://www.pascualdas.com/paises/colombia/
        - ENNPOINT: URL por la que se consulta el.

        - CODIGOS DE RESPUESTA DE APIS REST: https://es.wikipedia.org/wiki/Anexo:C%C3%B3digos_de_estado_HTTP
            * 1xx : Informacion.
            * 2XX : Procesos exitosos. (201: Creado exitoso, 204: creado ok pero no hay nada para retornar).
            * 3XX : Redirecciones. (301: Redireccion permanente, 303: Redireccion temporal.) Se usan poco.
            * 4XX : Error en el cliente, ejemplo: (404: Solicitud a recurso que no existe, 403: Solcitud a recurso que no tienes permiso.)
            * 5XX : Errores en el backend.

        - METODOS HTTP:
            * GET   : Metodo para pedir información.        SELECT
            * POST  : Metodo para enviar informacion.       CREATE
            * PUT   : Metodo para actualizar información.   UPDATE
            * DELETE: Metodo para eliminar.                 DELETE

        - BUENAS PRACTICAS:
            * HATEOAS:      La API se describe, cada recurso da información del recurso siguiente o de la cantidad de recursos totales que hay.
            * Seguridad:    El acceso PUBLICO O PRIVADO.
            * Testear:      Se debe testear un API antes de liberarla y documentarla bien para su buen uso.
            * Documentar:   Documentar muy bien el funcionamiento.
    ######################################################################################################################################    
    


*/
// https://code.visualstudio.com/docs/editor/emmet
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API REST</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
</br>

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>ELIGE LA OPCION A REALIZAR</h1>
        </div>    
    </div>
    </br> 
    <div class="row">
        <div class="col-md-3">
            <a class="btn btn-lg btn-primary" href="#" onclick="consultarBlog();">CONSULTAR POST</a>
        </div>
        <div class="col-md-3">
            <a class="btn btn-lg btn-success" href="#" onclick="guardarBlog('Primer Post', 'published', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '1102714782');">CREAR POST</a>
        </div>
        <div class="col-md-3">
            <a id="btn_modificar" class="btn btn-lg btn-warning" style="color:white !important;" href="#" onclick="modificarBlog('1', 'TITULO MODIFICADO', 'draft', 'Contenido modificado api rest.', '11111111');">MODIFICAR POST</a>
        </div>
        <div class="col-md-3">
            <a id="btn_eliminar" class="btn btn-lg btn-danger" href="#" onclick="eliminarBlog('6')">ELIMINAR POST</a>
        </div>    
    </div>
    </br>
    </br>
    <div class="row border">
        <div id="respuesta" class="col-md-12 text-center">
            RESPUESTA API REST JSON HERE
        </div>    
    </div>                 
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<script>

urlAPIRest = 'http://localhost/rest/backEnd/post.php';
$( document ).ready(function() {
    consultarBlog();
});



function consultarBlog(id=''){

    $.ajax({
        method: "GET",
        //dataType : 'json',
        url: urlAPIRest,
        data: {
            id:id
        },
        success : function(result){
            
            //$('div#respuesta').html(result);
            var datos = JSON.parse(result);
            //console.log(datos.id);
            html = '<table class="table table-striped table-hover p-1 m-1"><tr><th>REG NRO</th><th>ID</th><th>TITLE</th><th>STATUS</th><th>CONTENT</th><th>USER ID</th></tr>';

            // $.each(data, function(i, item) {
            //     alert(item.PageName);
            // });
            lastId = '1';
             $.each(datos, function(key, value){ 
                html+=("<tr><td>"+key+"</td><td>"+value.id+"</td><td>"+value.title+"</td><td>"+value.status+"</td><td>"+value.content+"</td><td>"+value.user_id+"</td></tr>");
                lastId = value.id;
             });
             html+= "</table>";

             $('#btn_eliminar').removeAttr("onclick");
             $('#btn_eliminar').attr("onclick", "eliminarBlog('"+lastId+"')");
             $('#btn_modificar').removeAttr("onclick");
             $('#btn_modificar').attr("onclick", "modificarBlog('"+lastId+"', 'TITULO MODIFICADO', 'draft', 'Contenido modificado api rest.', '11111111')");

             $('div#respuesta').html(html);

        }
        // ,error : function(xhr, status) {
        //     $('div#respuesta').html('<h1>Disculpe, existió un problema</h1>');
        // }    
    });    

}

function guardarBlog(title, status, content, user_id){

    $.ajax({
        method: "POST",
        url: urlAPIRest,
        data: {
            title: title, 
            status: status,
            content: content, 
            user_id: user_id
        },
        success : function(result){
            
            $('div#respuesta').html(result);

            var datos = JSON.parse(result);
            html = '<table class="table table-striped table-hover p-1 m-1"><tr><th>ID</th><th>TITLE</th><th>STATUS</th><th>CONTENT</th><th>USER ID</th></tr>';
            html+= '<tr><td colspan="5" align="center" style="color:red;"><strong>REGISTRO CREADO!!!!</strong></td></tr><tr>';
            html+= ("<tr><td><strong style='color:red;''>"+datos.id+"</strong></td><td>"+datos.title+"</td><td>"+datos.status+"</td><td>"+datos.content+"</td><td>"+datos.user_id+"</td></tr>");
            html+= "</tr></table>";
            $('div#respuesta').html(html);


        }
        // ,error : function(xhr, status) {
        //     $('div#respuesta').html('<h1>Disculpe, existió un problema</h1>');
        // }  
    });    

}

function modificarBlog(id, title, status, content, user_id){

    $.ajax({
        method: "PUT",
        url: urlAPIRest+'?id='+id+'&title='+title+'&status='+status+'&content='+content+"&user_id="+user_id,
        success : function(result){
            $('div#respuesta').html(result);
        }
        // ,error : function(xhr, status) {
        //     $('div#respuesta').html('<h1>Disculpe, existió un problema</h1>');
        // }  
    }); 
    consultarBlog();

}

function eliminarBlog(id){

    $.ajax({
        method: "DELETE",
        url: urlAPIRest+'?id='+id,
        success : function(result){
            $('div#respuesta').html(result);
        }
        // ,error : function(xhr, status) {
        //     $('div#respuesta').html('<h1>Disculpe, existió un problema</h1>');
        // }  
    });
    consultarBlog();

}

</script>

</body>
</html>