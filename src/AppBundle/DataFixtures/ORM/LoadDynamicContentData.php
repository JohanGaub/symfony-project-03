<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\DynamicContent;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadDynamicContentData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadDynamicContentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $example = '<h1>Dynamic Example</h1><p><br></p><p><ul><li>Vestibulum ultrices nisi sapien. Nulla non facilisis neque. Donec ipsum arcu, mollis quis turpis eu, auctor pellentesque neque. Maecenas semper justo ut justo efficitur, et rutrum neque rutrum. Integer eu egestas quam, luctus maximus nisl. Maecenas laoreet sapien ornare ligula efficitur semper. Nunc ut sapien a ante finibus semper vel non ligula. Integer ac mauris a velit luctus accumsan. Maecenas elit lacus, ultricies egestas vehicula nec, dapibus ut diam. Quisque elit nisi, rhoncus vel auctor vitae, varius eu ligula.<br></li><li>Vestibulum ultrices nisi sapien. Nulla non facilisis neque. Donec ipsum arcu, mollis quis turpis eu, auctor pellentesque neque. Maecenas semper justo ut justo efficitur, et rutrum neque rutrum. Integer eu egestas quam, luctus maximus nisl. Maecenas laoreet sapien ornare ligula efficitur semper. Nunc ut sapien a ante finibus semper vel non ligula. Integer ac mauris a velit luctus accumsan. Maecenas elit lacus, ultricies egestas vehicula nec, dapibus ut diam. Quisque elit nisi, rhoncus vel auctor vitae, varius eu ligula.</li><li>Vestibulum ultrices nisi sapien. Nulla non facilisis neque. Donec ipsum arcu, mollis quis turpis eu, auctor pellentesque neque. Maecenas semper justo ut justo efficitur, et rutrum neque rutrum. Integer eu egestas quam, luctus maximus nisl. Maecenas laoreet sapien ornare ligula efficitur semper. Nunc ut sapien a ante finibus semper vel non ligula. Integer ac mauris a velit luctus accumsan. Maecenas elit lacus, ultricies egestas vehicula nec, dapibus ut diam. Quisque elit nisi, rhoncus vel auctor vitae, varius eu ligula.<br></li></ul><p><br></p></p><h3 style="text-align: left;"><span style="text-align: left;"><u style="text-align: left;">Test 1</u></span></h3><p><span style="text-align: left;"><u style="text-align: left;"><br></u></span></p><p>Vestibulum ultrices nisi sapien. Nulla non facilisis neque. Donec ipsum arcu, mollis quis turpis eu, auctor pellentesque neque. Maecenas semper justo ut justo efficitur, et rutrum neque rutrum. Integer eu egestas quam, luctus maximus nisl. Maecenas laoreet sapien ornare ligula efficitur semper. Nunc ut sapien a ante finibus semper vel non ligula. Integer ac mauris a velit luctus accumsan. Maecenas elit lacus, ultricies egestas vehicula nec, dapibus ut diam. Quisque elit nisi, rhoncus vel auctor vitae, varius eu ligula.Vestibulum ultrices nisi sapien. Nulla non facilisis neque. Donec ipsum arcu, mollis quis turpis eu, auctor pellentesque neque. Maecenas semper justo ut justo efficitur, et rutrum neque rutrum. Integer eu egestas quam, luctus maximus nisl. Maecenas laoreet sapien ornare ligula efficitur semper. Nunc ut sapien a ante finibus semper vel non ligula. Integer ac mauris a velit luctus accumsan. Maecenas elit lacus, ultricies egestas vehicula nec, dapibus ut diam. Quisque elit nisi, rhoncus vel auctor vitae, varius eu ligula.<span style="text-align: left;"><u style="text-align: left;"><br style="text-align: left;"></u></span></p><p><br></p><p><h3 style="text-align: left;"><u><br class="Apple-interchange-newline">TEST 2</u></h3><p><u><br></u></p><p>Vestibulum ultrices nisi sapien. Nulla non facilisis neque. Donec ipsum arcu, mollis quis turpis eu, auctor pellentesque neque. Maecenas semper justo ut justo efficitur, et rutrum neque rutrum. Integer eu egestas quam, luctus maximus nisl. Maecenas laoreet sapien ornare ligula efficitur semper. Nunc ut sapien a ante finibus semper vel non ligula. Integer ac mauris a velit luctus accumsan. Maecenas elit lacus, ultricies egestas vehicula nec, dapibus ut diam. Quisque elit nisi, rhoncus vel auctor vitae, varius eu ligula.Vestibulum ultrices nisi sapien. Nulla non facilisis neque. Donec ipsum arcu, mollis quis turpis eu, auctor pellentesque neque. Maecenas semper justo ut justo efficitur, et rutrum neque rutrum. Integer eu egestas quam, luctus maximus nisl. Maecenas laoreet sapien ornare ligula efficitur semper. Nunc ut sapien a ante finibus semper vel non ligula. Integer ac mauris a velit luctus accumsan. Maecenas elit lacus, ultricies egestas vehicula nec, dapibus ut diam. Quisque elit nisi, rhoncus vel auctor vitae, varius eu ligula.</p></p><p><br></p><p><h3 style="text-align: left;"><u><br class="Apple-interchange-newline">TEST 3</u></h3><p><u><br></u></p><p>Vestibulum ultrices nisi sapien. Nulla non facilisis neque. Donec ipsum arcu, mollis quis turpis eu, auctor pellentesque neque. Maecenas semper justo ut justo efficitur, et rutrum neque rutrum. Integer eu egestas quam, luctus maximus nisl. Maecenas laoreet sapien ornare ligula efficitur semper. Nunc ut sapien a ante finibus semper vel non ligula. Integer ac mauris a velit luctus accumsan. Maecenas elit lacus, ultricies egestas vehicula nec, dapibus ut diam. Quisque elit nisi, rhoncus vel auctor vitae, varius eu ligula.Vestibulum ultrices nisi sapien. Nulla non facilisis neque. Donec ipsum arcu, mollis quis turpis eu, auctor pellentesque neque. Maecenas semper justo ut justo efficitur, et rutrum neque rutrum. Integer eu egestas quam, luctus maximus nisl. Maecenas laoreet sapien ornare ligula efficitur semper. Nunc ut sapien a ante finibus semper vel non ligula. Integer ac mauris a velit luctus accumsan. Maecenas elit lacus, ultricies egestas vehicula nec, dapibus ut diam. Quisque elit nisi, rhoncus vel auctor vitae, varius eu ligula.</p></p>';
        
        $charte = new DynamicContent();
        $charte->setType('charte');
        $charte->setContent($example);
        $em->persist($charte);
        
        $mentions = new DynamicContent();
        $mentions->setType('mentionslegales');
        $mentions->setContent($example);
        $em->persist($mentions);

        $em->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 12;
    }

}