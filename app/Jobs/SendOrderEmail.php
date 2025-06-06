<?php

namespace App\Jobs;

use App\Mail\OrderEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendOrderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $cart;
    protected $order;

    public function __construct($email, $cart, $order)
    {
        $this->email = $email;
        $this->cart = $cart;
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         Mail::to($this->email)->send(new OrderEmail($this->cart, $this->order));
    }
}
