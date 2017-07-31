<?php

namespace creativestyle\amitools;

use Aws\Ec2\Ec2Client;

class Finder
{
    /**
     * @var Ec2Client
     */
    private $client;

    /**
     * Finder constructor.
     * @param Ec2Client $client
     */
    public function __construct(Ec2Client $client)
    {
        $this->client = $client;
    }

    public function createFilterByTagName($value) {
        return [
            [
                'Name' => 'tag:name',
                'Values' => [$value]
            ]
        ];
    }

    public function getImages($filters) {
        $res = $this->client->describeImages([
            'Filters' => $filters
        ]);
        return $res['Images'];
    }

    public function sortImagesNewestFirst($images) {
        usort($images, function($a, $b) {
            if(!isset($a['CreationDate']) || !isset($b['CreationDate']))
            {
                return 0;
            }
            $dataA = new \DateTime($a['CreationDate']);
            $dataB = new \DateTime($b['CreationDate']);
            return $dataA < $dataB ? 1 : -1;
        });
        return $images;
    }

}