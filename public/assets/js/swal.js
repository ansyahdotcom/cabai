$(function() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000
    });

    const flashData = $('.flash-data').data('flashdata');
    if(flashData == 'login'){
        Toast.fire({
            icon: 'success',
            title:'Anda berhasil login!',
        });
    } else if(flashData == 'logout') {
        Toast.fire({
            icon: 'success',
            title: 'Anda berhasil logout'
        });
    } else if(flashData == 'change_passwd') {
        Toast.fire({
            icon: 'success',
            title: 'Password berhasil diubah'
        });
    } else if(flashData == 'wrong_passwd') {
        Toast.fire({
            icon: 'error',
            title: 'Maaf password salah!'
        });
    } else if(flashData == 'wrong_oldpasswd') {
        Toast.fire({
            icon: 'error',
            title: 'Maaf Password Lama Salah!'
        });
    } else if(flashData == 'belum_terdaftar') {
        Toast.fire({
            icon: 'error',
            title: 'Maaf user belum terdaftar!'
        });
    } else if (flashData == 'save') {
        Toast.fire({
            icon: 'success',
            title: 'Data berhasil disimpan',
        });
    } else if (flashData == 'notsave') {
        Toast.fire({
            icon: 'error',
            title: 'Gagal menyimpan data',
        });
    } else if (flashData == 'edit') {
        Toast.fire({
            icon: 'success',
            title: 'Data berhasil diubah',
        });
    } else if (flashData == 'delete') {
        Toast.fire({
            icon: 'success',
            title: 'Data berhasil dihapus',
        });
    } else if (flashData == 'notdelete') {
        Toast.fire({
            icon: 'error',
            title: 'Gagal menghapus data',
        });
    } else if (flashData == 'ubah_stppm') {
        Toast.fire({
            icon: 'error',
            title: 'Pilihan PPM tidak boleh semuanya nonaktif!',
        });
    }
});