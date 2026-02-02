<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\PengaduanModel;
use App\Models\LocationModel;
use App\Models\SarprasModel;

class Pengaduan extends BaseController
{
    protected $pengaduanModel;
    protected $locationModel;
    protected $sarprasModel;
    protected $damageModel;

    public function __construct()
    {
        $this->pengaduanModel = new PengaduanModel();
        $this->locationModel = new LocationModel();
        $this->sarprasModel = new SarprasModel();
    }

    public function create()
    {
        $data = [
            'locations' => $this->locationModel->findAll(),
            'items' => $this->sarprasModel->where('is_deleted', 0)->orderBy('nama', 'ASC')->findAll(),
        ];
        return view('member/pengaduan_form', $data);
    }

    public function store()
    {
        $validation = $this->validate([
            'judul' => 'required|min_length[3]',
            'deskripsi' => 'required',
            'lokasi' => 'required',
            'bukti_foto' => 'max_size[bukti_foto,2048]|is_image[bukti_foto]|mime_in[bukti_foto,image/jpg,image/jpeg,image/png]'
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = $this->request->getFile('bukti_foto');
        $fileName = null;

        if ($foto->isValid() && !$foto->hasMoved()) {
            $fileName = $foto->getRandomName();
            $foto->move('uploads/pengaduan/', $fileName);
        }

        $this->pengaduanModel->save([
            'user_id' => session()->get('id'),
            'sarpras_id' => $this->request->getVar('sarpras_id') ?: null,
            'judul' => $this->request->getVar('judul'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'lokasi' => $this->request->getVar('lokasi'),
            'bukti_foto' => $fileName,
            'status_id' => 1 // Belum Ditindaklanjuti
        ]);

        log_activity('Buat Pengaduan', 'Membuat laporan: ' . $this->request->getVar('judul'));

        return redirect()->to('/member/pengaduan')->with('success', 'Laporan berhasil dikirim');
    }
}
