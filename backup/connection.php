<?php

// Manager Class
$manager = new MongoDB\Driver\Manager("mongodb+srv://dev:dev@cluster0.tsmxe.mongodb.net/majorette?retryWrites=true&w=majority");

// Query Class
$query = new MongoDB\Driver\Query([]);

// Output of the executeQuery will be object of MongoDB\Driver\Cursor class
$cursor = $manager->executeQuery('majorette.pp', $query);

// Convert cursor to Array and print result
print_r($cursor->toArray());

?>