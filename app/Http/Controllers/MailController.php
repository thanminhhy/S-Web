<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotify;
use Illuminate\Http\Request;

class MailController extends Controller
{
    //
    public function index()
    {
        $data = [
            'subject' => 'Cambo Tutorial Mail',
            'body' => 'Hello This is my email delivery!'
        ];
        try {
            Mail::to('thanminhhy@gmail.com')->send(new MailNotify($data));
            return response()->json(['Great check your mail box']);
        } catch (Exception $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine()
            ], 500);
        }
    }
}
