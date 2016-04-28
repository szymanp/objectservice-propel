<?php
namespace SzymanTest\Service;

use Light\ObjectAccess\Transaction\Transaction;
use Light\ObjectAccess\Type\Complex\Create;
use Light\ObjectAccess\Type\Util\DefaultComplexType;
use Light\ObjectAccess\Type\Util\DefaultProperty;
use SzymanTest\Model\User;

class UserType extends DefaultComplexType implements Create
{
    public function __construct()
    {
        parent::__construct(User::class);
        $this
            ->addProperty($handle   = new DefaultProperty("handle",        "string"))
    	    ->addProperty(            new DefaultProperty("name",          "string"))
    	    ->addProperty(            new DefaultProperty("emailAddress",  "string"))
    	    ->addProperty($password = new DefaultProperty("password", "string"));
            
        $handle->setWritable(false);    // This is the primary-key, so we should not change it.
        $password->setReadable(false);
    }

	/**
	 * @inheritdoc
	 */
	public function createObject(Transaction $transaction)
	{
		// TODO Fix transaction
		return new User;
	}
}
