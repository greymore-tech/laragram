<?php

namespace App\Services;

use danog\MadelineProto\API;
use danog\MadelineProto\Logger;
use danog\MadelineProto\Settings;
use danog\MadelineProto\Settings\AppInfo;
use danog\MadelineProto\Settings\Logger as LoggerSettings;

class MadelineProtoService
{
    public ?API $madelineproto = null;

    public function getClient(): ?API
    {
        if (!$this->madelineproto) {
            $configSettings = config('telegram.settings');
            $appInfoArray = $configSettings['app_info'];
            $loggerArray = $configSettings['logger'];

            // 1. Create the main Settings object.
            $settings = new Settings;

            // 2. Configure AppInfo (Verified to be correct).
            $appInfo = (new AppInfo)
                ->setApiId($appInfoArray['api_id'])
                ->setApiHash($appInfoArray['api_hash']);
            $settings->setAppInfo($appInfo);

            // 3. Configure Logger (Verified to be correct).
            $logger = (new LoggerSettings)
                ->setType($loggerArray['type'], $loggerArray['param'])
                ->setLevel($loggerArray['level']);
            $settings->setLogger($logger);

            // 4. Initialize the API using the new session path from the config.
            // No other settings are needed.
            $this->madelineproto = new API(config('telegram.session.path'), $settings);
        }

        return $this->madelineproto;
    }

    public function __call($method, $parameters)
    {
        return $this->getClient()->{$method}(...$parameters);
    }
}
