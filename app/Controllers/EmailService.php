<?php

namespace App\Controllers;

use Config\Services;

class EmailService {
    /**
     * email details
     * @var string $email
     * @var string $name
     * @var string $password
     * @var bool   $recovery_password
     * @var string $subject
     */
    public string $email       = "";
    public string $name        = "";
    public string $subject     = "";

    public function send_email() {

        $message = "<div style='direction: ltr !important;'>
                        Dear Mr/Mrs {$this->name}
                        <b>
                        Hi!
                        </b>
                        <br/>
                        ------------------------
                        <br/>
                            this is a test mail
                        <br/>
                </div>";
        
        $config = array();
        $email_config = Services::email();
        $config["protocol"]    = "SMTP";
        $config["SMTPHost"]    = "smtp.gmail.com";
        $config["SMTPUser"]    = "amirhoseinh1373@gmail.com";
        $config["SMTPPass"]    = "Am0016973178iR#";
        // $config["SMTPHost"]    = "mail.bookmoapp.com";
        // $config["SMTPUser"]    = "support@bookmoapp.com";
        // $config["SMTPPass"]    = "MeoWjQOj&{_I";
        $config["SMTPPort"]    = 465;
        $config["SMTPTimeout"] = 60;
        $config["SMTPCrypto"]  = "ssl";
        $config["mailType"]    = "html";
        
        $email_config->initialize($config);
        
        $email_config->setTo($this->email);
        $email_config->setFrom( $config["SMTPUser"], "Support BookMo" );
        $email_config->setSubject($this->subject);
        $email_config->setMessage($message);
    
        // if (mail($email_details->email, $email_details->subject, $message, "From: amirhoseinh1373@gmail.com")) {
        //     echo "Email successfully sent to ...";
        // } else {
        //     echo "Email sending failed...";
        // }

        if ($email_config->send()) {
            return TRUE;
        } else {
            // print_r($email_config->printDebugger());
            helper( "public" );
            log_file((array)$email_config->printDebugger(), "log/email_not_send_" . time() . ".json");
            return FALSE;
        }
    }
}