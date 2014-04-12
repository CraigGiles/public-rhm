<?php 

class RedHotMayoControllerTestCase extends RedHotMayoTestCase {
    public function callPostWithJson($route, $config) {
        return $this->call('POST', $route, [], [], [], Config::get($config));
    }

    public function callPostWithArray($route, $config) {
        return $this->call('POST', $route, $this->getTestDataAsArray($config));
    }

    private function getTestDataAsArray($config) {
        $rawData = Config::get($config);
        $data = json_decode($rawData, true);
        Input::replace($input = $data);

        return $input;
    }
}
