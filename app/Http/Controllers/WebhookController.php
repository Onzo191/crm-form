<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    public function submitForm(Request $request)
    {
        // Lấy dữ liệu từ biểu mẫu
        $data = $request->all();

        // Gửi dữ liệu đến webhook
        $response = Http::post('https://open-sg.larksuite.com/anycross/trigger/callback/MDY2MTg1ODQ0ZmY2MTUxZDRkZDI5OWM5ZmY1OGRjMjYz', $data);

        // Kiểm tra kết quả gửi webhook
        if ($response->successful()) {
            return redirect('/')->with('success', 'Biểu mẫu đã được gửi thành công!');
        } else {
            return redirect('/')->with('error', 'Có lỗi xảy ra khi gửi biểu mẫu!');
        }
    }
}
