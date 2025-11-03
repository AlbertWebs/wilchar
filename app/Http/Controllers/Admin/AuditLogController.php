<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index()
    {
        return view('admin.logs.index');
    }

    /**
     * Display the specified audit log.
     */
    public function show(string $id)
    {
        return view('admin.logs.show', ['logId' => $id]);
    }
}
