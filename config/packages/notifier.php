<?php
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework) {
$framework->notifier()
->texterTransport('twilio', env('TWILIO_DSN'))
;
};