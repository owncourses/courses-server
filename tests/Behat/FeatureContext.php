<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

final class FeatureContext implements Context
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $this->dropDatabase();
        $this->createDatabase();
    }

    /**
     * Create database using doctrine schema tool.
     */
    private function createDatabase(): void
    {
        $em = $this->entityManager;
        $metadata = $em->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($em);
        $schemaTool->createSchema($metadata);
    }

    /**
     * Drop database using doctrine schema tool.
     */
    private function dropDatabase(): void
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
    }
}
