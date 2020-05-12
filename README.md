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

### Arrays
```php
<?php

use xihrni\tools\Arrays;

// ...

$list = [
    [
        'id' => 1,
        'parent_id' => null,
        'title' => '顶级分类1',
    ],
    [
        'id' => 2,
        'parent_id' => null,
        'title' => '顶级分类2',
    ],
    [
        'id' => 3,
        'parent_id' => 1,
        'title' => '子分类1',
    ],
    [
        'id' => 4,
        'parent_id' => 3,
        'title' => '子分类2',
    ],
];

$tree = Arrays::list2Tree($list);
var_dump($tree);
```

### Verify
```php
<?php

use xihrni\tools\Verify;

// ...

if (Verify::idCardNumber('110101199003078910')) {
    // true
} else {
    // false
}

if (Verify::isMobile()) {
    // true
} else {
    // false
}

if (Verify::isWechat()) {
    // true
} else {
    // false
}
```