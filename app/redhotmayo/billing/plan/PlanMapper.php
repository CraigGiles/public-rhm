<?php  namespace redhotmayo\billing\plan;

use InvalidArgumentException;

class PlanMapper {

    /**
     * @param int $population
     * @throws InvalidArgumentException
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public static function Map($population) {
        //todo: load config and grab the id that is associated with this population
        if (!is_numeric($population)) {
            throw new InvalidArgumentException("Population must be numeric");
        }

        if ($population < 400000) {
            return '01';
        } else if ($population < 1000000) {
            return '02';
        } else if ($population < 2000000) {
            return '03';
        } else if ($population < 4000000) {
            return '04';
        } else if ($population < 7000000) {
            return '05';
        } else if ($population < 10000000) {
            return '06';
        } else if ($population < 15000000) {
            return '07';
        } else if ($population < 25000000) {
            return '08';
        } else {
            return '08';
        }
    }
}
