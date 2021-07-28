<?php

use Monolog\Logger;
use Pyz\Shared\Scheduler\SchedulerConfig;
use Spryker\Client\RabbitMq\Model\RabbitMqAdapter;
use Spryker\Glue\Log\Plugin\GlueLoggerConfigPlugin;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Application\Log\Config\SprykerLoggerConfig;
use Spryker\Shared\DataImport\DataImportConstants;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\ErrorHandler\ErrorRenderer\WebHtmlErrorRenderer;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\EventBehavior\EventBehaviorConstants;
use Spryker\Shared\GlueApplication\GlueApplicationConstants;
use Spryker\Shared\Http\HttpConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\Monitoring\MonitoringConstants;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Shared\Queue\QueueConfig;
use Spryker\Shared\Queue\QueueConstants;
use Spryker\Shared\RabbitMq\RabbitMqEnv;
use Spryker\Shared\Router\RouterConstants;
use Spryker\Shared\Scheduler\SchedulerConstants;
use Spryker\Shared\SchedulerJenkins\SchedulerJenkinsConfig;
use Spryker\Shared\SchedulerJenkins\SchedulerJenkinsConstants;
use Spryker\Shared\SecuritySystemUser\SecuritySystemUserConstants;
use Spryker\Shared\Session\SessionConfig;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Shared\SessionRedis\SessionRedisConfig;
use Spryker\Shared\SessionRedis\SessionRedisConstants;
use Spryker\Shared\Storage\StorageConstants;
use Spryker\Shared\StorageRedis\StorageRedisConstants;
use Spryker\Shared\Testify\TestifyConstants;
use Spryker\Shared\Translator\TranslatorConstants;
use Spryker\Shared\User\UserConstants;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use Spryker\Yves\Application\YvesBootstrap;
use Spryker\Zed\Application\Communication\Bootstrap\ZedBootstrap;
use Spryker\Zed\Log\Communication\Plugin\ZedLoggerConfigPlugin;
use Spryker\Zed\Propel\PropelConfig;
use Symfony\Component\HttpFoundation\Cookie;

// ############################################################################
// ############################## PRODUCTION CONFIGURATION ####################
// ############################################################################

// ----------------------------------------------------------------------------
// ------------------------------ CODEBASE: TO REMOVE -------------------------
// ----------------------------------------------------------------------------

$sprykerBackendHost = getenv('SPRYKER_BE_HOST') ?: 'not-configured-host';
$sprykerFrontendHost = getenv('SPRYKER_FE_HOST') ?: 'not-configured-host';

$config[KernelConstants::SPRYKER_ROOT] = APPLICATION_ROOT_DIR . '/vendor/spryker';

$config[KernelConstants::RESOLVABLE_CLASS_NAMES_CACHE_ENABLED] = true;
$config[KernelConstants::RESOLVED_INSTANCE_CACHE_ENABLED] = true;

$config[KernelConstants::PROJECT_NAMESPACE] = 'Pyz';
$config[KernelConstants::PROJECT_NAMESPACES] = [
    'Pyz',
];
$config[KernelConstants::CORE_NAMESPACES] = [
    'SprykerShop',
    'SprykerEco',
    'Spryker',
    'SprykerSdk',
];

// >>> ROUTER

$config[RouterConstants::YVES_SSL_EXCLUDED_ROUTE_NAMES] = [
    'healthCheck' => '/health-check',
];
$config[RouterConstants::ZED_SSL_EXCLUDED_ROUTE_NAMES] = [
    'healthCheck' => 'health-check/index',
];

// >>> ERROR HANDLING

$config[ErrorHandlerConstants::YVES_ERROR_PAGE] = APPLICATION_ROOT_DIR . '/public/Yves/errorpage/5xx.html';
$config[ErrorHandlerConstants::ZED_ERROR_PAGE] = APPLICATION_ROOT_DIR . '/public/Backoffice/errorpage/5xx.html';
$config[ErrorHandlerConstants::ERROR_RENDERER] = WebHtmlErrorRenderer::class;

// >>> TRANSLATOR

$config[TranslatorConstants::TRANSLATION_ZED_FALLBACK_LOCALES] = [
    'de_DE' => ['en_US'],
];

