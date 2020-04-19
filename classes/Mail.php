<?php

class Mail {
    private $_mail,
            $_db;

    public function __construct($exception = true) {
        $this->_db = DBB::getInstance();
        $this->_mail = new PHPMailer($exception);
    }

    public function createMessage($address_to, $name_to, $subject, $body) {
        //configuration to send a mail
        $this->_mail->SMTPSecure = 'ssl';
        $this->_mail->Host = Config::get('mail/host');
        $this->_mail->Port = Config::get('mail/port');
        $this->_mail->Username = Config::get('mail/adres_mail');
        $this->_mail->Password = Config::get('mail/password');
        $this->_mail->Mailer = Config::get('mail/protocol');
        $this->_mail->IsSMTP();
        $this->_mail->SMTPAuth = true;
        $this->_mail->CharSet = 'utf-8';
        $this->_mail->SetLanguage(Config::get('mail/lang'));
        $this->_mail->SMTPDebug = 0;
        $this->_mail->From = Config::get('mail/adres_mail');
        $this->_mail->FromName = Config::get('mail/user_name');
        $this->_mail->AddReplyTo(Config::get('mail/adres_mail'), Config::get('mail/user_name'));
        $this->_mail->AddAddress($address_to, $name_to);
        $this->_mail->Subject = $subject;
        $this->_mail->Body = $body;
        $this->_mail->IsHTML(true);
        $this->_mail->AddBCC(Config::get('mail/adres_mail'), Config::get('mail/user_name'));

        try {
            $this->_mail->Send();
            // $reciver = $name_to . ' (' . $address_to . ')';
            // $sender = $this->_mail->FromName . ' (' . $this->_mail->From .')';
        }catch(Exception $e) {
            echo 'Error send message! ' . $e->getMessage();
            Logs::addError('Cant send mail! Message '. $e->getMessage() .' Line: '. $e->getLine() .' File: '. $e->getFile());
        }
    }


    public function setSMTPSecure($SMTPSecure = 'ssl') {
        // options secure: ssl or tls
        return $this->_mail->SMTPSecure = $SMTPSecure;
    }

    public function setSMTPAuth($SMTPAuth = true) {
        return $this->_mail->SMTPAuth = $SMTPAuth;
    }

    public function setMailerDebug($MailerDebug = false){
        return $this->_mail->MailerDebug = $MailerDebug;
    }

    public function setSMTPDebug($SMTPDebug = 0) {
        return $this->_mail->SMTPDebug = $SMTPDebug;
    }

    public function setFrom($from) {
        return $this->_mail->From = $from;
    }

    public function setFromName($FromName = '') {
        return $this->_mail->FromName = $FromName;
    }

    public function setReplyTo($address, $name = '') {
        return $this->_mail->AddReplyTo($address, $name);
    }

    public function setLanguage($lang = 'pl', $path = 'lib/phpmailer/language') {
        return $this->_mail->SetLanguage($lang, $path);
    }

    public function setCharset($charset = 'utf-8') {
        return $this->_mail->CharSet = $charset;
    }

    public function setIsHTML($value = true) {
        return $this->_mail->IsHTML($value);
    }

    public function setMailServer($host, $mailer, $port, $user, $password) {
        $this->_mail->Host      = $host;
        $this->_mail->Port      = $port;
        $this->_mail->Mailer    = $mailer;
        $this->_mail->UserName  = $user;
        $this->_mail->Password  = $password;
    }

    public function setMessage($subject = 'Subject', $body = '', $path_atachment = '') {
        $this->_mail->Subject = $subject;
        $this->_mail->Body = $body;
//        $this->_mail->AddAttachment($path_atachment);
    }

    public function setAddAddress($address, $name = '') {
        return $this->_mail->AddAddress($address, $name);
    }

    public function setAddressCC($cc, $cc_name = '') {
        return $this->_mail->AddCC($cc, $cc_name);
    }

    public function setAddressBCC($bcc, $bcc_name = '') {
        return $this->_mail->AddBCC($bcc, $bcc_name);
    }

    public function sendMail()
    {
        try {
            // sending mail
            $this->_mail->Send();
        }catch(Exception $e) {
            echo 'Error send message!<br/>' . $e->getMessage();
            Logs::addError('Cant send mail! Message '. $e->getMessage() .' Line: '. $e->getLine() .' File: '. $e->getFile());
        }
    }

    public function toString() {
        echo $this->_mail->Host . '<br/>';
        echo htmlentities($this->_mail->GetSentMIMEMessage()) . '<br/>';
    }
}