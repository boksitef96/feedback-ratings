<?php

namespace Functional\Controller\Feedback;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateFeedbackTest extends WebTestCase
{
    public function testCreateFeedback(): void
    {
        $client = static::createClient();

        $payload = [
            'overallRating' => 4,
            'text'          => 'This project was a great experience!',
        ];

        $client->request('POST', '/api/feedback/client/1/vico/1/project/3', [], [], [], json_encode($payload));

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);

        $this->assertEquals(4, $data['overall_rating']);
        $this->assertEquals('This project was a great experience!', $data['text']);
    }

    public function testCreateFeedbackInvalidPayload(): void
    {
        $client = static::createClient();

        $payload = [
            'overallRating' => 'test',
            'text'          => 'test',
        ];

        $client->request('POST', '/api/feedback/client/3/vico/1/project/4', [], [], [], json_encode($payload));

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);

        $this->assertEquals('INVALID_OR_MISSING_PARAMETER', $data['error_code']);
        $this->assertArrayHasKey('overallRating', $data['payload']);
        $this->assertArrayHasKey('text', $data['payload']);
    }

    public function testCreateFeedbackDuplicateFeedback(): void
    {
        $client = static::createClient();

        $payload = [
            'overallRating' => 4,
            'text'          => 'This project was a great experience!',
        ];

        $client->request('POST', '/api/feedback/client/1/vico/2/project/2', [], [], [], json_encode($payload));

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);

        $this->assertNotEmpty($data['detail']);
        $this->assertEquals('Project already have feedback.', $data['detail']);
    }
}