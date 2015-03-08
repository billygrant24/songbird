<?php
namespace Songbird\Package\Plates;

use League\Container\ContainerInterface;
use League\Container\ServiceProvider;
use League\Plates\Engine;

class PlatesServiceProvider extends ServiceProvider
{
    protected $provides = [
        'Template'
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->getContainer();
        $config = $this->getContainer()->get('Config');

        $this->registerEngine($app);
        $this->registerExtensions($app);
        $this->registerEventListeners($app);

        $template = $app->resolve('Songbird\Package\Plates\Template');

        $template->setTwig($app->get('Plates.Engine'));

        $template->getTwig()->addData([
            'siteTitle' => $config->get('vars.siteTitle'),
            'baseUrl' => $config->get('vars.baseUrl'),
            'themeDir' => $config->get('vars.baseUrl') . '/themes/' . $config->get('display.theme'),
            'dateFormat' => $config->get('dateFormat'),
            'excerptLength' => $config->get('excerptLength'),
        ]);

        $app->add('Template', $template);
    }

    /**
     * @param \League\Container\ContainerInterface $app
     */
    protected function registerEventListeners(ContainerInterface $app)
    {
        // Renders the full template.
        $app->addListener('RenderTemplate', $app->get('Songbird\Package\Plates\TemplateParser'));
    }

    /**
     * Register all Plates extensions required by Songbird.
     *
     * @param \League\Container\ContainerInterface $app
     */
    protected function registerExtensions(ContainerInterface $app)
    {
        $app->get('Plates.Engine')->loadExtension($app->resolve('Songbird\Package\Plates\Extension\QueryExtension'));
        $app->get('Plates.Engine')->loadExtension($app->resolve('Songbird\Package\Plates\Extension\FragmentExtension'));
    }

    /**
     * @param \League\Container\ContainerInterface $app
     */
    protected function registerEngine(ContainerInterface $app)
    {
        $config = $this->getContainer()->get('Config');

        $app->add('Plates.Engine', new Engine(null, $config['plates.extension']));

        $themeDir = vsprintf('%s/%s', [$config['plates.templatesDir'], $config['display.theme']]);
        $app->get('Plates.Engine')->addFolder('theme', $themeDir);
    }
}