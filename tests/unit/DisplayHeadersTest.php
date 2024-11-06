<?php

use Headers\DisplayHeaders;
use Headers\Interfaces\HeaderStringInterface;
use Headers\Response\Cookie;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DisplayHeaders::class)]
final class DisplayHeadersTest extends TestCase
{
    public function testDisplayHeadersComponentShouldDisplayHeadersAsString(): void
    {
        $displayHeaders = new DisplayHeaders();

        $cookieStub = Mockery::mock(Cookie::class);
        $cookieStub->allows()
                   ->getHeaderString()
                   ->andReturn('Set-Cookie: name=valor');

        $contentStub = Mockery::mock(HeaderStringInterface::class);
        $contentStub->allows()
                    ->getHeaderString()
                    ->andReturn('Content-Type: text/html; charset=utf-8');

        $displayHeaders->add($cookieStub);
        $displayHeaders->add($contentStub);

        $result = $displayHeaders->getHeaderString();
        $this->assertEquals(<<<HEADER
Set-Cookie: name=valor
Content-Type: text/html; charset=utf-8
HEADER, $result);
    }

    public function testDisplayHeadersComponentShouldDisplayNewHeadersAsString(): void
    {
        $displayHeaders = new DisplayHeaders();

        $cookieStub = Mockery::mock(Cookie::class);
        $cookieStub->allows()
                   ->getHeaderString()
                   ->andReturn('Set-Cookie: maisumcampo=valor12345');

        $contentStub = Mockery::mock(HeaderStringInterface::class);
        $contentStub->allows()
                    ->getHeaderString()
                    ->andReturn('Content-Type: multipart/form-data; boundary=something');

        $displayHeaders->add($cookieStub);
        $displayHeaders->add($contentStub);

        $result = $displayHeaders->getHeaderString();
        $this->assertEquals(<<<HEADER
Set-Cookie: maisumcampo=valor12345
Content-Type: multipart/form-data; boundary=something
HEADER, $result);
    }

    public function testDisplayHeaderShouldDisplayHearsInsideAFile(): void
    {
        $cookieStub = Mockery::mock(Cookie::class, ['getHeaderString' => 'Set-Cookie: maisumcampo=valor12345']);
        $contentStub = Mockery::mock(HeaderStringInterface::class, ['getHeaderString' => 'Content-Type: text/html; charset=utf-8']);

        $displayHeaders = new DisplayHeaders();
        $displayHeaders->add($cookieStub);
        $displayHeaders->add($contentStub);

        $displayHeaders->displayInFile('output.txt');

        $this->assertStringEqualsFile('output.txt', <<<HEADERS
HTTP/1.1 200 OK
Set-Cookie: maisumcampo=valor12345
Content-Type: text/html; charset=utf-8
HEADERS
        );
    }

    protected function setUp(): void
    {
        chdir('tests/unit');
        file_put_contents('output.txt', "HTTP/1.1 200 OK\n");
    }

    public function tearDown(): void
    {
        unlink('output.txt');
        Mockery::close();
    }
}