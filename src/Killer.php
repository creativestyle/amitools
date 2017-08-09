<?php

namespace creativestyle\amitools;

use Aws\Ec2\Ec2Client;

class Killer
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

    public function removeImage($imageId) {
        $this->client->deregisterImage([
            'ImageId' => $imageId
        ]);

        $snaps = $this->findSnapshoots($imageId);
        foreach ($snaps as $snap) {
            $this->client->deleteSnapshot([
                'SnapshotId'=>$snap
            ]);
        }

    }

    private function findSnapshoots($imageId) {
        $filters = [
            [
                'Name' => 'description',
                'Values'=> [ "*$imageId*" ]
            ]
        ];
        $res = $this->client->describeSnapshots([
            'Filters' => $filters
        ]);
        $ids = [];
        foreach ($res['Snapshots'] as $snap) {
            $ids[]=$snap['SnapshotId'];
        }
        return $ids;
    }
}