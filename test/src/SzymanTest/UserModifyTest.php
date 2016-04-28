<?php
namespace SzymanTest;

use SzymanTest\Service\ServiceContainer;
use SzymanTest\Model\User;
use SzymanTest\Model\UserQuery;

class UserModifyTest extends AbstractTest
{
    private $container;
    
    protected function setUp()
    {
        parent::setUp();
        $this->container = new ServiceContainer;
    }
    
    public function testPatchUser()
    {
        $data = '{name: "Sam Maximus Gamgee"}';
        $proc = $this->container->getRequestProcessor();
        $resp = $proc->handle($this->send('api/user/sam.g', 'PATCH', $data));
        
        $this->assertEquals(200, $resp->getStatusCode(), $this->container->getConfiguration()->getLogger());
        
        $user = UserQuery::create()->findPK('Administrator');
        $this->assertEquals('Sam Maximus Gamgee', $user->getName());
        
        $json = json_decode($resp->getContent());
        $this->assertTrue(is_object($json));
        $this->assertEquals('http://example.org/api/user/sam.g', $json->_links->self->href);
        $this->assertEquals($user->getHandle(), $json->handle);
        $this->assertEquals($user->getName(), $json->name);
        $this->assertEquals($user->getEmailAddress(), $json->emailAddress);
    }
}
