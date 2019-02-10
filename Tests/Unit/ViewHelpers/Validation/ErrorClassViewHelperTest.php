<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\ViewHelpers\Validation\ErrorClassViewHelper;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Test for ErrorClassViewHelperTest
 */
class ErrorClassViewHelperTest extends BaseTestCase
{
    /**
     * Viewhelper
     *
     * @var \DERHANSEN\SfEventMgt\ViewHelpers\Validation\ErrorClassViewHelper
     */
    protected $viewhelper = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->viewhelper = $this->getMockBuilder(ErrorClassViewHelper::class)
            ->setMethods(['getValidationErrors'])->getMock();
    }

    /**
     * Teardown
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->viewhelper);
    }

    /**
     * @return array
     */
    public function fieldnameDataProvider()
    {
        return [
            'No fieldname' => [
                [],
                '',
                ''
            ],
            'No error for fieldname' => [
                [
                    'registration.lastname' => []
                ],
                'firstname',
                ''
            ],
            'Error for fieldname with default class name' => [
                [
                    'registration.firstname' => []
                ],
                'firstname',
                'error-class'
            ],
            'Error for fieldname with custom class name' => [
                [
                    'registration.firstname' => []
                ],
                'firstname',
                'custom-class',
                'custom-class'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider fieldnameDataProvider
     * @param $validationErrors
     * @param $fieldname
     * @param $expected
     * @param string $errorClass
     */
    public function viewHelperReturnsExpectedStringForFieldname(
        $validationErrors,
        $fieldname,
        $expected,
        $errorClass = 'error-class'
    ) {
        $this->viewhelper->expects($this->once())->method('getValidationErrors')
            ->will($this->returnValue($validationErrors));
        $this->viewhelper->setArguments([
            'fieldname' => $fieldname,
            'class' => $errorClass
        ]);
        $this->assertEquals($expected, $this->viewhelper->render());
    }

    /**
     * @return array
     */
    public function registrationFieldDataProvider()
    {
        $mockField = $this->getMockBuilder(Field::class)->setMethods(['getUid'])->getMock();
        $mockField->expects($this->any())->method('getUid')->will($this->returnValue(2));

        return [
            'No registration field' => [
                [],
                '',
                ''
            ],
            'No error for registration field' => [
                [
                    'registration.fields.1' => []
                ],
                $mockField,
                ''
            ],
            'Error for fieldname with default class name' => [
                [
                    'registration.fields.2' => []
                ],
                $mockField,
                'error-class'
            ],
            'Error for fieldname with custom class name' => [
                [
                    'registration.fields.2' => []
                ],
                $mockField,
                'custom-class',
                'custom-class'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider registrationFieldDataProvider
     * @param $validationErrors
     * @param $registrationField
     * @param $expected
     * @param string $errorClass
     */
    public function viewHelperReturnsExpectedStringForRegistrationField(
        $validationErrors,
        $registrationField,
        $expected,
        $errorClass = 'error-class'
    ) {
        $this->viewhelper->expects($this->once())->method('getValidationErrors')
            ->will($this->returnValue($validationErrors));
        $this->viewhelper->setArguments([
            'registrationField' => $registrationField,
            'class' => $errorClass
        ]);
        $this->assertEquals($expected, $this->viewhelper->render());
    }
}
