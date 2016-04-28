<?php
namespace SzymanTest;

use Propel\Runtime\Propel;
use Symfony\Component\HttpFoundation\Request;
use SzymanTest\Model\Map\UserTableMap;
use SzymanTest\Model\User;

abstract class AbstractTest extends \PHPUnit_Framework_TestCase
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
    
    public function get($suffix, $accept = "application/json")
    {
        return Request::create('http://example.org/' . $suffix, 'GET', [], [], [], ['HTTP_ACCEPT' => $accept]);
    }
    
    public function send($suffix, $method, $data, $accept = "application/json")
    {
        return Request::create('http://example.org/' . $suffix, $method, [], [], [], 
                               ['HTTP_ACCEPT' => $accept, 'CONTENT_TYPE' => 'application/json'], $data);
    }
}
