# PHP 工具类

## Introduction
PHP使用的各种工具类

## Install
```composer
$ composer require xihrni/php-tools
```

## Demo
### Other
```php
<?php

use xihrni\tools\Other;

// $data = ...;
Other::dump($data);
```

### Yii2
```php
<?php

use xihrni\tools\Yii2;

// ...

public function beforeAction($action)
{
    $moduleId = Yii2::getFullModuleId($action->controller->module, $ids = []);
}
```