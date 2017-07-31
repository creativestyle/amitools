<?php

namespace creativestyle\amitools;


use Aws\AutoScaling\AutoScalingClient;

class LauchConfigurationReader
{
    /**
    * @var AutoScalingClient
    */
    private $client;

    /**
     * Finder constructor.
     * @param Ec2Client $client
     */
    public function __construct(AutoScalingClient $client)
    {
        $this->client = $client;
    }


    public function getAMIImageIdByPrefix($prefix){
        $res = $this->client->describeLaunchConfigurations();
        foreach($res['LaunchConfigurations'] as $config) {
            if(preg_match("/^$prefix-LaunchConfig/i", $config['LaunchConfigurationName'])) {
                return $config['ImageId'];
            }
        };
        return null;
    }

}