// >>> MONITORING

$config[MonitoringConstants::IGNORABLE_TRANSACTIONS] = [
    '_profiler',
    '_wdt',
];

// >>> TESTING

if (class_exists(TestifyConstants::class)) {
    $config[TestifyConstants::GLUE_OPEN_API_SCHEMA] = APPLICATION_SOURCE_DIR . '/Generated/Glue/Specification/spryker_rest_api.schema.yml';
    $config[TestifyConstants::BOOTSTRAP_CLASS_YVES] = YvesBootstrap::class;
    $config[TestifyConstants::BOOTSTRAP_CLASS_ZED] = ZedBootstrap::class;
}

// ----------------------------------------------------------------------------
// ------------------------------ SECURITY ------------------------------------
// ----------------------------------------------------------------------------

$trustedHosts
    = $config[HttpConstants::ZED_TRUSTED_HOSTS]
    = $config[HttpConstants::YVES_TRUSTED_HOSTS]
    = array_filter(explode(',', getenv('SPRYKER_TRUSTED_HOSTS') ?: ''));

$config[KernelConstants::DOMAIN_WHITELIST] = array_merge($trustedHosts, [
    $sprykerBackendHost,
    $sprykerFrontendHost,
]);
$config[KernelConstants::STRICT_DOMAIN_REDIRECT] = true;

$config[HttpConstants::ZED_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED]
    = $config[HttpConstants::YVES_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED]
    = $config[HttpConstants::GLUE_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED]
    = true;
$config[HttpConstants::ZED_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG]
    = $config[HttpConstants::YVES_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG]
    = $config[HttpConstants::GLUE_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG]
    = [
    'max_age' => 31536000,
    'include_sub_domains' => true,
    'preload' => true,
];

$config[LogConstants::LOG_SANITIZE_FIELDS] = [
    'password',
];

// ----------------------------------------------------------------------------
// ------------------------------ AUTHENTICATION ------------------------------
// ----------------------------------------------------------------------------


// >> ZED REQUEST

$config[UserConstants::USER_SYSTEM_USERS] = [
    'yves_system',
];
$config[SecuritySystemUserConstants::AUTH_DEFAULT_CREDENTIALS] = [
    'yves_system' => [
        'token' => getenv('SPRYKER_ZED_REQUEST_TOKEN') ?: '',
    ],
];

// ----------------------------------------------------------------------------
// ------------------------------ SERVICES ------------------------------------
// ----------------------------------------------------------------------------

// >>> DATABASE

$config[PropelConstants::ZED_DB_ENGINE]
    = $config[PropelQueryBuilderConstants::ZED_DB_ENGINE]
    = strtolower(getenv('SPRYKER_DB_ENGINE') ?: '') ?: PropelConfig::DB_ENGINE_MYSQL;
$config[PropelConstants::ZED_DB_HOST] = getenv('SPRYKER_DB_HOST');
$config[PropelConstants::ZED_DB_PORT] = getenv('SPRYKER_DB_PORT');
$config[PropelConstants::ZED_DB_USERNAME] = getenv('SPRYKER_DB_USERNAME');
$config[PropelConstants::ZED_DB_PASSWORD] = getenv('SPRYKER_DB_PASSWORD');
$config[PropelConstants::ZED_DB_DATABASE] = getenv('SPRYKER_DB_DATABASE');
$config[PropelConstants::ZED_DB_REPLICAS] = json_decode(getenv('SPRYKER_DB_REPLICAS') ?: '[]', true);
$config[PropelConstants::USE_SUDO_TO_MANAGE_DATABASE] = false;

// >>> DATA IMPORT
$config[DataImportConstants::IS_BULK_MODE_ENABLED] = false;

// >>> STORAGE

