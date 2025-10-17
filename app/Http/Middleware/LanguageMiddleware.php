<?php
// app/Http/Middleware/LanguageMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageMiddleware
{
    /**
     * 支持的语言列表
     */
    protected array $supportedLanguages = [
        'en' => 'en',    // 英语
        'zh-CN' => 'zh-CN', // 简体中文
        'ja' => 'ja',    // 日语
    ];

    /**
     * 默认语言
     */
    protected string $defaultLanguage = 'zh-CN';

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 获取当前语言
        $locale = $this->getLocale($request);

        // 设置应用语言
        App::setLocale($locale);

        // 可选：存储到 session 中
        Session::put('locale', $locale);

        // 让请求继续处理
        $response = $next($request);

        // 可选：在响应头中添加当前语言信息
        $response->header('Content-Language', $locale);

        return $response;
    }

    /**
     * 获取当前语言设置
     */
    protected function getLocale(Request $request): string
    {
        // 优先级 1: URL 参数 (例如 ?lang=en)
        if ($request->has('lang')) {
            $lang = $request->get('lang');
            if ($this->isSupported($lang)) {
                return $lang;
            }
        }

        // 优先级 2: Accept-Language 头
        $browserLocale = $this->getBrowserLocale($request);
        if ($browserLocale && $this->isSupported($browserLocale)) {
            return $browserLocale;
        }


        // 优先级 3: Session 中存储的语言
        if (Session::has('locale')) {
            $lang = Session::get('locale');
            if ($this->isSupported($lang)) {
                return $lang;
            }
        }

        // 优先级 4: 配置文件中的默认语言
        return config('app.locale', $this->defaultLanguage);
    }

    /**
     * 从 Accept-Language 头中获取浏览器偏好语言
     */
    protected function getBrowserLocale(Request $request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');

        if (!$acceptLanguage) {
            return null;
        }

        // 解析 Accept-Language 头
        $languages = explode(',', $acceptLanguage);

        foreach ($languages as $language) {
            // 处理权重，如 "en;q=0.8"
            $locale = explode(';', $language)[0];
            $locale = str_replace('_', '-', $locale);
            $locale = trim($locale);

            // 检查完整匹配 (如 zh-CN)
            if ($this->isSupported($locale)) {
                return $locale;
            }

            // 检查主要语言匹配 (如 zh)
            $primaryLanguage = explode('-', $locale)[0];
            if ($this->isSupported($primaryLanguage)) {
                return $primaryLanguage;
            }
        }

        return null;
    }

    /**
     * 检查语言是否被支持
     */
    protected function isSupported(string $locale): bool
    {
        return array_key_exists($locale, $this->supportedLanguages);
    }
}