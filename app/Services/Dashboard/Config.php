<?php

namespace App\Services\Dashboard;

use Auth;

class Config
{      
    const deliveryTimeUrl = 'customers/get-deliverytime-byzone';
    const saveSelectionUrl = 'customers/save-selections';
    const listAllPlansUrl = 'customers/getPlans';
    const listAllInvoiceUrl = 'customers/getInvoices';
    const cancellAllPlansUrl = 'customers/cancell-all-plans';
    const cancellPlansUrl = 'customers/cancell-plans';
    const saveStopTillDateUrl = 'customers/save-stoptill-date';
    const saveStopAllTillDateUrl = 'customers/save-stop-all-till-date';
    const cancelPausedDateUrl = 'customers/cancel-paused-date';
    const cardsUrl = '/getcards'; 
    const creditCardSaveUrl = '/customers/save-card'; 
    const billingInfoAddressSaveUrl = '/customers/update-info-address'; 
    const menuPageUrl = '/customers/menu-page'; 
    const nextDeliveryDateUrl = '/customers/next-delivery-date'; 
    const updateCardDefaultUrl = 'customers/update-default-card';
    const updateProfileUrl = 'customers/update-profile';
    const updatePasswordUrl = 'customers/update-password';
    const updateDeliveryUrl = 'customers/update-delivery-zone-timing';
    const saveNewPlanUrl = 'customers/new-plan';
    const subscriptionIdsUrl = 'customers/subscriptionids';
    const storeCouponUrl = 'customers/store-coupon';
    const orderSubscriptionSummaryUrl = 'customers/order-subscription-summary-client';
    const removeCouponUrl = 'customers/remove-coupon';
    const updatePlanUrl = 'customers/client/updateplan';
    const futureDeliveryTimingScheduleUrl = 'customers/future-delivery-timing-schedule';
    const previousWeeksSubscriptionUrl = 'customers/subscriptions/previousweeks';
    const menusUrl = 'customers/subscriptions/menus';
    const pastSubUrl = 'customers/subscriptions/past';
    const pastWeeksUrl = 'customers/subscriptions/past-weeks';
    const pastMenusUrl = 'customers/subscriptions/past-menus';
    const cancelSubscriptionCycleUrl = 'dashboard/billing-issue/cancel-subscription-cycle';
    const cancelSubscriptionUrl = 'dashboard/billing-issue/cancel-subscription';
    const viewSubscriptionsUrl = 'dashboard/billing-issue/view-subscriptions';
    const dashboardUrl = 'dashboard';
    const chargeCardUrl = 'dashboard/billing-issue/charge-card';
    const updateCardAndBillUrl = 'dashboard/billing-issue/update-card-and-bill';
    const updateDeliveryZoneUrl = 'dashboard/setup-delivery-zone-timing/update-delivery-zone-timing';
    const deliveryTimingsSettingsUrl = 'dashboard/delivery/delivery-timings-settings';
    

    public function getConfigIndexUrls()
    {
        return array(
            'userID' => Auth::id(),
            'deliveryTimeUrl' => self::deliveryTimeUrl,
            'saveSelectionUrl' => self::saveSelectionUrl,
            'listAllPlansUrl' => self::listAllPlansUrl,
            'listAllInvoiceUrl' => self::listAllInvoiceUrl,
            'cancellAllPlansUrl' => self::cancellAllPlansUrl,
            'cancellPlansUrl' => self::cancellPlansUrl,
            'saveStopTillDateUrl' => self::saveStopTillDateUrl,
            'saveStopAllTillDateUrl' => self::saveStopAllTillDateUrl,
            'cancelPausedDateUrl' => self::cancelPausedDateUrl,
            'cardsUrl' => self::cardsUrl,
            'creditCardSaveUrl' => self::creditCardSaveUrl,
            'billingInfoAddressSaveUrl' => self::billingInfoAddressSaveUrl,
            'menuPageUrl'   => self::menuPageUrl,
            'nextDeliveryDateUrl' => self::nextDeliveryDateUrl,
            'updateCardDefaultUrl' => self::updateCardDefaultUrl,
            'updateProfileUrl' => self::updateProfileUrl,
            'updatePasswordUrl' => self::updatePasswordUrl,
            'updateDeliveryUrl' => self::updateDeliveryUrl,
            'saveNewPlanUrl'     => self::saveNewPlanUrl,
            'subscriptionIdsUrl'       => self::subscriptionIdsUrl,
            'storeCouponUrl' => self::storeCouponUrl,
            'orderSubscriptionSummaryUrl' => self::orderSubscriptionSummaryUrl,
            'removeCouponUrl'       => self::removeCouponUrl,
            'updatePlanUrl'       => self::updatePlanUrl,
            'futureDeliveryTimingScheduleUrl' => self::futureDeliveryTimingScheduleUrl,
            'previousWeeksSubscriptionUrl' => self::previousWeeksSubscriptionUrl,
            'deliveryTimingsSettingsUrl' => self::deliveryTimingsSettingsUrl,
            'pastSubUrl' => self::pastSubUrl,
            'menusUrl' => self::menusUrl,
            'pastWeeksUrl' => self::pastWeeksUrl,
            'pastMenusUrl' => self::pastMenusUrl,
            'plans' => []
        );
    }

    public function getBillingIssuePageUrl()
    {
        return array(
            'cancelSubscriptionCycleUrl' => self::cancelSubscriptionCycleUrl,
            'cancelSubscriptionUrl' => self::cancelSubscriptionUrl,
            'viewSubscriptionsUrl' => self::viewSubscriptionsUrl,
            'dashboardUrl' => self::dashboardUrl,
            'chargeCardUrl' => self::chargeCardUrl,
            'updateCardAndBillUrl' => self::updateCardAndBillUrl
        );
    }

    public function getSetupDeliveryZoneUrls()
    {
        return array(
           'deliveryTimeUrl' => self::deliveryTimeUrl,
           'updateDeliveryZoneUrl' => self::updateDeliveryZoneUrl
        );
    }
}

