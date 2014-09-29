distance
========

PHP Wrapper around [Google's DistanceMatrix](https://developers.google.com/maps/documentation/distancematrix/).

instalation
--------
Through composer

```php
"require": {
    "yrizos/distance": "*"
}
```

usage
-----------
Instantiate Api Class with your [API Key](https://console.developers.google.com/project):
```php
$api = new \DistanceMatrix\Api('apikey');
```
_______________
Add a destination (Required) :
```php
$api->addDestination('Αγιου Γεωργίου 5, Θέρμη, Θεσσαλονίκη');
```
_______________
Add an origin (Required):
```php
$api->addOrigin('Αριστοτέλους 35, Εύοσμος, Θεσσαλονίκη');
```
_______________
Set the Units :
* metric (default)
* imperial

```php
$api->setUnits('metric');
```
_______________
Set the Output :
* json (default)
* xml

```php
$api->setOutput('json');
```
_______________
Set the Mode :
* bicycling
* walking
* driving (default)

```php
$api->setMode('driving');
```
_______________
Set the Language :
* en (default)

```php
$api->setLanguage('el');
```
_______________
Finally, run this thing :
```php
$results = $api->run();
```

license
----------
[MIT](LICENSE)
