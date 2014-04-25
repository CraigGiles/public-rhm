<?php 

class RedHotMayoControllerTestCase extends RedHotMayoTestCase {
    /**
     *  Call the POST request on a specific route. The HTTP_REFERER will automatically be configured
     * to use the given route and the data loaded from the config location will be passed into
     * the controllers function as Input::json()
     *
     * @param $route
     * @param $config
     * @return \Illuminate\Http\Response
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function callPostWithJson($route, $config) {
        return $this->call('POST', $route, [], [], ['HTTP_REFERER' => $route], Config::get($config));
    }

    /**
     *  Call the POST request on a specific route. The HTTP_REFERER will automatically be configured
     * to use the given route and the data loaded from the config location will be passed into
     * the controllers function as Input::get()
     *
     * @param $route
     * @param $config
     * @return \Illuminate\Http\Response
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function callPostWithArray($route, $config) {
        return $this->call('POST', $route, $this->getTestDataAsArray($config),[], ['HTTP_REFERER' => $route]);
    }

    /**
     *
     * @param $config
     * @return mixed
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    private function getTestDataAsArray($config) {
        $rawData = Config::get($config);
        $data = json_decode($rawData, true);
        Input::replace($input = $data);

        return $input;
    }
}
