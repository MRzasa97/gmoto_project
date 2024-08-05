<?php

namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiService
{
    const URL = "https://mojegs1.pl/api/v2/products/";
    public function __construct(
        public readonly HttpClientInterface $httpClient,
        private string $username,
        private string $password
    )
    {}

    public function fetchData(string $gtin): array
    {
        try {
            $response = $this->httpClient->request("GET", self::URL . $gtin, [
                'auth_basic' => [$this->username, $this->password]
            ]);
            return $response->toArray();
        } catch (ClientExceptionInterface $e) {
            $responseContent = $e->getResponse()->getContent(false);
            $responseData = json_decode($responseContent, true);
            if (isset($responseData['errors']['errors'][0]['detail'])) {
                throw new \RuntimeException('Client error occurred: ' . $responseData['errors']['errors'][0]['detail'], $e->getCode(), $e);
            }
            throw new \RuntimeException('Client error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        } catch (HttpExceptionInterface $e) {
            throw new \RuntimeException('HTTP error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        } catch (RedirectionExceptionInterface $e) {
            throw new \RuntimeException('Redirection error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        } catch (ServerExceptionInterface $e) {
            throw new \RuntimeException('Server error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Transport error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new \RuntimeException('An error occurred: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}