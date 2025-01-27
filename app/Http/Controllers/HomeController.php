<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Tracking;
use App\Tracking_Details;
use Illuminate\Support\Facades\Auth;
use App\Users;
use Illuminate\Support\Facades\Session;
// use App\Http\Controllers\Redirect;
use Exception; // Import the Exception class
use Illuminate\Support\Facades\Redirect;


class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index(Request $request)
    {
        $request->session()->forget('keyword');
        return view('home');
    }


    function chart() {
        $data = array(
            'data1' => self::_createdDocument(),
            'data2' => self::_acceptedDocument()
        );
        return $data;
    }

    function _createdDocument(){
        $id = Auth::user()->id;
        for($i=1; $i<=12; $i++){
            $new = str_pad($i, 2, '0', STR_PAD_LEFT);
            $current = '01.'.$new.'.'.date('Y');
            $data['months'][] = date('M/y',strtotime($current));
            $startdate = date('Y-m-d',strtotime($current));
            $end = '01.'.($new+1).'.'.date('Y');
            if($new==12){
                $end = '12/31/'.date('Y');
            }
            $enddate = date('Y-m-d',strtotime($end));
            $count = Tracking::where('prepared_by',$id)
                        ->where('prepared_date','>=',$startdate)
                        ->where('prepared_date','<=',$enddate)
                        ->count();
            $data['count'][] = $count;
        }
        return $data;
    }

    function _acceptedDocument(){
        $id = Auth::user()->id;
        for($i=1; $i<=12; $i++){
            $new = str_pad($i, 2, '0', STR_PAD_LEFT);
            $current = '01.'.$new.'.'.date('Y');
            $data['months'][] = date('M/y',strtotime($current));
            $startdate = date('Y-m-d',strtotime($current));
            $end = '01.'.($new+1).'.'.date('Y');
            if($new==12){
                $end = '12/31/'.date('Y');
            }
            $enddate = date('Y-m-d',strtotime($end));
            $count = Tracking_Details::where('received_by',$id)
                ->where('date_in','>=',$startdate)
                ->where('date_in','<=',$enddate)
                ->count();
            $data['count'][] = $count;
        }
        return $data;
    }

    public function jwt(Request $request) {
        $userid = $request->query('userid');
        $token = Session::put('token', $userid);
        if (!$userid) {
            return response()->json(['error' => 'User ID is required'], 400);
        }
        $userid=urlencode($userid);
        $message = $this->test($userid);
        if ($message instanceof \Illuminate\Http\RedirectResponse) {
            return $message;
        }
    }

    private function aes_decrypt($encrypted, $key, $cipherMethod) {
         // Base64 decode the encrypted data
        $decoded = urldecode($encrypted);

        // Base64 decode the URL-decoded data
        $data = base64_decode($decoded);

        // Extract the IV from the decoded data (assuming the first 16 bytes are the IV)
        $iv = substr($data, 0, 16);

        // Extract the encrypted data (assuming the rest is the encrypted payload)
        $encryptedData = substr($data, 16);

        // Perform the decryption
        $decrypted = openssl_decrypt($encryptedData, $cipherMethod, $key, OPENSSL_RAW_DATA, $iv);

        // $data = base64_decode($encrypted);

        // // Extract the IV from the encrypted data (assuming the first 16 bytes are the IV)
        // $iv = substr($data, 0, 16);

        // // Extract the encrypted data (assuming the rest is the encrypted payload)
        // $encryptedData = substr($data, 16);

        // // Perform the decryption
        // $decrypted = openssl_decrypt($encryptedData, $cipherMethod, $key, OPENSSL_RAW_DATA, $iv);

        return $decrypted;
    }

    public function test($userid) {
        $key = 'D65459959AAEF56E'; // Adjust to match your actual 

        if(empty($userid)){
            echo "nothing";
        }
        
        // dd(urlencode($userid));
        try {
            // Attempt AES-128-CBC decryption
            $decrypted = $this->aes_decrypt($userid, $key, 'AES-128-CBC');
            if ($decrypted === false) {
                throw new Exception('AES-128-CBC decryption failed');
            }
            // echo 'AES-128-CBC decryption successful: ' . $decrypted . PHP_EOL;
            $user = Users::where('username',"=", $decrypted)->first();
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
       
        Auth::login($user);
        if (Auth::check()) {
               return redirect::to('home');
        } else {
            return 'Authentication failed. Debugging:'. $user->username. $user->password;
        }
    }

    public function flushSessionPis(Request $request)
    {
        $token = Session::get('token');
        $system = $request->query('system'); // Get the 'system' value from query parameter
        Session::flush(); // Clear all session data
    
        $redirectUrl = '';
    
        // Handle different system cases
        switch ($system) {
            case 'pis':
                $redirectUrl = 'https://pis.cvchd7.com/pis/login_jwt?userid='.urlencode($token);
                break;
            case 'payroll':
                $redirectUrl = 'http://192.168.110.43:8083/Account/Login_Jwt?userid='.urlencode($token);
                break;
            // Add more cases as needed
            default:
                $redirectUrl = 'https://mis.cvchd7.com/dts/login_jwt?userid='.urlencode($token);
                break;
        }
    
        return redirect()->away($redirectUrl);
        // return redirect('https://pis.cvchd7.com/pis/login_jwt?userid=' . urlencode($token)); // Redirect to the Document Tracking System
        // return redirect('https://pis.cvchd7.com/pis/login_jwt?userid=QdEUi%2FPaK0VOzLzvzaPo83br%2B8jhFRjrdeMDrrqRS0g%3D');
    }
}