$config[StorageConstants::STORAGE_KV_SOURCE] = strtolower(getenv('SPRYKER_KEY_VALUE_STORE_ENGINE')) ?: 'redis';
$config[StorageRedisConstants::STORAGE_REDIS_PERSISTENT_CONNECTION] = true;
$config[StorageRedisConstants::STORAGE_REDIS_SCHEME] = getenv('SPRYKER_KEY_VALUE_STORE_PROTOCOL') ?: 'tcp';
$config[StorageRedisConstants::STORAGE_REDIS_HOST] = getenv('SPRYKER_KEY_VALUE_STORE_HOST');
$config[StorageRedisConstants::STORAGE_REDIS_PORT] = getenv('SPRYKER_KEY_VALUE_STORE_PORT');
$config[StorageRedisConstants::STORAGE_REDIS_PASSWORD] = getenv('SPRYKER_KEY_VALUE_STORE_PASSWORD');
$config[StorageRedisConstants::STORAGE_REDIS_DATABASE] = getenv('SPRYKER_KEY_VALUE_STORE_NAMESPACE') ?: 1;
$config[StorageRedisConstants::STORAGE_REDIS_DATA_SOURCE_NAMES] = json_decode(getenv('SPRYKER_KEY_VALUE_STORE_SOURCE_NAMES') ?: '[]', true) ?: [];
$config[StorageRedisConstants::STORAGE_REDIS_CONNECTION_OPTIONS] = json_decode(getenv('SPRYKER_KEY_VALUE_STORE_CONNECTION_OPTIONS') ?: '[]', true) ?: [];

// >>> SESSION

$config[SecuritySystemUserConstants::SYSTEM_USER_SESSION_REDIS_LIFE_TIME] = 20;
$config[SessionRedisConstants::LOCKING_TIMEOUT_MILLISECONDS] = 0;
$config[SessionRedisConstants::LOCKING_RETRY_DELAY_MICROSECONDS] = 0;
$config[SessionRedisConstants::LOCKING_LOCK_TTL_MILLISECONDS] = 0;

// >>> SESSION BACKOFFICE

$config[SessionConstants::ZED_SESSION_COOKIE_NAME]
    = $config[SessionConstants::ZED_SESSION_COOKIE_DOMAIN]
    = $sprykerBackendHost;

$config[SessionConstants::ZED_SESSION_SAVE_HANDLER] = SessionRedisConfig::SESSION_HANDLER_REDIS;
$config[SessionRedisConstants::ZED_SESSION_REDIS_SCHEME] = getenv('SPRYKER_SESSION_BE_PROTOCOL') ?: 'tcp';
$config[SessionRedisConstants::ZED_SESSION_REDIS_HOST] = getenv('SPRYKER_SESSION_BE_HOST');
$config[SessionRedisConstants::ZED_SESSION_REDIS_PORT] = getenv('SPRYKER_SESSION_BE_PORT');
$config[SessionRedisConstants::ZED_SESSION_REDIS_PASSWORD] = getenv('SPRYKER_SESSION_BE_PASSWORD');
$config[SessionRedisConstants::ZED_SESSION_REDIS_DATABASE] = getenv('SPRYKER_SESSION_BE_NAMESPACE') ?: 2;

$config[SessionConstants::ZED_SESSION_TIME_TO_LIVE]
    = $config[SessionRedisConstants::ZED_SESSION_TIME_TO_LIVE]
    = SessionConfig::SESSION_LIFETIME_1_HOUR;
$config[SessionConstants::ZED_SESSION_COOKIE_TIME_TO_LIVE] = SessionConfig::SESSION_LIFETIME_BROWSER_SESSION;
$config[SessionConstants::ZED_SESSION_COOKIE_SAMESITE] = getenv('SPRYKER_ZED_SESSION_COOKIE_SAMESITE') ?: Cookie::SAMESITE_STRICT;
$config[SessionConstants::ZED_SESSION_PERSISTENT_CONNECTION] = true;
// >>> LOGGING

// Due to some deprecation notices we silence all deprecations for the time being
$config[ErrorHandlerConstants::ERROR_LEVEL] = E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED;
$config[ErrorHandlerConstants::ERROR_LEVEL_LOG_ONLY] = E_DEPRECATED | E_USER_DEPRECATED;

$config[LogConstants::LOGGER_CONFIG] = SprykerLoggerConfig::class;
$config[LogConstants::LOGGER_CONFIG_ZED] = ZedLoggerConfigPlugin::class;
$config[LogConstants::LOGGER_CONFIG_GLUE] = GlueLoggerConfigPlugin::class;

