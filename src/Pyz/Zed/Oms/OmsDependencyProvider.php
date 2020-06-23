<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms;

use Pyz\Zed\MerchantOms\Communication\Plugin\Oms\CloseMerchantOrderItemCommandPlugin;
use Pyz\Zed\MerchantSalesOrder\Communication\Plugin\Oms\Condition\IsOrderPaidConditionPlugin;
use Pyz\Zed\MerchantSalesOrder\Communication\Plugin\Oms\CreateMerchantOrdersCommandPlugin;
use Spryker\Zed\Availability\Communication\Plugin\AvailabilityHandlerPlugin;
use Spryker\Zed\Availability\Communication\Plugin\Oms\AvailabilityReservationPostSaveTerminationAwareStrategyPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\Oms\Command\CreateGiftCardCommandPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\Oms\Condition\IsGiftCardConditionPlugin;
use Spryker\Zed\GiftCardMailConnector\Communication\Plugin\Oms\Command\ShipGiftCardByEmailCommandPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\SendOrderConfirmationPlugin;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\SendOrderShippedPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\OmsDependencyProvider as SprykerOmsDependencyProvider;
use Spryker\Zed\OmsProductOfferReservation\Communication\Plugin\Oms\ProductOfferOmsReservationAggregationPlugin;
use Spryker\Zed\OmsProductOfferReservation\Communication\Plugin\Oms\ProductOfferOmsReservationReaderStrategyPlugin;
use Spryker\Zed\OmsProductOfferReservation\Communication\Plugin\Oms\ProductOfferOmsReservationWriterStrategyPlugin;
use Spryker\Zed\OmsProductOfferReservation\Communication\Plugin\Oms\ProductOfferReservationPostSaveTerminationAwareStrategyPlugin;
use Spryker\Zed\ProductBundle\Communication\Plugin\Oms\ProductBundleAvailabilityHandlerPlugin;
use Spryker\Zed\ProductBundle\Communication\Plugin\Oms\ProductBundleReservationPostSaveTerminationAwareStrategyPlugin;
use Spryker\Zed\ProductOfferPackagingUnit\Communication\Plugin\Oms\ProductOfferPackagingUnitOmsReservationAggregationPlugin;
use Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Oms\ProductPackagingUnitOmsReservationAggregationPlugin;
use Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Reservation\LeadProductReservationHandlerPlugin;
use Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Reservation\LeadProductReservationPostSaveTerminationAwareStrategyPlugin;
use Spryker\Zed\SalesReturn\Communication\Plugin\Oms\Command\StartReturnCommandPlugin;
use Spryker\Zed\Shipment\Dependency\Plugin\Oms\ShipmentManualEventGrouperPlugin;
use Spryker\Zed\Shipment\Dependency\Plugin\Oms\ShipmentOrderMailExpanderPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\CancelAndRecalculateCommandByOrderPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\CancelCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\PartialCaptureCommandByOrderPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\PartialRefundCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Command\SavePartialRefundCommandPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\AuthorizationIsErrorConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\CaptureIsApprovedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\Is1HourPassedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\IsFullOrderCancelledConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\IsRefundAllowedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsAppointedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsCaptureConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsPaidConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PaymentIsRefundConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\PreAuthorizationIsErrorConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\RefundIsApprovedConditionPlugin;
use SprykerEco\Zed\Payone\Communication\Plugin\Oms\Condition\TrueConditionPlugin;

