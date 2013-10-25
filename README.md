KnpLabs - FriendlyContexts
================

[![Build Status](https://travis-ci.org/KnpLabs/FriendlyContexts.png?branch=master)](https://travis-ci.org/KnpLabs/FriendlyContexts)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/KnpLabs/FriendlyContexts/badges/quality-score.png?s=5292581c45ba61ea028dfb54c21c2ba50df604a2)](https://scrutinizer-ci.com/g/KnpLabs/FriendlyContexts/)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5620dc28-b1bb-43b0-be73-5c032d363fd7/mini.png)](https://insight.sensiolabs.com/projects/5620dc28-b1bb-43b0-be73-5c032d363fd7)

**How to install it**
```
execute : composer require knplabs/friendly-contexts dev-master
```
```bash
#behat.yml

default:
# ...
    extensions:
        Knp\FriendlyContexts\Extension: 
            Entity:
                enable: true
```

```php
// FeatureContext.php
// ...
use Knp/FriendlyContexts/Context/FriendlyContext;

class FeatureContext extends RawMinkContext
{
    public function __construct($options)
    {
        // ...
        $this->useContext('friendly', new FriendlyContext($options));
        // ...
    }
}
```
