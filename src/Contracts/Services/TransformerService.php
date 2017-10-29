<?php

namespace Social\Contracts\Services;

/**
 * Interface TransformerService
 * @package Social\Contracts\Services
 */
interface TransformerService
{
    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string;

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @param mixed $data
     * @return TransformerService
     */
    public function setData($data): TransformerService;

    /**
     * @param object|string $transformer
     * @return TransformerService
     */
    public function setTransformer($transformer): TransformerService;
}
