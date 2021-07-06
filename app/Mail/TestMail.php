<?php 

    namespace App\Mail;
    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;

    class TestMail extends Mailable{

        use Queueable, SerializesModels;
               
        public function build()
        {
            return $this->view('mail.welcome')
            ->from('hello@q-software.com', 'Q Software')
            ->subject('Hello & Welcome!')
            ->replyTo('hello@q-software.com', 'Q Software');
        }
    }

?>