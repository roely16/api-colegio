<?php 

    namespace App\Mail;
    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;

    class GestionMail extends Mailable{

        use Queueable, SerializesModels;
        
        public $data;

        public function __construct($data){

            $this->data = $data;

        }

        public function build(){

            return $this->view('mail.gestion')
            ->from('no-reply@colegio.com', 'Colegio')
            ->subject('Ingreso de Gestión No. ' . $this->data->gestion_id)
            ->with([
                "data" => $this->data
            ]);
        }
    }

?>