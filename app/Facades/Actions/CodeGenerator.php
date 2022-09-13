<?php

namespace App\Facades\Actions;

use App\Actions\CodeGenerator as ActionsCodeGenerator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string run()
 */
class CodeGenerator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ActionsCodeGenerator::class;
    }
}