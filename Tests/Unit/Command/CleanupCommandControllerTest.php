<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Command;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Command\CleanupCommandController;
use DERHANSEN\SfEventMgt\Service\RegistrationService;
use DERHANSEN\SfEventMgt\Service\UtilityService;
use TYPO3\TestingFramework\Core\BaseTestCase;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

/**
 * Test case for class DERHANSEN\SfEventMgt\Command\CleanupCommandController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CleanupCommandControllerTest extends BaseTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Command\CleanupCommandController
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new CleanupCommandController();
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
     * @return void
     */
    public function cleanupCommandHandlesExpiredRegistrationsAndCleansCacheTest()
    {
        // Inject configuration and configurationManager
        $configuration = [
            'plugin.' => [
                'tx_sfeventmgt.' => [
                    'settings.' => [
                        'clearCacheUids' => '1,2,3',
                        'registration.' => [
                            'deleteExpiredRegistrations' => 0
                        ]
                    ]
                ]
            ]
        ];

        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->setMethods(['getConfiguration'])
            ->getMock();
        $configurationManager->expects($this->once())->method('getConfiguration')->will(
            $this->returnValue($configuration)
        );
        $this->inject($this->subject, 'configurationManager', $configurationManager);

        $registrationService = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['handleExpiredRegistrations'])
            ->getMock();
        $registrationService->expects($this->once())->method('handleExpiredRegistrations')->with(0)->will(
            $this->returnValue($configuration)
        );
        $this->inject($this->subject, 'registrationService', $registrationService);

        $utilityService = $this->getMockBuilder(UtilityService::class)
            ->setMethods(['clearCacheForConfiguredUids'])
            ->getMock();
        $utilityService->expects($this->once())->method('clearCacheForConfiguredUids')->
        with($configuration['plugin.']['tx_sfeventmgt.']['settings.']);
        $this->inject($this->subject, 'utilityService', $utilityService);

        $this->subject->cleanupCommand();
    }
}
