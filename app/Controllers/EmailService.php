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
                    Dear <b>{$this->name}</b>

                    <br/>
                    I am handling a regular issue in the Journal of Composites and Compounds, which I am Guest editor in. I invite you to contribute an article in the Journal. If interested please let me know so that I can reserve it for you/your group.
                    <br/>
                    From 1 April 2022 to 1 June 2022, to extend great gratitude for the continuous support from our authors, the APC for my handling papers submitted is free.
                    <br/>
                    We hope that you and your colleagues could join us in publishing and spreading the cutting-edge researches in the field of nanomaterials and composites science.
                    <br/>
                    You will need to indicate your intention to submit your full paper by replying the email with the title of the paper, authors, and abstract/Table of contents. The full manuscript, as a MS Word file, should be submitted through the peer-review system of Journal. Authoring guidelines are provided as follows:
                    https://jourcc.com/index.php/jourcc/about/submissions
                    <br/>
                    This journal also has 16 indexes, including ISC, which will be ISI in 2022. The editors want to increase the impact factor of the journal above 20, which is now around 8. And after that, publishing an article in this journal will not be easy. As an editor, I invite you to send us an article in a journal-related field. 
                    https://scholar.google.com/citations?hl=en&user=mcBT7OgAAAAJ&view_op=list_works&authuser=4&gmla=AJsN-F7hxU-g_gDYZuRrcRLYrcV8KxKd5Z1Ah1E3qbQD6udmeninsEdjC1ag4Aj6WNxenftV8FBitHFNWc6NSoaiy6VKNQCdvv8z4xiNLetgtxr-XgGpMfI
                    <br/>
                    <br/>
                    Regards,
                    <br/>
                    <br/>
                    Parisa shafiee
                    <br/>
                    Guest Editor
                    <br/>
                    Journal of Composites and Compounds (eISSN 2716-9650)
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
        $config["SMTPPort"]    = 465; // 465 - 587
        $config["SMTPTimeout"] = 60;
        $config["SMTPCrypto"]  = "ssl";
        $config["mailType"]    = "html";
        $config["newline"]    = "\r\n";
        
        $email_config->initialize($config);
        
        $email_config->setTo($this->email);
        $email_config->setFrom( $config["SMTPUser"], "Support Amirhoein Hasani" );
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