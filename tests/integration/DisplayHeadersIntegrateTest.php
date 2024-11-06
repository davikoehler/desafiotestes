<?php

use Headers\DisplayHeaders;
use Headers\Response\Cookie;
use Headers\Response\Expires;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DisplayHeaders::class)]
#[UsesClass(Cookie::class)]
#[UsesClass(Expires::class)]
final class DisplayHeadersIntegrateTest extends TestCase
{
    public function testDisplayHeadersShouldIntegrateWithCookieClass()
    {
        $cookie1 = new Cookie('name1', 'value123456');
        $cookie2 = new Cookie('name2', 'value678912332', new DateTimeImmutable('2023-02-06 13:00:00'));
        $expires = new Expires();
        $expires->hours(2)
                ->minutes(20)
                ->seconds(38);
        $cookie2->setExpires($expires);


        $displayHeaders = new DisplayHeaders();
        $displayHeaders->add($cookie1);
        $displayHeaders->add($cookie2);

        $result = $displayHeaders->getHeaderString();

        $this->assertEquals(<<<HEADERS
Set-Cookie: name1=value123456
Set-Cookie: name2=value678912332; Expires=Mon, 06 Feb 2023 15:20:38 GMT
HEADERS, $result);
    }

    public function testDisplayHeadersShouldIntegrateWithCookieClassWithExpires(): void
    {
        $cookie1 = new Cookie('name1', 'value123456', new DateTimeImmutable('2023-02-06 13:00:00'));
        $expires1 = new Expires();
        $expires1->days(1)
                ->hours(2)
                ->minutes(42)
                ->seconds(29);
        $cookie1->setExpires($expires1);

        $cookie2 = new Cookie('name2', 'value678912332', new DateTimeImmutable('2023-02-06 13:00:00'));
        $expires2 = new Expires();
        $expires2->hours(2)
                ->minutes(20)
                ->seconds(38);
        $cookie2->setExpires($expires2);

        $displayHeaders = new DisplayHeaders();
        $displayHeaders->add($cookie1);
        $displayHeaders->add($cookie2);

        $result = $displayHeaders->getHeaderString();

        $this->assertEquals(<<<HEADERS
Set-Cookie: name1=value123456; Expires=Tue, 07 Feb 2023 15:42:29 GMT
Set-Cookie: name2=value678912332; Expires=Mon, 06 Feb 2023 15:20:38 GMT
HEADERS, $result);
    }

    public function testDisplayHeadersShouldIntegrateWith3CookieClassWithExpires(): void
    {
        $cookie1 = new Cookie('name1', 'value123456', new DateTimeImmutable('2023-02-06 13:00:00'));
        $expires1 = new Expires();
        $expires1->days(1)
                ->hours(2)
                ->minutes(42)
                ->seconds(29);
        $cookie1->setExpires($expires1);

        $cookie2 = new Cookie('name2', 'value678912332', new DateTimeImmutable('2023-02-06 13:00:00'));
        $expires2 = new Expires();
        $expires2->hours(2)
                ->minutes(20)
                ->seconds(38);
        $cookie2->setExpires($expires2);

        $cookie3 = new Cookie('name3', 'valueqwee12334', new DateTimeImmutable('2023-02-06 13:00:00'));
        $expires3 = new Expires();
        $expires3->hours(1)
                ->minutes(30)
                ->seconds(22);
        $cookie3->setExpires($expires3);

        $displayHeaders = new DisplayHeaders();
        $displayHeaders->add($cookie1);
        $displayHeaders->add($cookie2);
        $displayHeaders->add($cookie3);

        $result = $displayHeaders->getHeaderString();

        $this->assertEquals(<<<HEADERS
Set-Cookie: name1=value123456; Expires=Tue, 07 Feb 2023 15:42:29 GMT
Set-Cookie: name2=value678912332; Expires=Mon, 06 Feb 2023 15:20:38 GMT
Set-Cookie: name3=valueqwee12334; Expires=Mon, 06 Feb 2023 14:30:22 GMT
HEADERS, $result);
    }

    public function testDisplayHeadersWithoutHeadersShouldThrowAnException()
    {
        $this->expectException(Exception::class);
        
        $displayHeaders = new DisplayHeaders();
        $displayHeaders->getHeaderString();
    }
}