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
                    30-Apr-2022
                    <br/>
                    Dear <strong>{$this->name}</strong>

                    I am handling a regular issue, Vol. 4 No. 13, in the Journal of Composites and Compounds (ISSN 2716-9650),
                    serving as the Guest Editor. You can find the Aim and Scope of the journal <a href='https://jourcc.com/index.php/jourcc/about/submissions'>here</a>.
                    <br/>
                    We hope that you and your colleagues could join us in publishing and spreading the cutting-edge
                    researches in the field of composites and compounds science. 
                    <br/>
                    It should be noted that to extend great gratitude for the continuous support from our authors,
                    the APC is free for manuscripts submitted to the journal,
                    at which I am Handling Editor (No. 13).
                    <br/>
                    If you are interested in participating in this regular issue,
                    I am pleased to invite you to send an abstract replying this email by May, 20, 2022.
                    If invited to submit a full manuscript, it should be submitted through the peer-review system
                    of the journal (deadline for manuscript submission will be September 30, 2022).
                    Each paper submitted will be refereed and must meet the usual high quality standards of the journal. 
                    Author guidelines are provided as follows: <a href='https://jourcc.com/index.php/jourcc/about/submissions'>click here</a>
                    <br/>
                    It is worth noting that the journal is indexed in 16 databases and the journal editorial
                    board are looking for increasing the impact factor of the journal above 20,
                    which is now around 8 as well. <a href='https://scholar.google.com/citations?hl=en&user=mcBT7OgAAAAJ&view_op=list_works&authuser=4&gmla=AJsN-F7hxU-g_gDYZuRrcRLYrcV8KxKd5Z1Ah1E3qbQD6udmeninsEdjC1ag4Aj6WNxenftV8FBitHFNWc6NSoaiy6VKNQCdvv8z4xiNLetgtxr-XgGpMfI'>Journal Google Scholar</a>
                    <br/>
                    You can contact me if you have any questions.
                    I look forward to hearing from you and hope that the journal can welcome you as a
                    contributing author to this regular issue.
                    <br/>
                    Regards,
                    <br/>
                    <br/>
                    Dr. Parisa Shafiee 
                    <br/>
                    Guest Editor 
                    <br/>
                    Journal of Composites and Compounds
                </div>";
        
        $config = array();
        $email_config = Services::email();
        $config["protocol"]    = "SMTP";
        // $config["SMTPHost"]    = "smtp.gmail.com";
        // $config["SMTPUser"]    = "amirhoseinh1373@gmail.com";
        // $config["SMTPPass"]    = "Am0016973178iR#";
        $config["SMTPHost"]    = "mail.jourcc.com";
        $config["SMTPUser"]    = "invitation@jourcc.com";
        $config["SMTPPass"]    = "ABCabc123";
        $config["SMTPPort"]    = 465; // 465 - 587
        $config["SMTPTimeout"] = 60;
        $config["SMTPCrypto"]  = "ssl";
        $config["mailType"]    = "html";
        $config["newline"]    = "\r\n";
        
        $email_config->initialize($config);
        
        $email_config->setTo($this->email);
        $email_config->setFrom( $config["SMTPUser"], "Journal of Composites and Compounds" );
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