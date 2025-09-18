<?php

namespace App\Providers\AnnoRoute\Attribute;

abstract class Mapping
{
    /**
     * 路由地址，使用该属性来指定路由地址，完整的路由地址由 RequestMapping 的 routePrefix 和 route 拼接而成，例如：
     * RequestMapping 的 routePrefix 为 /admin/user，Mapping 的 route 为 /create，那么完整的路由地址为 /admin/user/create
     *
     * Routing address, use this attribute to specify the routing address. The complete routing address is composed
     * of the routePrefix of RequestMapping and the route concatenated, for example: If the routePrefix of RequestMapping
     * is /admin/user and the route of Mapping is /create, then the complete routing address is /admin/user/create
     *
     * @var string
     */
    public string $route = '';

    /**
     * 路由中间件，该属性用于指定路由中间件，多个中间件以数组形式传递，例如：['auth', 'admin']
     *
     * Route middleware, this attribute is used to specify the route middleware, multiple middleware are passed in
     * as an array, for example: ['auth', 'admin']
     *
     * @var string|array
     */
    public string | array $middleware = '';

    /**
     * HTTP 的请求方法
     *
     * HTTP request method
     *
     * @var string
     */
    public string $httpMethod = 'POST';

    /**
     * 权限字符串，该属性用于指定权限【能力】字符串，用于权限【能力】认证，完整的能力字符串由 RequestMapping 的 abilitiesPrefix 和 authorize
     * 拼接而成，例如：RequestMapping 的 abilitiesPrefix 为 admin，Mapping 的 authorize 为 user，那么完整的权限【能力】字符串为 admin.user
     *
     * Permission string, this attribute is used to specify the permission string, used for permission authentication,
     * the complete permission string is composed of the abilitiesPrefix of RequestMapping and the authorize concatenated,
     * for example: If the abilitiesPrefix of RequestMapping is admin and the authorize of Mapping is user, then the complete
     * permission string is admin.user
     *
     * @var string
     */
    public string $authorize = 'create';
}