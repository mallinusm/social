<?php

namespace Social\Services;

use Exception;
use League\Fractal\{
    Manager,
    Scope,
    TransformerAbstract
};
use League\Fractal\Resource\{
    Collection,
    Item
};
use League\Fractal\Serializer\ArraySerializer;
use ReflectionClass;
use Social\Contracts\Services\TransformerService;

/**
 * Class FractalTransformerService
 * @package Social\Services
 */
final class FractalTransformerService implements TransformerService
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var
     */
    private $data;

    /**
     * @var TransformerAbstract
     */
    private $transformer;

    /**
     * TransformerService constructor.
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        /**
         * The ArraySerializer wraps, by default, collections with a 'data' key.
         * We cannot disable this functionality other than by overriding the function.
         */
        $this->manager = $manager->setSerializer(new class extends ArraySerializer {
            /**
             * @param string $resourceKey
             * @param array $data
             * @return array
             */
            public function collection($resourceKey, array $data): array { return $data; }
        });
    }

    /**
     * @return Collection|Item
     */
    private function createResource()
    {
        if (is_array($this->data)) {
            return new Collection($this->data, $this->transformer);
        }

        return new Item($this->data, $this->transformer);
    }

    /**
     * @return Scope
     */
    private function createScope(): Scope
    {
        return new Scope($this->manager, $this->createResource());
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return $this->createScope()->toJson($options);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->createScope()->toArray();
    }

    /**
     * @param mixed $data
     * @return TransformerService
     */
    public function setData($data): TransformerService
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param object|string $transformer
     * @return TransformerService
     * @throws Exception
     */
    public function setTransformer($transformer): TransformerService
    {
        if (is_string($transformer)) {
            $class = new ReflectionClass($transformer);

            if (! $class->isInstantiable()) {
                throw new Exception(sprintf('Class %s is not instantiable.', $class->getName()));
            }

            $transformer = $class->newInstance();
        }

        if (! $transformer instanceof TransformerAbstract) {
            throw new Exception(
                sprintf('Transformer %s must extend %s.', get_class($transformer), TransformerAbstract::class)
            );
        }

        $this->transformer = $transformer;

        return $this;
    }
}
