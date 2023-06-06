<!DOCTYPE html>
<html>

<head>
    <title>Verifi Account</title>
</head>

<body>
    <!-- set variable token -->
    <?php
    $dataEmail = $email;
    $dataToken = $token;
    ?>
    <h1>Hallo <?= $nama_lengkap; ?></h1>
    <p>Terimakasih sudah registrasi pada sistemLogin kami.</p>
    <p>Untuk aktivasi account anda silahkan klik tombol aktivasi dibawah ini.</p>
    <form action="<?= base_url(); ?>/auth/activation" method="post">
        <?= csrf_field(); ?>
        <input type="hidden" value="<?= $dataEmail; ?>" name="email">
        <input type="hidden" value="<?= $dataToken; ?>" name="token">
        <button style="background-color: #068DA9; color:white; height:auto; border-radius:5px" type="submit">Activation</button>
    </form>


</body>

</html>