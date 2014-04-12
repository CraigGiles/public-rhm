<?php namespace redhotmayo\dataaccess\repository\sql;


use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\repository\ThrottleRegistrationRepository;
use redhotmayo\exception\NullArgumentException;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class ThrottleRegistrationRepositorySQL implements ThrottleRegistrationRepository {
    const SERVICE = 'redhotmayo\dataaccess\repository\sql\ThrottleRegistrationRepositorySQL';

    const TABLE_NAME = 'throttle_registration';
    const C_KEY = 'key';
    const C_MAX = 'max';

    /**
     * Add a key used to register with the max number of times it can be used.
     *
     * @param $key
     * @param $max
     *
     * @throws \Symfony\Component\Process\Exception\InvalidArgumentException
     * @throws \redhotmayo\exception\NullArgumentException
     */
    public function addKey($key, $max) {
        if (!isset($key) || !isset($max)) {
            throw new NullArgumentException();
        }
        if (!is_numeric($max)) {
            throw new InvalidArgumentException("max must be an integer value");
        }

        DB::table(self::TABLE_NAME)
            ->insert([self::C_KEY => $key, self::C_MAX => $max]);
    }

    /**
     * Determines if a key is valid and the current number of registrations is under the max
     *
     * @param array $input
     * @return mixed
     */
    public function canUserRegister(array $input) {
        $key = $this->getKeyFromInput($input);
        $result = $this->getMax($key);

        return $this->isValid($result);
    }

    /**
     * Decrements the max number of people who can register by one for the given key
     *
     * @param array $input
     */
    public function decrementMax(array $input) {
        $key = $this->getKeyFromInput($input);

        $max = $this->getMax($key);
        if ($this->isValid($max)) {
            $max--;

            DB::table(self::TABLE_NAME)
                ->where(self::C_KEY,'=',$key)
                ->update([self::C_MAX => $max]);
        }
    }

    private function getMax($key) {
        return DB::table(self::TABLE_NAME)
                    ->select(self::C_MAX)
                    ->where(self::C_KEY, '=', $key)
                    ->first()->max;

    }

    private function isValid($result) {
        return isset($result) && intval($result) > 0;
    }

    private function getKeyFromInput($input) {
        if (!isset($input[self::C_KEY])) {
            throw new \InvalidArgumentException('key attribute not found');
        }

        return $input[self::C_KEY];
    }
}
