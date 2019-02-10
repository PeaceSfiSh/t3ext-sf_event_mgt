<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ForeignRecordDemandTest extends BaseTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new ForeignRecordDemand();
    }

    /**
     * Teardown
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function setStoragePageSetsStoragePageForString()
    {
        $this->subject->setStoragePage('1,2,3');
        $this->assertEquals('1,2,3', $this->subject->getStoragePage());
    }

    /**
     * @test
     */
    public function getrestrictForeignRecordsToStoragePageReturnsInitialValue()
    {
        $this->assertFalse($this->subject->getRestrictForeignRecordsToStoragePage());
    }

    /**
     * @test
     */
    public function setRestrictForeignRecordsToStoragePageSetsValueForBoolean()
    {
        $this->subject->setRestrictForeignRecordsToStoragePage(true);
        $this->assertTrue($this->subject->getRestrictForeignRecordsToStoragePage());
    }
}
