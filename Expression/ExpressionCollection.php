<?php
namespace TonicHealthCheck\Check\Condition\Expression;

use Collections\Collection;

/**
 * Class ExpressionCollection
 * @package TonicHealthCheck\Check\Condition\Expression
 */
class ExpressionCollection extends Collection
{
    const OBJECT_CLASS = ExpressionInterface::class;

    /**
     * IncidentCollection constructor.
     */
    public function __construct()
    {
        parent::__construct(static::OBJECT_CLASS);
    }
}