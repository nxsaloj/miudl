<?php

namespace App\Http\Middleware;

use Closure;

class checkAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected $repository;
    public function __construct(\miudl\Seccion\SeccionRepositoryInterface $_repository)
    {
        $repository = $_repository;
    }

    public function handle($request, Closure $next)
    {
        $prefix = $request->route()->getPrefix();

        $usuario = \Auth::user();

        if($usuario->id > 1)
        {
            $base = url('/');
            $url = strval($request->url());


            if($base == $url || $url == $base.'/change') return $next($request);


            $urlmod = str_replace($base,'',$url);
            //\Log::debug('ERROR '.$urlmod);
            $permitido = $this->repository->esPermitido($usuario,$urlmod);

            if(!$permitido) abort(404);
        }
        return $next($request);
    }
}
