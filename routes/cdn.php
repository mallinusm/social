<?php

use Illuminate\Contracts\Routing\Registrar;
use Social\Http\Actions\Users\FetchAvatarAction;

/** @var Registrar $router */
$router->get('avatars/{avatar}', FetchAvatarAction::class);
