<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BackupMail extends Mailable
{
    use Queueable, SerializesModels;

    public $filePath;
    public $fileName;

    public function __construct($filePath, $fileName)
    {
        $this->filePath = $filePath;
        $this->fileName = $fileName;
    }

    public function build()
    {
        return $this->subject('Daily Database Backup - ' . date('Y-m-d'))
                    ->view('mail.backup') 
                    ->attach($this->filePath, [
                        'as' => $this->fileName,
                        'mime' => 'application/sql',
                    ]);
    }
}
