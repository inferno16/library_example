<?php

require_once '../bootstrap.php';

use Adapters\XmlAdapter;
use Models\Book\BookCollection;

$data = XmlAdapter::getInstance()->getBooksData(ROOT_DIR . '/books');
// Data is already validated, no need to do it again
$bookCollection = new BookCollection($data, false);
$bookCollection->save();
