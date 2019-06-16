WIP (!!!)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/squadron-api/base/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/squadron-api/base/?branch=master)
[![Build Status](https://travis-ci.org/squadron-api/base.svg?branch=master)](https://travis-ci.org/squadron-api/base)
[![Style CI](https://github.styleci.io/repos/189949621/shield)](https://github.styleci.io/repos/189949621)

### squadron/base

This is base package for Squadron API. Provides:

- Integration with [Sentry](https://sentry.io/)
- UUIDs for models from the box
- Some useful artisan commands
- Helpers for other Squadron packages

#### Routes

- `/api/ping` - simple application's ping, returns application version from `.env` 

####Artisan commands

- `squadron:utils:hash {value : The string that will be hashed}` - gets hash of the string
- `squadron:version:set` - sets version in `.env` from last commit

#### BaseModel

`\Squadron\Base\Models\BaseModel`

Package contains abstract class for models in Squadron with some benefits:

- UUID for primary key
- table auto-locate by naming convention (model `ThisIsCustomEntity` -> table `this_is_custom_entity`)
- default TIMESTAMP properties renamed to `createdAt` / `updatedAt` (trying to use CamelCase used all over app)

#### BaseController

`\Squadron\Base\Http\Controllers\BaseController`

This class is analogue of `\App\Http\Contollers\Controller` from fresh laravel's install with "traits initialization" support.
Traits initialization for controllers works as Eloquent models initialization + auto-injects dependencies in `initialize*` methods.

Here is an example below:

```
use Illuminate\Http\Request;

trait ControllerTrait
{
    // Controller trait init method must be named as `initialize{TraitName}`
    public function initializeControllerTrait(Request $request): void
    {
        // $request will be auto-injected
    }
}
```

#### BaseRequest

`\Squadron\Base\Http\Requests\BaseRequest`
This abstract class used for laravel's requests, authorizes all by default.
