<?php

namespace App\Http\Livewire;

use App\Http\Controllers\UtilController;
use App\Models\User;
use Livewire\Component;

class Home extends Component
{


    public $name, $gender, $birthday, $country, $email, $password, $photo, $isAdmin = false, $isEdit = false;

    protected $listeners = ['profile-picture-uploaded' => 'profilePictureUpload', 'editUser', 'update-user-data' => 'updateUserData', 'delete-user' => 'deleteUser', 'delete-user-confirmed' => 'deleteUserConfirmed'];

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'email.required' => 'Email é obrigatório.',
        'country.required' => 'O país é obrigatório.',
        'gender.required' => 'O gênero é obrigatório.',
        'birthday.required' => 'A data de nascimento é obrigatória.',
        'password.required' => 'A senha é obrigatória.',
        'email.email' => 'Insira um e-mail válido.',
    ];

    protected $rules =  [
        'name' => 'required',
        'email' => 'required|email',
        'gender' => 'required',
        'birthday' => 'required',
        'country' => 'required',
        'password' => 'required',
    ];


    /*  public function mount()
    {
        $utilController = new UtilController();
        $this->messages = $utilController->validateMessages();
        $this->rules = $utilController->validatedRules();

        //dd( $this->messages);
    } */


    public function render()
    {
        return view('livewire.home', [
            'users' => User::latest()->get(),
            'user_qtd' => count(User::where('isAdmin', false)->get()),
            'admin_qtd' => count(User::where('isAdmin', true)->get()),
        ]);
    }

    public function updateUserData($user)
    {
        $this->userId = $user['id'];
        $this->name = $user['name'];
        $this->email = $user['email'];
        $this->gender = $user['gender'];
        $this->birthday = $user['birthday'];
        $this->country = $user['country'];
        $this->isAdmin = $user['isAdmin'];
        $this->photo = $user['photo'];
    }

    public function deleteUserConfirmed()
    {
        $user = User::find($this->userId);
        if ($user->delete()) {
            $this->dispatchBrowserEvent('user-deleted');
        }
    }

    public function deleteUser($user)
    {
        $this->userId = $user['id'];
        $this->dispatchBrowserEvent('ask-delete-user');
    }

    public function profilePictureUpload($photo)
    {
        $this->photo = $photo;
    }

    public function editUser($user)
    {
        $this->isEdit = true;
        $this->emit('setUserSelected', $user);
    }



    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    public function store()
    {
        try {
            $utilController = new UtilController();

            $this->validate();

            $user = new User();
            $user->name = $this->name;
            $user->gender = $this->gender;
            $user->birthday = $this->birthday;
            $user->email = $this->email;
            $user->photo = $utilController->storageImage($this->photo, $this->name);
            $user->country = $this->country;
            $user->isAdmin = $this->isAdmin;
            $user->password = bcrypt($this->password);
            if ($user->save()) {
                $this->photo = null;

                $this->dispatchBrowserEvent('alert', [
                    'type' => 'success',
                    'message' => "Usuário adicionado com sucesso!"
                ]);

                $this->dispatchBrowserEvent('erase-form', [
                    'formID' => 'form-user-create',
                ]);
            } else {
                $this->dispatchBrowserEvent('alert', [
                    'type' => 'error',
                    'message' => "Erro ao adicionar usuário!"
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
