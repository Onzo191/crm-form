<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class SubmitFormController extends Controller
{
    private function getLarkSuiteConfig()
    {
        return [
            'app_id' => Config::get('larksuite.app_id'),
            'app_secret' => Config::get('larksuite.app_secret'),
            'base_app_id' => Config::get('larksuite.base_app_id'),
            'leads_table_id' => Config::get('larksuite.leads_table_id'),
            'products_table_id' => Config::get('larksuite.products_table_id'),
            'products_view_id' => Config::get('larksuite.products_view_id'),
        ];
    }

    private function getAccessToken()
    {
        $authClient = new Client([
            'verify' => false,
        ]);

        $authHeaders = [
            'Content-Type' => 'application/json',
        ];

        $authBody = [
            'app_id' => $this->getLarkSuiteConfig()['app_id'],
            'app_secret' => $this->getLarkSuiteConfig()['app_secret'],
        ];

        $authResponse = $authClient->post('https://open.larksuite.com/open-apis/auth/v3/tenant_access_token/internal', [
            'headers' => $authHeaders,
            'body' => json_encode($authBody),
        ]);

        $authData = json_decode($authResponse->getBody(), true);

        if (isset($authData['code']) && $authData['code'] === 0 && isset($authData['tenant_access_token'])) {
            return $authData['tenant_access_token'];
        } else {
            return null;
        }
    }

    public function view()
    {
        $products = $this->getAllProducts();
        return view('larksuite-form', compact('products'));
    }

    public function postWithWebhook(Request $request)
    {
        // Not work now (disabled)
        $data = $request->all();

        $response = Http::post('https://open-sg.larksuite.com/anycross/trigger/callback/MGM0YzMwZDQ5OTgxYWQwMzBjZjBhZGE3OWY0OTlmYzYz', $data);

        if ($response->successful()) {
            return redirect('/')->with('success', 'Biểu mẫu đã được gửi thành công!');
        } else {
            return redirect('/')->with('error', 'Có lỗi xảy ra khi gửi biểu mẫu!');
        }
    }

    public function postWithApi(Request $request)
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return 'Failed to obtain access token';
        }

        $mainClient = new Client([
            'verify' => false,
        ]);

        $mainHeaders = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        $mainBody = [
            'fields' => [
                'Khách hàng' => $request->input('name'),
                'Mô tả' => $request->input('description'),
                'Email' => $request->input('email'),
                'Số điện thoại' => $request->input('phone'),
                'Mã quan tâm' => $request->input('product'),
            ],
        ];

        $mainRequest = $mainClient->post("https://open.larksuite.com/open-apis/bitable/v1/apps/{$this->getLarkSuiteConfig()['base_app_id']}/tables/{$this->getLarkSuiteConfig()['leads_table_id']}/records?user_id_type=user_id", [
            'headers' => $mainHeaders,
            'body' => json_encode($mainBody),
        ]);

        $responseData = json_decode($mainRequest->getBody(), true);

        if ($responseData['code'] === 0) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function getAllProducts()
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return 'Failed to obtain access token';
        }

        $mainClient = new Client([
            'verify' => false,
        ]);

        $mainHeaders = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        $mainResponse = $mainClient->get("https://open.larksuite.com/open-apis/bitable/v1/apps/{$this->getLarkSuiteConfig()['base_app_id']}/tables/{$this->getLarkSuiteConfig()['products_table_id']}/records?page_size=100&view_id={$this->getLarkSuiteConfig()['products_view_id']}", [
            'headers' => $mainHeaders,
        ]);

        $mainData = json_decode($mainResponse->getBody(), true);

        $products = [];

        if ($mainData['code'] === 0) {
            foreach ($mainData['data']['items'] as $item) {
                $productId = $item['fields']['Mã sản phẩm'];
                $productName = $item['fields']['Tên sản phẩm'];
                $products[$productId] = $productName;
            }
        }

        return $products;
    }
}
