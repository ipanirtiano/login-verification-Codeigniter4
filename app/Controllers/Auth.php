<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuthModel;
use App\Models\TokenModel;
use App\Models\UsersModel;
use Google\Service\ServiceUsage\GoogleApiService;
use Google_Client;

class Auth extends BaseController
{
    protected $googleClient;
    protected $userModel;
    protected $authModel;
    protected $tokenModel;

    public function __construct()
    {
        session();
        // inisialisasi user model
        $this->userModel = new UsersModel();
        // inisialisasi auth model
        $this->authModel = new AuthModel();
        // inisialisasi token model
        $this->tokenModel = new TokenModel();

        // koneksi ke librari google client API
        $this->googleClient = new Google_Client();
        // set client ID
        $this->googleClient->setClientId('1038317697080-qbur4ludkj8a2i6jcdvc56ec4tp8vji2.apps.googleusercontent.com');
        // set Client secrtet ID
        $this->googleClient->setClientSecret('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');
        // set redirect URL
        $this->googleClient->setRedirectUri('http://localhost:8080/process');
        // set Scope untuk tarik data API google
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    public function index()
    {
        // cek session, jika session sudah ada maka redirect to halaman dashboard
        if (session()->has('nama_lengkap')) {
            return redirect()->to(base_url('/view'));
        }
        $data = [
            'tittle' => "Sign In",
            'validation' => \Config\Services::validation(),
            'link' => $this->googleClient->createAuthUrl()
        ];
        return view('Auth/index', $data);
    }

    // jika user login menggunakan akun google
    public function process()
    {
        // jika user login menggunakan google
        // set token API google sebelum tarik data
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar('code'));

        // cek token apakah ada error?
        if (!isset($token['error'])) {
            // get access Token
            $this->googleClient->setAccessToken($token['access_token']);
            // get API data servis google
            $googleServis = new \Google_Service_Oauth2($this->googleClient);
            // data servis API google
            $data = $googleServis->userinfo->get();

            // simpan data servis API variable row
            $row  = [
                'id' => $data['id'],
                'nama_lengkap' => $data['name'],
                'email' => $data['email'],
            ];

            // save data servis API kedalam table users
            $this->userModel->save($row);

            // set data session
            $data_session = [
                'login' => true,
                'nama_lengkap' => $row['nama_lengkap']
            ];
            session()->set($data_session);
            // return redirect to halaman dashboard
            return redirect()->to(base_url('/view'));
        }
    }

