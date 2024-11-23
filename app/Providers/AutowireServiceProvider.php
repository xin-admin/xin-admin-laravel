<?php
namespace App\Providers;

use App\Attribute\Autowired;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

/**
 * 类的自动注入注解
 */
class AutowireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->resolving(function ($object) {
            $this->injectDependencies($object);
        });
    }

    protected function injectDependencies($object): void
    {
        try {
            $reflector = new ReflectionClass($object);
            foreach ($reflector->getProperties() as $property) {
                $attributes = $property->getAttributes(Autowired::class);

                foreach ($attributes as $attribute) {
                    $propertyType = $property->getType();
                    if ($propertyType !== null) {
                        $service = app($propertyType->getName());
                        $property->setValue($object, $service);
                    }
                }
            }
        }catch (\ReflectionException $e) {
            return;
        }
    }
}