<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Usuários - Hcode Treinamentos</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
    @livewireStyles
    @livewireScripts
</head>

<body class="hold-transition skin-blue sidebar-mini">

    {{ $slot }}

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        const Toast = Swal.mixin({
        toast: true,
       // iconColor: '#fff',
        position: 'top',
        showConfirmButton: false,
        showCloseButton: true,
        timer: 5000,
        timerProgressBar:true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    window.addEventListener('alert',({detail:{type,message}})=>{
        Toast.fire({
            icon:type,
            title:message
        })
    })



    window.addEventListener('ask-delete-user',e=>{
        Swal.fire({
    title: 'Deseja eliminar?',
    text: "Esta ação é irreversível.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, eliminar!'
}).then((result) => {
  if (result.isConfirmed) {
    Livewire.emit('delete-user-confirmed')
   window.addEventListener('user-deleted',e=>{
    Swal.fire(
      'Eliminado!',
      'Usuário foi eliminado.',
      'success',
    );
   })
  }
})


    })


    window.addEventListener('erase-form',({detail : {formID}})=>{


        console.log(document.getElementById(formID))
      document.getElementById(formID).reset()
    })

    Livewire.on('choose-profile-picture',()=>{
            let inputField = document.getElementById('exampleInputFile');
            let file = inputField.files[0];
            let reader = new FileReader();
            reader.onloadend = ()=>{
                Livewire.emit('profile-picture-uploaded',reader.result);
            }
            reader.readAsDataURL(file)
    })

    Livewire.on('edit-choose-profile-picture',()=>{
            let inputField = document.getElementById('editProfilePhoto');
            let file = inputField.files[0];
            let reader = new FileReader();
            reader.onloadend = ()=>{
                Livewire.emit('edit-profile-picture-uploaded',reader.result);
            }
            reader.readAsDataURL(file)
    })

    </script>
</body>

<style>
    .error {
        color: red
    }
</style>


</html>
