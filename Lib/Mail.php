<?php

namespace sort\Lib;
 
class Mail
{
    const EOL = "\r\n";

    /**
     * @var string
     */
    protected $_filePath = null;

    /**
     * @var string
     */
    protected $_subject;

    /**
     * @var string
     */
    protected $_message;

    /**
     * @var string
     */
    protected $_from;

    /**
     * @var array
     */
    protected $_bcc;

    /**
     * @var array
     */
    protected $_to;

    /**
     * @var string
     */
    protected $_boundary;

    /**
     * @param null|string $boundary
     */
    public function __construct($boundary = null)
    {
        $this->_boundary = sha1($boundary === null ? rand() : $boundary);
    }

    /**
     * @param string $filePath
     */
    public function setFile($filePath)
    {
        $this->_filePath = $filePath;
    }

    /**
     * @param string $from
     */
    public function setFrom($from)
    {
        $this->_from = $from;
    }

    /**
     * @param array $bcc
     */
    public function setBcc($bcc)
    {
        if (!is_array($bcc)) {
            $bcc = array($bcc);
        }
        $this->_bcc = $bcc;
    }

    /**
     * @param array $to
     */
    public function setTo($to)
    {
        if (!is_array($to)) {
            $to = array($to);
        }
        $this->_to = $to;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->_subject = $subject;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->_message = $message;
    }

    /**
     * sends mail if file changed compared to yesterday
     *
     * @return void
     */
    public function sendMail()
    {
        $to = implode(', ', $this->_to);

        $additionalHeader = $this->_createHeader();

        $subject = $this->_subject;
        $attachment = $this->_getAttachment(self::EOL);

        $message = trim($this->_createMessage($attachment));

        $header = implode(self::EOL, $additionalHeader);

        $this->_sendMail($to, $subject, $message, $header);
    }

    /**
     * @return bool|string
     */
    protected function _getAttachment()
    {
        $path = $this->_filePath;
        $msgParts = array();

        if (file_exists($path)) {
            $basename = basename($path);

            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileType = finfo_file($fileInfo, $path);
            $fileSize = filesize($path);

            $file = fopen($path, "r");
            $attachment = fread($file, $fileSize);
            $attachment = chunk_split(base64_encode($attachment));
            fclose($file);

            $msgParts[] = "Content-Disposition: attachment;";
            $msgParts[] = "\tfilename=\"" . $basename ."\";";
            $msgParts[] = 'Content-Type: ' . $fileType . ';';
            $msgParts[] = "\tname=\"" . $basename . "\"";
            $msgParts[] = "Content-Transfer-Encoding: base64";
            $msgParts[] =  self::EOL . $attachment . self::EOL;

            return $msgParts;
        } else {
            return false;
        }
    }

    /**
     * create message body
     *
     * @param array $attachment
     * @return string
     */
    protected function _createMessage($attachment)
    {
        $messageParts[] = 'This is a multi-part message in MIME format.';
        $messageParts[] = self::EOL . '--' . $this->_boundary;
        $messageParts[] = 'Content-Type: text/plain; charset=utf-8';
        $messageParts[] = 'Content-Transfer-Encoding: 8bit';
        $messageParts[] = 'Content-Disposition: inline';
        $messageParts[] = self::EOL . $this->_message . self::EOL;

        $message = implode(self::EOL, $messageParts);

        if ($attachment) {
            $message .= self::EOL . '--' . $this->_boundary . self::EOL;
            $message .= implode(self::EOL, $attachment);
        }

        return $message;
    }

    /**
     * @return array
     */
    protected function _createHeader()
    {
        $header = array();

        $header[] = 'Mime-Version: 1.0';
        $header[] = 'Content-Type: multipart/mixed;';
        $header[] = "\tboundary=" . $this->_boundary;

        if (!empty($this->_bcc)) {
            $bcc = implode(', ', $this->_bcc);
            $header[] = 'Bcc: ' . $bcc;
        }

        $header[] = 'From: ' . $this->_from;

        return $header;
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param string $header
     */
    protected function _sendMail($to, $subject, $message, $header)
    {
        mail($to, $subject, $message, $header);
    }

}
 