# Napp Module Core

Napp Core Service Provider registers module dependencies.

Each module must register a ServiceProvider extending the `Napp\Core\Module\Provider\CoreServiceProvider`. Example:

```php

<?php 

namespace Napp\Forms;

use Napp\Forms\Event\FormWasSubmittedEvent;
use Napp\Forms\Listener\SendFormSubmissionEmailListener;
use Napp\Forms\Model\Form;
use Napp\Forms\Observer\FormObserver;
use Napp\Forms\Repository\FormsRepository;
use Napp\Forms\Repository\FormsRepositoryInterface;
use Napp\Core\Module\Provider\CoreServiceProvider;

class FormsServiceProvider extends CoreServiceProvider
{
    protected $routeNamespace = 'Napp\Forms';
    
    public const EXTENSION_FORM_BUILDER = 'extension.form_builder';
    
    public const FEATURE_ALLOW_SOMETHING = 'feature.form_allow_something';
    
    protected $repositories = [
        FormsRepositoryInterface::class => FormsRepository::class
    ];
    
    protected $features = [
        self::FEATURE_ALLOW_SOMETHING => [
            'translation_key',
            true // boolean true if the feature has settings    
        ],
        self::FEATURE_ALLOW_SOMETHING => 'translation_key'    
    ];
    
    protected $events = [
        FormWasSubmittedEvent::class => SendFormSubmissionEmailListener::class
    ];

    protected $permissions = [
        'forms.view',
        'forms.create',
        'forms.update',
        'forms.delete',
        'forms.duplicate',
        'forms.entries'
    ];

    protected $observers = [
        Form::class => FormObserver::class
    ];

    protected $extensions = [
        self::EXTENSION_FORMS => 'extensions.forms'
    ];

    protected function getCMSRoutes()
    {
        return __DIR__ . '/routes/cms.php';
    }

    protected function getApiRoutes()
    {
        return __DIR__ . '/routes/api.php';
    }

    protected function getFrontRoutes()
    {
        return __DIR__ . '/routes/front.php';
    }

    protected function getFrontApiRoutes()
    {
        return __DIR__ . '/routes/frontApi.php';
    }
}

```


## File Structure

At Napp we dont follow the default Laravel folder structure. We use Domain structure - so everything related to `Products` is within the `Napp\MyModule\Products` namespace. 


```
module-project/
	config/
	database/
		|-- factories/
		|-- migrations/
		|-- seeds/
	routes/
	src/
		|-- Products/
			|-- Console/
			|-- Controller/
			|-- Event/
			|-- Exception/
			|-- Factory/
			|-- Listener/
			|-- Model/
			|-- Middleware/
			|-- Notification/
			|-- Policy/
			|-- Repository/
			|-- Request/
			|-- Transformer/
		|-- Orders/
			|-- Controller/
			|-- Model/
			|-- Repository/
			|-- Request/
			|-- Transformer/
		MyModuleServiceProviders.php
		helpers.php
	resources/
		|-- lang/
		|-- views/
	tests/
	.gitattributes
	.gitignore
	.gitlab-ci.yml
	composer.json
	LICENSE.md
	phpunit.xml
	README.md
```
