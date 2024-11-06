<?php
namespace Headers;

use Headers\Interfaces\HeaderStringInterface;

class DisplayHeaders implements HeaderStringInterface
{
    protected array $headers = [];

    public function add(HeaderStringInterface $header): void
    {
        $this->headers[] = $header->getHeaderString();
    }

    public function getHeaderString(): string
    {
        if (count($this->headers) === 0) {
            throw new \Exception('There is no headers to display');
        }
        return implode("\n", $this->headers);
    }

    public function displayInFile(string $filePath): void
    {
        file_put_contents($filePath, implode("\n", $this->headers), FILE_APPEND);
    }
}