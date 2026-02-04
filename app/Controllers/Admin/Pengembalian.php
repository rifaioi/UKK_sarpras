<?php

namespace App\Controllers\Admin;

use App\Controllers\Petugas\Pengembalian as PetugasPengembalian;

class Pengembalian extends PetugasPengembalian
{
    // Admin inherits all methods (index, form, store, riwayat, detail, rusak, restock, scrap)
    // from Petugas\Pengembalian, which now uses session role for view paths and redirection.
}
