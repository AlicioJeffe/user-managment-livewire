<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;

class UtilController extends Controller
{
    //
    public function storageImage($image, $userName)
    {
        if ($image == null) {
            return null;
        }

        $image = Image::make($image)->encode('jpg');
        $name = Str::slug($userName) . '.jpg';
        Storage::disk('public')->put($name, $image);
        return $name;
    }

    public function validateMessages()
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'Email é obrigatório.',
            'country.required' => 'O país é obrigatório.',
            'gender.required' => 'O gênero é obrigatório.',
            'birthday.required' => 'A data de nascimento é obrigatória.',
            'password.required' => 'A senha é obrigatória.',
            'email.email' => 'Insira um e-mail válido.',
        ];
    }

    public function validatedRules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'gender' => 'required',
            'birthday' => 'required',
            'country' => 'required',
            'password' => 'required',
        ];
    }
}
