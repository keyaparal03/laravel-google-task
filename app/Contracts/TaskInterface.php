<?php

namespace App\Contracts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

interface TaskInterface
{
    public function add(Request $request);
    public function save(Request $request);
    public function edit(Request $request);
    public function update(Request $request);
    public function lists(Request $request);
    public function view(Request $request);
    public function delete(Request $request);
    public function tasks(Request $request);
}