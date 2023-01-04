<?php

namespace packages\httpmonitoring\Tests\Unit\Utility;

use P2media\Httpmonitoring\Utility\UriUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class UriUtilityTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     * @dataProvider uriProvider
     */
    public function trimUriReturnsExpectedOutputWhenGivenValidUri(string $uri, string|false $expected): void
    {
        $this->assertSame($expected, UriUtility::trimUri($uri));
    }

    public function uriProvider(): array
    {
        return [
            'normal https' => [
                'https://example.com',
                "example.com"
            ],
            'normal http' => [
                'http://example.com',
                "example.com"
            ],
            'only host' => [
                'https://example',
                'example'
            ],
            'only path' => [
                'example',
                'example'
            ],
            'username and password' => [
                'https://example:example@example.com',
                'example.com'
            ],
            'port' => [
                'https://example.com:1234',
                'example.com'
            ],
            'path' => [
                'https://example.com/path',
                'example.com/path'
            ],
            'fragment' => [
                'https://example.com#example',
                'example.com'
            ],
            'combined components' => [
                'https://example:example@example.com:1234/path#example',
                'example.com/path'
            ],
            'empty' => [
                '',
                ''
            ],
            'invalid uri' => [
                '//',
                false
            ]
        ];
    }

    /**
     * @test
     */
    public function isValidTrimmedUriReturnsTrueIfUriIsValidTest(): void {
        $this->assertTrue(UriUtility::isValidTrimmedUri('example.com'));
    }

    /**
     * @test
     */
    public function isValidTrimmedUriReturnsFalseIfUriIsInvalidTest(): void {
        $this->assertFalse(UriUtility::isValidTrimmedUri('//'));
    }
}