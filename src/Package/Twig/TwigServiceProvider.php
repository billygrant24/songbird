<?php
namespace Songbird\Package\Twig;

use League\Container\ContainerInterface;
use League\Container\ServiceProvider;
use Songbird\ConfigAwareInterface;
use Songbird\ConfigAwareTrait;
use Twig_Autoloader;
use Twig_Environment;
use Twig_Loader_Chain;
use Twig_Loader_Filesystem;
use Twig_Loader_String;

class TwigServiceProvider extends ServiceProvider implements ConfigAwareInterface
{
    use ConfigAwareTrait;

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
        $config = $this->getConfig();

        $this->registerEngine($app);
        $this->registerExtensions($app);
        $this->registerEventListeners($app);

        $template = $app->resolve('Songbird\Package\Twig\Template');

        $template->setTwig($app->get('Twig.Engine'));
        $template->setData([
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
        $app->addListener('RenderTemplate',
            $app->get('Songbird\Package\Twig\Parser\TemplateParser'));
    }

    /**
     * Register all Twig extensions required by Songbird.
     *
     * @param \League\Container\ContainerInterface $app
     */
    protected function registerExtensions(ContainerInterface $app)
    {
        $app->get('Twig.Engine')->addExtension($app->resolve('Songbird\Package\Twig\Extension\QueryExtension'));
        $app->get('Twig.Engine')->addExtension($app->resolve('Songbird\Package\Twig\Extension\FragmentExtension'));
    }

    /**
     * @param \League\Container\ContainerInterface $app
     *
     * @throws \Twig_Error_Loader
     */
    protected function registerEngine(ContainerInterface $app)
    {
        $config = $this->getConfig();

        Twig_Autoloader::register();

        $themeDir = vsprintf('%s/%s', [$config->get('twig.templatesDir'), $config->get('display.theme')]);

        $loader = new Twig_Loader_Filesystem();
        $loader->addPath($themeDir, 'theme');
        //$loader->addPath(__DIR__ . '/../resources/blocks', 'blocks');

        $loaders = new Twig_Loader_Chain([
            $loader,
            new Twig_Loader_String()
        ]);

        $app->add('Twig.Engine', new Twig_Environment($loaders, [
            'autoescape' => $config->get('twig.autoescape'),
            'cache' => $config->get('twig.cache'),
            'debug' => $config->get('twig.debug'),
        ]));

        if ($config->get('twig.cache')) {
            $app->get('Twig.Engine')->setCache(sprintf('%s/twig', $config->get('app.paths.cache')));
        }
    }
}