<?php

/**
 * Class login
 * handles the user's login and logout process
 */
class Login
{
    /**
     * @var object DATABASE Conexion 
     */
    private $db_connection = null;
    /**
     * @var array mesajes de error
     */
    public $errors = array();
    /**
     * @var array mensajes de aprovacion  / neutral mensajes
     */
    public $messages = array();
    /**
     * @var array 
     */
    public $fromExt = array(); //coleccion inputs 
    /**
     * la function "__construct()" automaticamente inicia objetos para inicio de sesion 
     * se usa como "$login = new Login();"
    */
    

    public function __construct()
    {
        // crear sesion (necesario)
        session_start();

        // acciones de login:
        // salir 
        if (isset($_GET["logout"])) {
            $this->doLogout();
        }
        // login 
        elseif (isset($_POST["login"])) {
            $this->dologinWithPostData();
        }
        // olvide mi contraseña1
    
      elseif (isset($_POST["olv"])) {
            $this->forgotmypassword();
         }
                 // olvide mi contraseña2

         elseif(isset($_POST["ol"])) {
            $this->forgotmypasswor();
         }
        }
            // funciones
     
    private function forgotmypassword(){

        if(empty($_POST['name_pet']))
        {
            $this->errors[] = "sin datos obtenidos, Por favor intenta mas tarde.";
        }
        elseif(!empty($_POST['name_pet']))
        { 
            
            
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }
            if (!$this->db_connection->connect_errno) {
                $Vname_pet = $this->db_connection->real_escape_string($_POST['name_pet']);
                

                $sql = "SELECT user_id, user_name, firstname, user_email, user_password_hash
                FROM users
                WHERE pet = '" .$Vname_pet. "';";
                $result_of_login_check = $this->db_connection->query($sql);
                if($result_of_login_check->num_rows == 1)
                 {
                    $result_row = $result_of_login_check->fetch_object();
                    
				
                    $usernew_password_hash = password_hash($result_row->user_name, PASSWORD_DEFAULT);

                     $sl = "UPDATE users SET user_password_hash='".$usernew_password_hash."' WHERE user_id='".$result_row->user_id."'";
                    $result_of_change_password = $this->db_connection->query($sl);

                    // if user has been added successfully

                    if ($sl) {
                        $this->messages[]= 'Su nueva contraseña es: <h4>'.$result_row->user_name.'</h4> asegurate de cambiarla mas tarde'.'<a href="login.php" >Volver a iniciar secion </a>';
                    } else {
                        $this->errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
                    } 

                } elseif($result_of_login_check->num_rows > 1){
                    
                    $this->errors[] = "No es seguro obtener de esta manera,"." <a href='olv2.php' >Verificar con mi correo electronico </a> ";

                } else {
                    $this->errors[] = "Usuario no existe.";
                }
            }else {
                $this->errors[] = "Problema de conexión de base de datos.";
            }
     
            
        }
        
      
            
      
        
    }
    
    


    /**
     * log in con post 
     */
    private function dologinWithPostData()
    {
        // check login form contents
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Username field was empty.";
        } elseif (empty($_POST['user_password'])) {
            $this->errors[] = "Password field was empty.";
        } elseif (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

            // crea conexion a data base usando config/db.php 
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // cambia el character  a utf8 y lo valida
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            // valida que no haya errores en la conexion 
            if (!$this->db_connection->connect_errno) {

                                $user_name = $this->db_connection->real_escape_string($_POST['user_name']);

                
                $sql = "SELECT user_id, user_name, firstname, user_email, user_password_hash
                        FROM users
                        WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_name . "';";
                $result_of_login_check = $this->db_connection->query($sql);

                // valida si usuario existe 
                if ($result_of_login_check->num_rows == 1) {

                    $result_row = $result_of_login_check->fetch_object();

                    // using PHP 5.5's password_verify() function to check if the provided password fits
                    // the hash of that user's password
                    if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {

                        // write user data into PHP SESSION (a file on your server)
                        $_SESSION['user_id'] = $result_row->user_id;
						$_SESSION['firstname'] = $result_row->firstname;
						$_SESSION['user_name'] = $result_row->user_name;
                        $_SESSION['user_email'] = $result_row->user_email;
                        $_SESSION['user_login_status'] = 1;

                    } else {
                        $this->errors[] = "Usuario y/o contraseña no coinciden.";
                    }
                } else {
                    $this->errors[] = "Usuario y/o contraseña no coinciden.";
                }
            } else {
                $this->errors[] = "Problema de conexión de base de datos.";
            }
        }
    }

    
    private function forgotmypasswor(){

        if(empty($_POST['user_email']))
        {
            $this->errors[] = "sin datos obtenidos, Por favor intenta mas tarde.";
        }
        elseif(!empty($_POST['user_email']))
        { 
            
            
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }
            if (!$this->db_connection->connect_errno) {
                $Vname_pet = $this->db_connection->real_escape_string($_POST['user_email']);
                

                $sql = "SELECT user_id, user_name, firstname, user_email, user_password_hash, pet
                FROM users
                WHERE user_email = '" .$Vname_pet. "';";
                $result_of_login_check = $this->db_connection->query($sql);
                if($result_of_login_check->num_rows == 1)
                 {
                    $result_row = $result_of_login_check->fetch_object();
                    
				
                    $usernew_password_hash = password_hash($result_row->pet, PASSWORD_DEFAULT);

                     $sl = "UPDATE users SET user_password_hash='".$usernew_password_hash."' WHERE user_id='".$result_row->user_id."'";
                    $result_of_change_password = $this->db_connection->query($sl);

                
                    if ($sl) {
                        $this->messages[]= 'Su nueva contraseña es: <h4>'.$result_row->pet.'</h4> asegurate de cambiarla mas tarde,'.' <a href="login.php" >Volver a iniciar secion </a> ';
                    } else {
                        $this->errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
                    } 

                }  else {
                    $this->errors[] = "Usuario no existe.";
                }
            }else {
                $this->errors[] = "Problema de conexión de base de datos.";
            }
     
            
        }
        
      
            
      
        
    }
    
    




































    /**
     *
     */
    public function doLogout()
    {
        // elimina la sesion 
        $_SESSION = array();
        session_destroy();
        // feeedback message
        $this->messages[] = "Has sido desconectado.";

    }

    /**
     * 
     * @return boolean 
     */
    public function isUserLoggedIn()
    {
        if (isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1) {
            return true;
        }
        // default return
        return false;
    }
}
