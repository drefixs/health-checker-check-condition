<?php

namespace TonicHealthCheck\Check\Condition\Expression\Traits;

use TonicHealthCheck\Check\Condition\Expression\ExpressionInterface;

trait RightExpressionTrait
{
    /**
     * @var ExpressionInterface;
     */
    public $rightExpression;

    /**
     * @param ExpressionInterface $rightExpression
     */
    public function setRightExpression(ExpressionInterface $rightExpression)
    {
        $this->rightExpression = $rightExpression;
    }

    /**
     * @return ExpressionInterface
     */
    public function getRightExpression()
    {
        return $this->rightExpression;
    }
}
