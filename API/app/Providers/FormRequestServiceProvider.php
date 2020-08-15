<?php
namespace App\Providers;

use App\Http\Requests\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;

class FormRequestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->resolving(FormRequest::class, function (Request $request, Application $app) {
            $this->initializeRequest($request, $app['request']);
        });

        $this->app->afterResolving(FormRequest::class, function (FormRequest $resolved) {
            $resolved->validateResolved();
        });

    }

    /**
     * Initialize the form request with data from the given request.
     *
     * @param  \Pearl\RequestValidate\FormRequest  $form
     * @param  \Illuminate\Http\Request  $current
     * @return void
     */
    protected function initializeRequest(FormRequest $form, Request $current)
    {
        $files = $current->files->all();

        $files = is_array($files) ? array_filter($files) : $files;

        $form->initialize(
            $current->query->all(), $current->request->all(), $current->attributes->all(),
            $current->cookies->all(), $files, $current->server->all(), $current->getContent()
        );

        $form->setContainer($this->app);

        $form->setUserResolver($current->getUserResolver());
        $form->setRouteResolver($current->getRouteResolver());
    }
}