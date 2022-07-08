<?php
require_once '../../../conexion/conexion.php';
include "../../../includes/redireccion_2.php";

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Alumnos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="../../../css/estilos-asistencia7.css"> -->
    <link rel="stylesheet" href="../../../css/estilos-nav2.css">

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="../../../view/asistencia/alumno/view.asistencia-curso-paralelo.php">
                    <!-- Navbar -->
                    <img src="../../../img/logo.png" alt="logo" width="40px" srcset="" class="img_logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <p class="texto">UNIDAD EDUCATIVA "ROSA OLGA VILLACRES LOZANO"</p>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header> 
    <h4>Lista de Asistencia de Alumnos
    <?php
            //creamos la sesion
            if (!isset($_SESSION)) {
                session_start();
            }
            //Array para almacenar la consulta
            $curso_paralelo = array();

             $idCurso = isset($_POST['idCursoEnviar']) ? $_POST['idCursoEnviar'] : $idCurso = false;
             $idParalelo = isset($_POST['idParaleloEnviar']) ? $_POST['idParaleloEnviar'] : $idParalelo = false;
             $jornada = isset($_POST['jornadaEnviar']) ? $_POST['jornadaEnviar'] : $jornada = false;
            
            //amacena el curso y el paralelo en la Sesion
            $curso_paralelo['curso'] = $idCurso;
            $curso_paralelo['paralelo'] = $idParalelo;
            $curso_paralelo['jornada'] = $jornada;
            $_SESSION['curso_paralelo'] = $curso_paralelo;
            
             $paralelo = "";

             if($idParalelo == "A"){
                $paralelo = "A";
             }else if($idParalelo == "B"){
                $paralelo = "B";
             }else if($idParalelo == "C"){
                $paralelo = "C";
             }

             echo " $idCurso $paralelo jornada $jornada";
        ?>
    </h4>
    <div class="botones-tabla">
        <form class="crear" target="_blank" action="../../../reportes/reportes-asistencia-alumnos.php" method="POST">
            <button class="btn-pdf" type="submit">
                <img class="img-1" src="../../../img/pdf.png" alt="pdf">Reportes en PDF
            </button>
        </form>
    </div>   
    
    <div class="container">
        <table class="table table table-striped table-bordered shadow-lg mt-2" id="tabla-asistencia">
            <thead class="bg-dark text-white">
                <tr>
                    <th scope="col">N°</th>
                    <th scope="col">Nombres</th>
                    <th scope="col">Cédula</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Hora ingreso</th>
                    <th scope="col">Estatus</th>                    
                    <th scope="col">Estado</th>                    
                </tr>
            </thead>
            <tbody>   <!-- LUEGO CAMBIAR COLOR -->
                <?php

                    //creamos la sesion
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    //Array para almacenar la consulta
                    $almacena_query = array();


                    // $salida = "";
                    $fecha = date('Y-m-d');
                    echo $fecha;
                    // $sql = "SELECT 	al.idAlumno, CONCAT(al.nombres, ' ' , al.apellidos) AS nombres, al.cedula, a.fecha, a.hora, a.status, a.estado
                    // FROM  tb_asistencia_alumno AS a, tb_alumno AS al
                    // WHERE a.idAlumno = al.idAlumno and a.fecha = '$fecha'
                    // GROUP BY al.idAlumno;";

                    $idCurso = isset($_POST['idCursoEnviar']) ? $_POST['idCursoEnviar'] : $idCurso = false;
                    $idParalelo = isset($_POST['idParaleloEnviar']) ? $_POST['idParaleloEnviar'] : $idParalelo = false;

                    $salida = "";
                    $sql = "SELECT al.idAlumno, CONCAT(al.nombres, ' ', al.apellidos) AS nombres, al.cedula, a.fecha, a.hora, al.estado, a.status
                    FROM tb_alumno AS al, tb_curso AS c, tb_paralelo AS p, tb_asistencia_alumno AS a
                    WHERE al.idCurso = c.idCurso AND al.idParalelo = p.idParalelo AND al.idAlumno = a.idAlumno 
                    AND a.fecha = '$fecha' AND c.descripcion = '$idCurso' AND p.descripcion = '$idParalelo' AND al.jornada = '$jornada'
                    GROUP BY a.idAlumno
                    ORDER BY al.idAlumno;
                    ";

                     //amacena consulta para reportes
                    $almacena_query['query1'] = $sql;
                    $_SESSION['query-pdf'] = $almacena_query;

                    $result = $conexion->query($sql);
                    //preguntamos si el registro contiene datos
                    if ($result->num_rows > 0) {
                        while ($mostrar = $result->fetch_assoc()) {
                            $status = $mostrar['status']; 
                            $estado = $mostrar['estado']; 
                            $idDocente = $mostrar['idAlumno'];
                            $estado1 = '';
                            if($estado == 1){
                                $estado1 = 'Activo'; 
                            }else if($estado == 0){
                                $estado1 = 'Inactivo';
                            }

                            if($status === 'PRESENTE'){
                                $salida .= "
                                <tr>
                                    <th scope='row'>
                                        " . $mostrar['idAlumno'] . "
                                        <input value='". $mostrar['idAlumno'] . "' hidden name='idAlumno' id='idAlumno'/> 
                                    </th>
                                    <td>
                                        " . $mostrar['nombres'] . "   
                                    </td>  
                                    <td>
                                        " . $mostrar['cedula'] . "   
                                    </td>           
                                    <td>
                                        " . $mostrar['fecha'] . "  
                                    </td>                                
                                    <td>                                    
                                        " . $mostrar['hora'] . "                                        
                                    </td>  
                                    <td>
                                        <a class='btn btn-success'>Presente</a>                                                  
                                    </td>  
                                    <td>
                                        " . $estado1 ."
                                    </td>                                                                                                                     
                                </tr>";
                            } else {
                                $salida .= "
                                <tr>
                                    <th scope='row'>
                                        " . $mostrar['idAlumno'] . "
                                        <input value='". $mostrar['idAlumno'] . "' hidden name='idAlumno' id='idAlumno'/> 
                                    </th>
                                    <td>
                                        " . $mostrar['nombres'] . "   
                                    </td>
                                    <td>
                                        " . $mostrar['cedula'] . "   
                                    </td>           
                                    <td>
                                        " . $mostrar['fecha'] . "  
                                    </td>                                
                                    <td>                                    
                                        " . $mostrar['hora'] . "                                        
                                    </td>  
                                    <td>
                                        <a class='btn btn-danger'>Ausente</a>                                                  
                                    </td>  
                                    <td>
                                        " . $estado1 ."
                                    </td>                                                                                                                     
                                </tr>";
                            }                            
                        } 
                    }else {
                        echo ' Actualmente no existen alumnos registrados';
                    }
                     //presentamos los datos y cerramos la conexion
                    echo $salida;
                    $conexion->close();
                ?>
            </tbody>
        </table>
        <script src="../../../js/asistencia/validar-asistencia1.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </div>
</body>
<script>
    $(document).ready(function() {
        $('#tabla-asistencia').DataTable();
    } );
</script>
</html>