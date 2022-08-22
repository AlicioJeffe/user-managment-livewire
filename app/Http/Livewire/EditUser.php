<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Controllers\UtilController;
use App\Models\User;

class EditUser extends Component
{
    public $user, $name, $gender, $birthday, $country, $email, $password, $newPhoto, $currentPhoto, $isAdmin = false, $isEdit = false, $userId;

    protected $listeners = ['edit-profile-picture-uploaded' => 'profilePictureUpload', 'setUserSelected' => 'getUserSelected'];

    public function render()
    {
        return view('livewire.edit-user');
    }


        protected $messages = [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'Email é obrigatório.',
            'country.required' => 'O país é obrigatório.',
            'gender.required' => 'O gênero é obrigatório.',
            'birthday.required' => 'A data de nascimento é obrigatória.',
            'password.required' => 'A senha é obrigatória.',
            'email.email' => 'Insira um e-mail válido.',
        ];

        protected $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'gender' => 'required',
            'birthday' => 'required',
            'country' => 'required',
            'password' => 'required',
        ];


    public function getUserSelected($user)
    {
        $this->userId = $user['id'];
        $this->name = $user['name'];
        $this->email = $user['email'];
        $this->gender = $user['gender'];
        $this->birthday = $user['birthday'];
        $this->country = $user['country'];
        $this->isAdmin = $user['isAdmin'];
        $this->currentPhoto = $user['photo'];
    }


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function profilePictureUpload($photo)
    {
        $this->newPhoto = $photo;
    }

    public function update()
    {
        try {
            $utilController = new UtilController();

            $this->validate();

            $user = User::find($this->userId);
            //dd($user);
            $user->name = $this->name;
            $user->gender = $this->gender;
            $user->birthday = $this->birthday;
            $user->email = $this->email;
            $user->photo = $utilController->storageImage($this->newPhoto, $this->name) ?? $user->photo;
            $user->country = $this->country;
            $user->isAdmin = $this->isAdmin;
            $user->password = bcrypt($this->password);
            if ($user->save()) {

                $this->newPhoto = null;
                $this->currentPhoto = null;

                $this->emit('update-user-data', $user);

                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => "Usuário actualizado com sucesso!"
                ]);

                $this->dispatchBrowserEvent('erase-form', [
                    'formID' => 'form-user-update',
                ]);
            } else {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => "Erro ao actualizar usuário!"
                ]);
            }
        } catch (\Exception $e) {

            $this->validate();
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' =>  $e->getMessage()
            ]);
        }
    }
}
