<?php

namespace App\Services\Customers\Account\Billing;


Class CardID
{   

    public function __construct($cardIds, $defaultCard) {
        $this->cardIds = $cardIds;
        $this->defaultCard = $defaultCard;
    }

    public function getCarId()
    {
        return $this->get();
    }

    private function get()
    {
        if (empty($this->defaultCard)) {
            if (!is_array($this->cardIds))  {
                $this->cardIds = json_decode($this->cardIds);
            }

            if (empty($this->cardIds)) {
                return '';
            }

            $card = array_reverse($this->cardIds);
            return $card[0]->id;
        }

        return $this->defaultCard;
    }
}
