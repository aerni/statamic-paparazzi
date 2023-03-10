<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Facades\Model as ModelApi;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Statamic\Facades\URL;

class Paparazzi
{
    public function models(): Collection
    {
        return func_get_args()
            ? ModelApi::select(...func_get_args())
            : ModelApi::all();
    }

    public function model(string $id): ?Model
    {
        return ModelApi::find($id);
    }

    public function route(): string
    {
        $baseUrl = config('paparazzi.preview_url', '/paparazzi');
        $parameters = '/{model}/{template}/{contentId}';

        return URL::assemble($baseUrl, $parameters);
    }

    public function __call(string $method, array $arguments)
    {
        return $this->model(Str::snake($method))
            ?->content($arguments[0] ?? []);
    }
}
