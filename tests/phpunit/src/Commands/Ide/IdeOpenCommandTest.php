<?php

namespace Acquia\Cli\Tests\Commands\Ide;

use Acquia\Cli\Command\Ide\IdeOpenCommand;
use Acquia\Cli\Tests\CommandTestBase;
use Symfony\Component\Console\Command\Command;

/**
 * Class IdeOpenCommandTest.
 *
 * @property IdeOpenCommand $command
 * @package Acquia\Cli\Tests\Ide
 */
class IdeOpenCommandTest extends CommandTestBase {

  /**
   * {@inheritdoc}
   */
  protected function createCommand(): Command {
    return $this->injectCommand(IdeOpenCommand::class);
  }

  public function setUp($output = NULL): void {
    parent::setUp();
    putenv('DISPLAY=1');
  }

  public function tearDown(): void {
    parent::tearDown();
    putenv('DISPLAY');
  }

  /**
   * Tests the 'ide:open' command.
   *
   * @throws \Psr\Cache\InvalidArgumentException
   */
  public function testIdeOpenCommand(): void {

    $this->mockApplicationsRequest();
    $this->mockApplicationRequest();
    $this->mockIdeListRequest();

    $inputs = [
      // Would you like Acquia CLI to search for a Cloud application that matches your local git config?
      'n',
      // Please select a Cloud Platform application:
      0,
      // Would you like to link the project at ... ?
      'y',
      // Please select the IDE you'd like to open:
      0,
    ];
    $this->executeCommand([], $inputs);

    // Assert.
    $this->prophet->checkPredictions();
    $output = $this->getDisplay();
    $this->assertStringContainsString('Please select a Cloud Platform application:', $output);
    $this->assertStringContainsString('[0] Sample application 1', $output);
    $this->assertStringContainsString('Please select the IDE you\'d like to open:', $output);
    $this->assertStringContainsString('[0] IDE Label 1', $output);
    $this->assertStringContainsString('Your IDE URL: https://9a83c081-ef78-4dbd-8852-11cc3eb248f7.ides.acquia.com', $output);
    $this->assertStringContainsString('Your Drupal Site URL: https://9a83c081-ef78-4dbd-8852-11cc3eb248f7.web.ahdev.cloud', $output);
    $this->assertStringContainsString('Opening your IDE in browser...', $output);
  }

}
