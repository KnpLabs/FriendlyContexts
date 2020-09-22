<?php


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Knp\FriendlyContexts\Context\Context;

class FeatureContext extends Context
{
    /**
     * @BeforeScenario
     */
    public function createDatabase()
    {
        /** @var ObjectManager[] $managers */
        $managers = $this->get('doctrine')->getManagers();

        foreach ($managers as $manager) {
            if ($manager instanceof EntityManagerInterface) {
                $schemaTool = new SchemaTool($manager);
                $schemaTool->dropDatabase();
                $schemaTool->createSchema(
                    $manager->getMetadataFactory()->getAllMetadata()
                );
            }
        }
    }

}