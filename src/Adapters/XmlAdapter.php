<?php

namespace Adapters;

use Models\Book\BookModel;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use RuntimeException;
use SimpleXMLElement;
use Traits\Singleton;

class XmlAdapter
{
    use Singleton;

    public function getXmlFilesRecursively($path, $callback = null): void
    {
        if (!is_callable($callback)) {
            throw new RuntimeException('Callback is not callable');
        }

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

        /** @var RecursiveDirectoryIterator $file */
        foreach (new RegexIterator($files, '/^.+\.xml$/i') as $file) {
            $callback($file->getPathname());
        }
    }

    public function getBooksData($path): array
    {
        $books = [];

        $this->getXmlFilesRecursively(
            $path,
            static function ($filePath) use (&$books) {
                echo "<pre>$filePath</pre>";
                $booksXml = new SimpleXMLElement($filePath, 0, true);
                // File has only one book
                if ($booksXml->getName() === 'book') {
                    $booksXml = [$booksXml];
                }
                foreach ($booksXml as $book) {
                    $bookData = array_intersect_key((array)$book, array_flip(BookModel::REQUIRED_FIELDS));
                    $valid = count($bookData) === count(BookModel::REQUIRED_FIELDS) && $book->getName() === 'book';

                    $background = $valid ? 'Azure' : 'LavenderBlush';
                    echo "<pre style=\"background: $background; margin: 0\">" . htmlspecialchars($book->asXML()) . '</pre>';

                    if (!$valid) {
                        // Handle error (log, throw, etc.)
                        continue;
                    }

                    $books[] = $bookData;
                }
            }
        );

        return $books;
    }
}
