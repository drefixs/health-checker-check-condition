<?php

namespace TonicHealthCheck\Check\Condition\Expression\Interfaces;

use TonicHealthCheck\Check\Condition\Expression\ExpressionInterface;

/**
 * Interface RightExpressionInterface
 * @package TonicHealthCheck\Check\Condition\Expression\Interfaces
 */
interface RightExpressionInterface
{
    /**
     * @return ExpressionInterface
     */
    public function getRightExpression();

    /**
     * @param ExpressionInterface $rightExpression
     */
    public function setRightExpression(ExpressionInterface $rightExpression);
}
