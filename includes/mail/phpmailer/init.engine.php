<?php

/**
 * Mailing System
 * @author Bylancer
 * @Copyright (c) 2015-18 Devendra Katariya (bylancer.com)
 */

include_once('class.phpmailer.php');
include_once('class.smtp.php');
include_once('PHPMailerAutoload.php');

$config['smtp_debug'] = false;



# SMTP***********************************
if ($config['email_type'] == 'smtp') {

    $mail = new PHPMailer();
    $mail->IsSMTP();

    $mail->Host     = $config['smtp_host'];
    $mail->SMTPAuth = $config['smtp_auth'];
    $mail->SMTPDebug = $config['smtp_debug'];
    $mail->Debugoutput = 'html';
    $mail->SMTPKeepAlive = true;
    if ($config['smtp_secure'] == 1) { # SSL
        $mail->SMTPSecure = 'ssl';
    } else if ($config['smtp_secure'] == 2) { # TLS
        $mail->SMTPSecure = 'tls';
    }
    $mail->Username = $config['smtp_username'];
    $mail->Password = $config['smtp_password'];
    $mail->Port = $config['smtp_port'];
    $mail->Priority = 1;
    $mail->Encoding = 'base64';
    $mail->CharSet = "utf-8";
    if ($config['email_template'] == 0) {
        $mail->IsHTML(true);
        $mail->ContentType = "text/html";
    } else {
        $mail->ContentType = "text/plain";
    }
    $mail->SetFrom($config['admin_email'], $name = $config['site_title']);
    if ($email_reply_to != null) {
        $mail->AddReplyTo($email_reply_to, $email_reply_to_name);
    }

    /* Clear Mails */
    $mail->clearAddresses();
    $mail->clearCustomHeaders();
    $mail->clearAllRecipients();
    $mail->AddAddress($email_to, $email_to_name);
    $mail->Subject  =  $email_subject;
    $mail->Body = $email_body;

    /* Send Error */
    if (!$mail->Send()) {
        return false;
        //echo $mail->ErrorInfo;
    } else {
        return true;
        //echo $mail->ErrorInfo;
    }
}

