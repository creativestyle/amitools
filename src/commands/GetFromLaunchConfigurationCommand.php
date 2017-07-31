<?php
/**
 * Created by PhpStorm.
 * User: krzysztof
 * Date: 31/07/2017
 * Time: 14:57
 */

namespace creativestyle\amitools\commands;


use creativestyle\amitools\LauchConfigurationReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetFromLaunchConfigurationCommand extends Command
{
    protected function configure()
    {
        $this->setName('ami:getfromlaunchconfig')->setDescription('Gets AMI Image ID from Launch configuration with given name');
        $this->addArgument('name', InputArgument::REQUIRED, 'Prefix of the Launch Configuration');
        $this->addOption('profile', null, InputArgument::OPTIONAL, 'AWS profile, defaults to AWS_PROFILE environment variable');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $profile = $input->getOption('profile') ?? $_SERVER['AWS_PROFILE'] ?? 'default';
        $name = $input->getArgument('name');
        $client = new \Aws\AutoScaling\AutoScalingClient([
            'version' => 'latest',
            'profile'=>$profile,
            'region'=>'eu-central-1'
        ]);

        $reader = new LauchConfigurationReader($client);
        $imageId = $reader->getAMIImageIdByPrefix($name);
        $output -> writeln($imageId);
    }


}