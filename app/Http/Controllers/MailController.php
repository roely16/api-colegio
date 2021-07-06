<?php 

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    use App\Mail\TestMail;
    use Illuminate\Support\Facades\Mail;

    class MailController extends Controller{
    
        public function test(){

            Mail::to('gerson.roely@gmail.com')->send(new TestMail());
            
            return response()->json('ok');

        }        

    }

?>