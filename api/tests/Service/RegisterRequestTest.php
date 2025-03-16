<?php

namespace App\Tests\Service;

use App\Entity\RequestHistory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\RegisterRequest;

class RegisterRequestTest extends KernelTestCase
{
    private mixed $entityManager;
    private array $requestData;
    private int $userId;
    private string $ipAddress;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->requestData = [
            'date' => '2021-01-01',
            'name' => 'TEST',
            'symbol' => 'TEST',
            'open' => '100',
            'high' => '100',
            'low' => '100',
            'close' => '100',
        ];
        $this->userId = 1;
        $this->ipAddress = '127.0.0.1';

        $this->entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testRegisterRequestService(): void
    {
        $registerRequestService = static::getContainer()->get(RegisterRequest::class);

        $this->assertNotNull($registerRequestService, 'The RegisterRequest service should be available in the container.');
        assert($registerRequestService instanceof RegisterRequest, 'The RegisterRequest service should be an instance of RegisterRequest.');
        $registerRequestService->register($this->requestData, $this->userId, $this->ipAddress);

        $insertedValue = $this->entityManager->getRepository(RequestHistory::class)->findLastInsertedRecord();
        self::assertNotNull($insertedValue, 'The last inserted record should not be null.');
        self::assertEquals($this->requestData, $insertedValue->getRequestData(), 'The request data should be the same as the one passed to the register method.');
        self::assertEquals($this->userId, $insertedValue->getUserId(), 'The user id should be the same as the one passed to the register method.');
        self::assertEquals($this->ipAddress, $insertedValue->getIpAddress(), 'The ip address should be the same as the one passed to the register method.');
    }

    protected function tearDown(): void
    {
        if ($this->entityManager) {
            $requestHistoryRepository = $this->entityManager->getRepository(RequestHistory::class);
            $record = $requestHistoryRepository->findLastInsertedRecord();
            if ($record)  {
                $this->entityManager->remove($record);
                $this->entityManager->flush();
            }
            $this->entityManager->close();
        }
        $this->entityManager = null;

        parent::tearDown();
    }
}
