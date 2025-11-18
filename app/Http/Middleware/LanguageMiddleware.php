<?php

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
        'zh' => 'zh', // 简体中文
        'jp' => 'ja',    // 日语
    ];

    /**
     * 默认语言
     */
    protected string $defaultLanguage = 'zh';

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 获取当前语言
        $locale = $this->getLocale($request);
        // 设置应用语言
        App::setLocale($locale);
        // 让请求继续处理
        return $next($request);
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

        // 优先级 2: User-Language 头
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
     * 从 User-Language 头中获取浏览器偏好语言
     */
    protected function getBrowserLocale(Request $request): ?string
    {
        $acceptLanguage = $request->header('User-Language');

        if (!$acceptLanguage) {
            return null;
        }

        return $acceptLanguage;
    }

    /**
     * 检查语言是否被支持
     */
    protected function isSupported(string $locale): bool
    {
        return array_key_exists($locale, $this->supportedLanguages);
    }
}