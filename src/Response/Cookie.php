<?php
namespace Headers\Response;

use DateInterval;
use DateTimeImmutable;
use Headers\Interfaces\HeaderStringInterface;
use InvalidArgumentException;

class Cookie implements HeaderStringInterface
{
    protected array $parameters;

    public function __construct(
        protected string $cookieName,
        protected string $cookieValue,
        protected DateTimeImmutable $startDate = new DateTimeImmutable('now'),
    ) {
        $this->cookieName = trim($this->cookieName);
        $this->cookieValue = trim($this->cookieValue);
        $this->setCookieValue();
    }

    protected function setCookieValue(): void
    {
        $cookieNameParts = explode(' ', $this->cookieName);
        if (count($cookieNameParts) > 1) {
            throw new InvalidArgumentException(
                "Cookie name cannot have spaces between provided: $this->cookieName"
            );
        }

        if (mb_detect_encoding($this->cookieName) !== 'ASCII') {
            throw new InvalidArgumentException("Cookie name can only be US ASCII, ex: [0-9][a-zA-Z]-");
        }

        $cookieValue = urlencode($this->cookieValue);
        $this->parameters[] = "Set-Cookie: {$this->cookieName}={$cookieValue}";
    }

    public function setExpires(Expires $expiresComponent): void
    {
        $dateInterval = $this->startDate->add(
            DateInterval::createFromDateString($expiresComponent->get())
        );
        $formattedDate = $dateInterval->format('D, d M Y H:i:s \G\M\T');
        $this->parameters[] = "Expires=$formattedDate";
    }

    public function getHeaderString(): string
    {
        return implode('; ', $this->parameters);
    }
}