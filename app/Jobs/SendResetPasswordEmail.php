<?php

namespace App\Jobs;

use App\Mail\ResetPasswordEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendResetPasswordEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $name;
    protected $newPassword;

    public function __construct($email, $name, $newPassword)
    {
        $this->email = $email;
        $this->name = $name;
        $this->newPassword = $newPassword;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new ResetPasswordEmail($this->name, $this->newPassword));
    }
}