# PHPMail*******************************************************************************
else if ($config['email_type'] == 'mail') {

    $mail = new PHPMailer(true);
    $mail->Debugoutput = 'html';
    $mail->Priority = 1;
    $mail->Encoding = 'base64';
    $mail->CharSet = "utf-8";

    if ($config['email_template'] == 0) {
        $mail->IsHTML(true);
        $mail->ContentType = "text/html";
    } else {
        $mail->IsHTML(false);
    }
    $mail->SetFrom($config['admin_email'], $name = $config['site_title']);
    if ($email_reply_to != null) {
        $mail->AddReplyTo($email_reply_to, $email_reply_to_name);
    }

    /* Clear Mails */
    $mail->clearAddresses();
    $mail->clearCustomHeaders();
    $mail->clearAllRecipients();
    $mail->AddAddress($email_to, $email_to_name);
    $mail->Subject  =  $email_subject;
    $mail->Body = $email_body;

    /* Send Error */
    if (!$mail->Send()) {
        echo $mail->ErrorInfo;
    } else {
        echo $mail->ErrorInfo;
    }
}
# Amazon SES*******************************************************************************
else if ($config['email_type'] == 'aws') {

    $mail = new PHPMailer();
    $mail->IsSMTP();

    $mail->Host     = $config['aws_host'];
    $mail->SMTPAuth = true;
    $mail->SMTPDebug = $config['smtp_debug'];
    $mail->Debugoutput = 'html';
    $mail->SMTPKeepAlive = true;
    $mail->SMTPSecure = 'tls';
    $mail->Username = $config['aws_access_key'];
    $mail->Password = $config['aws_secret_key'];
    $mail->Port = 465;

    $mail->Priority = 1;
    $mail->Encoding = 'base64';
    $mail->CharSet = "utf-8";
    if ($config['email_template'] == 0) {
        $mail->IsHTML(true);
        $mail->ContentType = "text/html";
    } else {
        $mail->ContentType = "text/plain";
    }
    $mail->SetFrom($config['admin_email'], $name = $config['site_title']);
    if ($email_reply_to != null) {
        $mail->AddReplyTo($email_reply_to, $email_reply_to_name);
    }

    /* Clear Mails */
    $mail->clearAddresses();
    $mail->clearCustomHeaders();
    $mail->clearAllRecipients();
    $mail->AddAddress($email_to, $email_to_name);
    $mail->Subject  =  $email_subject;
    $mail->Body = $email_body;

    /* Send Error */
    if (!$mail->Send()) {
        //echo $mail->ErrorInfo;
    } else {
        //echo $mail->ErrorInfo;
    }
}
# # Mandrill*******************************************************************************
else if ($config['email_type'] == 'mandrill') {

    try {
        $mail = new PHPMailer();
        $mail->IsSMTP();

        $mail->Host     = 'smtp.mandrillapp.com';
        $mail->SMTPAuth = true;
        $mail->SMTPDebug = $config['smtp_debug'];
        $mail->Debugoutput = 'html';
        $mail->SMTPKeepAlive = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = $config['mandrill_user'];
        $mail->Password = $config['mandrill_key'];
        $mail->Port = 587;

        $mail->Priority = 1;
        $mail->Encoding = 'base64';
        $mail->CharSet = "utf-8";
        $mail->isHTML(true);  // Set email format to HTML
        $mail->smtpConnect(
            array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true
                )
            )
        );
        if ($config['email_template'] == 0) {
            $mail->IsHTML(true);
            $mail->ContentType = "text/html";
        } else {
            $mail->ContentType = "text/plain";
        }
        $mail->SetFrom($config['admin_email'], $name = $config['site_title']);
        if ($email_reply_to != null) {
            $mail->AddReplyTo($email_reply_to, $email_reply_to_name);
        }

        # *************************************************************************
        /* Clear Mails */
        $mail->clearAddresses();
        $mail->clearCustomHeaders();
        $mail->clearAllRecipients();
        $mail->AddAddress($email_to, $email_to_name);
        $mail->Subject  =  $email_subject;
        $mail->Body = $email_body;

        /* Send Error */
        if ($mail->Send()) {
            // echo 'send';
            // print_r($mail->ErrorInfo);
            // exit;
            //echo $mail->ErrorInfo;
        } else {
            // print_r($mail->ErrorInfo);
            // exit;
            // echo $mail->ErrorInfo;
        }
    } catch (\Throwable $ex) {
        print_r($ex->getMessage());
        exit;
    }

    # *************************************************************************
} else if ($config['email_type'] == 'gmail') {

    try {
        $mail = new PHPMailer();
        $mail->IsSMTP();

        $mail->Host     = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        // $mail->SMTPDebug = $config['smtp_debug'];
        // $mail->Debugoutput = 'html';
        // $mail->SMTPKeepAlive = true;
        $mail->SMTPSecure = 'tls';
        // $mail->Username = "testingcheck9@gmail.com";
        $mail->Username = $config['gmail_user'];
        // $mail->Password = "Admin@123";
        $mail->Password = $config['gmail_pass'];
        $mail->Port = 587;

        // $mail->Priority = 1;
        // $mail->Encoding = 'base64';
        // $mail->CharSet = "utf-8";
        // $mail->isHTML(true);  // Set email format to HTML
        $mail->smtpConnect(
            array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true
                )
            )
        );
        if ($config['email_template'] == 0) {
            $mail->IsHTML(true);
            $mail->ContentType = "text/html";
        } else {
            $mail->ContentType = "text/plain";
        }
        $mail->SetFrom($config['admin_email'], $name = $config['site_title']);
        if ($email_reply_to != null) {
            $mail->AddReplyTo($email_reply_to, $email_reply_to_name);
        }

        # *************************************************************************
        /* Clear Mails */
        $mail->clearAddresses();
        $mail->clearCustomHeaders();
        $mail->clearAllRecipients();
        $mail->AddAddress($email_to, $email_to_name);
        $mail->Subject  =  $email_subject;
        $mail->Body = $email_body;

        /* Send Error */
        if ($mail->Send()) {
            echo 'send';
            // print_r($mail->ErrorInfo);
            // exit;
            //echo $mail->ErrorInfo;
        } else {
            // print_r($mail->ErrorInfo);
            // exit;
            echo $mail->ErrorInfo;
        }
    } catch (\Throwable $ex) {
        print_r($ex->getMessage());
        exit;
    }

    # *************************************************************************
}
# ********************************************************************************************************************************
else if ($config['email_type'] == 'sendgrid') { # SendGrid
    $mail = new PHPMailer();
    $mail->IsSMTP();

    $mail->Host     = 'smtp.sendgrid.net';
    $mail->SMTPAuth = true;
    $mail->SMTPDebug = $config['smtp_debug'];
    $mail->Debugoutput = 'html';
    $mail->SMTPKeepAlive = true;
    $mail->SMTPSecure = 'tls';
    $mail->Username = $config['sendgrid_user'];
    $mail->Password = $config['sendgrid_pass'];
    $mail->Port = 587;

    $mail->Priority = 1;
    $mail->Encoding = 'base64';
    $mail->CharSet = "utf-8";
    if ($config['email_template'] == 0) {
        $mail->IsHTML(true);
        $mail->ContentType = "text/html";
    } else {
        $mail->ContentType = "text/plain";
    }
    $mail->SetFrom($config['admin_email'], $name = $config['site_title']);
    if ($email_reply_to != null) {
        $mail->AddReplyTo($email_reply_to, $email_reply_to_name);
    }

    /* Clear Mails */
    $mail->clearAddresses();
    $mail->clearCustomHeaders();
    $mail->clearAllRecipients();
    $mail->AddAddress($email_to, $email_to_name);
    $mail->Subject  =  $email_subject;
    $mail->Body = $email_body;

    /* Send Error */
    if (!$mail->Send()) {
        //echo $mail->ErrorInfo;
    } else {
        //echo $mail->ErrorInfo;
    }
}
