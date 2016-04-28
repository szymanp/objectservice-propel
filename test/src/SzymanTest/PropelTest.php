<?php
namespace SzymanTest;

use Propel\Runtime\Propel;
use SzymanTest\Model\Map\UserTableMap;
use SzymanTest\Model\User;

class PropelTest extends \PHPUnit_Framework_TestCase
{
    private $con;
    
    protected function setUp()
    {
        $this->con = Propel::getWriteConnection(UserTableMap::DATABASE_NAME);
        $this->con->beginTransaction();
    }
    
    protected function tearDown()
    {
        $this->con->rollback();
    }
    
    public function testNewUser()
    {
        $user = new User;
        $user->setHandle("jbond");
        $user->setName("Jonathan Bond");
        $user->setEmailAddress("jbond@example.org");
        $user->save();
    }

}