    // jika user login bukan dari google
    public function process_login()
    {
        // set validation
        if (!$this->validate([
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Username tidak boleh kosong!',
                    'valid_email' => 'Email tidak valid'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password tidak boleh kosong!'
                ]
            ]
        ])) {
            // jika form tidak tervalidasi
            // return halaman kembali ke halaman form login
            return redirect()->to(base_url('/'))->withInput();
        } else {
            // jika form tervalidasi
            // get data form login
            $data_userForm = $this->request->getVar();
            // get data auth dari table auth
            $data_auth = $this->authModel->where('email', $data_userForm['email'])->first();

            // cek apakah email terdaftar pada database
            if (isset($data_auth)) {
                // cek password
                if (password_verify($data_userForm['password'], $data_auth['password'])) {
                    // cek aktivasi akun
                    if ($data_auth['is_active'] != 0) {
                        // set data session
                        $data_session = [
                            'login' => true,
                            'nama_lengkap' => $data_auth['nama_lengkap'],
                            'profil' => ''
                        ];
                        session()->set($data_session);
                        // return redirect to halaman dashboard
                        return redirect()->to(base_url('/view'));
                    } else {
                        // set session flash data
                        session()->setFlashdata('pesan', 'Silahkan aktivasi account melalui email anda');
                        // jika form tidak tervalidasi
                        return redirect()->to(base_url('/'))->withInput();
                    }
                } else {
                    // set session flash data
                    session()->setFlashdata('pesan', 'Email atau Password anda tidak terdaftar!');
                    // jika password tidak terdaftar
                    return redirect()->to(base_url('/'))->withInput();
                }
            } else {
                // set session flash data
                session()->setFlashdata('pesan', 'Email atau Password anda tidak terdaftar!');
                // jika username tidak terdaftar
                return redirect()->to(base_url('/'))->withInput();
            }
        }
    }


    public function logout()
    {
        // hancurkan session
        session_destroy();
        // return redirect to halaman login
        return redirect()->to(base_url('/'));
    }


    public function register()
    {
        $data = [
            'tittle' => 'Sign Up',
            'validation' => \Config\Services::validation(),
            'link' => $this->googleClient->createAuthUrl()
        ];
        return view('auth/register', $data);
    }

    public function process_register()
    {
        // set validation 
        if (!$this->validate([
            'nama_lengkap' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Lengkap tidak boleh kosong!'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email tidak boleh kosong!',
                    'valid_email' => 'Email tidak valid'
                ]
            ],
            'password1' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password tidak boleh kosong!'
                ]
            ],
            'password2' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password tidak boleh kosong!'
                ]
            ]
        ])) {
            // jika form tidak tervalidasi
            return redirect()->to(base_url('/register'))->withInput();
        } else {
            // get data dari form register
            $data_register = $this->request->getVar();

            // cek email apakah email sudah terdaftar?
            $data_auth = $this->authModel->where('email', $data_register['email'])->first();

            // jika email sudah terdaftar
            if (isset($data_auth)) {
                // cek apakah email sudah teraktivasi?
                if ($data_auth['is_active'] == 0) {
                    // set session flash data
                    session()->setFlashdata('pesan', 'Email sudah terdaftar! Silahkan aktivasi melalui email anda');
                    return redirect()->to(base_url('/register'))->withInput();
                } else {
                    // set session flash data
                    session()->setFlashdata('pesan', 'Email sudah terdaftar!');
                    return redirect()->to(base_url('/register'))->withInput();
                }
            }


            // cek password1 dan password2
            if ($data_register['password1'] == $data_register['password2']) {
                // hash password
                $password_hash = password_hash($data_register['password2'], PASSWORD_DEFAULT);

                // inisialisasi random angka
                $random_angka1 = random_int(111, 999);
                $random_angka2 = random_int(111, 999);
                $random_angka3 = random_int(111, 999);
                $random_angka4 = random_int(111, 999);
                $random_angka5 = random_int(111, 999);
                // simpan random angka ke dalam variable
                $randomAngka = $random_angka1 . $random_angka2 . $random_angka3 . $random_angka4 . $random_angka5;

                // random generate string untuk id
                // panjang random string
                $lenght = 10;
                // karakter yang akan di random
                $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charLenght = strlen($char);
                $random_string = '';
                for ($i = 0; $i < $lenght; $i++) {
                    $random_string .= $char[random_int(0, $charLenght - 1)];
                }
                $id = $random_string . $randomAngka;

                // input data register ke dalam table auth
                $this->authModel->save([
                    'kode_auth' => $id,
                    'nama_lengkap' => $data_register['nama_lengkap'],
                    'email' => $data_register['email'],
                    'password' => $password_hash,
                    'is_active' => 0
                ]);

                // input data register ke dalam table user
                $this->userModel->save([
                    'id' => $id,
                    'nama_lengkap' => $data_register['nama_lengkap'],
                    'email' => $data_register['email'],
                ]);

                // set data token
                $token = base64_encode(random_bytes(32));

                // insert data token ke table token
                $this->tokenModel->save([
                    'email' => $data_register['email'],
                    'token' => $token
                ]);

                // send email activation verification
                // set variable
                $email_tujuan = $data_register['email'];
                $subject = 'Verification Account Login';

                // set data email
                $data_email = [
                    'nama_lengkap' => $data_register['nama_lengkap'],
                    'email' => $data_register['email'],
                    'token' => $token
                ];
                // set view message email
                $message = view('email/verify', $data_email);

                // inisialisasi email servis
                $email = service('email');
                $email->setTo($email_tujuan);
                $email->setFrom('admin@gmail.com', 'Admin sistem login');
                $email->setSubject($subject);
                $email->setMessage($message);

                // send email 
                if ($email->send()) {
                    // set session flash data
                    session()->setFlashdata('pesan-success', 'Registrasi Berhasil, Silahkan aktivasi account melalui email anda.');
                    return redirect()->to(base_url('/register'));
                } else {
                    $data_error = $email->printDebugger(['header']);
                    print_r($data_error);
                    return redirect()->to(base_url('/register'));
                }
            } else {
                // set session flash data
                session()->setFlashdata('pesan', 'Password tidak match!');
                // jika form tidak tervalidasi
                return redirect()->to(base_url('/register'))->withInput();
            }
        }
    }


    public function activation()
    {
        // set data aktivasi
        $email = $this->request->getVar('email');
        $token = $this->request->getVar('token');
        // cek apakah email dan token terdaftar pada database?
        $data_token = $this->tokenModel->where('token', $token)->first();

        if (isset($data_token)) {
            // update data auth
            $data_auth = $this->authModel->where('email', $email)->first();
            // id data auth
            $id_auth = $data_auth['id'];

            // update table auth untuk aktivasi account
            $this->authModel->save([
                'id' => $id_auth,
                'is_active' => 1
            ]);

            // delete token jika sudah dipakai
            $this->tokenModel->where('token', $token)->delete();

            // set session flash data
            session()->setFlashdata('pesan-success', 'Aktivasi account Berhasil!, Silahkan Login.');
            return redirect()->to(base_url('/'));
        } else {
            // set session flash data
            session()->setFlashdata('pesan', 'Token expired!.');
            return redirect()->to(base_url('/'));
        }
    }
}
