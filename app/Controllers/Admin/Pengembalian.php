<?php

namespace App\Controllers\Admin;

use App\Controllers\Petugas\Pengembalian as PetugasPengembalian;

class Pengembalian extends PetugasPengembalian
{
    // Admin inherits all methods (index, form, store, riwayat, detail, rusak, restock, scrap)
    // from Petugas\Pengembalian, which now uses session role for view paths and redirection.
    /**
     * Override form to redirect to Admin Inspection
     */
    public function form($peminjaman_id)
    {
        return redirect()->to("/admin/inspections/create/$peminjaman_id?type=post-return");
    }

    /**
     * Override store to act as a fallback or if we ever stop using Inspections controller directly
     * But actually, the form redirects to Inspection controller. 
     * The Inspection controller handles the store.
     * So we just need to make sure Inspection controller redirects back to /admin/pengembalian.
     * My previous fix in Inspections::store already handles dynamic redirection based on role!
     * So overriding form() is generally enough for the entry point.
     */
}
