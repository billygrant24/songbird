<?php
namespace Songbird\Package\Plates\Extension;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Songbird\Document\Pagination\Paginator;

class QueryExtension implements ExtensionInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function register(Engine $engine)
    {
        $engine->registerFunction('query', [$this, 'query']);
        $engine->registerFunction('paginator', [$this, 'paginator']);
    }

    /**
     * Provides an interface to query content from template files.
     *
     * @param array $params
     *
     * @return \JamesMoss\Flywheel\Result
     */
    public function query(array $params = [])
    {
        $config = $this->getContainer()->get('Config');
        $repo = $this->getContainer()->get('App.Repo.Documents');

        $collection = $repo->query();

        if (isset($params['type'])) {
            $collection->where('_type', '==', $params['type']);
        }

        $queries = isset($params['query']) ? $params['query'] : [];
        foreach ($queries as $query) {
            $collection->where($query[0], $query[1], $query[2]);
        }

        $orderBy = isset($params['orderBy']) ? $params['orderBy'] : $config['display.sorting'];
        $collection->orderBy($orderBy);

        $limit = isset($params['limit']) ? $params['limit'] : $config['display.perPage'];
        $collection->paginate($limit);

        return $collection->execute();
    }

    public function paginator($resultSet)
    {
        return new Paginator($resultSet);
    }
}