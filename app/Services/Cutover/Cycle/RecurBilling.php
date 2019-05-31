<?php

namespace App\Services\Cutover\Cycle;

use App\Services\Cutover\Traits\InfusionEventNotifierProvider;
use App\Services\Cutover\Traits\BilingRepositoryProvider;
use App\Services\Cutover\Traits\CreateNewSubscriptionCycleProvider;
use App\Services\Cutover\Traits\ReportsMailingProvider;
use App\Services\Cutover\Traits\RecurringProvider;
use App\Services\Cutover\Traits\CancelledUsersWithForDeliveryMenuProvider;
use App\Services\Cutover\Traits\SetNewMenuSelectionsProvider;

use App\Services\Cutover\Data\Subscriptions;
use App\Services\Cutover\Data\SubscriptionsSelections;

use App\Services\Cutover\Data\Cycle;
use App\Services\Cutover\Data\User;

use App\Repository\ProductPlanRepository;
use App\Repository\MealsRepository;

use App\Services\Log;

Class RecurBilling
{   
    use BilingRepositoryProvider;
    use CreateNewSubscriptionCycleProvider;
    use InfusionEventNotifierProvider;
    use ReportsMailingProvider;
    use RecurringProvider;
    use CancelledUsersWithForDeliveryMenuProvider;
    use SetNewMenuSelectionsProvider;

    private $currentCycleId;
    private $deliveryTimingId;
    private $packages = array();
    private $packagesCreate = array();
    private $excemptBillingWithStatus = array('paused-due');
    private $successBillingStorage = array();
    private $cutoverDate;
    private $deliveryDate;
    private $mealPlansDefaultMenu = array();
    private $mealPlansRepository;

    const BILLING_ISSUE_STATUS = 'billing issue';
    const UNPAID_STATUS = 'unpaid';
    const PAID_STATUS = 'paid';
    const PAUSED_STATUS = 'paused';
    const CANCELLED_STATUS = 'cancelled';
    const PENDING_STATUS = 'pending';
    const ACTIVE_STATUS = 'active';

    public function __construct(
        int $cycleId, 
        int $previousCycleId, 
        int $deliveryTimingId, 
        \DateTime $date, 
        \DateTime $deliveryDate,
        \DateTime $currentDeliveryDate
    ) {
        $this->cycle = new Cycle;
        $this->user = new User;
        $this->currentCycleId = $cycleId;
        $this->previousCycleId = $previousCycleId;
        $this->deliveryTimingId = $deliveryTimingId;
        $this->cutoverDate = $date->format('Y-m-d');
        $this->deliveryDate = $deliveryDate->format('Y-m-d');
        $this->currentDeliveryDate = $currentDeliveryDate->format('Y-m-d');

        $this->subscriptions = new Subscriptions;
        $this->selections = new SubscriptionsSelections;
        $this->mealPlansRepository = new ProductPlanRepository;
        $this->mealsRepository = new MealsRepository;

        $this->log = new Log('cutover','logs/'.date('Y').'/'.date('m').'/system-'.date('Y-m-d').'.log');
    }

    public function handle()
    {   
        $this->setNewMenuSelectionsProvider();
        $this->recurringProvider();
        $this->createNewCycleForActiveSubscription();
        $this->cancelledUsersWithForDeliveryMenu();
        $this->eventInfusionsoftNotification($this->deliveryTimingId);
        $this->sendReportToMailProvider($this->deliveryTimingId);
    }

}
