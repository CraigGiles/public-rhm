<?php  namespace redhotmayo\filters;

class ApiSubscriptionFilter extends SubscriptionFilter {
    const FILTER = 'redhotmayo\filters\ApiSubscriptionFilter';

    public function filter() {
        $active = $this->isActive();

        if (!$active) {
            return [
                'status' => $active,
                'message' => self::MESSAGE];
        }
    }
} 
