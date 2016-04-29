<?php
namespace Szyman\ObjectService\Propel;

use Light\ObjectAccess\Resource\ResolvedResource;
use Light\ObjectAccess\Resource\ResolvedObject;
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

    /** @inheritdoc */
    public function transfer()
    {
        $save = array();
        $delete = array();
    
        foreach($this->created as $r)
        {
            if ($r instanceof ResolvedObject)
            {
                $save[] = $r->getValue();
            }
        }
        foreach($this->changed as $r)
        {
            if ($r instanceof ResolvedObject)
            {
                $save[] = $r->getValue();
            }
        }
        foreach($this->deleted as $r)
        {
            if ($r instanceof ResolvedObject)
            {
                $delete[] = $r->getValue();
            }
        }
        
        $save = array_unique($save, SORT_REGULAR);
        $delete = array_unique($delete, SORT_REGULAR);
        
        foreach($save as $o) $o->save();
        foreach($delete as $o) $o->delete();
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
