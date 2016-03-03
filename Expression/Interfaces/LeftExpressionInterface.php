<?php

namespace TonicHealthCheck\Check\Condition\Expression\Interfaces;

use TonicHealthCheck\Check\Condition\Expression\ExpressionInterface;

/**
 * Interface LeftExpressionInterface
 * @package TonicHealthCheck\Check\Condition\Expression\Interfaces
 */
interface LeftExpressionInterface
{
    /**
     * @return ExpressionInterface
     */
    public function getLeftExpression();

    /**
     * @param ExpressionInterface $leftExpression
     */
    public function setLeftExpression(ExpressionInterface $leftExpression);
}
