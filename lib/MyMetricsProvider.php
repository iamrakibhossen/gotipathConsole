<?php


namespace WHMCS\Module\Server\gotipathConsole;

/**
 * The above namespace is automatically registered for autoloading classes within
 * a "lib" sub-directory relative to your module directory. So, place this class
 * in modules/servers/mymodule/lib/MyMtricsProvider.php.
 */

use WHMCS\UsageBilling\Contracts\Metrics\MetricInterface;
use WHMCS\UsageBilling\Contracts\Metrics\ProviderInterface;
use WHMCS\UsageBilling\Metrics\Metric;
use WHMCS\UsageBilling\Metrics\Units\GigaBytes;
use WHMCS\UsageBilling\Metrics\Units\WholeNumber;
use WHMCS\UsageBilling\Metrics\Usage;

class MyMetricsProvider implements ProviderInterface
{
    // Next We have to define Class for api call
    // There should able to communicate with console backend server
    // and get the data from there
    // For now we are using static data
    // Then we are assigning the data to the metrics
    // and return the metrics to the WHMCS
    private $moduleParams = [];
    public function __construct($moduleParams)
    {
        // A sample `$params` array may be defined as:
        //
        // ```
        // array(
        //     "server" => true
        //     "serverid" => 1
        //     "serverip" => "11.111.4.444"
        //     "serverhostname" => "my.testserver.tld"
        //     "serverusername" => "root"
        //     "serverpassword" => ""
        //     "serveraccesshash" => "ZZZZ1111222333444555AAAA"
        //     "serversecure" => true
        //     "serverhttpprefix" => "https"
        //     "serverport" => "77777"
        // )
        // ```
        $this->moduleParams = $moduleParams;
    }


    /**
     * Function to make a request to the protected endpoint.
     *
     * @param string $apiUrl
     * @param string $accessToken
     * @return string
     */
    function  makeRequest($apiUrl, $accessToken = '', $data, $method)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json'
            ),

        ));
        $response = curl_exec($curl);
        return $response;
    }

    public function metrics()
    {
        return [
            new Metric(
                'storage',
                'Storage Usages',
                MetricInterface::TYPE_PERIOD_MONTH,
                new GigaBytes('GB'),
                new Usage(0)
            ),
            new Metric(
                'bandwidth',
                'Bandwidth Usages',
                MetricInterface::TYPE_PERIOD_MONTH,
                new GigaBytes('GB'),
                new Usage(0)
            ),
            new Metric(
                'transcoding',
                'Transcoding',
                MetricInterface::TYPE_PERIOD_MONTH,
                new WholeNumber('number', 'Minute', 'Minutes'),
                new Usage(0)
            ),
            new Metric(
                'live_streaming',
                'Live Streaming',
                MetricInterface::TYPE_PERIOD_MONTH,
                new WholeNumber('number', 'Minute', 'Minutes'),
                new Usage(0)
            ),
        ];
    }

    public function usage()
    {
        $serverData = $this->getAllServiceUsage();
        $usage = [];
        foreach ($serverData as $data) {
            $usage[$data['username']] = $this->wrapUserData($data);
        }

        return $usage;
    }

    public function tenantUsage($tenant)
    {
        $userData = $this->getServiceUsage($tenant);
        return $this->wrapUserData($userData);
    }

    private function wrapUserData($data)
    {
        $wrapped = [];
        foreach ($this->metrics() as $metric) {
            $key = $metric->systemName();
            if ($data[$key]) {
                $value = $data[$key];
                $metric = $metric->withUsage(
                    new Usage($value)
                );
            }

            $wrapped[] = $metric;
        }

        return $wrapped;
    }

    private function getServiceUsage($serviceId)
    {
        // $data = array(
        //     "username" => $serviceId,
        // );
        // $response = $this->makeRequest("http://teststreamapi.test/api/usage", '', $data, 'POST');
        // $data = json_decode($response);
        
        return [
            'username' => $serviceId, //$serviceId,
            'storage' => 15000, //$data->storage,
            'bandwidth' => 15000, //$data->bandwidth,
            'transcoding' => 10000, //$data->transcoding,
            'live_streaming' => 2000, //$data->live_streaming,
        ];
    }

    private function getAllServiceUsage()
    {
        return [
            [
                'username' => '12345678',
                'storage' => 140423,
                'bandwidth' => 140423,
                'transcoding' => 20,
                'live_streaming' => 20,
            ],
        ];
    }
}
