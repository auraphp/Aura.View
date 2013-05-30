<?php
namespace Aura\Html;

return [
    'anchor'        => function () { return new Helper\Anchor; },
    'attribs'       => function () { return new Helper\Attribs; },
    'base'          => function () { return new Helper\Base; },
    'form'          => function () { return new Helper\Form; },
    'input'         => function () {
        $helper_locator = new HelperLocator(new HelperFactory(
            new Escape,
            require __DIR__ . '/input_registry.php'
        ));
        $input = new Helper\Input;
        $input->setHelperLocator($helper_locator);
        return $input;
    },
    'img'           => function () { return new Helper\Img; },
    'links'         => function () { return new Helper\Links; },
    'metas'         => function () { return new Helper\Metas; },
    'ol'            => function () { return new Helper\Ol; },
    'scripts'       => function () { return new Helper\Scripts; },
    'scriptsFoot'   => function () { return new Helper\Scripts; },
    'styles'        => function () { return new Helper\Styles; },
    'tag'           => function () { return new Helper\Tag; },
    'title'         => function () { return new Helper\Title; },
    'ul'            => function () { return new Helper\Ul; },
];

