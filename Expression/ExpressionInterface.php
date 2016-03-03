<?php

namespace TonicHealthCheck\Check\Condition\Expression;

use TonicHealthCheck\Check\CheckResult;

/**
 * Interface ExpressionInterface
 * @package TonicHealthCheck\Check\Condition\Expression
 */
interface ExpressionInterface
{
    /**
     * @param mixed
     * @return CheckResult
     */
    public function interpret($context);
}
