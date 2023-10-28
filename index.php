<?php
/* 
TAKEN FORM: https://github.com/oscaruhp/empleados
AUTHOR: Oscar Uh

MODIFIED AND ADAPTED BY: Angelower Santana-Velásquez

*/

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/*  Conecta con la base de datos en el servidor local 
	usando las credenciales de usuario y contraseña */
$servidor = "localhost"; 
$usuario = "root"; 
$passwd = ""; 
$nombreBaseDatos = "laboratory_lims";
$conexionBD = new mysqli($servidor, $usuario, $passwd, $nombreBaseDatos);


/* Consulta UN registro de paciente de la tabla patients teniendo como criterio de búsqueda 
   la variable 'id' que viene en el $_GET["consultar"] 
   */
if (isset($_GET["consultar"])){
        $sqlPatient = mysqli_query($conexionBD,"SELECT * FROM patients WHERE id=".$_GET["consultar"]);
        if(mysqli_num_rows($sqlPatient) > 0){
            $patient = mysqli_fetch_all($sqlPatient,MYSQLI_ASSOC);
            echo json_encode($patient); 
            exit();
        } else{  echo json_encode(["success"=>0]); }
}

/* Consulta UN registro de especialista de la tabla admin teniendo como criterio de búsqueda 
   la variable 'id' que viene en el $_GET["consultar"] 
   */
  if (isset($_GET["consultar_admin"])){
    $sqlAdmin = mysqli_query($conexionBD,"SELECT * FROM admin WHERE id=".$_GET["consultar_admin"]);
    if(mysqli_num_rows($sqlAdmin) > 0){
        $admin = mysqli_fetch_all($sqlAdmin,MYSQLI_ASSOC);
        echo json_encode($admin); 
        exit();
    } else{  echo json_encode(["success"=>0]); }
}

/* Borra un registro de paciente de la tabla patients, teniendo como criterio de búsqueda 
   la variable 'id' que viene en el $_GET["borrar"] 
   */
if (isset($_GET["borrar"])){
        $sqlActivo = mysqli_query($conexionBD,"DELETE FROM patients WHERE id=".$_GET["borrar"]);
        if($sqlActivo){
            echo json_encode(["success"=>1]);
            exit();
        }
        else{  echo json_encode(["success"=>0]); }
}

/* Borra un registro de especialista de la tabla admin, teniendo como criterio de búsqueda 
   la variable 'id' que viene en el $_GET["borrar"] 
   */
  if (isset($_GET["borrar_admin"])){
    $sqlActivo = mysqli_query($conexionBD,"DELETE FROM admin WHERE id=".$_GET["borrar_admin"]);
    if($sqlActivo){
        echo json_encode(["success"=>1]);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}

/* Inserta un registro de paciente de la tabla patients. La información es recibida en método POST */
if(isset($_GET["insertar"])){
        $data = json_decode(file_get_contents("php://input"));
		$id_=$data->id;
        $doc=$data->doc;
		$name=$data->name;
        $lastname=$data->lastname;
        $age=$data->age; 
        $gender=$data->gender;
        $eps=$data->eps;    
        $tp=$data->tp;
        $ptt=$data->ptt;
        $at_iii=$data->at_iii;
        $tt=$data->tt;  
        $fibri=$data->fibri;    
            if(($doc!="")&&($name!="")&&($lastname!="")&&($age!="")&&($gender!="")&&($eps!="")&&($tp!="")&&($ptt!="")&&($at_iii!="")&&($tt!="")&&($fibri!="")){        
                $sqlPatient = mysqli_query($conexionBD,"INSERT INTO patients(id,doc,name,lastname,age,gender,eps,tp,ptt,at_iii,tt,fibri) VALUES(null,'$doc','$name','$lastname','$age','$gender','$eps','$tp','$ptt','$at_iii','$tt','$fibri') ");
                if ($sqlPatient) {
                    echo json_encode(["success" => 1]);
                } else {
                    echo json_encode(["success" => 0]);
                }
                exit();
            }
        }


/* Inicio de sesión de especialista de la tabla admin. La información es recibida en método POST */
if (isset($_GET["iniciar_sesion"])) {
    $data = json_decode(file_get_contents("php://input"));
    $user = $data->user;
    $password = $data->password;
    if ($user != "" && $password != "") {
        // Validar las credenciales en la base de datos
        $sqlAdmin = mysqli_query($conexionBD, "SELECT * FROM admin WHERE user='$user' AND password='$password'");
        if (mysqli_num_rows($sqlAdmin) > 0) {
            echo json_encode(["success" => 1]);
        } else {
            echo json_encode(["success" => 0]);
        }
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}


/* Actualiza todos los campos de la tabla patients, teniendo como criterio de búsqueda 
   la variable 'id' que viene en el $_GET["actualizar"]
   */
if(isset($_GET["actualizar"])){ 
    $data = json_decode(file_get_contents("php://input"));
    $id=(isset($data->id))?$data->id:$_GET["actualizar"];
    $doc=$data->doc;
	$name=$data->name;
	$lastname=$data->lastname;  
    $age=$data->age; 
    $gender=$data->gender;
    $eps=$data->eps;    
    $tp=$data->tp;
    $ptt=$data->ptt;
    $at_iii=$data->at_iii;
    $tt=$data->tt;  
    $fibri=$data->fibri;
	$sqlPatient = mysqli_query($conexionBD,"UPDATE patients SET doc='$doc', name='$name',lastname='$lastname',age='$age',gender='$gender',eps='$eps',tp='$tp',ptt='$ptt',at_iii='$at_iii',tt='$tt',fibri='$fibri' WHERE id='$id'");
	echo json_encode(["success"=>1 ]);
	exit();
    
}


/*
    Muestra todos los registros almacenados en la tabla patients y admin, usando la URL raíz.
*/
$sqlPatients_ = mysqli_query($conexionBD,"SELECT * FROM patients ");
if(mysqli_num_rows($sqlPatients_) > 0){
    $patients_ = mysqli_fetch_all($sqlPatients_,MYSQLI_ASSOC);
} else {
    $patients_ = [];
}

$sqlAdmin_ = mysqli_query($conexionBD,"SELECT * FROM admin ");
if(mysqli_num_rows($sqlAdmin_) > 0){
    $pAdmin_ = mysqli_fetch_all($sqlAdmin_,MYSQLI_ASSOC);
} else {
    $pAdmin_ = [];
}

$response = [
    "patients" => $patients_,
    "admins" => $pAdmin_
];

echo json_encode($response);


?>