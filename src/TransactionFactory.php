<?php
namespace Szyman\ObjectService\Propel;

use Propel\Runtime\Connection\ConnectionInterface;
use Szyman\ObjectService\Service\TransactionFactory;

final class TransactionFactory implements TransactionFactory
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
