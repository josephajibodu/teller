<?php

namespace JosephAjibodu\Teller\Helpers;

class HttpClient
{
    protected string $baseUrl;

    protected string $secret;

    protected array $headers;

    public function __construct(string $baseUrl, string $secret, array $headers = [])
    {
        $this->baseUrl = $baseUrl;
        $this->secret = $secret;
        $this->headers = $headers;
    }

    public function get(string $endpoint, array $query = [])
    {
        $url = $this->baseUrl.$endpoint;
        if (! empty($query)) {
            $url .= '?'.http_build_query($query);
        }

        return $this->makeRequest('GET', $url);
    }

    public function post(string $endpoint, array $data = [])
    {
        $url = $this->baseUrl.$endpoint;

        return $this->makeRequest('POST', $url, $data);
    }

    public function put(string $endpoint, array $data = [])
    {
        $url = $this->baseUrl.$endpoint;

        return $this->makeRequest('PUT', $url, $data);
    }

    public function delete(string $endpoint)
    {
        $url = $this->baseUrl.$endpoint;

        return $this->makeRequest('DELETE', $url);
    }

    protected function makeRequest(string $method, string $url, array $data = [])
    {
        $ch = curl_init();

        $headers = [];
        foreach ($this->headers as $key => $value) {
            $headers[] = "$key: $value";
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if (! empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new \Exception("HTTP request failed: {$curlError}");
        }

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON response: '.json_last_error_msg());
        }

        // Check for Paystack API errors
        if (isset($decoded['status']) && $decoded['status'] === false) {
            throw new \Exception('Paystack API Error: '.($decoded['message'] ?? 'Unknown error'));
        }

        return $decoded;
    }
}
