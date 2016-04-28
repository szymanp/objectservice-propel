<?php
namespace Szyman\ObjectService\Propel;

use Light\ObjectAccess\Resource\ResolvedResource;
use Light\ObjectAccess\Transaction\Util\AbstractTransaction;
use Propel\Runtime\Connection\ConnectionInterface;

final class Transaction extends AbstractTransaction
{
    /** @var ConnectionInterface */
    private $con;
    
    public function __construct(ConnectionInterface $connection)
    {
        $this->con = $connection;
    }

    /** @inheritdoc */
	public function begin()
    {
        $this->con->beginTransaction();
    }

	/**
	 * Transfer changes done in this transaction, but do not commit yet.
	 *
	 * With some ORM systems object are first modified, then the changes are transferred to the database,
	 * and finally, the database changes are committed. This method is intended to carry out the step
	 * of transferring the changes to the database.
	 */
	public function transfer()
    {
    }
	
    /** @inheritdoc */
	public function commit()
    {
        $this->con->commit();
    }

    /** @inheritdoc */
	public function rollback()
    {
        $this->con->rollback();
    }
}
