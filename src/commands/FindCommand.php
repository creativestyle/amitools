<?php
/**
 * Created by PhpStorm.
 * User: krzysztof
 * Date: 31/07/2017
 * Time: 14:57
 */

namespace creativestyle\amitools\commands;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FindCommand extends Command
{
    protected function configure()
    {
        $this->setName('ami:find')->setDescription('Finds newest image by tag');
        $this->addArgument('name', InputArgument::REQUIRED, 'Value of the name tag');
        $this->addOption('profile', null, InputArgument::OPTIONAL, 'AWS profile, defaults to AWS_PROFILE environment variable');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $profile = $input->getOption('profile') ?? $_SERVER['AWS_PROFILE'] ?? 'default';
        $tag = $input->getArgument('name');
        $client = new \Aws\Ec2\Ec2Client([
            'version' => 'latest',
            'profile'=>$profile,
            'region'=>'eu-central-1'
        ]);

        $finder = new \creativestyle\amitools\Finder($client);

        $images = $finder->getImages($finder->createFilterByTagName($tag));
        $finder->sortImagesNewestFirst($images);


        $newestImage = $images[0]['ImageId'] ?? '';
        $output -> write($newestImage);
    }


}