<?php

namespace DigitalMarketingFramework\Notification\Mail;

use DigitalMarketingFramework\Core\Initialization;
use DigitalMarketingFramework\Core\Notification\NotificationChannelInterface;
use DigitalMarketingFramework\Core\Registry\RegistryDomain;
use DigitalMarketingFramework\Core\Registry\RegistryInterface;
use DigitalMarketingFramework\Mail\Manager\DefaultMailManager;
use DigitalMarketingFramework\Mail\Manager\MailManagerInterface;
use DigitalMarketingFramework\Notification\Mail\GlobalConfiguration\Schema\GlobalMailNotificationChannelConfigurationSchema;
use DigitalMarketingFramework\Notification\Mail\Notification\MailNotificationChannel;

class NotificationMailInitialization extends Initialization
{
    protected const PLUGINS = [
        RegistryDomain::CORE => [
            NotificationChannelInterface::class => [
                MailNotificationChannel::class,
            ],
        ],
    ];

    protected const SCHEMA_MIGRATIONS = [];

    public function __construct(
        protected ?MailManagerInterface $mailManager = null,
        string $packageAlias = '',
    ) {
        parent::__construct('notification-mail', '1.0.0', $packageAlias, new GlobalMailNotificationChannelConfigurationSchema());
    }

    protected function getAdditionalPluginArguments(string $interface, string $pluginClass, RegistryInterface $registry): array
    {
        if ($pluginClass === MailNotificationChannel::class) {
            return [$this->mailManager ?? $registry->createObject(DefaultMailManager::class)];
        }

        return parent::getAdditionalPluginArguments($interface, $pluginClass, $registry);
    }
}
