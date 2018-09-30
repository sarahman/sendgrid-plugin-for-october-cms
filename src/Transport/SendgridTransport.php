<?php namespace Sarahman\Mailer\Transport;

use Swift_Transport;
use Swift_Mime_Message;
use Swift_Events_EventListener;

/**
 * Class SendGridTransport
 *
 * @package Illuminate\Mail\Transport
 * @see https://github.com/clarification/sendgrid-laravel-driver/blob/master/src/Transport/SendGridTransport.php
 */
class SendGridTransport implements Swift_Transport
{
    /**
     * The SendGrid API key.
     *
     * @var string
     */
    private $key;

    /** @var \SendGrid\Mail\Mail */
    private $mail;

    /**
     * Create a new SendGrid transport instance.
     *
     * @param  string $key
     * @return void
     */
    public function __construct($key)
    {
        $this->key = $key;
        $this->mail = new \SendGrid\Mail\Mail();
    }

    /**
     * {@inheritdoc}
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function stop()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function registerPlugin(Swift_Events_EventListener $plugin)
    {
        //
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $this->setSubject($message);
        $this->setFrom($message);
        $this->setTo($message);
        $this->setCc($message);
        $this->setBcc($message);
        $this->setText($message);
        $this->setReplyTo($message);
        $this->setAttachment($message);

        try {
            $sendGrid = new \SendGrid($this->key);
            $response = $sendGrid->send($this->mail);
            return in_array($response->statusCode(), [200, 202]);

        } catch (\Exception $e) {
            printf("Caught exception: %s\n", $e->getMessage());
            return false;
        }
    }

    /**
     * @param  Swift_Mime_Message $message
     */
    private function setSubject(Swift_Mime_Message $message)
    {
        if ($subject = $message->getSubject()) {
            $this->mail->setSubject($subject);
        }
    }

    /**
     * @param  Swift_Mime_Message $message
     * @throws \SendGrid\Mail\TypeException
     */
    private function setFrom(Swift_Mime_Message $message)
    {
        if ($from = $message->getFrom()) {
            $this->mail->setFrom(key($from), current($from));
        }
    }

    /**
     * @param  Swift_Mime_Message $message
     */
    private function setTo(Swift_Mime_Message $message)
    {
        if ($to = $message->getTo()) {
            $this->mail->addTos($to);
        }
    }

    /**
     * @param Swift_Mime_Message $message
     */
    private function setCc(Swift_Mime_Message $message)
    {
        if ($cc = $message->getCc()) {
            $this->mail->addCcs($cc);
        }
    }

    /**
     * @param Swift_Mime_Message $message
     */
    private function setBcc(Swift_Mime_Message $message)
    {
        if ($bcc = $message->getBcc()) {
            $this->mail->addBccs($bcc);
        }
    }

    /**
     * @param Swift_Mime_Message $message
     */
    private function setReplyTo(Swift_Mime_Message $message)
    {
        if ($replyTo = $message->getReplyTo()) {
            $this->mail->setReplyTo(key($replyTo), current($replyTo));
        }
    }

    /**
     * Set text contents.
     *
     * @param Swift_Mime_Message $message
     */
    private function setText(Swift_Mime_Message $message)
    {
        empty($message->getBody()) || $this->mail->addContent($message->getContentType(), $message->getBody());
        foreach ($message->getChildren() AS $attachment) {
            if (!$attachment instanceof \Swift_MimePart) continue;
            $this->mail->addContent($attachment->getContentType(), $attachment->getBody());
        }
    }

    /**
     * Set Attachment Files.
     *
     * @param Swift_Mime_Message $message
     */
    private function setAttachment(Swift_Mime_Message $message)
    {
        foreach ($message->getChildren() AS $attachment) {
            if (!$attachment instanceof \Swift_Attachment) continue;
            $data = $attachment->getBody();
            base64_encode(base64_decode($data)) === $data || $data = base64_encode($data);
            $this->mail->addAttachment($data, $attachment->getContentType(), $attachment->getFilename(), $attachment->getDisposition(), $attachment->getId());
        }
    }
}
