<?php

namespace TonicHealthCheck\Tests\Check\Condition;

use PHPUnit_Framework_MockObject_MockObject;
use TonicHealthCheck\Check\CheckException;
use TonicHealthCheck\Check\CheckInterface;
use TonicHealthCheck\Check\CheckResult;
use TonicHealthCheck\Check\Condition\ConditionCheck;
use TonicHealthCheck\Check\Condition\Expression\Evaluator;
use TonicHealthCheck\Check\Condition\Expression\Exception\RightExpressionException;
use TonicHealthCheck\Checker\ChecksList;
use TonicHealthCheck\Tests\Check\CheckCollectionConcreteMock;
use TonicHealthCheck\Tests\Check\CheckConcreteMock;

/**
 * Class ConditionCheckTest
 * @package Check\Condition
 */
class ConditionCheckTest extends \PHPUnit_Framework_TestCase
{
    const TESTNODE_NAME_1 = 'testnode1';
    const TESTNODE_NAME_2 = 'testnode2';
    const TESTNODE_NAME_3 = 'testnode3';
    const TESTNODE_NAME_4 = 'testnode4';
    /**
     * @var ConditionCheck;
     */
    public $conditionCheck;
    /**
     * @var ChecksList
     */
    private $checksList;


    /**
     * set up
     */
    public function setUp()
    {
        $this->setChecksList(new ChecksList());
        $this->addCheckToList(self::TESTNODE_NAME_1);
        $this->addCheckToList(self::TESTNODE_NAME_2);
        $this->addCheckToList(self::TESTNODE_NAME_3);
        $this->addCheckToList(self::TESTNODE_NAME_4);

        $checkExpression = $this->getCheckByCheckName(self::TESTNODE_NAME_1)->getIndent();
        $checkExpression .= ' && ';
        $checkExpression .= $this->getCheckByCheckName(self::TESTNODE_NAME_2)->getIndent();
        $checkExpression .= ' || ';
        $checkExpression .= $this->getCheckByCheckName(self::TESTNODE_NAME_3)->getIndent();
        $evaluator = new Evaluator($checkExpression);
        $conditionCheck = new ConditionCheck(
            'testnode',
            $evaluator,
            $this->getChecksList()
        );

        $this->setConditionCheck($conditionCheck);

        parent::setUp();
    }

    /**
     * Test is LeftExpressionInterface is first fail
     *
     * @expectedException \TonicHealthCheck\Check\Condition\Expression\Exception\LeftExpressionException
     * @expectedExceptionCode 501
     */
    public function testCheckIsExpressionLeftFail()
    {

        $checkExpression = ' || ';
        $checkExpression .= $this->getCheckByCheckName(self::TESTNODE_NAME_1)->getIndent();
        $checkExpression .= ' && ';
        $checkExpression .= $this->getCheckByCheckName(self::TESTNODE_NAME_2)->getIndent();
        $checkExpression .= ' || ';
        $checkExpression .= $this->getCheckByCheckName(self::TESTNODE_NAME_3)->getIndent();

        $evaluator = new Evaluator($checkExpression);
        new ConditionCheck(
            'testnode',
            $evaluator,
            $this->getChecksList()
        );
    }

    /**
     * Test is RightExpressionInterface is last fail
     *
     * @expectedException \TonicHealthCheck\Check\Condition\Expression\Exception\RightExpressionException
     * @expectedExceptionCode 502
     */
    public function testCheckIsExpressionRightFail()
    {

        $checkExpression = $this->getCheckByCheckName(self::TESTNODE_NAME_1)->getIndent();
        $checkExpression .= ' && ';
        $checkExpression .= $this->getCheckByCheckName(self::TESTNODE_NAME_2)->getIndent();
        $checkExpression .= ' || ';
        $checkExpression .= $this->getCheckByCheckName(self::TESTNODE_NAME_3)->getIndent();
        $checkExpression .= ' || ';
        $evaluator = new Evaluator($checkExpression);
        new ConditionCheck(
            'testnode',
            $evaluator,
            $this->getChecksList()
        );
    }

    /**
     * Test is ok
     */
    public function testCheckIsOk()
    {
        $this
            ->getCheckByCheckName(self::TESTNODE_NAME_1)
            ->method('performCheck')
            ->willReturn($this->getCheckResultOk());

        $this
            ->getCheckByCheckName(self::TESTNODE_NAME_2)
            ->method('performCheck')
            ->willReturn($this->getCheckResultFail());
        $this
            ->getCheckByCheckName(self::TESTNODE_NAME_3)
            ->method('performCheck')
            ->willReturn($this->getCheckResultOk());

        $checkResult = $this->getConditionCheck()->performCheck();

        $this->assertTrue($checkResult->isOk());
        $this->assertNull($checkResult->getError());

    }

    /**
     * Test is fail
     */
    public function testCheckIsFail()
    {
        $status = 2345;

        $errorMsg = 'Error message';
        $errorCode = 1234;

        $checkResultFail = new CheckResult($status, new CheckException($errorMsg, $errorCode));

        $this
            ->getCheckByCheckName(self::TESTNODE_NAME_1)
            ->method('performCheck')
            ->willReturn($checkResultFail);

        $this
            ->getCheckByCheckName(self::TESTNODE_NAME_2)
            ->method('performCheck')
            ->willReturn($this->getCheckResultOk());
        $this
            ->getCheckByCheckName(self::TESTNODE_NAME_3)
            ->method('performCheck')
            ->willReturn($this->getCheckResultOk());

        $checkResult = $this->getConditionCheck()->performCheck();

        $this->assertFalse($checkResult->isOk());
        $this->assertInstanceOf(
            CheckException::class,
            $checkResult->getError()
        );
        $this->assertEquals(
            $errorCode,
            $checkResult->getError()->getCode()
        );

    }

    /**
     * @return ConditionCheck
     */
    public function getConditionCheck()
    {
        return $this->conditionCheck;
    }

    /**
     * @return ChecksList
     */
    public function getChecksList()
    {
        return $this->checksList;
    }

    /**
     * @param ConditionCheck $conditionCheck
     */
    protected function setConditionCheck($conditionCheck)
    {
        $this->conditionCheck = $conditionCheck;
    }

    /**
     * @param ChecksList $checksList
     */
    protected function setChecksList($checksList)
    {
        $this->checksList = $checksList;
    }

    /**
     * @param $nodeName
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCheckByCheckName($nodeName)
    {
        return $this->getChecksList()->find(
            function (CheckInterface $check) use ($nodeName) {
                return $check->getCheckNode() == $nodeName;
            }
        );
    }

    /**
     * @param $checkName
     */
    protected function addCheckToList($checkName)
    {
        $this->getChecksList()->add(
            $this
                ->getMockBuilder(CheckConcreteMock::class)
                ->setConstructorArgs([$checkName])
                ->setMethods(['check','performCheck'])
                ->getMock()
        );
    }

    /**
     * @return CheckResult
     */
    protected function getCheckResultFail()
    {
        $checkResultFail = new CheckResult(23, new CheckException('Error message 23'));

        return $checkResultFail;
    }

    /**
     * @return CheckResult
     */
    protected function getCheckResultOk()
    {
        $checkResultOk = new CheckResult(CheckResult::STATUS_OK);

        return $checkResultOk;
    }

}

