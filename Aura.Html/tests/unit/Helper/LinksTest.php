<?php
namespace Aura\Html\Helper;

class LinksTest extends \PHPUnit_Framework_TestCase
{
    public function test__invoke()
    {
        $links = new Links;
        $actual = $links();
        $this->assertInstanceOf('Aura\Html\Helper\Links', $actual);
    }
    
    public function testAddAndGet()
    {
        $links = new Links;
        
        $data = (object) [
            'prev' => [
                'rel' => 'prev',
                'href' => '/path/to/prev',
            ],
            'next' => [
                'rel' => 'next',
                'href' => '/path/to/next',
            ]
        ];
        
        $links->add($data->prev);
        $links->add($data->next);
        
        $actual = $links->get();
        $expect = '    <link rel="prev" href="/path/to/prev" />' . PHP_EOL
                . '    <link rel="next" href="/path/to/next" />' . PHP_EOL;
       
        $this->assertSame($expect, $actual);
    }

    /**
     * @todo Implement testSetIndent().
     */
    public function testSetIndent()
    {
        $links = new Links;
        $links->setIndent('  ');
        
        $data = (object) [
            'prev' => [
                'rel' => 'prev',
                'href' => '/path/to/prev',
            ],
            'next' => [
                'rel' => 'next',
                'href' => '/path/to/next',
            ]
        ];
        
        $links->add($data->prev);
        $links->add($data->next);
        
        $actual = $links->get();
        $expect = '  <link rel="prev" href="/path/to/prev" />' . PHP_EOL
                . '  <link rel="next" href="/path/to/next" />' . PHP_EOL;
       
        $this->assertSame($expect, $actual);
    }
}
