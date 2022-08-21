<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class Home extends Component
{
    public $name, $gender, $birthday, $country, $email, $password, $photo, $isAdmin = false;

    public function render()
    {
        return view('livewire.home');
    }


    public function store()
    {
        try {
            //dd($this->name);
            /*   $this->validate([
                'name' => 'required',
                'gender' => 'required',
                'birthday' => 'required',
                'email' => 'required',
                'password' => 'required'
            ]); */

            $user = new User();
            $user->name = $this->name;
            $user->gender = $this->gender;
            $user->birthday = $this->birthday;
            $user->email = $this->email;
            $user->country = $this->country;
            $user->isAdmin = $this->isAdmin;
            $user->password = bcrypt($this->password);
            if ($user->save()) {
                // Set Flash Message
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => "Usuário adicionado com sucesso!"
                ]);
            } else {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => "Erro ao adicionar usuário!"
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' =>  $e->getMessage()
            ]);
        }
    }
}