$config[LogConstants::LOG_QUEUE_NAME] = 'log-queue';
$config[LogConstants::LOG_ERROR_QUEUE_NAME] = 'error-log-queue';

$config[LogConstants::LOG_LEVEL] = Logger::INFO;
$config[PropelConstants::LOG_FILE_PATH]
    = $config[EventConstants::LOG_FILE_PATH]
    = $config[LogConstants::LOG_FILE_PATH_YVES]
    = $config[LogConstants::LOG_FILE_PATH_ZED]
    = $config[LogConstants::LOG_FILE_PATH_GLUE]
    = $config[LogConstants::LOG_FILE_PATH]
    = $config[QueueConstants::QUEUE_WORKER_OUTPUT_FILE_NAME]
    = getenv('SPRYKER_LOG_STDOUT') ?: 'php://stderr';
$config[LogConstants::EXCEPTION_LOG_FILE_PATH_YVES]
    = $config[LogConstants::EXCEPTION_LOG_FILE_PATH_ZED]
    = $config[LogConstants::EXCEPTION_LOG_FILE_PATH_GLUE]
    = $config[LogConstants::EXCEPTION_LOG_FILE_PATH]
    = getenv('SPRYKER_LOG_STDERR') ?: 'php://stderr';

// >>> QUEUE

$config[EventBehaviorConstants::EVENT_BEHAVIOR_TRIGGERING_ACTIVE] = true;

$config[EventConstants::MAX_RETRY_ON_FAIL] = 5;
$config[QueueConstants::QUEUE_PROCESS_TRIGGER_INTERVAL_MICROSECONDS] = 1001;

$config[QueueConstants::QUEUE_ADAPTER_CONFIGURATION] = [
    EventConstants::EVENT_QUEUE => [
        QueueConfig::CONFIG_QUEUE_ADAPTER => RabbitMqAdapter::class,
        QueueConfig::CONFIG_MAX_WORKER_NUMBER => 5,
    ],
];

$config[QueueConstants::QUEUE_ADAPTER_CONFIGURATION_DEFAULT] = [
    QueueConfig::CONFIG_QUEUE_ADAPTER => RabbitMqAdapter::class,
    QueueConfig::CONFIG_MAX_WORKER_NUMBER => 1,
];

$config[RabbitMqEnv::RABBITMQ_API_HOST] = getenv('SPRYKER_BROKER_API_HOST');
$config[RabbitMqEnv::RABBITMQ_API_PORT] = getenv('SPRYKER_BROKER_API_PORT');
$config[RabbitMqEnv::RABBITMQ_API_USERNAME] = getenv('SPRYKER_BROKER_API_USERNAME');
$config[RabbitMqEnv::RABBITMQ_API_PASSWORD] = getenv('SPRYKER_BROKER_API_PASSWORD');
$config[RabbitMqEnv::RABBITMQ_API_VIRTUAL_HOST] = getenv('SPRYKER_BROKER_NAMESPACE');

$config[RabbitMqEnv::RABBITMQ_ENABLE_RUNTIME_SETTING_UP] = false;

$rabbitConnections = json_decode(getenv('SPRYKER_BROKER_CONNECTIONS') ?: '[]', true);
$defaultConnection = [
    RabbitMqEnv::RABBITMQ_HOST => getenv('SPRYKER_BROKER_HOST'),
    RabbitMqEnv::RABBITMQ_PORT => getenv('SPRYKER_BROKER_PORT'),
    RabbitMqEnv::RABBITMQ_PASSWORD => getenv('SPRYKER_BROKER_PASSWORD'),
    RabbitMqEnv::RABBITMQ_USERNAME => getenv('SPRYKER_BROKER_USERNAME'),
];

