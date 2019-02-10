<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field\PrefillFieldViewHelper;
use TYPO3\TestingFramework\Core\BaseTestCase;
use TYPO3\CMS\Extbase\Mvc\Request;

/**
 * Test case for prefillField viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillFieldViewHelperTest extends BaseTestCase
{
    /**
     * @test
     * @return void
     */
    public function viewHelperReturnsFieldDefaultValueIfNoOriginalRequest()
    {
        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue(null));

        $viewHelper = $this->getAccessibleMock(PrefillFieldViewHelper::class, ['getRequest'], [], '', false);
        $viewHelper->expects($this->once())->method('getRequest')->will($this->returnValue($mockRequest));

        $field = new Field();
        $field->setDefaultValue('Default');

        $viewHelper->_set('arguments', ['registrationField' => $field]);
        $actual = $viewHelper->_callRef('render');
        $this->assertSame('Default', $actual);
    }

    /**
     * @return array
     */
    public function viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider()
    {
        return [
            'submitted value returned' => [
                1,
                [
                    'field' => '1',
                    'value' => 'Submitted value'
                ],
                'Submitted value'
            ],
            'empty value returned if not found' => [
                2,
                [
                    'field' => '1',
                    'value' => 'Submitted value'
                ],
                ''
            ]
        ];
    }

    /**
     * @test
     * @dataProvider viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider
     * @param mixed $fieldUid
     * @param mixed $fieldValues
     * @param mixed $expected
     * @return void
     */
    public function viewHelperReturnsExpectedValueIfOriginalRequestExist($fieldUid, $fieldValues, $expected)
    {
        $arguments = [
            'registration' => [
                'fieldValues' => [$fieldValues]
            ]
        ];

        $mockOriginalRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getArguments'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockOriginalRequest->expects($this->once())->method('getArguments')->will($this->returnValue($arguments));

        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->once())->method('getOriginalRequest')
            ->will($this->returnValue($mockOriginalRequest));

        $viewHelper = $this->getAccessibleMock(PrefillFieldViewHelper::class, ['getRequest'], [], '', false);
        $viewHelper->expects($this->once())->method('getRequest')->will($this->returnValue($mockRequest));

        $mockField = $this->getMockBuilder(Field::class)->getMock();
        $mockField->expects($this->once())->method('getUid')->will($this->returnValue($fieldUid));

        $viewHelper->_set('arguments', ['registrationField' => $mockField]);
        $actual = $viewHelper->_callRef('render');
        $this->assertSame($expected, $actual);
    }
}
