<?php
namespace SzymanTest;

use SzymanTest\Service\ServiceContainer;
use SzymanTest\Model\User;
use SzymanTest\Model\UserQuery;

class UserCreateTest extends AbstractTest
{
    private $container;
    
    protected function setUp()
    {
        parent::setUp();
        $this->container = new ServiceContainer;
    }
    
    public function testPostUser()
    {
        $data = '{"handle": "sam.jackson", "name": "Samuel Jackson", "emailAddress": "samj@example.org", "password": "123456q"}';
        $proc = $this->container->getRequestProcessor();
        $resp = $proc->handle($this->send('api/user', 'POST', $data));
        
        $this->assertEquals(200, $resp->getStatusCode(), $this->container->getConfiguration()->getLogger());
        
        $user = UserQuery::create()->findPK('sam.jackson');
        $this->assertNotEmpty($user);
        $this->assertEquals('Samuel Jackson', $user->getName());
        $this->assertEquals('samj@example.org', $user->getEmailAddress());
        $this->assertEquals('123456q', $user->getPassword());
        $this->assertEquals('http://example.org/api/user/sam.jackson', $resp->headers->get('Location'));
    }
}
