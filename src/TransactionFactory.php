<?php
namespace Szyman\ObjectService\Propel;

use Propel\Runtime\Connection\ConnectionInterface;
use Szyman\ObjectService\Service;

final class TransactionFactory implements Service\TransactionFactory
{
    /** @var ConnectionInterface */
    private $con;
    
    public function __construct(ConnectionInterface $connection)
    {
        $this->con = $connection;
    }

    /** @inheritdoc */
	public function newTransaction()
    {
        return new Transaction($this->con);
    }
}
