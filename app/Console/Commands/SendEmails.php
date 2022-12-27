<?php

namespace App\Console\Commands;

use App\Mail\SendEmail;
use App\Services\EmailService;
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

    protected $emailService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EmailService $emailService)
    {
        parent::__construct();

        $this->emailService = $emailService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $emails = $this->emailService->getPending(1);

        foreach ($emails as $email) {
            Mail::to($email['to'])->send(new SendEmail($email));

            $this->info('Mail with id '.$email['id'].' sent');

            $this->emailService->updateToSent($email['id']);
        }

        return 1;
    }
}
