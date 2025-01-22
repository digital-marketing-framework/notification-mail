<?php

namespace DigitalMarketingFramework\Notification\Mail\Notification;

use DigitalMarketingFramework\Core\Notification\NotificationChannel;
use DigitalMarketingFramework\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Mail\Manager\MailManagerInterface;
use DigitalMarketingFramework\Mail\Utility\MailUtility;
use DigitalMarketingFramework\Notification\Mail\GlobalConfiguration\Schema\GlobalMailNotificationChannelConfigurationSchema;

class MailNotificationChannel extends NotificationChannel
{
    public function __construct(
        string $keyword,
        RegistryInterface $registry,
        protected MailManagerInterface $mailManager,
    ) {
        parent::__construct($keyword, $registry);
    }

    protected function getConfigPackageKey(): string
    {
        return 'notification-mail';
    }

    protected function getFrom(): string
    {
        return $this->getConfiguration()[GlobalMailNotificationChannelConfigurationSchema::KEY_SENDER]
            ?? GlobalMailNotificationChannelConfigurationSchema::DEFAULT_SENDER;
    }

    protected function getTo(): string
    {
        return $this->getConfiguration()[GlobalMailNotificationChannelConfigurationSchema::KEY_RECEIVER]
            ?? GlobalMailNotificationChannelConfigurationSchema::DEFAULT_RECEIVER;
    }

    public function notify(
        string $title,
        string $message,
        mixed $details,
        string $component,
        int $level,
    ): void {
        $mail = $this->mailManager->createMessage();

        $from = MailUtility::getAddressData($this->getFrom(), true);
        foreach ($from as $value) {
            $mail->addFrom($value);
        }

        $to = MailUtility::getAddressData($this->getTo());
        foreach ($to as $value) {
            $mail->addTo($value);
        }

        $mail->text($this->getBody($title, $message, $details, $component, $level));
        $mail->html($this->getHtmlBody($title, $message, $details, $component, $level));

        $subject = MailUtility::sanitizeHeaderString($this->levelToString($level) . ': ' . $title);
        if ($subject === '') {
            $this->logger->warning('Dirty mail header found: "' . $title . '"');
        }

        $mail->subject($subject);

        $this->mailManager->sendMessage($mail);
    }
}
