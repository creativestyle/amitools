<?php
/**
 * Created by PhpStorm.
 * User: krzysztof
 * Date: 31/07/2017
 * Time: 14:57
 */

namespace creativestyle\amitools\commands;


use creativestyle\amitools\Killer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveOldAMIsCommand extends Command
{
    protected function configure()
    {
        $this->setName('ami:remove')->setDescription('Removes old AMIs');
        $this->addArgument('name', InputArgument::REQUIRED, 'Value of the name tag');
        $this->addArgument('lc', InputArgument::REQUIRED, 'Prefix of the Launch Configuration');
        $this->addArgument('leave', InputArgument::REQUIRED, 'Number of images to leave');
        $this->addOption('profile', null, InputArgument::OPTIONAL, 'AWS profile, defaults to AWS_PROFILE environment variable');
        $this->addOption('limit', null, InputArgument::OPTIONAL, 'Maxium number of images to remove', 5);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $profile = $input->getOption('profile') ?? $_SERVER['AWS_PROFILE'] ?? 'default';
        $tag = $input->getArgument('name');
        $leave = $input->getArgument('leave');
        $limit = $input->getOption('limit');
        $client = new \Aws\Ec2\Ec2Client([
            'version' => 'latest',
            'profile'=>$profile,
            'region'=>'eu-central-1'
        ]);

        $finder = new \creativestyle\amitools\Finder($client);

        $images = $finder->getImages($finder->createFilterByTagName($tag));
        $finder->sortImagesNewestFirst($images);
        $oldestImages = array_reverse($images);

        $howManyRemove = count($oldestImages)-$leave;

        if($howManyRemove <= 0) {
            $output -> writeln('Nothing to remove');
            return;
        }

        if($limit < $howManyRemove) {
            $howManyRemove = $limit;
        }

        $removeAmis = array_slice($oldestImages, 0, $howManyRemove);


        if(count($removeAmis)) {
            $killer = new Killer($client);
            foreach ($removeAmis as $ami) {
                $amiId = $ami['ImageId'];
                $output -> writeln("Removing $amiId");
                $killer->removeImage($amiId);
            }
        }

    }


}