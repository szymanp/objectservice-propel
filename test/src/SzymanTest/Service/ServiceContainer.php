<?php
namespace SzymanTest\Service;

use Light\ObjectAccess\Type\TypeRegistry;
use Light\ObjectAccess\Type\Util\DefaultTypeProvider;
use Light\ObjectService\Service\EndpointRegistry;
use Szyman\ObjectService\Configuration\Endpoint;
use Szyman\ObjectService\Configuration\Util\DefaultObjectProvider;
use Szyman\ObjectService\Configuration\Util\DefaultConfiguration;
use Szyman\ObjectService\Configuration\Util\DefaultRequestBodyTypeMap;
use Szyman\ObjectService\Configuration\Util\TypeBasedResponseContentTypeMap;
use Szyman\ObjectService\Request\StandardRequestBodyDeserializerFactory;
use Szyman\ObjectService\Request\StandardRequestHandlerFactory;
use Szyman\ObjectService\Response\StandardResponseCreatorFactory;
use Szyman\ObjectService\Service\RequestProcessor;
use Szyman\ObjectService\Propel\TransactionFactory;
use Propel\Runtime\Propel;
use SzymanTest\Model\Map\UserTableMap;
use SzymanTest\Model\User;
use SzymanTest\TestLogger;

class ServiceContainer
{
    private $endpointRegistry;
    private $configuration;
    private $requestProcessor;

    public function getEndpointRegistry()
    {
        if ($this->endpointRegistry) return $this->endpointRegistry;
    
        $typeProvider = new DefaultTypeProvider();
        $typeProvider->addType($userType = new UserType());
        $typeProvider->addType($userCollectionType = new UserCollectionType());

        $objectProvider = new DefaultObjectProvider();
        $objectProvider->publishCollection("api/user", $userCollectionType);

        $endpoint = Endpoint::create("http://example.org/", $objectProvider, $typeProvider);

        $endpointRegistry = new EndpointRegistry();
        $endpointRegistry->addEndpoint($endpoint);
        
        return $this->endpointRegistry = $endpointRegistry;
    }
    
    public function getTransactionFactory()
    {
        return new TransactionFactory(Propel::getWriteConnection(UserTableMap::DATABASE_NAME));
    }
    
    public function getConfiguration()
    {
        if ($this->configuration) return $this->configuration;
        
		$requestBodyTypeMap = new DefaultRequestBodyTypeMap();
		$contentTypeMap = new TypeBasedResponseContentTypeMap();
		$contentTypeMap->addClass(User::class, 'JSON', 'application/vnd.user+json');
		$contentTypeMap->addClass(\Exception::class, 'JSON', 'application/vnd.exception+json');

		$conf = DefaultConfiguration::newBuilder()
			->endpointRegistry($this->getEndpointRegistry())
			->requestBodyDeserializerFactory(StandardRequestBodyDeserializerFactory::getInstance())
			->requestBodyTypeMap($requestBodyTypeMap)
			->requestHandlerFactory(StandardRequestHandlerFactory::getInstance())
			->responseCreatorFactory(new StandardResponseCreatorFactory($contentTypeMap))
			->transactionFactory($this->getTransactionFactory())
            ->logger(new TestLogger())
			->build();
        
        return $this->configuration = $conf;
    }
    
    public function getRequestProcessor()
    {
        if ($this->requestProcessor) return $this->requestProcessor;
        
        $this->requestProcessor = new RequestProcessor($this->getConfiguration());
        
        return $this->requestProcessor;
    }
}
