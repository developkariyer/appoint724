<p align="center">
    <img src="https://github.com/developkariyer/yii2/blob/4a4d4ccad09f8a9a6dbafa29392afca72fa72f05/web/android-chrome-192x192.png">
    <h1 align="center">Appointment SAAS</h1>
    <br>
</p>


DEFAULT DIRECTORY STRUCTURE
---------------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources


REVISED DIRECTORY STRUCTURE
---------------------------

      components/         contains project specific utilities (e.g. Menu, Url, Language, Log)
      messages/           contains i18n translation files (currently tr only)
      models/form/        contains form specific models
      models/queries      contains Query models for some models
      models/traits       contains common used methods and properties throughout models
      views/authitentity  contains login specific views used by SiteController
      views/business      contains business related views used by BusinessController
      views/user          contains user specific views used by UserController


REQUIREMENTS
------------

The minimum requirement by this project that your Web server supports PHP 8.1.


TODO
----
### Short Term
- Log user Login/Logout/Register/Session activities to 'logins' table
- Check if all models honour LogBehavior
- Convert delete() to softDelete() for SoftDeleteTrait enabled models
- Check if retrieved models (find/get etc.) are filtered based on deleted_at in SoftDeleteTrait enabled models
- Modify SoftDeleteTrait enabled tables unique values to include deleted_at to avoid unnecessary "not unique" errors
- Decide when to create a new record and when to softUndelete and put into Model or Controller logic

### Medium Term
- Construct a test implementation
- Build a roadmap
- Standardize documentation

### Long Term
- Add expert_type feature
- Add rule builder


INSTALLATION
------------

### Install via Composer

If you do not have [Composer](https://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](https://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
composer create-project --prefer-dist yiisoft/yii2-app-basic basic
~~~

Now you should be able to access the application through the following URL, assuming `basic` is the directory
directly under the Web root.

~~~
http://localhost/basic/web/
~~~

### Install from an Archive File

Extract the archive file downloaded from [yiiframework.com](https://www.yiiframework.com/download/) to
a directory named `basic` that is directly under the Web root.

Set cookie validation key in `config/web.php` file to some random secret string:

```php
'request' => [
    // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
    'cookieValidationKey' => '<secret random string goes here>',
],
```

You can then access the application through the following URL:

~~~
http://localhost/basic/web/
~~~


### Install with Docker

Update your vendor packages

    docker-compose run --rm php composer update --prefer-dist
    
Run the installation triggers (creating cookie validation code)

    docker-compose run --rm php composer install    
    
Start the container

    docker-compose up -d
    
You can then access the application through the following URL:

    http://127.0.0.1:8000

**NOTES:** 
- Minimum required Docker engine version `17.04` for development (see [Performance tuning for volume mounts](https://docs.docker.com/docker-for-mac/osxfs-caching/))
- The default configuration uses a host-volume in your home directory `.docker-composer` for composer caches


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.

