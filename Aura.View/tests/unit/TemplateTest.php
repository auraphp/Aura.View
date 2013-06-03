<?php
namespace Aura\View;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
    protected $template;
    
    protected $factory;
    
    protected $helper;
    
    protected function setUp()
    {
        $this->finder = new Finder;
        $this->finder->setClosure('another_template', function () {
            echo 'Hello Another Template!';
        });
        $this->finder->setClosure('partial_template', function () {
            echo 'Hello Partial ' . $this->partial_noun . '!';
        });
        
        $this->factory = new Factory;
        $this->factory->setFinder($this->finder);
        
        $this->helper = new MockHelper;
        
        $this->template = new MockTemplate(
            $this->factory,
            $this->helper,
            []
        );
    }
    
    public function testMagicMethods()
    {
        // __isset()
        $this->assertFalse(isset($this->template->foo));
        
        // __set()/__isset()
        $this->template->foo = 'bar';
        $this->assertTrue(isset($this->template->foo));
        
        // __get()
        $this->assertSame('bar', $this->template->foo);
        
        // __unset()
        unset($this->template->foo);
        $this->assertFalse(isset($this->template->foo));
        
        // __call()
        $actual = $this->template->helloHelper();
        $this->assertSame('Hello Helper', $actual);
    }
    
    public function testGetters()
    {
        $this->assertSame($this->factory, $this->template->getFactory());
        $this->assertSame($this->helper, $this->template->getHelper());
    }
    
    /**
     * @todo Implement testSetData().
     */
    public function testAddSetAndGetData()
    {
        $expect = [];
        $actual = (array) $this->template->getData();
        $this->assertSame($expect, $actual);
        
        // add data
        $this->template->foo = 'bar';
        $this->template->addData(['baz' => 'dib']);
        $expect = [
            'foo' => 'bar',
            'baz' => 'dib',
        ];
        $actual = (array) $this->template->getData();
        $this->assertSame($expect, $actual);
        
        // set data
        $data = [
            'foo' => 'bar'
        ];
        $this->template->setData($data);
        $this->assertSame('bar', $this->template->foo);
        
        $actual = (array) $this->template->getData();
        $this->assertSame($data, $actual);
    }

    public function testInvoke()
    {
        $template = $this->template;
        $template->noun = 'World';
        ob_start();
        $template();
        $actual = ob_get_clean();
        $expect = 'Hello World!';
    }
    
    public function testSetHelper_invalidHelper()
    {
        $this->setExpectedException('Aura\View\Exception\InvalidHelper');
        $this->template->setHelper('not a helper');
    }
    
    public function testTemplate()
    {
        $actual = $this->template->template('another_template');
        $this->assertSame('Hello Another Template!', $actual);
    }
    
    public function testPartial()
    {
        $actual = $this->template->partial('partial_template', ['partial_noun' => 'World']);
        $this->assertSame('Hello Partial World!', $actual);
    }
    
    // public function testFetchDirect()
    // {
    //     // the template file
    //     $file = 'root' . DIRECTORY_SEPARATOR
    //           . 'fetch' . DIRECTORY_SEPARATOR
    //           . 'zim.php';
    //     
    //     $file = Vfs::url($file);
    //     
    //     // the template code
    //     $code = '<?php echo $this->foo; ?\>';
    //     
    //     // put it in place
    //     $dir = dirname($file);
    //     mkdir($dir, 0777, true);
    //     file_put_contents($file, $code);
    //     
    //     // get a template object
    //     $template = $this->newTemplate();
    //     $template->foo = 'bar';
    //     
    //     // fetch it
    //     $actual = $template->fetch($file);
    //     $expect = 'bar';
    //     $this->assertSame($expect, $actual);
    //     
    //     // fetch it again for coverage
    //     $actual = $template->fetch($file);
    //     $expect = 'bar';
    //     $this->assertSame($expect, $actual);
    // }
    // 
    // public function testPartial()
    // {
    //     // the template file
    //     $file = 'root' . DIRECTORY_SEPARATOR
    //           . 'partial' . DIRECTORY_SEPARATOR
    //           . '_foo.php';
    //     
    //     $file = Vfs::url($file);
    //     
    //     // the template code
    //     $code = '<?php echo $this->foo; ?\>';
    //     
    //     // put it in place
    //     $dir = dirname($file);
    //     mkdir($dir, 0777, true);
    //     file_put_contents($file, $code);
    //     
    //     // get a template object
    //     $template = $this->newTemplate([$dir]);
    //     $actual = $template->partial('_foo', ['foo' => 'dib']);
    //     $expect = 'dib';
    //     $this->assertSame($expect, $actual);
    // }
    // 
    // public function testFindTemplateNotFound()
    // {
    //     $template = $this->newTemplate();
    //     $this->setExpectedException('Aura\View\Exception\TemplateNotFound');
    //     $template->find('no_such_template');
    // }
    // 
    // public function testGetHelper()
    // {
    //     $template = $this->newTemplate();
    //     $actual = $template->getHelper('mockHelper');
    //     $this->assertInstanceOf('Aura\View\Helper\MockHelper', $actual);
    //     $again = $template->getHelper('mockHelper');
    //     $this->assertSame($actual, $again);
    // }
    // 
    // public function testGetHelperNotMapped()
    // {
    //     $template = $this->newTemplate();
    //     $this->setExpectedException('Aura\View\Exception\HelperNotMapped');
    //     $actual = $template->getHelper('noSuchHelper');
    // }
    // 
    // public function testGetHelperLocator()
    // {
    //     $template = $this->newTemplate();
    //     $actual = $template->getHelperLocator();
    //     $this->assertInstanceOf('Aura\View\HelperLocator', $actual);
    // }
    // 
    // public function testGetFinder()
    // {
    //     $template = $this->newTemplate();
    //     $actual = $template->getFinder();
    //     $this->assertInstanceOf('Aura\View\Finder', $actual);
    // }
    // 
    // public function test__raw()
    // {
    //     $template = $this->newTemplate();
    //     $actual = $template->__raw();
    //     $this->assertInstanceOf('StdClass', $actual);
    // }
    // 
    // public function testGithubIssue15()
    // {
    //     $expect = [
    //         [
    //             'id' => "1",
    //             'author_id' => "1",
    //             'title' => "Hello World",
    //             'body' => "Hello World",
    //         ],
    //         [
    //             'id' => "2",
    //             'author_id' => "2",
    //             'title' => "Sample title 2",
    //             'body' => "Sample body for the title 2",
    //         ],
    //         [
    //             'id' => "3",
    //             'author_id' => "3",
    //             'title' => "Sample title 3",
    //             'body' => "body 3",
    //         ]
    //     ];
    //     
    //     $template = $this->newTemplate();
    //     $template->posts = $expect;
    //     
    //     foreach ($template->posts as $key => $actual) {
    //         $this->assertSame($expect[$key]['id'], $actual['id']);
    //         $this->assertSame($expect[$key]['author_id'], $actual['author_id']);
    //         $this->assertSame($expect[$key]['title'], $actual['title']);
    //         $this->assertSame($expect[$key]['body'], $actual['body']);
    //     }
    // }
}
