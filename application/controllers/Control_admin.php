<?php
  defined('BASEPATH') or die('Tidak dapat akses langsung!');

  class Control_admin extends CI_Controller
  {
    private $username;
    private $email;
    private $fullname;
    private $privilege;
    private $nimJurusan;

    public function __construct()
    {
      parent::__construct();
      $this->load->library('encryption');
      $this->load->library('admin');
      $this->load->model('model_admin');
      $this->template->set_folder('admin');
    }

    public function index()
    {
      $this->is_loggedIn();

      $data['pendaftar']  = $this->model_admin->get_pendaftar($this->nimJurusan);

      $this->template->load('admin/admin_index', $data);
    }

    public function login()
    {
      $this->load->view('admin/admin_login');
    }

    protected function is_loggedIn()
    {
      $login  = get_cookie('admin');
      !empty($login) ?: redirect('admin/login');
      $login  = json_decode($this->encryption->decrypt($login));
      $this->username   = $login->username;
      $this->email      = $login->email;
      $this->fullname   = $login->fullname;
      $this->privilege  = $login->privilege;
      $this->nimJurusan = $login->nimJurusan;

      !empty($this->privilege) ?: redirect('admin/login');
    }

    public function pendaftaran_delete($nim='')
    {
      $this->is_loggedIn();
      $this->admin->pendaftaran_delete($nim, $this->nimJurusan);
      redirect('admin');
    }

    public function pendaftaran_edit($nim='')
    {
      $this->is_loggedIn();
      $data['pendaftar']  = $this->admin->pendaftaran_detail($nim, $this->nimJurusan);
      if($data['pendaftar']==false){
        die(redirect('admin'));
      }

      $this->template->load('admin/admin_pendaftar_detail', $data);
    }

  }