class OmsDependencyProvider extends SprykerOmsDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->extendCommandPlugins($container);
        $container = $this->extendConditionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function extendCommandPlugins(Container $container): Container
    {
        $container->extend(self::COMMAND_PLUGINS, function (CommandCollectionInterface $commandCollection) {
            $commandCollection->add(new SendOrderConfirmationPlugin(), 'Oms/SendOrderConfirmation');
            $commandCollection->add(new SendOrderShippedPlugin(), 'Oms/SendOrderShipped');
            $commandCollection->add(new ShipGiftCardByEmailCommandPlugin(), 'GiftCardMailConnector/ShipGiftCard');
            $commandCollection->add(new CreateGiftCardCommandPlugin(), 'GiftCard/CreateGiftCard');
            $commandCollection->add(new CreateMerchantOrdersCommandPlugin(), 'MerchantSalesOrder/CreateOrders');
            $commandCollection->add(new CloseMerchantOrderItemCommandPlugin(), 'MerchantOms/CloseOrderItem');
            $commandCollection->add(new StartReturnCommandPlugin(), 'Return/StartReturn');

            // ----- Payone
            $commandCollection->add(new CancelCommandPlugin(), 'Payone/Cancel');
            $commandCollection->add(new CancelAndRecalculateCommandByOrderPlugin(), 'Payone/CancelAndRecalculate');
            $commandCollection->add(new PartialCaptureCommandByOrderPlugin(), 'Payone/PartialCapture');
            $commandCollection->add(new SavePartialRefundCommandPlugin(), 'Payone/SavePartialRefund');
            $commandCollection->add(new PartialRefundCommandPlugin(), 'Payone/PartialRefund');

            return $commandCollection;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function extendConditionPlugins(Container $container): Container
    {
        $container->extend(OmsDependencyProvider::CONDITION_PLUGINS, function (ConditionCollectionInterface $conditionCollection) {
            $conditionCollection
                ->add(new IsGiftCardConditionPlugin(), 'GiftCard/IsGiftCard');
            $conditionCollection
                ->add(new IsOrderPaidConditionPlugin(), 'MerchantSalesOrder/IsOrderPaid');

            // ----- Payone
            $conditionCollection->add(new TrueConditionPlugin(), 'Payone/TrueCondition');
            $conditionCollection->add(new PaymentIsAppointedConditionPlugin(), 'Payone/PaymentIsAppointed');
            $conditionCollection->add(new Is1HourPassedConditionPlugin(), 'Payone/Is1HourPassed');
            $conditionCollection->add(new PreAuthorizationIsErrorConditionPlugin(), 'Payone/PreAuthorizationIsError');
            $conditionCollection->add(new IsFullOrderCancelledConditionPlugin(), 'Payone/IsFullOrderCancelled');
            $conditionCollection->add(new CaptureIsApprovedConditionPlugin(), 'Payone/CaptureIsApproved');
            $conditionCollection->add(new PaymentIsCaptureConditionPlugin(), 'Payone/PaymentIsCapture');
            $conditionCollection->add(new IsRefundAllowedConditionPlugin(), 'Payone/IsRefundAllowed');
            $conditionCollection->add(new RefundIsApprovedConditionPlugin(), 'Payone/RefundIsApproved');
            $conditionCollection->add(new PaymentIsRefundConditionPlugin(), 'Payone/PaymentIsRefund');
            $conditionCollection->add(new PaymentIsPaidConditionPlugin(), 'Payone/PaymentIsPaid');
            $conditionCollection->add(new AuthorizationIsErrorConditionPlugin(), 'Payone/AuthorizationIsError');

            return $conditionCollection;
        });

        return $container;
    }

    /**
     * @deprecated Use `\Pyz\Zed\Oms\OmsDependencyProvider::getReservationHandlerTerminationAwareStrategyPlugins()` instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface[]
     */
    protected function getReservationHandlerPlugins(Container $container): array
    {
        return [
            new AvailabilityHandlerPlugin(),
            new ProductBundleAvailabilityHandlerPlugin(),
            new LeadProductReservationHandlerPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsOrderMailExpanderPluginInterface[]
     */
    protected function getOmsOrderMailExpanderPlugins(Container $container): array
    {
        return [
            new ShipmentOrderMailExpanderPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsManualEventGrouperPluginInterface[]
     */
    protected function getOmsManualEventGrouperPlugins(Container $container): array
    {
        return [
            new ShipmentManualEventGrouperPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationAggregationPluginInterface[]
     */
    protected function getOmsReservationAggregationPlugins(): array
    {
        return [
            new ProductOfferPackagingUnitOmsReservationAggregationPlugin(),
            new ProductOfferOmsReservationAggregationPlugin(),
            new ProductPackagingUnitOmsReservationAggregationPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationWriterStrategyPluginInterface[]
     */
    protected function getOmsReservationWriterStrategyPlugins(): array
    {
        return [
            new ProductOfferOmsReservationWriterStrategyPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationPostSaveTerminationAwareStrategyPluginInterface[]
     */
    protected function getReservationPostSaveTerminationAwareStrategyPlugins(): array
    {
        return [
            new ProductOfferReservationPostSaveTerminationAwareStrategyPlugin(),
            new AvailabilityReservationPostSaveTerminationAwareStrategyPlugin(),
            new ProductBundleReservationPostSaveTerminationAwareStrategyPlugin(),
            new LeadProductReservationPostSaveTerminationAwareStrategyPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationReaderStrategyPluginInterface[]
     */
    protected function getOmsReservationReaderStrategyPlugins(): array
    {
        return [
            new ProductOfferOmsReservationReaderStrategyPlugin(),
        ];
    }
}
