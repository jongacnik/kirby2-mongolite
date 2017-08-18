<?php

require __DIR__ . '/vendor/autoload.php';

/**
 * MongoLite init
 */

$client = new MongoLite\Client(c::get('mongolite.location', kirby()->roots()->content()));
$database = $client->{c::get('mongolite.dbname', 'mongolite')};

/**
 * MongoLite field
 */

$kirby->set('field', 'mongolite', __DIR__ . '/fields/mongolite');

/**
 * MongoLite site method:
 *
 * @return entire database
 * site()->mongolite()
 *
 * @return entries collection
 * site()->mongolite('entries')
 */

$kirby->set('site::method', kirby()->option('mongolite.method', 'mongolite'), function ($site, $collection = false) use ($database) {
  return $collection ? $database->{$collection} : $database;
});


/**
 * MongoLite routes:
 *
 * @return collections as json
 * http://kirbysite.com/kirby-mongolite
 *
 * @return collection entries as json
 * http://kirbysite.com/kirby-mongolite/:collection
 */

$kirby->set('route', [
  'pattern' => kirby()->option('mongolite.route', 'kirby-mongolite'),
  'action' => function () use ($database) {
    return response::json($database->listCollections());
  }
]);

$kirby->set('route', [
  'pattern' => kirby()->option('mongolite.route', 'kirby-mongolite') . '/(:any)',
  'action' => function ($collection) use ($database) {
    // add pagination and filtering
    return response::json($database->{$collection}->find()->toArray());
  }
]);