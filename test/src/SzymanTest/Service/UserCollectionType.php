<?php
namespace SzymanTest\Service;

use Light\ObjectAccess\Resource\ResolvedCollection;
use Light\ObjectAccess\Resource\ResolvedCollectionValue;
use Light\ObjectAccess\Resource\ResolvedCollectionResource;
use Light\ObjectAccess\Resource\Origin;
use Light\ObjectAccess\Resource\Origin_Unavailable;
use Light\ObjectAccess\Resource\Origin_ElementInCollection;
use Light\ObjectAccess\Resource\Origin_PropertyOfObject;
use Light\ObjectAccess\Type\Collection\Element;
use Light\ObjectAccess\Type\CollectionType;
use Light\ObjectAccess\Type\TypeRegistry;
use Szyman\Exception\NotImplementedException;
use SzymanTest\Model\User;
use SzymanTest\Model\UserQuery;

class UserCollectionType implements CollectionType
{
    /** @inheritdoc */
    public function getBaseTypeName()
    {
        return User::class;
    }

    /**
     * Returns an element from the given collection at the specified key.
     * @param ResolvedCollection    $coll
     * @param string|integer        $key
     * @return Element
     */
    public function getElementAtKey(ResolvedCollection $coll, $key)
    {
        if ($coll instanceof ResolvedCollectionValue)
        {
            return $this->readElementFromList($coll->getValue(), $key);
        }
        elseif ($coll instanceof ResolvedCollectionResource)
        {
            return $this->readElementFromDatabase($coll->getOrigin(), $key);
        }
        else
        {
            throw new \LogicException(get_class($coll));
        }
    }
    
    protected function readElementFromList($coll, $key)
    {
        throw new NotImplementedException();
    }
    
    protected function readElementFromDatabase(Origin $origin, $key)
    {
        if ($origin instanceof Origin_Unavailable)
        {
            // The collection was published directly
            $result = UserQuery::create()->findPk($key);
            if (!$result)
            {
                return Element::notExists();
            }
            else
            {
                return Element::valueOf($result);
            }
        }
        elseif ($origin instanceof Origin_PropertyOfObject)
        {
            // The collection was accessed via a property of another object.
            throw new NotImplementedException($origin);
        }
        elseif ($origin instanceof Origin_ElementInCollection)
        {
            // The collection was an element of another collection
            throw new NotImplementedException($origin);
        }
        else
        {
            throw new \LogicException();
        }
    }

    /** @inheritdoc */
    public function isValidValue(TypeRegistry $typeRegistry, $value)
    {
        return $value instanceof User;
    }
}
