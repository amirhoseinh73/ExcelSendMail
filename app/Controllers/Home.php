<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('index');
    }

    public function submitEmail() {
        $file = $this->request->getFile( "file" );

        if ( ! $file->isValid() ) die( "file format error" );

        var_dump( $file->guessExtension() );

        if ( $file->guessExtension() !== "xlsx" ) die( "file format error" );

        $sheet_data = get_excel_data( $file );

        unset($sheetData[0]);

        $email_service = new EmailService();
        $email_service->subject = "test email amirhosein";

        $email_address = "/log-mail.csv";
        
        $excel = fopen(FCPATH . $email_address, "w+");

        $array_titles = array(
            "name",
            "email",
            "status"
        );

        $excel_title = implode(',',$array_titles);
        fwrite($excel,
            ''. $excel_title .'
            '
        );
        
        fwrite( $excel, pack( 'CCC', 0xef, 0xbb, 0xbf ) );

        foreach ( $sheet_data as $row ) {
            // process element here;
            // access column by index
            $name = $row[ 0 ];
            $email = $row[ 1 ];

            if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {

                $data = implode( ",", array(
                    $name,
                    $email,
                    "error email format"
                ) );
                fwrite($excel,
                    ''. $data .'
                    '
                );
                continue;
            };

            $email_service->name = $name;
            $email_service->email = $email;

            $is_send_email = $email_service->send_email();

            $data = implode( ",", array(
                $name,
                $email,
                ( $is_send_email ? "ok" : "email not send" )
            ) );
            fwrite($excel,
                ''. $data .'
                '
            );

        }

        fclose($excel);

        echo "<a href='" . base_url( $email_address ) . "' >click to open log file</a>";
        echo "<br/>";
        echo "======================";
        echo "<br/>";
        die( "ok" );
    }
}
