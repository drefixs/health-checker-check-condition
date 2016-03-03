<?php

namespace TonicHealthCheck\Check\Condition\Expression\Exception;

use TonicHealthCheck\Check\Condition\Expression\EvaluatorException;

/**
 * Class RightExpressionException
 * @package TonicHealthCheck\Check\Condition\Expression\Exception
 */
class RightExpressionException extends EvaluatorException
{
    const TEXT_CAN_NOT_BE_FIRST = "RightExpressionInterface can't be last token in the expression:\n%s";

    const CODE_CAN_NOT_BE_FIRST = 502;

    /**
     * @param string $expression
     * @return RightExpressionException
     */
    public static function canNotBeLast($expression)
    {
        return new static(sprintf(self::TEXT_CAN_NOT_BE_FIRST, $expression), self::CODE_CAN_NOT_BE_FIRST);
    }
}
