<?php
namespace Aura\View;
class FormatTypesTest extends \PHPUnit_Framework_TestCase
{
    public function test__constructAndGetContentType()
    {
        $map = [
            '.txt' => 'example/override',
            '.new' => 'example/new',
        ];
        
        $format_types = new FormatTypes($map);
        
        // get a type that was not changed
        $expect = 'application/json';
        $actual = $format_types->getContentType('.json');
        $this->assertSame($expect, $actual);
        
        // get a type that was overridden
        $expect = 'example/override';
        $actual = $format_types->getContentType('.txt');
        $this->assertSame($expect, $actual);
        
        // get a type that was added
        $expect = 'example/new';
        $actual = $format_types->getContentType('.new');
        $this->assertSame($expect, $actual);
        
        // no such format
        $actual = $format_types->getContentType('.nosuchformat');
        $this->assertNull($actual);
    }
    
    public function testMatchAcceptFormats()
    {
        $accept = [
            'text/html',
            'application/xhtml+xml',
            'application/json',
            'application/xml',
        ];
        
        $formats = [
            '.json',
            '.xhtml',
        ];
        
        // should find .xhtml first, since it shows up first for accept
        $format_types = new FormatTypes;
        $actual = $format_types->matchAcceptFormats($accept, $formats);
        $expect = '.xhtml';
        $this->assertSame($expect, $actual);
        
        // no such type
        $accept = [
            'application/no-such-type',
        ];
        $actual = $format_types->matchAcceptFormats($accept, $formats);
        $this->assertNull($actual);
        
        // no such format
        $formats = ['.nosuchformat'];
        $actual = $format_types->matchAcceptFormats($accept, $formats);
        $this->assertNull($actual);
    }
}
