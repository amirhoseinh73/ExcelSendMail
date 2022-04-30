<?php

namespace App\Controllers;

ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');

class Home extends BaseController
{
    public function index()
    {
        return view('index');
    }

    public function submitEmail() {
        $file = $this->request->getFile( "file" );

        if ( ! $file->isValid() ) die( "file format error" );

        echo "<pre>";
        var_dump( $file->guessExtension() );

        if ( $file->guessExtension() !== "xlsx" ) die( "file format error" );

        $sheet_data = get_excel_data( $file );

        unset( $sheet_data[ 0 ] );

        $sheet_data = array_values( $sheet_data );

        $email_service = new EmailService();
        $email_service->subject = "Invitaion for Article (Journal of Composites and Compounds)";

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

        $i = 140;
        var_dump( count( $sheet_data ) );
        while ( $i < count( $sheet_data ) ) {
            $row = $sheet_data[ $i ];
            $i++;
            if ( $i > 160 && $i < count( $sheet_data ) - 2 ) $i = count( $sheet_data ) - 2;
            echo $i . "<br/>";
            // process element here;
            // access column by index
            $name = trim( $row[ 0 ] );
            $email = trim( $row[ 1 ] );

            var_dump( $email );
            // continue;

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
