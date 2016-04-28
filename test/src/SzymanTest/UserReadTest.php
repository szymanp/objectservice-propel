<?php
namespace SzymanTest;

use SzymanTest\Service\ServiceContainer;
use SzymanTest\Model\User;
use SzymanTest\Model\UserQuery;

class UserReadTest extends AbstractTest
{
    private $container;
    
    protected function setUp()
    {
        parent::setUp();
        $this->container = new ServiceContainer;
    }
    
    public function testReadUserById()
    {
        $proc = $this->container->getRequestProcessor();
        $resp = $proc->handle($this->get('api/user/Administrator'));
        $user = UserQuery::create()->findPK('Administrator');
        
        $this->assertEquals(200, $resp->getStatusCode(), $this->container->getConfiguration()->getLogger());
        
        $json = json_decode($resp->getContent());
        $this->assertTrue(is_object($json));
        $this->assertEquals('http://example.org/api/user/Administrator', $json->_links->self->href);
        $this->assertEquals($user->getHandle(), $json->handle);
        $this->assertEquals($user->getName(), $json->name);
        $this->assertEquals($user->getEmailAddress(), $json->emailAddress);
        $this->assertFalse(isset($json->password));
        $this->assertFalse(isset($json->_embedded));
    }
    
    public function testReadUserByNonexistingId()
    {
        $proc = $this->container->getRequestProcessor();
        $resp = $proc->handle($this->get('api/user/JohnQDoe'));
        
        $this->assertEquals(404, $resp->getStatusCode(), $this->container->getConfiguration()->getLogger());
        $this->assertEquals('application/vnd.exception+json', $resp->headers->get('CONTENT-TYPE'));
    }
}
