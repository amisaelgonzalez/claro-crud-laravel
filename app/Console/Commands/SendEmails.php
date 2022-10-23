<?php

namespace App\Console\Commands;

use App\Enum\EmailStatusEnum;
use App\Mail\SendEmail;
use App\Models\Email;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $emails = Email::pending()->get();

        foreach ($emails as $email) {
            Mail::to($email->to)->send(new SendEmail($email));

            $this->info('Mail with id '.$email->id.' sent');
            $email->status = EmailStatusEnum::SENT;
            $email->update();
        }

        return 1;
    }
}
