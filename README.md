# Workbot-JOBTN

## Documentation technique
### Versions des différents composants de l'application
- php 8.0 avec Symfony 5.4 et Doctrine/ORM 2.13
- twig 3.0
- Mysql 8.0.3

### Outils utilisés 
- IDE : PhpStorm 2022.2.2
- XAMPP 

## Commandes à exécuter après avoir cloné ce projet.
 - composer require
 - npm install
 - npm install @symfony/webpack-encore --save-dev
 - npr run watch
 - npm run build 
Follow these steps to refactor annotations to attributes in a symfony 5.4+ project
### **Annotation to attribute steps:** ##

## 1) Install rector
    composer require rector/rector --dev
## 2) Create rector.php
    vendor/bin/rector init

## 3) Add the following code to rector.php
   
       use Rector\Doctrine\Set\DoctrineSetList;
       use Rector\Php74\Rector\Property\TypedPropertyRector;
       use Rector\Set\ValueObject\SetList;
       use Rector\Config\RectorConfig;
       use Rector\Symfony\Set\SymfonySetList;

       return static function (RectorConfig $rectorConfig): void {
       // here we can define, what sets of rules will be applied
       // tip: use "SetList" class to autocomplete sets
        $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SensiolabsSetList::ANNOTATIONS_TO_ATTRIBUTES
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
        SymfonySetList::SYMFONY_CODE_QUALITY

       ]);
       // register single rule
       $rectorConfig->rule(TypedPropertyRector::class);
       };


## 4) remove the starting semicolon ( ; ) from your xampp/php/php.ini
      ;extension=php_intl.dll

## 5) run refactor
      vendor/bin/rector process    


Reference : https://github.com/rectorphp/rector
