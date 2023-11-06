<?php
namespace src\Services;

class MailerService
{
    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $headers;

    public function __construct(string $to, string $subject, string $message, $from = null)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->headers = 'From: ' . ($from == null ? 'Ederalab <service-ederalab@gmail.com>' : $from ) . "\r\n"
                        .'Content-Type:text/html;charset="utf-8"'."\r\n"
                        .'Content-Transfer-Encoding:8bit'."\r\n"
                        .'X-Mailer: PHP/' . phpversion();
    }

    public function setTo(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setHeaders(string $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders(): string
    {
        return $this->headers;
    }

    public function send()
    {
        return @mail($this->getTo(), $this->getSubject(), $this->getMessage(), $this->getHeaders());
    }
}