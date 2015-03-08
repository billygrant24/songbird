<?php
namespace Songbird\Package\Twig\Extension;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Songbird\ConfigAwareInterface;
use Songbird\ConfigAwareTrait;
use Songbird\Document\Pagination\Paginator;
use Twig_Extension;
use Twig_SimpleFunction;

class QueryExtension extends Twig_Extension implements ContainerAwareInterface, ConfigAwareInterface
{
    use ContainerAwareTrait, ConfigAwareTrait;

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('query', [$this, 'getQuery']),
            new Twig_SimpleFunction('paginator', [$this, 'getPaginator']),
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'query';
    }

    /**
     * Provides an interface to query content from template files.
     *
     * @param array $params
     *
     * @return \JamesMoss\Flywheel\Result
     */
    public function getQuery(array $params = [])
    {
        $config = $this->getConfig();
        $repo = $this->getContainer()->get('App.Repo.Documents');
        $collection = $repo->query();

        if (isset($params['type'])) {
            $collection->where('_type', '==', $params['type']);
        }

        $queries = isset($params['query']) ? $params['query'] : [];
        foreach ($queries as $query) {
            $collection->where($query[0], $query[1], $query[2]);
        }

        $orderBy = isset($params['orderBy']) ? $params['orderBy'] : $config->get('display.sorting');
        $collection->orderBy($orderBy);

        $limit = isset($params['limit']) ? $params['limit'] : $config->get('display.perPage');
        $collection->paginate($limit);

        return $collection->execute();
    }

    public function getPaginator($resultSet)
    {
        return new Paginator($resultSet);
    }
}