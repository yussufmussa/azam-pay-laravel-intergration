<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CheckoutController extends Controller
{
    private $httpClient;

    public function __construct()
    {
        // Initialize Guzzle HTTP client
        $this->httpClient = new Client();
    }

    // Function to get Bearer Token
    private function getToken()
    {
       return $this->generateToken();
    }

    // Function to generate the access token
    public function generateToken()
    {
        $generateTokenUrl = 'https://authenticator-sandbox.azampay.co.tz/AppRegistration/GenerateToken';

        // Replace with your actual application details
        $appName = 'binshop';
        $clientId = 'fe3ef867-04d8-4fe1-bcf2-ac58ed68c71e';
        $clientSecret = 'avhtwXXB7iBztLxpWdJ3mPNKfNfaaPxsXb/zO7eQv8MrMyOdd2+mFtjaEgct9Mw5PJsrtYi1YceGyrjeNDPsbjuxLBt+h7KlAFIoFLw2iYj5fUdyBU0RmLYmzVC8XY9f0JjFDvxtaOX+hA6H/1ROXYD6HNekFNNm5Ri6pdcW7iiZidiouLQEUYPQtn3Id2abF6TTkJVbipzAInRKCQUrc5ubf403v3PCamuc+BsoV4tyQfCeYZWUNsRrO/eDJt2KvQmv4j0bwRdhOImuPxkk5+Y1ZhZp8XIvuAzVctbhmwBjJ2Qchex1M6rUz1GncCuLLCelJJ3V/fRIhg0MPg66f/dhlk6mX4g/En12PAZvJBZaAa9VqCv4sNvWTGowjlbMT8ZqZR8ejcIJneXjN7uLMUhtKXG30HZ8OC6csTjfOrF3rb+N4AGeDgqFiNTN4/IXGI9jZbBYGTMFL/SQ3w0lC1BIqMFm1YCkuKiyu+xjTSRzEWZBVUcHK7NLS7cC6DWbTgtF+nYxLlXIqzjhp9FcN51OKlDZDRYw6Ra3uMnXNbVP7IOEGq/aTuSDTc/9e1OGu+GH1rxYgt49B7tzDJ0TQFOdpcrpuQolkx6hU56Wg3UqRkFZFtzcxq0K8GUxKbMYlxuCSPummsqdNy8/6rzZ5Sf/Bcvw64tnGPH4ntZgUKk=';

        // Make a request to generate the access token
        $response = $this->httpClient->post($generateTokenUrl, [
            'json' => [
                'appName' => $appName,
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
            ],
        ]);

        // Assuming the response is JSON
        $accessToken = json_decode($response->getBody(), true)['data']['accessToken'];

        return $accessToken;
    }

    // Function to initiate Mno Checkout
    public function mobileCheckout(Request $request)
    {

        $request->validate([
            'provider' => 'required',
            'amount' => 'required|integer',
            'phone' => 'required',

        ]);

        $checkoutUrl = 'https://sandbox.azampay.co.tz/azampay/mno/checkout';

        // Get the Bearer Token
        $token = $this->getToken();

        // Replace with your actual checkout details
        $accountNumber = $request->phone;
        $amount = $request->amount; // Adjust as needed
        $currency = 'TZS';
        $externalId = '34459349898';
        $provider = $request->provider; // Change based on the selected provider

    
        // Make a request to the Checkout API
        $response = $this->httpClient->post($checkoutUrl, [
            'headers' => [
                'Authorization' => "Bearer $token",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'accountNumber' => $accountNumber,
                'amount' => $amount,
                'currency' => $currency,
                'externalId' => $externalId,
                'provider' => $provider,
            ],
        ]);

        // Handle the response from Azampay
        if ($response->getStatusCode() == 200) {
            // Payment initiation successful
            $responseData = json_decode($response->getBody(), true);
            $message = $responseData['message'];
            return view('success')->with(['message' => $message]);
        } else {
            // Payment initiation failed, handle accordingly
            return "Payment initiation failed: " . $response->getStatusCode();
        }
    }
}
