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
- Establish access control system 
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

*** DO NOT INSTALL THIS VERSION. IT IS EXTREMELY EXPERIMENTAL AND MISSES CRICITICAL CONFIGURATION FILES ***


CONFIGURATION
-------------

Use `.env`

