<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiTest extends ApiTestCase
{
    public function apiToken(): string
    {
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'bogomolov.evgen1067@mail.ru']);
        return $user->getApiToken();
    }

    public function testUsers(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/users');
        $this->assertResponseStatusCodeSame(401);
        $response = $client->withOptions([
            'headers' => ['x-auth-token' => $this->apiToken(), 'content-type' => 'application/json; charset=utf-8'],
        ])->request('GET', '/api/users');
        $this->assertResponseStatusCodeSame(200);
        $resultArray = $response->toArray();
        $this->assertJson($response->getContent());
        $this->assertIsArray($resultArray);
    }

    public function testQuestions(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/questions');
        $this->assertResponseStatusCodeSame(401);
        $response = $client->withOptions([
            'headers' => ['x-auth-token' => $this->apiToken(), 'content-type' => 'application/json; charset=utf-8'],
        ])->request('GET', '/api/questions');
        $this->assertResponseStatusCodeSame(200);
        $resultArray = $response->toArray();
        $this->assertJson($response->getContent());
        $this->assertIsArray($resultArray);
    }

    public function testAnswers(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/answers');
        $this->assertResponseStatusCodeSame(401);
        $response = $client->withOptions([
            'headers' => ['x-auth-token' => $this->apiToken(), 'content-type' => 'application/json; charset=utf-8'],
        ])->request('GET', '/api/answers');
        $this->assertResponseStatusCodeSame(200);
        $resultArray = $response->toArray();
        $this->assertJson($response->getContent());
        $this->assertIsArray($resultArray);
    }

    public function testCategories(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/question_categories');
        $this->assertResponseStatusCodeSame(401);
        $response = $client->withOptions([
            'headers' => ['x-auth-token' => $this->apiToken(), 'content-type' => 'application/json; charset=utf-8'],
        ])->request('GET', '/api/question_categories');
        $this->assertResponseStatusCodeSame(200);
        $resultArray = $response->toArray();
        $this->assertJson($response->getContent());
        $this->assertIsArray($resultArray);
    }
}