$config[RabbitMqEnv::RABBITMQ_CONNECTIONS] = [];
foreach ($rabbitConnections as $key => $connection) {
    $config[RabbitMqEnv::RABBITMQ_CONNECTIONS][$key] = $defaultConnection;
    $config[RabbitMqEnv::RABBITMQ_CONNECTIONS][$key][RabbitMqEnv::RABBITMQ_CONNECTION_NAME] = $key . '-connection';
    $config[RabbitMqEnv::RABBITMQ_CONNECTIONS][$key][RabbitMqEnv::RABBITMQ_STORE_NAMES] = [$key];
    foreach ($connection as $constant => $value) {
        $config[RabbitMqEnv::RABBITMQ_CONNECTIONS][$key][constant(RabbitMqEnv::class . '::' . $constant)] = $value;
    }
    $config[RabbitMqEnv::RABBITMQ_CONNECTIONS][$key][RabbitMqEnv::RABBITMQ_DEFAULT_CONNECTION] = $key === APPLICATION_STORE;
}

// >>> SCHEDULER
$config[SchedulerConstants::ENABLED_SCHEDULERS] = [
    SchedulerConfig::SCHEDULER_JENKINS,
];

$config[SchedulerJenkinsConstants::JENKINS_CONFIGURATION] = [
    SchedulerConfig::SCHEDULER_JENKINS => [
        SchedulerJenkinsConfig::SCHEDULER_JENKINS_BASE_URL => sprintf(
            '%s://%s:%s/',
            getenv('SPRYKER_SCHEDULER_PROTOCOL') ?: 'http',
            getenv('SPRYKER_SCHEDULER_HOST'),
            getenv('SPRYKER_SCHEDULER_PORT')
        ),
    ],
];

$config[SchedulerJenkinsConstants::JENKINS_TEMPLATE_PATH] = getenv('SPRYKER_JENKINS_TEMPLATE_PATH') ?: null;

// ----------------------------------------------------------------------------
// ------------------------------ ZED -----------------------------------------
// ----------------------------------------------------------------------------

$config[ZedRequestConstants::ZED_API_SSL_ENABLED] = (bool)getenv('SPRYKER_ZED_SSL_ENABLED');
$backofficeDefaultPort = $config[ZedRequestConstants::ZED_API_SSL_ENABLED] ? 443 : 80;
$zedPort = ((int)getenv('SPRYKER_ZED_PORT')) ?: $backofficeDefaultPort;
$config[ZedRequestConstants::HOST_ZED_API] = sprintf(
    '%s%s',
    getenv('SPRYKER_ZED_HOST') ?: 'not-configured-host',
    $zedPort !== $backofficeDefaultPort ? ':' . $zedPort : ''
);
$config[ZedRequestConstants::BASE_URL_ZED_API] = sprintf(
    'http://%s',
    $config[ZedRequestConstants::HOST_ZED_API]
);
$config[ZedRequestConstants::BASE_URL_SSL_ZED_API] = sprintf(
    'https://%s',
    $config[ZedRequestConstants::HOST_ZED_API]
);

// ----------------------------------------------------------------------------
// ------------------------------ BACKOFFICE ----------------------------------
// ----------------------------------------------------------------------------

$backofficePort = (int)(getenv('SPRYKER_BE_PORT')) ?: 443;
$config[ApplicationConstants::BASE_URL_ZED] = sprintf(
    'https://%s%s',
    $sprykerBackendHost,
    $backofficePort !== 443 ? ':' . $backofficePort : ''
);

// ----------------------------------------------------------------------------
// ------------------------------ API -----------------------------------------
// ----------------------------------------------------------------------------

$glueHost = getenv('SPRYKER_API_HOST') ?: 'localhost';
$gluePort = (int)(getenv('SPRYKER_API_PORT')) ?: 443;
$config[GlueApplicationConstants::GLUE_APPLICATION_DOMAIN]
    = sprintf(
        'https://%s%s',
        $glueHost,
        $gluePort !== 443 ? ':' . $gluePort : ''
    );

if (class_exists(TestifyConstants::class)) {
    $config[TestifyConstants::GLUE_APPLICATION_DOMAIN] = $config[GlueApplicationConstants::GLUE_APPLICATION_DOMAIN];
}

$config[GlueApplicationConstants::GLUE_APPLICATION_CORS_ALLOW_ORIGIN] = getenv('SPRYKER_GLUE_APPLICATION_CORS_ALLOW_ORIGIN') ?: '';
