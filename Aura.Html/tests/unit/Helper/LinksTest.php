<?php
namespace Aura\Html\Helper;

class LinksTest extends AbstractHelperTest
{
    public function test__invoke()
    {
        $links = $this->helper;
        $actual = $links();
        $this->assertInstanceOf('Aura\Html\Helper\Links', $actual);
    }
    
    public function testAddAndGet()
    {
        $links = $this->helper;
        
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
        $links = $this->helper;
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
