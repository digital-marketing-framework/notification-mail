<?php

namespace DigitalMarketingFramework\Notification\Mail\GlobalConfiguration\Schema;

use DigitalMarketingFramework\Core\Notification\Schema\GlobalNotificationChannelConfigurationSchema;
use DigitalMarketingFramework\Core\SchemaDocument\Schema\StringSchema;

class GlobalMailNotificationChannelConfigurationSchema extends GlobalNotificationChannelConfigurationSchema
{
    public const KEY_SENDER = 'sender';

    public const DEFAULT_SENDER = '';

    public const KEY_RECEIVER = 'receiver';

    public const DEFAULT_RECEIVER = '';

    public function __construct()
    {
        parent::__construct();
        $this->renderingDefinition->setLabel('Notification Mail');

        $this->addProperty(static::KEY_SENDER, new StringSchema(static::DEFAULT_SENDER));
        $this->addProperty(static::KEY_RECEIVER, new StringSchema(static::DEFAULT_RECEIVER));
    }
}
