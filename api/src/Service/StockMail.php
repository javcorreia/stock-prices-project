<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class StockMail
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(string $to, array $stockInfo): void
    {
        $email = new Email()
            ->from('stock-app@docker')
            ->to($to)
            ->subject("Stock check for {$stockInfo['symbol']}: " . date('U'))
            ->text('Stock check at day ' . date('Y-m-d') . ' for ' . $stockInfo['name'] . "\n" . json_encode($stockInfo, JSON_PRETTY_PRINT))
            ->html('<p>Stock check at day <strong><u>' . date('Y-m-d') . '</u></strong> for ' . $stockInfo['name'] . '</p><pre>' . json_encode($stockInfo, JSON_PRETTY_PRINT) . '</pre>');

        $this->mailer->send($email);
    }
}