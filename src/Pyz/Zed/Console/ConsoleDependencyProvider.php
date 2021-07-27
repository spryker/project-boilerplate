<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Console;

use Pyz\Zed\Development\Communication\Console\AcceptanceCodeTestConsole;
use Pyz\Zed\Development\Communication\Console\ApiCodeTestConsole;
use Pyz\Zed\Development\Communication\Console\FunctionalCodeTestConsole;
use SecurityChecker\Command\SecurityCheckerCommand;
use Spryker\Zed\Cache\Communication\Console\EmptyAllCachesConsole;
use Spryker\Zed\CodeGenerator\Communication\Console\BundleClientCodeGeneratorConsole;
use Spryker\Zed\CodeGenerator\Communication\Console\BundleCodeGeneratorConsole;
use Spryker\Zed\CodeGenerator\Communication\Console\BundleServiceCodeGeneratorConsole;
use Spryker\Zed\CodeGenerator\Communication\Console\BundleSharedCodeGeneratorConsole;
use Spryker\Zed\CodeGenerator\Communication\Console\BundleYvesCodeGeneratorConsole;
use Spryker\Zed\CodeGenerator\Communication\Console\BundleZedCodeGeneratorConsole;
use Spryker\Zed\Console\ConsoleDependencyProvider as SprykerConsoleDependencyProvider;
use Spryker\Zed\DataExport\Communication\Console\DataExportConsole;
use Spryker\Zed\DataImport\Communication\Console\DataImportConsole;
use Spryker\Zed\DataImport\Communication\Console\DataImportDumpConsole;
use Spryker\Zed\Development\Communication\Console\CodeArchitectureSnifferConsole;
use Spryker\Zed\Development\Communication\Console\CodeFixturesConsole;
use Spryker\Zed\Development\Communication\Console\CodePhpMessDetectorConsole;
use Spryker\Zed\Development\Communication\Console\CodePhpstanConsole;
use Spryker\Zed\Development\Communication\Console\CodeStyleSnifferConsole;
use Spryker\Zed\Development\Communication\Console\CodeTestConsole;
use Spryker\Zed\Development\Communication\Console\GenerateClientIdeAutoCompletionConsole;
use Spryker\Zed\Development\Communication\Console\GenerateGlueIdeAutoCompletionConsole;
use Spryker\Zed\Development\Communication\Console\GenerateIdeAutoCompletionConsole;
use Spryker\Zed\Development\Communication\Console\GenerateServiceIdeAutoCompletionConsole;
use Spryker\Zed\Development\Communication\Console\GenerateYvesIdeAutoCompletionConsole;
use Spryker\Zed\Development\Communication\Console\GenerateZedIdeAutoCompletionConsole;
use Spryker\Zed\Development\Communication\Console\PluginUsageFinderConsole;
use Spryker\Zed\Development\Communication\Console\RemoveClientIdeAutoCompletionConsole;
use Spryker\Zed\Development\Communication\Console\RemoveGlueIdeAutoCompletionConsole;
use Spryker\Zed\Development\Communication\Console\RemoveIdeAutoCompletionConsole;
use Spryker\Zed\Development\Communication\Console\RemoveServiceIdeAutoCompletionConsole;
use Spryker\Zed\Development\Communication\Console\RemoveYvesIdeAutoCompletionConsole;
use Spryker\Zed\Development\Communication\Console\RemoveZedIdeAutoCompletionConsole;
use Spryker\Zed\DocumentationGeneratorRestApi\Communication\Console\GenerateRestApiDocumentationConsole;
use Spryker\Zed\EventBehavior\Communication\Console\EventBehaviorTriggerTimeoutConsole;
use Spryker\Zed\EventBehavior\Communication\Console\EventTriggerListenerConsole;
use Spryker\Zed\EventBehavior\Communication\Plugin\Console\EventBehaviorPostHookPlugin;
use Spryker\Zed\IndexGenerator\Communication\Console\PostgresIndexGeneratorConsole;
use Spryker\Zed\IndexGenerator\Communication\Console\PostgresIndexRemoverConsole;
use Spryker\Zed\Installer\Communication\Console\InitializeDatabaseConsole;
use Spryker\Zed\Kernel\Communication\Console\ResolvableClassCacheConsole;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Log\Communication\Console\DeleteLogFilesConsole;
use Spryker\Zed\Maintenance\Communication\Console\MaintenanceDisableConsole;
use Spryker\Zed\Maintenance\Communication\Console\MaintenanceEnableConsole;
use Spryker\Zed\Monitoring\Communication\Plugin\Console\MonitoringConsolePlugin;
use Spryker\Zed\Propel\Communication\Console\DatabaseDropConsole;
use Spryker\Zed\Propel\Communication\Console\DatabaseDropTablesConsole;
use Spryker\Zed\Propel\Communication\Console\DeleteMigrationFilesConsole;
use Spryker\Zed\Propel\Communication\Console\DeployPreparePropelConsole;
use Spryker\Zed\Propel\Communication\Console\EntityTransferGeneratorConsole;
use Spryker\Zed\Propel\Communication\Console\PropelSchemaValidatorConsole;
use Spryker\Zed\Propel\Communication\Console\PropelSchemaXmlNameValidatorConsole;
use Spryker\Zed\Propel\Communication\Console\RemoveEntityTransferConsole;
use Spryker\Zed\Propel\Communication\Plugin\Application\PropelApplicationPlugin;
use Spryker\Zed\Publisher\Communication\Console\PublisherTriggerEventsConsole;
use Spryker\Zed\Queue\Communication\Console\QueueDumpConsole;
use Spryker\Zed\Queue\Communication\Console\QueueTaskConsole;
use Spryker\Zed\Queue\Communication\Console\QueueWorkerConsole;
use Spryker\Zed\RabbitMq\Communication\Console\DeleteAllExchangesConsole;
use Spryker\Zed\RabbitMq\Communication\Console\DeleteAllQueuesConsole;
use Spryker\Zed\RabbitMq\Communication\Console\PurgeAllQueuesConsole;
use Spryker\Zed\RabbitMq\Communication\Console\QueueSetupConsole;
use Spryker\Zed\RabbitMq\Communication\Console\SetUserPermissionsConsole;
use Spryker\Zed\RestRequestValidator\Communication\Console\BuildRestApiValidationCacheConsole;
use Spryker\Zed\RestRequestValidator\Communication\Console\RemoveRestApiValidationCacheConsole;
use Spryker\Zed\Router\Communication\Plugin\Console\BackendGatewayRouterCacheWarmUpConsole;
use Spryker\Zed\Router\Communication\Plugin\Console\BackofficeRouterCacheWarmUpConsole;
use Spryker\Zed\Router\Communication\Plugin\Console\MerchantPortalRouterCacheWarmUpConsole;
use Spryker\Zed\Router\Communication\Plugin\Console\RouterDebugBackendApiConsole;
use Spryker\Zed\Router\Communication\Plugin\Console\RouterDebugBackendGatewayConsole;
use Spryker\Zed\Router\Communication\Plugin\Console\RouterDebugBackofficeConsole;
use Spryker\Zed\Scheduler\Communication\Console\SchedulerCleanConsole;
use Spryker\Zed\Scheduler\Communication\Console\SchedulerResumeConsole;
use Spryker\Zed\Scheduler\Communication\Console\SchedulerSetupConsole;
use Spryker\Zed\Scheduler\Communication\Console\SchedulerSuspendConsole;
use Spryker\Zed\Search\Communication\Console\GenerateSourceMapConsole;
use Spryker\Zed\Search\Communication\Console\RemoveSourceMapConsole;
use Spryker\Zed\Search\Communication\Console\SearchConsole;
use Spryker\Zed\Search\Communication\Console\SearchSetupSourcesConsole;
use Spryker\Zed\Session\Communication\Console\SessionRemoveLockConsole;
use Spryker\Zed\SetupFrontend\Communication\Console\InstallProjectDependenciesConsole;
use Spryker\Zed\SetupFrontend\Communication\Console\Npm\RunnerConsole;
use Spryker\Zed\SetupFrontend\Communication\Console\ZedBuildFrontendConsole;
use Spryker\Zed\SetupFrontend\Communication\Console\ZedInstallDependenciesConsole;
use Spryker\Zed\Storage\Communication\Console\StorageDeleteAllConsole;
use Spryker\Zed\StorageRedis\Communication\Console\StorageRedisExportRdbConsole;
use Spryker\Zed\StorageRedis\Communication\Console\StorageRedisImportRdbConsole;
use Spryker\Zed\Synchronization\Communication\Console\ExportSynchronizedDataConsole;
use Spryker\Zed\Transfer\Communication\Console\DataBuilderGeneratorConsole;
use Spryker\Zed\Transfer\Communication\Console\RemoveDataBuilderConsole;
use Spryker\Zed\Transfer\Communication\Console\RemoveTransferConsole;
use Spryker\Zed\Transfer\Communication\Console\TransferGeneratorConsole;
use Spryker\Zed\Transfer\Communication\Console\ValidatorConsole;
use Spryker\Zed\Translator\Communication\Console\CleanTranslationCacheConsole;
use Spryker\Zed\Translator\Communication\Console\GenerateTranslationCacheConsole;
use Spryker\Zed\Twig\Communication\Console\CacheWarmerConsole;
use Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin;
use Spryker\Zed\Uuid\Communication\Console\UuidGeneratorConsole;
use Spryker\Zed\ZedNavigation\Communication\Console\BuildNavigationConsole;
use Spryker\Zed\ZedNavigation\Communication\Console\RemoveNavigationCacheConsole;
use SprykerSdk\Spryk\Console\SprykBuildConsole;
use SprykerSdk\Spryk\Console\SprykDumpConsole;
use SprykerSdk\Spryk\Console\SprykRunConsole;
use SprykerSdk\Zed\Benchmark\Communication\Console\BenchmarkRunConsole;
use SprykerSdk\Zed\ComposerConstrainer\Communication\Console\ComposerConstraintConsole;
use Stecman\Component\Symfony\Console\BashCompletion\CompletionCommand;

