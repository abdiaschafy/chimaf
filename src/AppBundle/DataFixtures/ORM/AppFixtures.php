<?php

namespace AppBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtures\Fixtures\Loader as DataFixtureLoader;

class AppFixtures extends DataFixtureLoader
{
    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        return array(
            __DIR__ . '/ProduitData.yml',
            __DIR__ . '/UserData.yml'
        );
    }
}
