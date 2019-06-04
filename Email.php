<?php


namespace Ferelli\ERP;

include_once 'src/PHPMailer.php';
include_once 'src/Exception.php';
include_once 'src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email {

    private $email;

    function __construct() {
        $this->email = new PHPMailer(TRUE);  //True muestra los exceptions en pantalla
        $this->email->IsSMTP(); //Indica que se incluira una configuracion de credenciales
        $this->email->Host = "smtp.office365.com";
        $this->email->Username = 'developer@beautyangelsacademy.com';
        $this->email->Password = 'Bonds789!';
        $this->email->SMTPAuth = true;
    }

    public function sendEmail($message){

        try{
            $this->email->setFrom('developer@beautyangelsacademy.com', 'Desarrollo');
            $this->email->addAddress('administracion@beautyangels.com', 'Administracion Beauty Angels');
            $this->email->addCC('luis.marquez@ferelli.com.mx');

            $this->email->Subject  = 'A terminado la ejecucion del script del SAAS';
            $this->email->Body = isset($message) ? "El script termino de correr, pero se presentaron los siguientes errores: \n \n".$message : "El script termino su ejecucion exitosamente";

            $this->email->send();

        }catch (Exception $e) {throw new Exception($e->errorMessage());}
        catch (\Exception $e) {throw new Exception($e->errorMessage());}

    }

}



?>