/**
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @method \Pyz\Zed\Console\ConsoleConfig getConfig()
 */
class ConsoleDependencyProvider extends SprykerConsoleDependencyProvider
{
    protected const COMMAND_SEPARATOR = ':';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    protected function getConsoleCommands(Container $container): array
    {
        $commands = [
            new CacheWarmerConsole(),
            new BuildNavigationConsole(),
            new RemoveNavigationCacheConsole(),
            new BuildRestApiValidationCacheConsole(),
            new RemoveRestApiValidationCacheConsole(),
            new EmptyAllCachesConsole(),
            new TransferGeneratorConsole(),
            new RemoveTransferConsole(),
            new EntityTransferGeneratorConsole(),
            new RemoveEntityTransferConsole(),
            new InitializeDatabaseConsole(),
            new SearchConsole(),
            new GenerateSourceMapConsole(),
            new RemoveSourceMapConsole(),
            new SearchSetupSourcesConsole(),
            new SessionRemoveLockConsole(),
            new QueueTaskConsole(),
            new QueueWorkerConsole(),
            new DataImportConsole(),

            // Publish and Synchronization
            new EventBehaviorTriggerTimeoutConsole(),
            new PublisherTriggerEventsConsole(),
            new ExportSynchronizedDataConsole(),

            // Setup commands
            new RunnerConsole(),
            new DeployPreparePropelConsole(),

            new DatabaseDropConsole(),
            new DatabaseDropTablesConsole(),
            new DeleteMigrationFilesConsole(),

            new DeleteLogFilesConsole(),
            new StorageRedisExportRdbConsole(),
            new StorageRedisImportRdbConsole(),
            new StorageDeleteAllConsole(),

            new InstallProjectDependenciesConsole(),
            new ZedBuildFrontendConsole(),
            new ZedInstallDependenciesConsole(),

            new DeleteAllQueuesConsole(),
            new PurgeAllQueuesConsole(),
            new DeleteAllExchangesConsole(),
            new QueueSetupConsole(),
            new SetUserPermissionsConsole(),

            new MaintenanceEnableConsole(),
            new MaintenanceDisableConsole(),

            new UuidGeneratorConsole(),

            new CleanTranslationCacheConsole(),
            new GenerateTranslationCacheConsole(),

            new SchedulerSetupConsole(),
            new SchedulerCleanConsole(),
            new SchedulerSuspendConsole(),
            new SchedulerResumeConsole(),

            new BackofficeRouterCacheWarmUpConsole(),
            new BackendGatewayRouterCacheWarmUpConsole(),
            new MerchantPortalRouterCacheWarmUpConsole(),

            new ResolvableClassCacheConsole(),

            new DataExportConsole(),
        ];

        $propelCommands = $container->getLocator()->propel()->facade()->getConsoleCommands();
        $commands = array_merge($commands, $propelCommands);

        if ($this->getConfig()->isDevelopmentConsoleCommandsEnabled()) {
            $commands[] = new CodeTestConsole();
            $commands[] = new CodeFixturesConsole();
            $commands[] = new AcceptanceCodeTestConsole();
            $commands[] = new FunctionalCodeTestConsole();
            $commands[] = new ApiCodeTestConsole();
            $commands[] = new CodeStyleSnifferConsole();
            $commands[] = new CodeArchitectureSnifferConsole();
            $commands[] = new CodePhpstanConsole();
            $commands[] = new CodePhpMessDetectorConsole();
            $commands[] = new ValidatorConsole();
            $commands[] = new BundleCodeGeneratorConsole();
            $commands[] = new BundleYvesCodeGeneratorConsole();
            $commands[] = new BundleZedCodeGeneratorConsole();
            $commands[] = new BundleServiceCodeGeneratorConsole();
            $commands[] = new BundleSharedCodeGeneratorConsole();
            $commands[] = new BundleClientCodeGeneratorConsole();
            $commands[] = new GenerateZedIdeAutoCompletionConsole();
            $commands[] = new RemoveZedIdeAutoCompletionConsole();
            $commands[] = new GenerateClientIdeAutoCompletionConsole();
            $commands[] = new RemoveClientIdeAutoCompletionConsole();
            $commands[] = new GenerateServiceIdeAutoCompletionConsole();
            $commands[] = new RemoveServiceIdeAutoCompletionConsole();
            $commands[] = new GenerateYvesIdeAutoCompletionConsole();
            $commands[] = new RemoveYvesIdeAutoCompletionConsole();
            $commands[] = new GenerateIdeAutoCompletionConsole();
            $commands[] = new RemoveIdeAutoCompletionConsole();
            $commands[] = new GenerateGlueIdeAutoCompletionConsole();
            $commands[] = new RemoveGlueIdeAutoCompletionConsole();
            $commands[] = new DataBuilderGeneratorConsole();
            $commands[] = new RemoveDataBuilderConsole();
            $commands[] = new CompletionCommand();
            $commands[] = new PropelSchemaValidatorConsole();
            $commands[] = new PropelSchemaXmlNameValidatorConsole();
            $commands[] = new DataImportDumpConsole();
            $commands[] = new PluginUsageFinderConsole();
            $commands[] = new PostgresIndexGeneratorConsole();
            $commands[] = new PostgresIndexRemoverConsole();
            $commands[] = new GenerateRestApiDocumentationConsole();
            $commands[] = new QueueDumpConsole();
            $commands[] = new EventTriggerListenerConsole();
            $commands[] = new RouterDebugBackofficeConsole();
            $commands[] = new RouterDebugBackendGatewayConsole();
            $commands[] = new RouterDebugBackendApiConsole();

            $commands[] = new SprykRunConsole();
            $commands[] = new SprykDumpConsole();
            $commands[] = new SprykBuildConsole();
            $commands[] = new ComposerConstraintConsole();

            if (class_exists(SecurityCheckerCommand::class)) {
                $commands[] = new SecurityCheckerCommand();
            }

            if (class_exists(BenchmarkRunConsole::class)) {
                $commands[] = new BenchmarkRunConsole();
            }
        }

        return $commands;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
     */
    public function getConsolePostRunHookPlugins(Container $container)
    {
        return [
            new EventBehaviorPostHookPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    public function getApplicationPlugins(Container $container): array
    {
        $applicationPlugins = parent::getApplicationPlugins($container);
        $applicationPlugins[] = new PropelApplicationPlugin();
        $applicationPlugins[] = new TwigApplicationPlugin();

        return $applicationPlugins;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Monitoring\Communication\Plugin\Console\MonitoringConsolePlugin[]
     */
    public function getEventSubscriber(Container $container): array
    {
        return [
            new MonitoringConsolePlugin(),
        ];
    }
}
