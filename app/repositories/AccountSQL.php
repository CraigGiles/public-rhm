<?php

class AccountSQL {
    /**
     * @param Account $account
     */
    public function save($account) {
        try {
            $id = DB::table('accounts')
                    ->insertGetId(array(
                        'userId' => $account->getUserID(),
                        'weeklyOpportunity' => $account->getWeeklyOpportunity(),
//                    'estimatedAnnualSales' => $account->getEstimatedAnnualSales(),
//                    'owner' => $account->getOwner(),
//                    'mobilePhone' => $account->getMobilePhone(),
//                    'websiteAddress' => $account->getWebsiteAddress(),
//                    'isTargetAccount' => $account->getIsTargetAccount(),
                        'accountName' => $account->getAccountName(),
                        'operatorType' => $account->getOperatorType(),
                        'addressId' => $account->getAddress()->getId(),
                        'contactName' => $account->getContactName(),
                        'phone' => $account->getPhone(),
                        'serviceType' => $account->getServiceType(),
                        'cuisineType' => $account->getCuisineType(),
                        'seatCount' => $account->getSeatCount(),
//                    'averageCheck' => $account->getAverageCheck(),
                        'emailAddress' => $account->getEmailAddress(),
                        'openDate' => $account->getOpenDate(),
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    )
                );

            $account->setId($id);
            return $id;
        } catch (Exception $e) {
            //todo: LOG EXCEPTION
            throw $e;
        }
    }
} 